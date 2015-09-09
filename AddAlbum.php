<!--Created by Youliang Pan-->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type= "text/css" href="css/stylesheet.css" />
	<title>Vincent Pan's Photo Gallery</title>
</head>
<body>
<div id="container">
	<div id="header">Modify Albums
	</div>
		<div id="navdiv">
			<ul class="mblinks">
				<li><a href="AddPhoto.php">Edit Photos</a><li>
				<li><a href="AddAlbum.php" class="selected">Edit Albums</a></li>
				<li><a href="Search.php">Search Photos</a></li>
				<li><a href="Photos.php">Photos</a></li>
				<li><a href="Albums.php">Albums</a></li>
				<li><a href="index.php">Home</a></li>
			</ul>
		</div>
		<div id="content">
			<?php
				if ( isset( $_SESSION[ 'logged_user' ] ) ) {
					//Protected content here
					$logged_user = $_SESSION[ 'logged_user' ];
					print "<p>Welcome, $logged_user !</p>";
					print "<p> To log out, click <a href='index.php'>here</a>";
					print '<div id = "form1">';
					print("<h3> Add an album here: </h3>");
					//SET THE DATE
					require_once 'config.php';
					$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
					date_default_timezone_set("America/New_York");
					$currentdate = date("Y-m-d");
					
					if (isset( $_POST['submit'] ) ) { 
						$AlbumTitle = $_POST['AlbumTitle'];
						if($AlbumTitle != Null){
							$Albums = $mysqli-> query("SELECT AlbumId FROM Albums");
							while($row = $Albums -> fetch_assoc()){
								$AlbumNum = $row['AlbumId'];
							}
							$AlbumNum = $AlbumNum + 1;
							$insertquery = "INSERT INTO Albums (AlbumId, Title, DateCreated, DateModified) VALUES ($AlbumNum,'$AlbumTitle','$currentdate','$currentdate')";
							$mysqli -> query($insertquery);
							echo "<br> Successfully added an album named $AlbumTitle !";
						}
						else{
							echo "Album Title cannot be blank!";
						}
						if(isset($_POST['delete'])){
							$DelAlb = $_POST['DeleteAlb'];
							$Albums1 = $mysqli -> query("SELECT * FROM Albums");
							$mysqli -> query("DELETE FROM Connections WHERE AlbumId = $DelAlb");
							while($row = $Albums1 -> fetch_assoc()){
								$AlbumTitle = $row['Title'];
								$AlbumNumber = $row['AlbumId'];
								if($AlbumNumber == $DelAlb){
									$mysqli -> query("DELETE FROM Albums WHERE AlbumId = $AlbumNumber");
									echo "Album was successfully deleted!"; 
								}
							}
						}
					}
					if(isset($_POST['delete'])){
						$DelAlb = $_POST['DeleteAlb'];
						$Albums2 = $mysqli -> query("SELECT * FROM Albums");
						$mysqli -> query("DELETE FROM Connections WHERE AlbumId = $DelAlb");
						while($row = $Albums2 -> fetch_assoc()){
							$AlbumTitle = $row['Title'];
							$AlbumNumber = $row['AlbumId'];
							if($AlbumNumber == $DelAlb){
								$mysqli -> query("DELETE FROM Albums WHERE AlbumId = $AlbumNumber");
								echo "Album was successfully deleted!"; 
							}
						}
					}
					if(isset($_POST['edit'])){
						$newAlbumTitle = $_POST['newtitle'];
						$EditAlbumId = $_POST['editAlb'];
						if($newAlbumTitle != Null){
							$mysqli -> query("UPDATE Albums SET Title = '$newAlbumTitle' WHERE AlbumId = $EditAlbumId");
							$mysqli -> query("UPDATE Albums SET DateModified = '$currentdate' WHERE AlbumId = $EditAlbumId");
							echo "Album was successfully changed!";
						}
						else{
							echo "Editting Album name failed: Album Title cannot be blank!";
						}
					}
					//Adding albums to database
					//deleting albums from database

					print('
						<form action = "AddAlbum.php" method = "post">
							<h3>Type in the name of your album to create: </h3> 
							<input type = "text" placeholder = "Example Album" name = "AlbumTitle">
							<br>
							<input type="submit" name="submit" value="Create"> 
						</form> 
						</div>
						<div id = "form2">
						<form action = "AddAlbum.php" method = "post">
							<h3> Select an album to delete: </h3>
							<select name = "DeleteAlb">
								<option value = "0"></option>');
						//dynamically generate the form based on the albums in the database, choose the default selected
						//based on either get data or url information
						$Album = $mysqli-> query("SELECT AlbumId, Title FROM Albums");
						while($row = $Album -> fetch_assoc()){
							$AlbumId = $row['AlbumId'];
							$AlbumTitle = $row['Title'];
							print("<option value = $AlbumId");
							print(">$AlbumTitle</option>");
						}
					print('</select>
						<input type = "submit" name = "delete" value = "Delete">
					</form>
					</div>
					<div id = "form3">
					<form action = "AddAlbum.php" method = "post">
						<h3>Edit Albums: </h3>
						<h4> Select an Album and make a new Title </h4>
						<select name = "editAlb">
							<option value = "0"></option>');
								//dynamically generate the form based on the albums in the database, choose the default selected
								//based on either get data or url information
								$Album2 = $mysqli-> query("SELECT AlbumId, Title FROM Albums");
								while($row = $Album2 -> fetch_assoc()){
									$AlbumId = $row['AlbumId'];
									$AlbumTitle = $row['Title'];
									print("<option value = $AlbumId");
									print(">$AlbumTitle</option>");
								}
						print('
						</select>
						New Name: <input type = "text" placeholder = "New Album Title" name = "newtitle">
						<br>
						<input type= "submit" name="edit" value="Edit Album"> 
					</form>
					</div>');
				} else {
					print "<p>Please <a href='index.php'>login</a></p>";
				}
			?>
		</div>
</div><!-- container div tag close-->
</body><!-- body div tag close -->
</html><!-- html div tag close -->