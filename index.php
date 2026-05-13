<?php
include 'koneksi.php';
if (isset($_GET['tanggalMulai']) && isset($_GET['tanggalSelesai'])) {
    $queryRange = mysqli_query($connect, "SELECT * FROM(
            SELECT *,
              SUM(CASE 
                  when jenis = 'Kas Masuk' then nominal 
                  when jenis = 'Kas Keluar' then -nominal 
                  ELSE 0 
              END) OVER (ORDER BY tanggal ASC, kasID ASC) AS saldo
            FROM kas
            ) AS t 
            WHERE tanggal BETWEEN '{$_GET['tanggalMulai']}' AND '{$_GET['tanggalSelesai']}'
            ORDER BY tanggal desc");
} else {
    $queryRange = mysqli_query($connect, "SELECT *, sum(case 
                    when jenis = 'Kas Masuk' then nominal 
                    when jenis = 'Kas Keluar' then -nominal 
                    else 0 
                end) over (order by tanggal asc, kasID asc) AS saldo
            from kas
            order by tanggal desc, kasID desc");
}

$total = mysqli_query($connect, "SELECT 
		sum(case when jenis = 'Kas Masuk' then nominal else 0 end) AS totalMasuk,
		sum(case when jenis = 'Kas Keluar' then nominal else 0 end) AS totalKeluar
		FROM kas");

$queryProgram = mysqli_query($connect, "SELECT * from program where status = 'aktif'");
$queryPilih = mysqli_query($connect, "SELECT * from program where status = 'aktif'");
$queryRecent = mysqli_query($connect, "SELECT * from donasi where status = 'Terverifikasi' order by donasiID desc limit 1");
$data_total = mysqli_fetch_assoc($total);
$pemasukan = $data_total['totalMasuk'] ?? 0;
$pengeluaran = $data_total['totalKeluar'] ?? 0;
$saldo = $pemasukan - $pengeluaran;

if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahDonasi') {
    mysqli_query($connect, "INSERT INTO donasi(tanggal, programID, nama, nominal, noRef) VALUES ('{$_POST['tanggal']}','{$_POST['program']}','{$_POST['nama']}', '{$_POST['nominal']}','{$_POST['noRef']}')");
    header("Location: index.php?aksi=terimakasih#donasi");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA</title>

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
    <nav class="navbar navbar-expand-lg bg-light shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="asset/SIGMA.png" alt="Logo" height="50" class="me-2">
                <div>
                    <span>SIGMA</span>
                    <sub>Sistem Informasi Gema Muslim Amanah</sub>
                </div>
            </a>

            <div class="ms-auto d-flex align-items-center">

                <a href="login.php" class="btn btn-outline-success rounded-pill px-4 d-flex align-items-center">
                    <i class="bi bi-person-fill me-2"></i> Admin
                </a>
            </div>
        </div>
    </nav>

    <section class="hero-banner" id="beranda">
        <h1 class="hero-title">
            Mewujudkan Transparansi <br> dan Kemajuan Ummat
        </h1>
    </section>

    <section class="about-section">
        <div class="container">
            <div class="glass-box">
                <h2 class="fw-bold mb-4">TENTANG SIGMA</h2>
                <p>SIGMA (Sistem Informasi Gema Muslim Amanah) adalah platform digital yang dirancang untuk memperkuat
                    transparansi dan akuntabilitas organisasi. Kami berfokus pada digitalisasi pengelolaan dana ummat,
                    pelaporan kegiatan secara real-time, dan mempermudah akses informasi bagi para relawan serta donatur
                    secara amanah.</p>
            </div>
        </div>
    </section>

    <section class="programs-section">
        <div class="container">
            <h2 class="section-title">Featured Programs</h2>

            <div class="row g-4 justify-content-center flex-nowrap overflow-auto pb-4 custom-scrollbar" style="scrollbar-width: thin;">
                <?php while ($program = mysqli_fetch_assoc($queryProgram)):
                    $queryTerkumpul = mysqli_query($connect, "SELECT sum(nominal) as terkumpul from kas where programID='{$program['programID']}'");
                    $nominal = mysqli_fetch_assoc($queryTerkumpul);
                    $persen = round(($nominal['terkumpul'] / $program['target']) * 100);
                    ?>
                    <div class="col-md-4">
                        <div class="program-card">
                            <img src="<?= $program['gambar'] ?>" alt="<?= $program['nama'] ?>">
                            <div class="program-content">
                                <h5><?= $program['nama'] ?></h5>
                                <div class="progress-info">
                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?= $persen ?>%"></div>
                                    </div>
                                    <p>Funding Progress <span><?= $persen ?>%</span></p>
                                </div>
                                <a href="#donasi" class="btn-donasi">Donasi Sekarang</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="kas-section py-5" id="kas">
        <div class="container">
            <h2 class="kas-title">Transparansi Kas Masjid</h2>

            <div class="row g-3 mb-5 text-center">
                <div class="col-md-3">
                    <div class="summary-card shadow-sm p-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="bi bi-wallet2 fs-3 me-2"></i>
                            <span class="text-kas-1 fw-bold">Total Saldo</span>
                        </div>
                        <h5 class="fw-bold"><?= "Rp " . number_format($saldo, 2, ',', '.'); ?></h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-card shadow-sm p-3 border-4">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="bi bi-arrow-up-circle fs-3 me-2 arrow-up"></i>
                            <span class="text-kas-2 fw-bold">Pemasukan</span>
                        </div>
                        <h5 class="fw-bold"><?= "Rp " . number_format($pemasukan, 2, ',', '.'); ?></h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-card shadow-sm p-3 border-4">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="bi bi-arrow-down-circle fs-3 me-2 arrow-down"></i>
                            <span class="text-kas-3 fw-bold">Pengeluaran</span>
                        </div>
                        <h5 class="fw-bold"><?= "Rp " . number_format($pengeluaran, 2, ',', '.'); ?></h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-card-donasi shadow-sm p-3 text-white">
                        <span class="text-kas-4 fw-bold d-block mb-1">Donasi Terbaru</span>
                        <?php while ($donasiTerbaru = mysqli_fetch_assoc($queryRecent)):
                            $programTerbaru = mysqli_query($connect, "SELECT * from program where programID = '{$donasiTerbaru['programID']}'"); ?>
                            <h5 class="fw-bold mb-0"><?= "Rp " . number_format($donasiTerbaru['nominal'], 2, ',', '.'); ?>
                            </h5>
                            <?php while ($programRecent = mysqli_fetch_assoc($programTerbaru)): ?>
                                <small class="opacity-75">- <?= $programRecent['nama'] ?></small>
                            <?php endwhile; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="table-container p-4 shadow-sm bg-white rounded-4">
                    <form action="index.php?#kas" method="GET">
                        <div class="row align-items-center mb-3">

                            <div class="col-md-3">
                                <h3 class="fw-bold m-0" style="color: var(--hijau-muda);">Tabel Kas</h3>
                            </div>

                            <div class="col-md-9">
                                <div class="d-flex align-items-center justify-content-end flex-wrap gap-2 mt-2">
                                    <h5 class="fw-bold me-1" style="color: var(--hijau-muda); white-space: nowrap;">Data
                                        Tanggal:</h5>

                                    <input type="date" class="form-control input-tanggal"
                                        style="height: 40px; width: 160px; border-radius: 10px !important;"
                                        name="tanggalMulai" value="<?= $_GET['tanggalMulai'] ?? '' ?>" required>

                                    <h5 class="fw-bold" style="color: var(--hijau-muda);">-</h5>

                                    <input type="date" class="form-control input-tanggal"
                                        style="height: 40px; width: 160px; border-radius: 10px !important;"
                                        name="tanggalSelesai" value="<?= $_GET['tanggalSelesai'] ?? '' ?>" required>

                                    <button type="submit" class="btn btn-primary d-flex align-items-center px-3"
                                        style="height: 40px; border-radius: 8px;">

                                        <i class="bi bi-search me-2"></i> Cari Data

                                    </button>

                                    <button type="reset" class="btn btn-outline-danger d-flex align-items-center px-3"
                                        style="height: 40px; border-radius: 8px;">
                                        Reset
                                    </button>

                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" style="max-height: 80vh; overflow-y: auto; scroll-behavior: smooth;">
                        <table class="table table-hover align-middle custom-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Nominal</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($kas = mysqli_fetch_array($queryRange)):
                                    $queryProgramKas = mysqli_query($connect, "SELECT * from program WHERE programID = '{$kas['programID']}'");
                                    $programKas = mysqli_fetch_assoc($queryProgramKas); ?>
                                    <tr>
                                        <td><?= $kas['tanggal'] ?></td>
                                        <td>
                                            <span
                                                class="badge <?= ($kas['jenis'] == 'Kas Masuk') ? 'bg-sage text-success' : 'bg-sage text-danger'; ?> rounded-pill px-3 "><?= $kas['jenis'] ?></span>
                                        </td>
                                        <td><?= $programKas['nama'] ?></td>
                                        <td><?= $kas['keterangan'] ?></td>
                                        <td
                                            class="fw-bold <?= ($kas['jenis'] == 'Kas Masuk') ? 'text-success' : 'text-danger'; ?>">
                                            <?= "Rp " . number_format($kas['nominal'], 2, ',', '.'); ?>
                                        </td>
                                        <td class="fw-bold" style="color: var(--coklat)">
                                            <?= "Rp " . number_format($kas['saldo'], 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <section class="donation-area" id="donasi">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-5">
                    <div class="payment-card shadow-sm">
                        <h4 class="fw-bold mb-4 text-center">Metode Donasi</h4>
                        <div class="qr-wrapper text-center mb-4">
                            <img src="asset/qr-code.png" alt="QRIS SIGMA" class="img-fluid rounded shadow-sm"
                                style="max-width: 200px; border: 8px solid white;">
                            <p class="mt-3 text-muted small">Scan QRIS a.n Gema Muslim Amanah</p>
                        </div>

                        <div class="bank-list">
                            <div
                                class="bank-item p-3 mb-2 bg-white rounded d-flex justify-content-between align-items-center shadow-sm">
                                <span class="fw-bold text-secondary">BSI</span>
                                <span class="text-success fw-bold">7123456789</span>
                            </div>
                            <div
                                class="bank-item p-3 bg-white rounded d-flex justify-content-between align-items-center shadow-sm">
                                <span class="fw-bold text-secondary">Muamalat</span>
                                <span class="text-success fw-bold">1230009876</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <?php if (isset($_GET['aksi']) && $_GET['aksi'] = 'terimakasih'): ?>
                        <div class="form-card p-4 p-md-5 shadow-sm bg-white border-0 text-center"
                            style="border-radius: 25px;">
                            <div class="card-pemberitahuan">
                                <div class="header-dekoratif">
                                    <div class="lingkaran-ikon">
                                        <i class="bi bi-envelope-paper-heart fs-1" style="color: var(--coklat);"></i>
                                    </div>
                                </div>


                                <div class="card-body px-0 pb-4 pt-0 text-center">
                                    <h1 class="fw-bold mb-2 pt-3 mt-1" style="color: var(--hijau-tua)">Terima Kasih!</h1>

                                    <hr style="color: var(--hijau-tua);">

                                    <h3 class="fw-normal mb-3 px-3" style="color: var(--hijau-muda);">Jazaakumullahu
                                        Khairan!</h3>

                                    <div class="border-y py-2 mb-4 border-light-subtle">
                                        <p class="font-pesan mb-0 px-2">
                                            Terima kasih banyak telah berdonasi di <br>
                                            <strong style="color: var(--hijau-tua);">Masjid Sigma.</strong>
                                        </p>
                                    </div>

                                    <div class="pesan-instruksi mb-4 px-2 py-2 rounded-3"
                                        style="background-color: var(--sage);">
                                        <p class="font-instruksi mb-2 fw-semibold" style="color: var(--hijau-muda);">Langkah
                                            Selanjutnya:</p>
                                        <p class="font-instruksi mb-0">Silahkan hubungi admin untuk verifikasi.</p>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="https://wa.me/6281334081356" target="_blank" class="btn-donasi">
                                            <i class="bi bi-whatsapp me-2"></i> Verifikasi Donasi Sekarang
                                        </a>
                                        <div class="mt-3">
                                            <a href="index.php?#beranda"
                                                class="text-decoration-none text-muted small">Kembali ke Beranda</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="form-card p-4 p-md-5 shadow-sm bg-white border-0" style="border-radius: 25px;">
                            <h4 class="fw-bold mb-4">Konfirmasi Donasi</h4>
                            <form action="index.php?aksi=tambahDonasi" method="POST" id="formDonasi">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Nama donatur"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Tanggal Transfer</label>
                                        <input type="date" name="tanggal" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Jenis Program</label>
                                    <select class="form-select" id="selectProgram" name="program" required>
                                        <option value="" disabled selected>Pilih Program...</option>
                                        <?php while ($program = mysqli_fetch_assoc($queryPilih)): ?>
                                            <option value="<?= $program['programID'] ?>"> <?= $program['nama'] ?> </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Nominal (Rp)</label>
                                        <input type="number" name="nominal" class="form-control"
                                            placeholder="Contoh: 100000" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">No. Bukti Transfer</label>
                                        <input type="text" name="noRef" class="form-control"
                                            placeholder="Nomor referensi/ID" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-3 mt-3 rounded-pill fw-bold">Kirim
                                    Konfirmasi</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</body>

</html>