<!--Created by Youliang Pan-->
<!-- Net id: yp89-->
<!-- info2300, 3-10-15-->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type= "text/css" href="css/stylesheet.css" />
	<title>Vincent Pan's Photo Gallery</title>
</head>
<body>
<div id="container">
	<div id="header">Vincent's Photo Gallery
	</div>
		<div id="navdiv">
			<ul class="mblinks">
				<li><a href="AddPhoto.php">Edit Photos</a><li>
				<li><a href="AddAlbum.php">Edit Albums</a></li>
				<li><a href="Search.php">Search Photos</a></li>
				<li><a href="Photos.php">Photos</a></li>
				<li><a href="Albums.php">Albums</a></li>
				<li><a href="index.php" class="selected">Home</a></li>
			</ul>
		</div>
		<div id="content">

			<h2>Welcome to my Gallery of Art </h2>
			<?php
				$loginform = '<form action="index.php" method="post">

				<p>Username: <input type="text" name="username"> </p>
				<p>Password: <input type="password" name="password"> </p>
				<br>
				<input type="submit" name = "Submit" value = "Submit">
			</form>';
				if (!isset( $_POST['username'] ) && !isset( $_POST['password'] ) ) {
					echo '<p> Please log in to create/update albums or upload photos </p>';
					print $loginform;
			?>

			<?php
				} elseif (isset($_POST['Submit'])){
					$username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
					$password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
					$hashedpassword = hash('sha256',$password);
					$_SESSION['logged_user'] = $username;
					require_once 'config.php';
					$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
					$users = $mysqli-> query("SELECT * FROM Users");
						while($row = $users -> fetch_assoc()){
							$dbusername = $row['UserNames'];
							$dbpass = $row['Passwords'];
						}
					if($username == $dbusername && $hashedpassword == $dbpass){
						echo ("<p>Congratulations, $username , You have successfully logged in!<p>");
						print("You now have access to the Add Albums or Add Photos tab!");
						$_SESSION[ 'logged_user' ] = $username;
					} else {
						echo '<p>You did not login successfully.</p>';
						echo '<p>Please <a href="login.php">login</a></p>';
					}
				}
			?>
			<form action = "index.php" method = "post">
				<input type="submit" name = "Logout" value = "Logout">
			</form>
			<?php
				if(isset($_POST['Logout'])){
					unset($_SESSION["logged_user"] );
					unset($_SESSION);
					$_SESSION = array();
					session_destroy();
					print "You have logged out!";
				}
			?>
		</div>
</div><!-- container div tag close-->
</body><!-- body div tag close -->
</html><!-- html div tag close -->