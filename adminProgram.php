<?php
include "koneksi.php";
	$query = mysqli_query($connect, "SELECT * from program");
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahProgram') {
	    mysqli_query($connect, "INSERT INTO program(nama, deskripsi, target, status) VALUES ('{$_POST['nama']}','{$_POST['deskripsi']}','{$_POST['target']}','{$_POST['status']}')");
	    header("Location: adminProgram.php");
	    exit;
	}
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpanProgram') {
	    $id = $_GET['id'];
	    mysqli_query($connect, "UPDATE program SET nama='{$_POST['nama']}', deskripsi='{$_POST['deskripsi']}', target='{$_POST['target']}', status='{$_POST['status']}' where programID = '$id'");
	    header("Location: adminProgram.php");
	    exit;
	}
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus'){
	    $id = $_GET['id'];
	    mysqli_query($connect, "DELETE FROM program WHERE programID = '$id'");
	    header("Location: adminProgram.php");
	    exit;
	}
?>
    <?php if(isset($_GET['aksi'])&& $_GET['aksi'] == 'inputProgram'):?>
	    <form action="adminProgram.php?aksi=tambahProgram" method="POST">
	    	<input type="text" name="nama" placeholder="nama"><br>
	    	<input type="text" name="deskripsi" placeholder="deskripsi"><br>
	    	<input type="number" name="target" placeholder="nominal"><br>
	    	<select name="status">
	    		<option value="aktif">Aktif</option>
	    		<option value="nonaktif">Nonaktif</option>
	    	</select><br>
	    	<button type="submit">Submit</button>
	    </form>


    <?php elseif(isset($_GET['aksi'])&& $_GET['aksi'] == 'editProgram'): 
		$id = $_GET['id'];
		$queryProgram = mysqli_query($connect, "SELECT * from program where programID = '$id'");
		$program = mysqli_fetch_assoc($queryProgram); ?>
	    <form action="adminProgram.php?aksi=simpanProgram&id=<?=$program['programID']?>" method="POST">
	    	<input type="text" name="nama" value="<?=$program['nama']?>"><br>
	    	<input type="text" name="deskripsi" value="<?=$program['deskripsi']?>"><br>
	    	<input type="number" name="target" value="<?=$program['target']?>"><br>
	    	<select name="status">
	    		<option value="aktif" <?=($program['status'] == 'aktif')? 'selected': ''?>>Aktif</option>
	    		<option value="nonaktif" <?=($program['status'] == 'nonaktif')? 'selected': ''?>>Nonaktif</option>
	    	</select><br>
	    	<button type="submit">Submit</button>
	    </form>
 

	<?php else: ?>
		<a href="adminProgram.php?aksi=inputProgram" 
		     style="padding: 5px 10px; background: blue; color: white; text-decoration: none; border-radius: 3px;">
		     Tambah
		</a><br> <br> 
		<table border="1" cellpadding="8">
			<tr>
				<th>Program ID</th>
				<th>Nama Program</th>
				<th>Deskripsi</th>			
				<th>Target</th>
				<th>Status</th>
				<th>Aksi</th>
			</tr>
			<?php while ($data=mysqli_fetch_assoc($query)) { ?>
				<tr>
					<td><?=$data['programID']?></td>
					<td><?=$data['nama']?></td>
					<td><?=$data['deskripsi']?></td>
					<td><?=$data['target']?></td>
					<td><?=$data['status']?></td>	
					<td>
		                    <a href="adminProgram.php?aksi=editProgram&id=<?=$data['programID']?>" 
		                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
		                       Edit
		                    </a>  
		                    <a href="adminProgram.php?aksi=hapus&id=<?=$data['programID']?>" 
		                       onclick="return confirm('Hapus record ini?')"
		                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
		                       Hapus
		                    </a>  		
					</td>
				</tr>
			<?php } ?>
		</table>

<?php endif;?>