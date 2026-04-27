<?php
require 'koneksi.php';
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpanEdit') {
        $id = $_GET['id'];
        mysqli_query($connect, "UPDATE kas SET tanggal='{$_POST['tanggal']}', jenis='{$_POST['jenis']}', kategori='{$_POST['kategori']}', nominal='{$_POST['nominal']}', keterangan='{$_POST['keterangan']}' WHERE kasID = '$id'");
        header("Location: kas.php");
        exit;
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahKas') {
        mysqli_query($connect, "INSERT INTO kas(tanggal, jenis, kategori, nominal, keterangan) VALUES ('{$_POST['tanggal']}','{$_POST['jenis']}','{$_POST['kategori']}', '{$_POST['nominal']}','{$_POST['keterangan']}')");
        header("Location: kas.php");
        exit;
    }
?>

    <?php if(isset($_GET['aksi'])&& $_GET['aksi'] == 'editKas'): 
        $id = $_GET['id'];
        $query = mysqli_query($connect, "SELECT * from kas where kasID = '$id' ");
        $data = mysqli_fetch_array($query); ?>
        	<form action="edit.php?aksi=simpanEdit&id=<?=$data['kasID']?>" method="POST">
                <label for="id">ID : </label>
                <i><?= $data['kasID']?></i><br>
                <label for="jenis">Jenis</label>
                <select name="jenis" required>
                    <option value="" disabled selected>Jenis Kas...</option>
                    <option value="Kas Masuk" <?= ($data['jenis'] == 'Kas Masuk')?'selected':''; ?>>Kas Masuk</option>
                    <option value="Kas Keluar" <?= ($data['jenis'] == 'Kas Keluar')?'selected':''; ?>>Kas Keluar</option>
                </select> <br>
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" value="<?= $data['kategori'] ?>" required> <br>
                <label for="tanggal">Tanggal Transfer</label>
                <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required> <br>
                <label for="nominal">Nominal (Rp)</label>
                <input type="number" name="nominal" value="<?= $data['nominal'] ?>" required> <br>
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" value="<?= $data['keterangan'] ?>" required> <br>
                <button type="submit">Simpan Perubahan</button>
            </form>
    <?php endif; ?>

    <?php if(isset($_GET['aksi'])&& $_GET['aksi'] == 'inputKas'):  ?>
            <form action="edit.php?aksi=tambahKas" method="POST">
                <label for="jenis">Jenis</label>
                <select name="jenis" required>
                    <option value="" disabled selected>Jenis Kas...</option>
                    <option value="Kas Masuk">Kas Masuk</option>
                    <option value="Kas Keluar">Kas Keluar</option>
                </select> <br>
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" required> <br>
                <label for="tanggal">Tanggal Transfer</label>
                <input type="date" name="tanggal" required> <br>
                <label for="nominal">Nominal (Rp)</label>
                <input type="number" name="nominal" required> <br>
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan"> <br>
                <button type="submit">Input kas</button>
            </form>
    <?php endif; ?>