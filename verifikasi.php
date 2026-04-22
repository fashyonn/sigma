<?php
include "koneksi.php";
$query = mysqli_query($connect, "SELECT * from verifikasi");
$data = mysqli_fetch_array($query);
echo uniqid(); 
echo mt_rand(100, 999);

if (isset($_GET['aksi']) && $_GET['aksi'] == 'verifikasi') {
    $id = $_GET['id'];
    // Update kolom 'terverifikasi' menjadi 1 (atau teks 'Ya')
    // mysqli_query($connect, "UPDATE tabel_donasi SET terverifikasi = '1' WHERE DonasiID = '$id_verif'");
    mysqli_query($connect, "UPDATE verifikasi SET statusVerifikasi = 'Terverifikasi' WHERE verifikasiID = '$id'");
    // mysqli_query($connect, "INSERT INTO kas (kasID, tanggal, jenis, kategori, keterangan, jumlah) VALUES ('123', '2026-04-14', 'Kas Masuk', 'Donasi', 'qwerty', '1000000');")
    // Redirect agar URL bersih kembali
    header("Location: verifikasi.php");
    exit;
}
?>

<table border="1" cellpadding="5">
	<tr>
		<th>Donasi ID</th>
		<th>Nama Donatur</th>
		<th>Tangal Donasi</th>			
		<th>Jenis Donasi</th>
		<th>Jumlah</th>
		<th>Nomor Bukti</th>
		<th>Terverfikasi</th>
		<th>Aksi</th>
	</tr>
<?php
	while ($data = mysqli_fetch_array($query)) {
?>
		<tr>
			<td><?= $data['verifikasiID']?></td>
			<td><?= $data['nama']?></td>
			<td><?= $data['tanggal']?></td>
			<td><?= $data['jenis']?></td>
			<td><?= $data['jumlah']?></td>
			<td><?= $data['noBukti']?></td>
			<td>
                <?php if ($data['statusVerifikasi'] == 'Terverifikasi'): ?>
                    <b style="color: green;">Terverifikasi</b>
                <?php else: ?>
                    <i style="color: red;">Belum Terverifikasi</i>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($data['statusVerifikasi'] == 'Belum' || empty($data['terverifikasi'])): ?>
                    <a href="verifikasi.php?aksi=verifikasi&id=<?= $data['verifikasiID']; ?>" 
                       onclick="return confirm('Verifikasi donasi ini?')"
                       style="padding: 5px 10px; background: #4CAF50; color: white; text-decoration: none; border-radius: 3px;">
                       Verifikasi
                    </a>
                <?php else: ?>
                    <span style="color: #888;">Selesai</span>
                <?php endif; ?>
            </td>
		</tr>
<?php
	}
?>
</table>