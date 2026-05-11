<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
}
$queryUser = mysqli_query($connect, "SELECT username from user where userID = '{$_SESSION['userID']}'");
$user = mysqli_fetch_assoc($queryUser);
$username = $user['username'];

$total = mysqli_query($connect, "SELECT 
        sum(case when jenis = 'Kas Masuk' then nominal else 0 end) AS totalMasuk,
        sum(case when jenis = 'Kas Keluar' then nominal else 0 end) AS totalKeluar
        FROM kas");
$queryProgram = mysqli_query($connect, "SELECT * from program where status = 'aktif'");
$data_total = mysqli_fetch_assoc($total);
$pemasukan = $data_total['totalMasuk'] ?? 0;
$pengeluaran = $data_total['totalKeluar'] ?? 0;
$saldo = $pemasukan - $pengeluaran;

if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahKas') {
    mysqli_query($connect, "INSERT INTO kas(programID, tanggal, jenis, nominal, keterangan) VALUES ('{$_POST['program']}','{$_POST['tanggal']}','{$_POST['jenis']}','{$_POST['nominal']}','{$_POST['keterangan']}')");
    header("Location: kas.php");
    exit;
}
if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpanEdit') {
    $id = $_GET['id'];
    mysqli_query($connect, "UPDATE kas SET programID='{$_POST['program']}', tanggal='{$_POST['tanggal']}', jenis='{$_POST['jenis']}', nominal='{$_POST['nominal']}', keterangan='{$_POST['keterangan']}' WHERE kasID = '$id'");
    header("Location: kas.php");
    exit;
}
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($connect, "DELETE FROM kas where kasID = '$id'");
    header("Location: kas.php");
    exit;
}
if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');
    $query = mysqli_query($connect, "SELECT * FROM (
            SELECT 
                kasID, tanggal, jenis, programID, nominal, keterangan,
                sum(case 
                    when jenis = 'Kas Masuk' then nominal 
                    when jenis = 'Kas Keluar' then -nominal 
                    else 0 
                end) OVER (order by tanggal asc, kasID asc) AS saldo
              FROM kas) AS t 
          where MONTH(tanggal) = '$bulan' 
          and YEAR(tanggal) = '$tahun'
          order by tanggal asc");
} else {
    $query = mysqli_query($connect, "SELECT *, sum(case 
                    when jenis = 'Kas Masuk' then nominal 
                    when jenis = 'Kas Keluar' then -nominal 
                    else 0 
                end) over (order by tanggal asc, kasID asc) AS saldo
            from kas");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kas</title>

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
    <div class="dash-body d-flex" id="wrapper">
        <div class="sidebar" style="background-color: var(--hijau-tua); min-width: 250px; min-height: 100vh;">
            <div class="sidebar-heading text-center py-4 text-white">
                <div class="logo">
                    <img src="asset/SIGMA.png" height="40" class="mb-2"><br>
                </div>
                <b style="color: var(--cream);">SIGMA ADMIN</b>
            </div>
            <div class="list-group list-group-flush px-3">
                <nav class="list-group list-group-flush px-2">
                    <a href="dashboard.php" class="list-group-item list-group-item-action"><i
                            class="bi bi-speedometer2 me-2"></i>
                        Dashboard</a>
                    <a href="verifikasi.php" class="list-group-item list-group-item-action"><i
                            class="bi bi-check-circle me-2"></i>
                        Verifikasi Donasi</a>
                    <a href="kas.php" class="list-group-item list-group-item-action active-menu"><i
                            class="bi bi-cash me-2"></i>
                        Laporan Kas</a>
                    <a href="program.php" class="list-group-item list-group-item-action"><i
                            class="bi bi-calendar-plus"></i>
                        Program Masjid</a>
                    <a href="user.php" class="list-group-item list-group-item-action"><i class="bi bi-people me-2"></i>
                        User
                        Manajemen</a>
                    <a href="logout.php" class="list-group-item list-group-item-action mt-5 text-danger"><i
                            class="bi bi-box-arrow-right me-2"></i> Logout</a>
                </nav>
            </div>
        </div>

        <div id="page-content-wrapper" class="w-100" style="background-color: var(--putih);">
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
                <h5 class="fw-bold m-0">Laporan Kas</h5>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 small text-muted">Selamat datang <?= $username ?> !</span>
                    <i class="bi bi-person-circle fs-4"></i>
                </div>
            </nav>


            <?php if (isset($_GET['aksi']) && $_GET['aksi'] == 'editKas'):
                $id = $_GET['id'];
                $query = mysqli_query($connect, "SELECT * from kas where kasID = '$id' ");
                $data = mysqli_fetch_array($query); ?>

                <div class="container p-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="bg-white p-5 rounded-4 shadow-sm border">
                                <div class="mb-4 text-center">
                                    <h4 class="fw-bold text-dark">Edit Kas</h4>
                                </div>

                                <hr class="mb-4 opacity-10">

                                <form action="kas.php?aksi=simpanEdit&id=<?= $data['kasID'] ?>" method="POST">
                                    <div class="mb-3 row align-items-center">
                                        <label for="kasID" class="col-sm-3 col-form-label fw-semibold">Kas ID</label>
                                        <div class="col-sm-9 text-end">
                                            <input type="text" readonly class="form-control-plaintext text-muted fw-bold"
                                                id="kasID" value="<?= $data['kasID'] ?>" style="text-align: right;">
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-center">
                                        <label for="inputTanggal"
                                            class="col-sm-3 col-form-label fw-semibold">Tanggal</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control rounded-pill px-3" name="tanggal"
                                                id="inputTanggal" value="<?= $data['tanggal'] ?>">
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label for="validationJenis"
                                            class="col-sm-3 col-form-label fw-semibold">Jenis</label>
                                        <div class="col-sm-9">
                                            <select class="form-select rounded-pill px-3" name="jenis" id="validationJenis"
                                                required>
                                                <option disabled selected>Jenis Kas...</option>
                                                <option value="Kas Masuk" <?= ($data['jenis'] == 'Kas Masuk') ? 'selected' : ''; ?>>Kas Masuk</option>
                                                <option value="Kas Keluar" <?= ($data['jenis'] == 'Kas Keluar') ? 'selected' : ''; ?>>Kas Keluar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label for="validationProgram"
                                            class="col-sm-3 col-form-label fw-semibold">Program</label>
                                        <div class="col-sm-9">
                                            <select class="form-select rounded-pill px-3" name="program"
                                                id="validationProgram" required>
                                                <option value="" disabled selected>Pilih Program...</option>
                                                <?php while ($program = mysqli_fetch_assoc($queryProgram)): ?>
                                                    <option value="<?= $program['programID'] ?>"
                                                        <?= ($program['programID'] == $data['programID']) ? 'selected' : ''; ?>>
                                                        <?= $program['nama'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-center">
                                        <label for="inputKet" class="col-sm-3 col-form-label fw-semibold">Keterangan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control rounded-pill px-3" name="keterangan"
                                                id="inputKet" value="<?= $data['keterangan'] ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-center">
                                        <label for="inputNominal"
                                            class="col-sm-3 col-form-label fw-semibold">Nominal</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" class="form-control rounded-pill px-3" name="nominal"
                                                    id="inputNominal" value="<?= $data['nominal'] ?>">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <a href="kas.html"><button type="button"
                                                class="btn btn-light rounded-pill px-4">Batal</button></a>
                                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Simpan
                                            Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



            <?php elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'inputKas'): ?>
                <div class="container p-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="bg-white p-5 rounded-4 shadow-sm border">
                                <div class="mb-4 text-center">
                                    <h4 class="fw-bold text-dark">Tambah Kas</h4>
                                </div>

                                <hr class="mb-4 opacity-10">

                                <form action="kas.php?aksi=tambahKas" method="POST">
                                    <div class="mb-3 row align-items-center">
                                        <label for="inputTanggal"
                                            class="col-sm-3 col-form-label fw-semibold">Tanggal</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control rounded-pill px-3" name="tanggal"
                                                id="inputTanggal">
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label for="validationJenis"
                                            class="col-sm-3 col-form-label fw-semibold">Jenis</label>
                                        <div class="col-sm-9">
                                            <select class="form-select rounded-pill px-3" name="jenis" id="validationJenis"
                                                required>
                                                <option disabled selected>Pilih Jenis...</option>
                                                <option value="Kas Masuk">Masuk</option>
                                                <option value="Kas Keluar">Keluar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label for="validationProgram"
                                            class="col-sm-3 col-form-label fw-semibold">Program</label>
                                        <div class="col-sm-9">
                                            <select class="form-select rounded-pill px-3" name="program"
                                                id="validationProgram" required>
                                                <option value="" disabled selected>Pilih Program...</option>
                                                <?php while ($program = mysqli_fetch_assoc($queryProgram)): ?>
                                                    <option value="<?= $program['programID'] ?>"><?= $program['nama'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-center">
                                        <label for="inputKet" class="col-sm-3 col-form-label fw-semibold">Keterangan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control rounded-pill px-3" name="keterangan"
                                                id="inputKet" placeholder="Masukan keterangan...">
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-center">
                                        <label for="inputNominal"
                                            class="col-sm-3 col-form-label fw-semibold">Nominal</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" class="form-control rounded-pill px-3" name="nominal"
                                                    id="inputNominal" placeholder="contoh: 1000000" required>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-4">
                                            <a href="kas.php"><button type="button"
                                                    class="btn btn-light rounded-pill px-4">Batal</button></a>
                                            <button type="submit"
                                                class="btn btn-success rounded-pill px-4 shadow-sm">Tambah</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            <?php else: ?>
                <div class="container-fluid p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="stat-card p-4 shadow-sm rounded-4" style="background-color: var(--sage);">
                                <small class="text-muted fw-bold">TOTAL SALDO KAS</small>
                                <h2 class="fw-bold mt-2" style="color: var(--hijau-muda);">
                                    <?= "Rp " . number_format($saldo, 2, ',', '.'); ?>
                                </h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card p-4 shadow-sm rounded-4 bg-white border-5">
                                <i class="bi bi-arrow-up-circle text-uppercase" style="color: var(--hijau-muda);"></i>
                                <small class="text-muted fw-bold text-uppercase">Pemasukan</small>
                                <h2 class="fw-bold mt-2" style="color: var(--hijau-muda);">
                                    <?= "Rp " . number_format($pemasukan, 2, ',', '.'); ?>
                                </h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card p-4 shadow-sm rounded-4 bg-white border-5">
                                <i class="bi bi-arrow-down-circle text-uppercase" style="color: maroon;"></i>
                                <small class="text-muted fw-bold text-uppercase">Pengeluaran</small>
                                <h2 class="fw-bold mt-2" style="color: maroon;">
                                    <?= "Rp " . number_format($pengeluaran, 2, ',', '.'); ?>
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold">Laporan Kas</h5>
                            <div class="col-md-9">
                                <form action="kas.php" method="GET">
                                <div class="d-flex align-items-center justify-content-start flex-wrap gap-2">
                                    <select class="form-select rounded-pill px-3" name="bulan" style="max-width: 140px; height: 35px;">
                                        <option value="" disabled selected>Pilih Bulan...</option>
                                        <?php $bulan = [
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

                                    <h5 class="fw-bold" style="color: var(--hijau-muda);">/</h5>

                                    <input type="number" class="form-select rounded-pill px-3" name="tahun"
                                        value="<?= $_GET['tahun'] ?? date('Y') ?>" style="max-width: 140px; height: 35px;">

                                    <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                                    <button type="reset" class="btn btn-sm btn-outline-danger">Reset</button>
                                </div>  
                                </form>
                            </div>
                            <a href="kas.php?aksi=inputKas"><button class="btn btn-sm btn-outline-dark"><i
                                        class="bi bi-plus"> Tambah Kas</i></button></a>
                        </div>
                        <div class="table-responsive" style="max-height: 80vh; overflow-y: auto; scroll-behavior: smooth;">
                            <table class="table align-middle custom-admin-table">
                                <thead>
                                    <tr>
                                        <th>Kas ID</th>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Program</th>
                                        <th>Keterangan</th>
                                        <th>Nominal</th>
                                        <th>Saldo</th>
                                        <?php if ($_SESSION['role'] == 'administrator'): ?>
                                        <th class="text-center pe-4">Aksi</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = mysqli_fetch_assoc($query)):
                                        $queryProgram = mysqli_query($connect, "SELECT * from program WHERE programID = '{$data['programID']}'");
                                        $program = mysqli_fetch_assoc($queryProgram); ?>
                                        <tr>
                                            <td class="ps-4 fw-bold"><?= $data['kasID'] ?></td>
                                            <td><?= $data['tanggal'] ?></td>
                                            <td>
                                                <span class="badge <?= ($data['jenis'] == 'Kas Masuk') ? 'bg-sage text-success' : 'bg-sage text-danger'; ?> rounded-pill px-3 " ><?= $data['jenis'] ?></span>
                                            </td>
                                            <td><span
                                                    class="badge bg-light text-secondary-emphasis rounded-pill px-3"><?= $program['nama'] ?></span>
                                            </td>
                                            <td><?= $data['keterangan'] ?></td>
                                            <td
                                                class="fw-bold <?= ($data['jenis'] == 'Kas Masuk') ? 'text-success' : 'text-danger'; ?>">
                                                <?= "Rp " . number_format($data['nominal'], 2, ',', '.'); ?>
                                            </td>
                                            <td class="ps-4 fw-bold"><?= "Rp " . number_format($data['saldo'], 2, ',', '.'); ?>
                                            </td>
                                            <?php if ($_SESSION['role'] == 'administrator'): ?>
                                            <td class="text-center">
                                                <a href="kas.php?aksi=editKas&id=<?= $data['kasID']; ?>"><button
                                                        class="btn btn-sm btn-outline-dark"><i
                                                            class="bi bi-pencil"></i></button></a>
                                                <a href="kas.php?aksi=hapus&id=<?= $data['kasID']; ?>"><button
                                                        class="btn btn-sm btn-coklat"><i class="bi bi-trash"></i></button></a>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>