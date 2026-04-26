<?php
include "koneksi.php";
$query = mysqli_query($connect, "SELECT * from kas");
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($connect, "DELETE FROM kas WHERE kasID = '$id'");
    header("Location: kas.php");
    exit;
}
?>
  <a href="edit.php?aksi=inputKas" 
     style="padding: 5px 10px; background: blue; color: white; text-decoration: none; border-radius: 3px;">
     input
  </a> 
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
			<td>
                    <a href="edit.php?aksi=editKas&id=<?= $data['kasID']; ?>" 
                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
                       Edit
                    </a>  
                    <a href="kas.php?aksi=hapus&id=<?= $data['kasID']; ?>" 
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
