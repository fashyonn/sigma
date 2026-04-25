<?php
	$nama = $_POST['nama'];
	$tanggal = $_POST['tanggal'];
	$program = $_POST['program'];
	$nominal = $_POST['nominal'];
	$bukti = $_POST['noRef'];

require "koneksi.php";
$query = mysqli_query($connect, "INSERT INTO donasi(nama, tanggal, nominal, keterangan, noRef) VALUES ('$nama','$tanggal','$nominal', '$program','$bukti'); ");

require "verifikasi.php";
?>