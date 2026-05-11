<?php
include "koneksi.php";
    session_start();
    if(!isset($_SESSION['userID'])){
        header("Location: login.php");
    }
        $queryUser = mysqli_query($connect, "SELECT username from user where userID = '{$_SESSION['userID']}'" );
    $user = mysqli_fetch_assoc($queryUser); $username = $user['username'];

    $query = mysqli_query($connect, "SELECT * from program");
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpanProgram') {
        $id = $_GET['id'];
        mysqli_query($connect, "UPDATE program SET nama='{$_POST['nama']}', gambar='{$_POST['gambar']}', target='{$_POST['target']}', status='{$_POST['status']}' where programID = '$id'");
        header("Location: program.php");
        exit;
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahProgram') {
        mysqli_query($connect, "INSERT INTO program(nama, gambar, target, status) VALUES ('{$_POST['nama']}','{$_POST['gambar']}','{$_POST['target']}','{$_POST['status']}')");
        header("Location: program.php");
        exit;
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus'){
        $id = $_GET['id'];
        mysqli_query($connect, "DELETE FROM program WHERE programID = '$id'");
        header("Location: program.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Masjid</title>

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
                    <a href="dashboard.php" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i> 
                        Dashboard</a>
                    <a href="verifikasi.php" class="list-group-item list-group-item-action"><i class="bi bi-check-circle me-2"></i> 
                        Verifikasi Donasi</a>
                    <a href="kas.php" class="list-group-item list-group-item-action"><i class="bi bi-cash me-2"></i>
                        Laporan Kas</a>
                    <a href="program.php" class="list-group-item list-group-item-action active-menu"><i class="bi bi-calendar-plus"></i>
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
                <h5 class="fw-bold m-0">Program Masjid</h5>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 small text-muted">Selamat datang <?=$username?> !</span>
                    <i class="bi bi-person-circle fs-4"></i>
                </div>
            </nav>

    <?php if(isset($_GET['aksi'])&& $_GET['aksi'] == 'editProgram'): 
        $id = $_GET['id'];
        $queryProgram = mysqli_query($connect, "SELECT * from program where programID = '$id'");
        $program = mysqli_fetch_assoc($queryProgram); ?>

            <div class="container p-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="bg-white p-5 rounded-4 shadow-sm border">
                            <div class="mb-4 text-center">
                                <h4 class="fw-bold text-dark">Edit Program</h4>
                            </div>

                            <hr class="mb-4 opacity-10">

                            <form action="program.php?aksi=simpanProgram&id=<?=$program['programID']?>" method="POST">
                                <div class="mb-3 row align-items-center">
                                    <label for="programID" class="col-sm-3 col-form-label fw-semibold">Program ID</label>
                                    <div class="col-sm-9 text-end">
                                        <input type="text" readonly class="form-control-plaintext text-muted fw-bold"
                                            id="programID" value="<?=$program['programID']?>" style="text-align: right;">
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputNama" class="col-sm-3 col-form-label fw-semibold">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control rounded-pill px-3" name="nama"
                                            id="inputNama" value="<?=$program['nama']?>">
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputTarget" class="col-sm-3 col-form-label fw-semibold">Target</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" class="form-control rounded-pill px-3"
                                                name="target" id="inputTarget" value="<?=$program['target']?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputGambar" class="col-sm-3 col-form-label fw-semibold">Link Gambar</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control rounded-pill px-3" name="gambar"
                                            id="inputGambar" value="<?=$program['gambar']?>">
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label for="validationStatus"
                                        class="col-sm-3 col-form-label fw-semibold">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-select rounded-pill px-3" name="status"
                                            id="validationStatus" required>
                                            <option disabled selected>Pilih Status...</option>
                                            <option value="aktif" <?=($program['status'] == 'aktif')? 'selected': ''?>>Aktif</option>
                                            <option value="nonaktif" <?=($program['status'] == 'nonaktif')? 'selected': ''?>>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="program.php"><button type="button" class="btn btn-light rounded-pill px-4">Batal</button></a>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


<?php elseif(isset($_GET['aksi'])&& $_GET['aksi'] == 'inputProgram'):?>
            <div class="container p-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="bg-white p-5 rounded-4 shadow-sm border">
                            <div class="mb-4 text-center">
                                <h4 class="fw-bold text-dark">Tambah Program</h4>
                            </div>

                            <hr class="mb-4 opacity-10">

                            <form action="program.php?aksi=tambahProgram" method="POST">
                                <div class="mb-3 row align-items-center">
                                    <label for="inputNama" class="col-sm-3 col-form-label fw-semibold">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control rounded-pill px-3" name="nama"
                                            id="inputNama" placeholder="Masukan nama program...">
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputTarget" class="col-sm-3 col-form-label fw-semibold">Target</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" class="form-control rounded-pill px-3"
                                                name="target" id="inputTarget" placeholder="contoh: 1000000">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label for="validationStatus"
                                        class="col-sm-3 col-form-label fw-semibold">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-select rounded-pill px-3" name="status"
                                            id="validationStatus" required>
                                            <option disabled selected>Pilih Status...</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Non-Aktif">Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputGambar" class="col-sm-3 col-form-label fw-semibold">Link Gambar</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control rounded-pill px-3" name="gambar"
                                            id="inputGambar" placeholder="Masukan link gambar...">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="program.html"><button type="button" class="btn btn-light rounded-pill px-4">Batal</button></a>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



<?php else:?>
            <div class="container-fluid p-4">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold">Daftar Program Masjid</h5>
                        <?php if ($_SESSION['role'] == 'administrator'): ?>
                        <a href="program.php?aksi=inputProgram"><button class="btn btn-sm btn-outline-dark"><i class="bi bi-plus"> Tambah Program</i></button></a>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle custom-admin-table">
                            <thead>
                                <tr>
                                    <th>Program ID</th>
                                    <th>Nama</th>
                                    <th>Target</th>
                                    <th class="text-center pe-4">Status</th>
                                    <?php if ($_SESSION['role'] == 'administrator'): ?>
                                    <th class="text-center pe-4">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while ($data=mysqli_fetch_assoc($query)): ?>                              
                                <tr>
                                    <td class="ps-4 fw-bold"><?=$data['programID']?></td>
                                    <td><?=$data['nama']?></td>
                                    <td><?=$data['target']?></td>
                                    <td class="text-center pe-4">
                                        <?php if ($data['status'] == 'aktif'): ?>
                                            <span class="badge bg-warning-subtle text-success rounded-pill">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning rounded-pill">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($_SESSION['role'] == 'administrator'): ?>
                                    <td class="text-center">
                                        <a href="program.php?aksi=editProgram&id=<?= $data['programID']; ?>"><button class="btn btn-sm btn-outline-dark"><i
                                                class="bi bi-pencil"></i></button></a>
                                        <a href="program.php?aksi=hapus&id=<?= $data['programID']; ?>"><button class="btn btn-sm btn-coklat"><i class="bi bi-trash"></i></button></a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif;?>
        </div>
    </div>
</body>

</html>