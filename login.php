<?php
include "koneksi.php";
$query = mysqli_query($connect, "SELECT * from user");
	session_start();

if(isset($_GET['aksi'])&& $_GET['aksi']=='login'){
	while ($user = mysqli_fetch_assoc($query)) {
		if($user['username']==$_POST['username'] && $user['password'] == $_POST['password']){
			$_SESSION['userID'] = $user['userID'];
			header("Location: adminKas.php");
		}
		else{
			echo "your username or password is wrong!";
		}
	}
}

?>

	<form action="login.php?aksi=login" method="POST">
		<input type="text" name="username" placeholder="username">
		<input type="password" name="password" placeholder="password">
		<button t>submit</button>
	</form>


