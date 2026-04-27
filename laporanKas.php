<?php
include "koneksi.php";

$total = mysqli_query($connect, "SELECT 
                SUM(CASE WHEN jenis = 'Kas Masuk' THEN nominal ELSE 0 END) AS totalMasuk,
                SUM(CASE WHEN jenis = 'Kas Keluar' THEN nominal ELSE 0 END) AS totalKeluar
              FROM kas");
$data_total = mysqli_fetch_assoc($total);

$pemasukan  = $data_total['totalMasuk'] ?? 0;
$pengeluaran = $data_total['totalKeluar'] ?? 0;
$saldo  = $pemasukan - $pengeluaran;

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($connect, "DELETE FROM kas WHERE kasID = '$id'");
    header("Location: kas.php");
    exit;
}

if(isset($_GET['bulan'])&&isset($_GET['tahun'])){
	$bulan = $_GET['bulan'] ?? date('m');
	$tahun = $_GET['tahun'] ?? date('Y');

	$query = mysqli_query($connect, "SELECT * FROM (
            SELECT 
                kasID, tanggal, jenis, kategori, nominal, keterangan,
                SUM(CASE 
                    WHEN jenis = 'Kas Masuk' THEN nominal 
                    WHEN jenis = 'Kas Keluar' THEN -nominal 
                    ELSE 0 
                END) OVER (ORDER BY tanggal ASC, kasID ASC) AS saldo
            FROM kas
        ) AS t 
        WHERE MONTH(tanggal) = '$bulan' 
        AND YEAR(tanggal) = '$tahun'
        ORDER BY tanggal ASC");
}
else{
	$query = mysqli_query($connect, "SELECT *, sum(case 
                when jenis = 'Kas Masuk' then nominal 
                when jenis = 'Kas Keluar' then -nominal 
                else 0 
            end) over (order by tanggal asc, kasID asc) AS saldo
        from kas");
}
?>

<h3>Pemasukan: <?=$pemasukan?></h3>
<h3>Pengeluaran: <?=$pengeluaran?></h3>
<h3>Saldo: <?=$saldo?></h3>

	<form method="GET" action="">
	    <label>Pilih Bulan:</label>
	    <select name="bulan">
	        <?php
	        $bulan = [
	            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
	            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
	            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
	        ];
	        foreach ($bulan as $no => $nama) {
	            $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $no) ? 'selected' : '';
	            echo "<option value='$no' $selected>$nama</option>";
	        }
	        ?>
	    </select>

	    <label>Tahun:</label>
	    <input type="number" name="tahun" value="<?= $_GET['tahun'] ?? date('Y') ?>" style="width: 80px;">

	    <button type="submit">Tampilkan</button>
	    <a href="?" style="padding: 3px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">Reset</a>
	</form>

  <a href="edit.php?aksi=inputKas" 
     style="padding: 5px 10px; background: blue; color: white; text-decoration: none; border-radius: 3px;">
     input
  </a><br> <br> 

	<table border="1" cellpadding="8">
		<tr>
			<th>Kas ID</th>
			<th>Tanggal</th>
			<th>Jenis</th>			
			<th>Kategori</th>
			<th>Keterangan</th>
			<th>Nominal</th>
			<th>Saldo</th>
			<th>Aksi</th>
		</tr>
<?php
while ($data=mysqli_fetch_array($query)) {
?>
		<tr>
			<td><?=$data['kasID']?></td>
			<td><?=$data['tanggal']?></td>
			<td><?=$data['jenis']?></td>
			<td><?=$data['kategori']?></td>
			<td><?=$data['keterangan']?></td>	
			<td style="color: <?= ($data['jenis'] == 'Kas Masuk') ? 'green' : 'red'; ?>"><?= "Rp " . number_format($data['nominal'], 2, ',', '.'); ?></td>
			<td><?= "Rp " . number_format($data['saldo'], 2, ',', '.'); ?></td>
			<td>
                    <a href="edit.php?aksi=editKas&id=<?= $data['kasID']; ?>" 
                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
                       Edit
                    </a>  
                    <a href="laporanKas.php?aksi=hapus&id=<?= $data['kasID']; ?>" 
                       onclick="return confirm('Hapus record ini?')"
                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
                       Hapus
                    </a>  		
			</td>
		</tr>
<?php
}
?>
	</table>
