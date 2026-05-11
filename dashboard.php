<?php
include "koneksi.php";
    session_start();
    if(!isset($_SESSION['userID'])){
        header("Location: login.php");
    }
    $queryUser = mysqli_query($connect, "SELECT username from user where userID = '{$_SESSION['userID']}'" );
    $user = mysqli_fetch_assoc($queryUser); $username = $user['username'];
    $queryVerifikasi = mysqli_query($connect, "SELECT * from donasi order by tanggal desc limit 3");

    $total = mysqli_query($connect, "SELECT 
        sum(case when jenis = 'Kas Masuk' then nominal else 0 end) AS totalMasuk,
        sum(case when jenis = 'Kas Keluar' then nominal else 0 end) AS totalKeluar
        FROM kas");
    $queryProgram= mysqli_query($connect, "SELECT * from program where status = 'aktif'");
    $data_total = mysqli_fetch_assoc($total);
    $pemasukan  = $data_total['totalMasuk'] ?? 0;
    $pengeluaran = $data_total['totalKeluar'] ?? 0;
    $saldo  = $pemasukan - $pengeluaran;
    
    $queryDonasi = mysqli_query($connect, "SELECT count(*) as d from donasi where status='Belum Terverifikasi'");
    $queryProgram = mysqli_query($connect, "SELECT count(*) as p from program where status = 'aktif'");
    $perluVerif= mysqli_fetch_assoc($queryDonasi);
    $programAktif= mysqli_fetch_assoc($queryProgram);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

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
                    <a href="dashboard.php" class="list-group-item list-group-item-action active-menu"><i 
                        class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="verifikasi.php" class="list-group-item list-group-item-action"><i class="bi bi-check-circle me-2"></i> 
                        Verifikasi Donasi</a>
                    <a href="kas.php" class="list-group-item list-group-item-action"><i class="bi bi-cash me-2"></i>
                        Laporan Kas</a>
                    <a href="program.php" class="list-group-item list-group-item-action"><i class="bi bi-calendar-plus"></i>
                         Program Masjid</a>
                    <a href="user.php" class="list-group-item list-group-item-action"><i class="bi bi-people me-2"></i> User
                        Manajemen</a>
                    <a href="logout.php" class="list-group-item list-group-item-action mt-5 text-danger"><i
                            class="bi bi-box-arrow-right me-2"></i> Logout</a>
                </nav>
            </div>
        </div>

        <div id="page-content-wrapper" class="w-100" style="background-color: var(--putih);">
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
                <h5 class="fw-bold m-0">Ringkasan Statistik</h5>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 small text-muted">Selamat datang <?=$username?> !</span>
                    <i class="bi bi-person-circle fs-4"></i>
                </div>
            </nav>

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
                        <div class="stat-card p-4 shadow-sm rounded-4 bg-white border-start border-warning border-5">
                            <small class="text-muted fw-bold text-uppercase">Donasi Perlu Verifikasi</small>
                            <h2 class="fw-bold mt-2" style="color: var(--coklat);"><?=$perluVerif['d']?> Transaksi</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card p-4 shadow-sm rounded-4 bg-white border-start border-success border-5">
                            <small class="text-muted fw-bold text-uppercase">Program Aktif</small>
                            <h2 class="fw-bold mt-2" style="color: var(--hijau-tua);"><?=$programAktif['p']?> Program</h2>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold">Antrian Verifikasi Donasi</h5>
                        <a href="verifikasi.php"><button class="btn btn-sm btn-outline-dark rounded-pill">Lihat Semua</button></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle custom-admin-table">
                            <thead>
                                <tr>
                                    <th>Donasi ID</th>
                                    <th>Donatur</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Nomor Bukti</th>
                                    <th>Status</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($data = mysqli_fetch_assoc($queryVerifikasi)):
            $queryProgram= mysqli_query($connect, "SELECT * from program WHERE programID = '{$data['programID']}'");
            $program = mysqli_fetch_assoc($queryProgram); ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= $data['donasiID']?></td>
                                    <td><?= $data['nama']?></td>
                                    <td><?= $data['tanggal']?></td>
                                    <td><span class="badge bg-sage text-success rounded-pill px-3"><?= $program['nama']?></span></td>
                                    <td class="fw-bold"><?= "Rp " . number_format($data['nominal'], 2, ',', '.'); ?></td>
                                    <td><span class="text-muted"><?= $data['noRef']?></span></td>
                                    <td>
                                        <?php if ($data['status'] == 'Terverifikasi'): ?>
                                            <span class="badge bg-warning-subtle text-success rounded-pill">Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning rounded-pill">Belum Terverifikasi</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-4">

                                        <div class="d-flex gap-2 justify-content-center">
                                        <?php if ($data['status'] == 'Belum Terverifikasi' || empty($data['status'])): ?>
                                            <a class="btn btn-sm btn-success rounded-pill px-3" href="verifikasi.php?aksi=verifikasi&id=<?= $data['donasiID']; ?>" 
                                               onclick="return confirm('Verifikasi donasi ini?')"
                                               style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
                                               Verifikasi
                                            </a>                    
                                        <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>