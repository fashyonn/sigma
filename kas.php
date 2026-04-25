<?php
include "koneksi.php";
$query = mysqli_query($connect, "SELECT * from kas");
?>
	<table border="1" cellpadding="8">
		<tr>
			<th>Kas ID</th>
			<th>Tanggal</th>
			<th>Jenis</th>			
			<th>Kategori</th>
			<th>Nominal</th>
			<th>Keterangan</th>
			<th>DonasiID</th>
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
			<td><?= "Rp " . number_format($data['nominal'], 2, ',', '.'); ?></td>
			<td><?=$data['keterangan']?></td>	
			<td><?=$data['donasiID']?></td>
		</tr>
<?php
}
?>
	</table>
