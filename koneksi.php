<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'sigma1';
$port = 3306;

$connect = new mysqli($hostname,$username,$password,$database,$port);
	if($connect->connect_error){
		die("koneksi gagal terhubung!");
	}else{
		echo "koneksi berhasil!";
	}
?>

