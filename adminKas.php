<?php
include "koneksi.php";
	$total = mysqli_query($connect, "SELECT 
		sum(case when jenis = 'Kas Masuk' then nominal else 0 end) AS totalMasuk,
		sum(case when jenis = 'Kas Keluar' then nominal else 0 end) AS totalKeluar
		FROM kas");
	$queryProgram= mysqli_query($connect, "SELECT * from program where status = 'aktif'");
	$data_total = mysqli_fetch_assoc($total);
	$pemasukan  = $data_total['totalMasuk'] ?? 0;
	$pengeluaran = $data_total['totalKeluar'] ?? 0;
	$saldo  = $pemasukan - $pengeluaran;

    if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahKas') {
        mysqli_query($connect, "INSERT INTO kas(programID, tanggal, jenis, nominal, keterangan) VALUES ('{$_POST['program']}','{$_POST['tanggal']}','{$_POST['jenis']}','{$_POST['nominal']}','{$_POST['keterangan']}')");
        header("Location: adminKas.php");
        exit;
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpanEdit') {
        $id = $_GET['id'];
        mysqli_query($connect, "UPDATE kas SET programID='{$_POST['program']}', tanggal='{$_POST['tanggal']}', jenis='{$_POST['jenis']}', nominal='{$_POST['nominal']}', keterangan='{$_POST['keterangan']}' WHERE kasID = '$id'");
        header("Location: adminKas.php");
        exit;
    }
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
	    $id = $_GET['id'];
	    mysqli_query($connect, "DELETE FROM kas where kasID = '$id'");
	    header("Location: adminKas.php");
	    exit;
	}
	if(isset($_GET['bulan'])&&isset($_GET['tahun'])){
		$bulan = $_GET['bulan'] ?? date('m');
		$tahun = $_GET['tahun'] ?? date('Y');
		$query = mysqli_query($connect, "SELECT * FROM (
		   	SELECT 
				kasID, tanggal, jenis, kategori, nominal, keterangan,
		 		sum(case 
		  			when jenis = 'Kas Masuk' then nominal 
					when jenis = 'Kas Keluar' then -nominal 
		        	else 0 
		      	end) OVER (order by tanggal asc, kasID asc) AS saldo
		      FROM kas) AS t 
	      where MONTH(tanggal) = '$bulan' 
	      and YEAR(tanggal) = '$tahun'
	      order by tanggal asc");
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
    <?php if(isset($_GET['aksi'])&& $_GET['aksi'] == 'editKas'): 
        $id = $_GET['id'];
        $query = mysqli_query($connect, "SELECT * from kas where kasID = '$id' ");
        $data = mysqli_fetch_array($query); ?>
        	<form action="adminKas.php?aksi=simpanEdit&id=<?=$data['kasID']?>" method="POST">
                <label for="id">ID : </label>
                <i><?= $data['kasID']?></i><br>
                <label for="jenis">Jenis</label>
                <select name="jenis" required>
                    <option value="" disabled selected>Jenis Kas...</option>
                    <option value="Kas Masuk" <?= ($data['jenis'] == 'Kas Masuk')?'selected':''; ?>>Kas Masuk</option>
                    <option value="Kas Keluar" <?= ($data['jenis'] == 'Kas Keluar')?'selected':''; ?>>Kas Keluar</option>
                </select> <br>

		        <select name="program" required>
		            <option value="" disabled selected>Pilih Program...</option>
		        <?php while($program=mysqli_fetch_assoc($queryProgram)):?>
		            <option value="<?=$program['programID']?>"<?=($program['programID']==$data['programID'])?'selected':''; ?>><?=$program['nama']?></option>
		        <?php endwhile; ?>
		        </select> 

                <label for="tanggal">Tanggal Transfer</label>
                <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required> <br>
                <label for="nominal">Nominal (Rp)</label>
                <input type="number" name="nominal" value="<?= $data['nominal'] ?>" required> <br>
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" value="<?= $data['keterangan'] ?>" required> <br>
                <button type="submit">Simpan Perubahan</button>
            </form>


    <?php elseif(isset($_GET['aksi'])&& $_GET['aksi'] == 'inputKas'):  ?>
            <form action="adminKas.php?aksi=tambahKas" method="POST">
                <label for="jenis">Jenis</label>
                <select name="jenis" required>
                    <option value="" disabled selected>Jenis Kas...</option>
                    <option value="Kas Masuk">Kas Masuk</option>
                    <option value="Kas Keluar">Kas Keluar</option>
                </select> <br>

		        <select name="program" required>
		            <option value="" disabled selected>Pilih Program...</option>
		        <?php while($program=mysqli_fetch_assoc($queryProgram)):?>
		            <option value="<?=$program['programID']?>"><?=$program['nama']?></option>
		        <?php endwhile; ?>
		        </select> 

                <label for="tanggal">Tanggal Transfer</label>
                <input type="date" name="tanggal" required> <br>
                <label for="nominal">Nominal (Rp)</label>
                <input type="number" name="nominal" required> <br>
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan"> <br>
                <button type="submit">Input kas</button>
            </form>

	<?php else:?>
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
  	<a href="adminKas.php?aksi=inputKas" style="padding: 5px 10px; background: blue; color: white; text-decoration: none; border-radius: 3px;">
  		input
	</a><br><br> 
	<table border="1" cellpadding="8">
		<tr>
			<th>Kas ID</th>
			<th>Tanggal</th>
			<th>Jenis</th>			
			<th>Program</th>
			<th>Keterangan</th>
			<th>Nominal</th>
			<th>Saldo</th>
			<th>Aksi</th>
		</tr>
		<?php while ($data=mysqli_fetch_assoc($query)):
			$queryProgram= mysqli_query($connect, "SELECT * from program WHERE programID = '{$data['programID']}'");
			$program = mysqli_fetch_assoc($queryProgram);  ?>
			<tr>
				<td><?=$data['kasID']?></td>
				<td><?=$data['tanggal']?></td>
				<td><?=$data['jenis']?></td>
				<td><?= $program['nama']?></td>
				<td><?=$data['keterangan']?></td>	
				<td style="color: <?= ($data['jenis'] == 'Kas Masuk') ? 'green' : 'red'; ?>"><?= "Rp " . number_format($data['nominal'], 2, ',', '.'); ?></td>
				<td><?= "Rp " . number_format($data['saldo'], 2, ',', '.'); ?></td>
				<td>
            	<a href="adminKas.php?aksi=editKas&id=<?= $data['kasID']; ?>" 
                 style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
                 Edit
            	</a>  
            	<a href="adminKas.php?aksi=hapus&id=<?= $data['kasID']; ?>" 
                 onclick="return confirm('Hapus record ini?')"
                 style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
                 Hapus
            	</a>  		
				</td>
			</tr>
		<?php endwhile; ?>
	</table>

	<?php endif; ?>