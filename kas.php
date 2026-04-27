<?php
include "koneksi.php";
	if(isset($_GET['tanggalMulai']) && isset($_GET['tanggalSelesai'])){
		$query = mysqli_query($connect, "SELECT * FROM(
			SELECT *,
			  SUM(CASE 
			      when jenis = 'Kas Masuk' then nominal 
			      when jenis = 'Kas Keluar' then -nominal 
			      ELSE 0 
			  END) OVER (ORDER BY tanggal ASC, kasID ASC) AS saldo
			FROM kas
			) AS t 
			WHERE tanggal BETWEEN '{$_GET['tanggalMulai']}' AND '{$_GET['tanggalSelesai']}'
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
	<form method="GET" action="">
		<label>Data tanggal:</label>
		<input type="date" name="tanggalMulai" value="<?= $_GET['tanggalMulai'] ?? '' ?>" required>
		<label>sampai</label>
		<input type="date" name="tanggalSelesai" value="<?= $_GET['tanggalSelesai'] ?? '' ?>" required>
		<button type="submit">Cari Data</button>
		<a href="?" style="padding: 3px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">Reset</a>
	</form>
	<table border="1" cellpadding="8">
		<tr>
			<th>Kas ID</th>
			<th>Tanggal</th>
			<th>Jenis</th>			
			<th>Kategori</th>
			<th>Keterangan</th>
			<th>Nominal</th>
			<th>Saldo</th>
		</tr>
		<?php while ($data=mysqli_fetch_array($query)) :
			$queryProgram= mysqli_query($connect, "SELECT * from program WHERE programID = '{$data['programID']}'");
			$program = mysqli_fetch_assoc($queryProgram); ?>
			<tr>
				<td><?=$data['kasID']?></td>
				<td><?=$data['tanggal']?></td>
				<td><?=$data['jenis']?></td>
				<td><?= $program['nama']?></td>
				<td><?=$data['keterangan']?></td>	
				<td style="color: <?= ($data['jenis'] == 'Kas Masuk') ? 'green' : 'red'; ?>"><?= "Rp " . number_format($data['nominal'], 2, ',', '.'); ?></td>
				<td><?= "Rp " . number_format($data['saldo'], 2, ',', '.'); ?></td>
			</tr>
		<?php endwhile; ?>
	</table>