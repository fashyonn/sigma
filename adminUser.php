<?php
include "koneksi.php";
	$query = mysqli_query($connect, "SELECT * from user");
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambahAdmin') {
	    mysqli_query($connect, "INSERT INTO user(username, password, email, role, status) VALUES ('{$_POST['username']}','{$_POST['password']}','{$_POST['email']}', '{$_POST['role']}','{$_POST['status']}')");
	    header("Location: adminUser.php");
	    exit;
	}
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpanAdmin') {
	    $id = $_GET['id'];
	    mysqli_query($connect, "UPDATE user SET username='{$_POST['username']}', password='{$_POST['password']}', email='{$_POST['email']}', role='{$_POST['role']}', status='{$_POST['status']}' where userID = '$id'");
	    header("Location: adminUser.php");
	    exit;
	}
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus'){
	    $id = $_GET['id'];
	    mysqli_query($connect, "DELETE FROM user WHERE userID = '$id'");
	    header("Location: adminUser.php");
	    exit;
	}
?>



    <?php if(isset($_GET['aksi'])&& $_GET['aksi'] == 'inputAdmin'):?>
	    <form action="adminUser.php?aksi=tambahAdmin" method="POST">
	    	<input type="text" name="username" placeholder="username"><br>
	    	<input type="text" name="password" placeholder="password"><br>
	    	<input type="text" name="email" placeholder="email"><br>
	    	<select name="role">
	    		<option value="administrator">Administrator</option>
	    		<option value="bendahara">Bendahara</option>
	    	</select><br>
	    	<select name="status">
	    		<option value="aktif">Aktif</option>
	    		<option value="nonaktif">Nonaktif</option>
	    	</select><br>
	    	<button type="submit">Submit</button>
	    </form>




	<?php elseif(isset($_GET['aksi'])&& $_GET['aksi'] == 'editAdmin'): 
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




	<?php else:?>
		<a href="adminUser.php?aksi=inputAdmin" 
		     style="padding: 5px 10px; background: blue; color: white; text-decoration: none; border-radius: 3px;">
		     Tambah
		</a><br> <br> 
		<table border="1" cellpadding="8">
			<tr>
				<th>User ID</th>
				<th>Username</th>
				<th>Email</th>			
				<th>Role</th>
				<th>Status</th>
				<th>Aksi</th>
			</tr>
			<?php while ($data=mysqli_fetch_assoc($query)) { ?>
				<tr>
					<td><?=$data['userID']?></td>
					<td><?=$data['username']?></td>
					<td><?=$data['email']?></td>
					<td><?=$data['role']?></td>
					<td><?=$data['status']?></td>	
					<td>
		                    <a href="adminUser.php?aksi=editAdmin&id=<?=$data['userID']?>" 
		                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
		                       Edit
		                    </a>  
		                    <a href="adminUser.php?aksi=hapus&id=<?=$data['userID']?>" 
		                       onclick="return confirm('Hapus record ini?')"
		                       style="padding: 5px 10px; background: black; color: white; text-decoration: none; border-radius: 3px;">
		                       Hapus
		                    </a>  		
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php endif; ?>
