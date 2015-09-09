<!--Created by Youliang Pan-->
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type= "text/css" href="css/stylesheet.css" />
	<title>Vincent Pan's Album Gallery</title>

</head>
<body>
<div id="container">
	<div id="header">View Albums
	</div>
		<div id="navdiv">
			<ul class="mblinks">
				<li><a href="AddPhoto.php">Edit Photos</a><li>
				<li><a href="AddAlbum.php">Edit Albums</a></li>
				<li><a href="Search.php">Search Photos</a></li>
				<li><a href="Photos.php">Photos</a></li>
				<li><a href="Albums.php" class="selected">Albums</a></li>
				<li><a href="index.php" >Home</a></li>
			</ul>
		</div>
		<div id="content">
			<h2>Welcome to my Gallery of Art </h2>
			<h3> Here are all Albums in the database! </h3>
			<!--List all albums-->
			<?php
				require_once 'config.php';
				$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
				$result = $mysqli-> query("SELECT * FROM Albums");

				print('<ul id="picturelist">');
				print'<li>';
				while($row = $result -> fetch_assoc()){
					$AlbumNum = $row['AlbumId'];
					$AlbumTitle = $row['Title'];					
					print("<li>");
					print("<a href = 'Photos.php?AlbumId=$AlbumNum'>");
					print("<h2> $AlbumTitle </h2>");
					print("</a>");
				}
				print '</li>';
				print ("</ul>");
			?>
		</div>
</div><!-- container div tag close-->
</body><!-- body div tag close -->
</html><!-- html div tag close -->