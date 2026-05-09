<?php
include "koneksi.php";
    session_start();
    if(!isset($_SESSION['userID'])){
        header("Location: login.php");
    }

	$query = mysqli_query($connect, "SELECT * from user");
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahAdmin') {
	    mysqli_query($connect, "INSERT INTO user(username, password, email, role, status) VALUES ('{$_POST['username']}','{$_POST['password']}','{$_POST['email']}', '{$_POST['role']}','{$_POST['status']}')");
	    header("Location: user.php");
	    exit;
	}
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpanAdmin') {
	    $id = $_GET['id'];
	    mysqli_query($connect, "UPDATE user SET username='{$_POST['username']}', password='{$_POST['password']}', email='{$_POST['email']}', role='{$_POST['role']}', status='{$_POST['status']}' where userID = '$id'");
	    header("Location: user.php");
	    exit;
	}
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus'){
	    $id = $_GET['id'];
	    mysqli_query($connect, "DELETE FROM user WHERE userID = '$id'");
	    header("Location: user.php");
	    exit;
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Manajemen</title>

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
                    <a href="dashboard.html" class="list-group-item list-group-item-action"><i
                            class="bi bi-speedometer2 me-2"></i>
                        Dashboard</a>
                    <a href="verifikasi.html" class="list-group-item list-group-item-action"><i
                            class="bi bi-check-circle me-2"></i>
                        Verifikasi Donasi</a>
                    <a href="kas.html" class="list-group-item list-group-item-action"><i class="bi bi-cash me-2"></i>
                        Laporan Kas</a>
                    <a href="program.html" class="list-group-item list-group-item-action"><i class="bi bi-calendar-plus"></i>
                        Program Masjid</a>
                    <a href="user.html" class="list-group-item list-group-item-action active-menu"><i
                            class="bi bi-people me-2"></i> User
                        Manajemen</a>
                    <a href="index.html" class="list-group-item list-group-item-action mt-5 text-danger"><i
                            class="bi bi-box-arrow-right me-2"></i> Logout</a>
                </nav>
            </div>
        </div>

        <div id="page-content-wrapper" class="w-100" style="background-color: var(--putih);">
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
                <h5 class="fw-bold m-0">User Manajemen</h5>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 small text-muted">Selamat Datang, Admin</span>
                    <i class="bi bi-person-circle fs-4"></i>
                </div>
            </nav>

    <?php if(isset($_GET['aksi'])&& $_GET['aksi'] == 'editAdmin'): 
        $id = $_GET['id'];
        $query = mysqli_query($connect, "SELECT * from user where userID = '$id'");
        $data = mysqli_fetch_assoc($query); ?>
        <form action="adminUser.php?aksi=simpanAdmin&id=<?= $data['userID']?>" method="POST">
            <label for="id">ID : </label>
            <i><?= $data['userID']?></i><br>

            <input type="text" name="username" value="<?=$data['username']?>"><br>
            <input type="text" name="password" value="<?=$data['password']?>"><br>
            <input type="text" name="email" value="<?=$data['email']?>"><br>
            <select name="role">
                <option value="administrator" <?=($data['role'] == 'administrator')? 'selected': ''?>>Administrator</option>
                <option value="bendahara" <?=($data['role'] == 'bendahara')? 'selected': ''?>>Bendahara</option>
            </select><br>
            <select name="status">
                <option value="aktif" <?=($data['status'] == 'aktif')? 'selected': ''?>>Aktif</option>
                <option value="nonaktif" <?=($data['status'] == 'nonaktif')? 'selected': ''?>>Nonaktif</option>
            </select><br>
            <button type="submit">Submit</button>
        </form>


            <div class="container-fluid p-4">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold">Data User SIGMA</h5>
                    </div>
                </div>

                            <hr class="mb-4 opacity-10">

                            <form action="update_proses.php" method="POST">
                                <div class="mb-3 row align-items-center">
                                    <label for="programID" class="col-sm-3 col-form-label fw-semibold">Program ID</label>
                                    <div class="col-sm-9 text-end">
                                        <input type="text" readonly class="form-control-plaintext text-muted fw-bold"
                                            id="programID" value="PRO-001" style="text-align: right;">
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputUsername" class="col-sm-3 col-form-label fw-semibold">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control rounded-pill px-3" name="nama"
                                            id="inputUsername" placeholder="Beasiswa Pendidikan">
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputPassword" class="col-sm-3 col-form-label fw-semibold">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control rounded-pill px-3" name="nama"
                                            id="inputPassword" placeholder="Beasiswa Pendidikan">
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label for="inputEmail" class="col-sm-3 col-form-label fw-semibold">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control rounded-pill px-3" name="nama"
                                            id="inputEmail" placeholder="Beasiswa Pendidikan">
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label for="role"
                                        class="col-sm-3 col-form-label fw-semibold">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-select rounded-pill px-3" name="status"
                                            id="role" required>
                                            <option selected disabled value="">Pilih Role...</option>
                                            <option value="administrator">Administrator</option>
                                            <option value="bendahara">Bendahara</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label for="validationStatus"
                                        class="col-sm-3 col-form-label fw-semibold">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-select rounded-pill px-3" name="status"
                                            id="validationStatus" required>
                                            <option selected disabled value="">Pilih Status...</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Non-Aktif">Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>
            </div>




    <?php else:?>

            <div class="container-fluid p-4">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold">Data User SIGMA</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle custom-admin-table">
                            <thead>
                                <tr>
                                    <th>ID User</th>
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php while($data=mysqli_fetch_assoc($query)):?>
                                <tr>
                                    <td><?=$data['userID']?></td>
                                    <td><b><?=$data['username']?></b></td>
                                    <td><?=$data['email']?></td>
                                    <td><span class="badge bg-sage text-success"><?=$data['role']?></span></td>
                                    <td>
                                        <?php if ($data['status'] == 'aktif'): ?>
                                            <span class="badge bg-warning-subtle text-success rounded-pill">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning rounded-pill">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="user.php?aksi=editAdmin&id=<?=$data['userID']?>"><button class="btn btn-sm btn-outline-dark"><i
                                                class="bi bi-pencil"></i></button></a>
                                        <a href="user.php?aksi=hapus&id=<?=$data['userID']?>" 
		                       onclick="return confirm('Hapus user ini?')"><button class="btn btn-sm btn-coklat"><i class="bi bi-shield-lock"></i></button></a>
                                    </td>
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
