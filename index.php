<?php
require 'koneksi.php';
    $total = mysqli_query($connect, "SELECT 
        SUM(case when jenis = 'Kas Masuk' then nominal else 0 end) AS totalMasuk,
        SUM(case when jenis = 'Kas Keluar' then nominal else 0 end) AS totalKeluar
        FROM kas");
    $data_total = mysqli_fetch_assoc($total);
    $pemasukan  = $data_total['totalMasuk'] ?? 0;
    $pengeluaran = $data_total['totalKeluar'] ?? 0;
    $saldo  = $pemasukan - $pengeluaran;

    if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahDonasi') {
        mysqli_query($connect, "INSERT INTO donasi(tanggal, nama, program, nominal, noRef) VALUES ('{$_POST['tanggal']}','{$_POST['nama']}','{$_POST['program']}', '{$_POST['nominal']}','{$_POST['noRef']}')");
        header("Location: index.php");
        exit;
    }
?>
    <h3>Pemasukan: <?=$pemasukan?></h3>
    <h3>Pengeluaran: <?=$pengeluaran?></h3>
    <h3>Saldo: <?=$saldo?></h3><br>
	<form action="index.php?aksi=tambahDonasi" method="POST">
        <input type="text" name="nama" class="form-control" placeholder="Nama donatur" required>          
        <input type="date" name="tanggal" class="form-control" required>                               
        <select name="program" class="form-select" id="selectProgram" required>
            <option value="" disabled selected>Pilih Program...</option>
            <option value="Beasiswa Pendidikan">Beasiswa Pendidikan</option>
            <option value="Bantuan Sosial">Bantuan Sosial</option>
            <option value="Pembangunan Masjid">Pembangunan Masjid</option>
        </select>                      
        <input type="number" name="nominal" class="form-control" placeholder="Contoh: 100000" required>                     
        <input type="text" name="noRef" class="form-control" placeholder="Nomor referensi/ID" required>
        <button type="submit" class="btn btn-success w-100 py-3 mt-3 rounded-pill fw-bold">Kirim Konfirmasi</button>
    </form>
