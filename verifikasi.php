<?php
include "koneksi.php";
	$query = mysqli_query($connect, "SELECT * from donasi");
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'verifikasi') {
	    $id = $_GET['id'];
	    mysqli_query($connect, "UPDATE donasi SET status = 'Terverifikasi' WHERE donasiID = '$id'");
	    mysqli_query($connect, "INSERT INTO kas(tanggal,jenis,kategori,nominal,keterangan,donasiID)
	               SELECT tanggal,'Kas Masuk','Donasi',nominal, program , donasiID
	               FROM donasi 
	               WHERE donasiID = '$id'");
	    header("Location: verifikasi.php");
	    exit;
	}
?>
	<table border="1" cellpadding="8">
		<tr>
			<th>Donasi ID</th>
			<th>Nama Donatur</th>
			<th>Tanggal Donasi</th>			
			<th>Nominal</th>
			<th>Program</th>
			<th>Nomor Bukti</th>
			<th>Status</th>
			<th>Aksi</th>
		</tr>
		<?php while ($data = mysqli_fetch_array($query)) { ?>
			<tr>
				<td><?= $data['donasiID']?></td>
				<td><?= $data['nama']?></td>
				<td><?= $data['tanggal']?></td>
				<td><?= "Rp " . number_format($data['nominal'], 2, ',', '.'); ?></td>
				<td><?= $data['program']?></td>
				<td><?= $data['noRef']?></td>
				<td>
	                <?php if ($data['status'] == 'Terverifikasi'): ?>
	                    <i style="padding: 5px 10px; border-radius: 12px; background: green; color: white; ">Terverifikasi</i>
	                <?php else: ?>
	                    <i style="padding: 5px 10px; border-radius: 12px; background: red; color: white;">Belum Terverifikasi</i>
	                <?php endif; ?>
	            </td>
	            <td>
	                <?php if ($data['status'] == 'Belum Terverifikasi' || empty($data['status'])): ?>
	                    <a href="verifikasi.php?aksi=verifikasi&id=<?= $data['donasiID']; ?>" 
	                       onclick="return confirm('Verifikasi donasi ini?')"
	                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
	                       Verifikasi
	                    </a>                    
	                <?php endif; ?>
	            </td>
			</tr>
		<?php } ?>
	</table>