<?php
require 'koneksi.php';

$total = mysqli_query($connect, "SELECT 
                SUM(CASE WHEN jenis = 'Kas Masuk' THEN nominal ELSE 0 END) AS totalMasuk,
                SUM(CASE WHEN jenis = 'Kas Keluar' THEN nominal ELSE 0 END) AS totalKeluar
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
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>input</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h3>Pemasukan: <?=$pemasukan?></h3>
    <h3>Pengeluaran: <?=$pengeluaran?></h3>
    <h3>Saldo: <?=$saldo?></h3><br>


	<form id="formDonasi" action="index.php?aksi=tambahDonasi" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Nama donatur" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Tanggal Transfer</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Jenis Program</label>
                                <select name="program" class="form-select" id="selectProgram" required>
                                    <option value="" disabled selected>Pilih Program...</option>
                                    <option value="Beasiswa Pendidikan">Beasiswa Pendidikan</option>
                                    <option value="Bantuan Sosial">Bantuan Sosial</option>
                                    <option value="Pembangunan Masjid">Pembangunan Masjid</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Nominal (Rp)</label>
                                    <input type="number" name="nominal" class="form-control" placeholder="Contoh: 100000" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">No. Bukti Transfer</label>
                                    <input type="text" name="noRef" class="form-control" placeholder="Nomor referensi/ID" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-3 mt-3 rounded-pill fw-bold">Kirim
                                Konfirmasi</button>
                        </form>
</body>
</html>