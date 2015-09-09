<!--Created by Youliang Pan-->
<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
	<link rel="stylesheet" type= "text/css" href="css/stylesheet.css" />
	<title>Vincent Pan's Photo Gallery</title>
</head>
<body>
<div id="container">
	<div id="header">Update Photos
	</div>
		<div id="navdiv">
			<ul class="mblinks">
				<li><a href="AddPhoto.php" class = "selected">Edit Photos</a><li>
				<li><a href="AddAlbum.php">Edit Albums</a></li>
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
					print('<h3>Add a photo here!</h3>
						<br>
						<br>
						<form action="AddPhoto.php" method="post" enctype="multipart/form-data">
							<p>
								<label for="new-photo">Single photo upload: </label>
								<input type = "text" placeholder = "Photocaption" name = "PhotoCaption">
								<br><br>
								<input id = "new-photo" type="file" name="newphoto">
								<br><br>
								<h3>Select an Album for this photo to go into </h3>');
						//Use php to print out forms in html that allow user to check which albums the uploaded
						//image belongs to.
						$counter = 0;
						require_once 'config.php';
						$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
						$Albums = $mysqli-> query("SELECT Title FROM Albums");
						while($row = $Albums -> fetch_assoc()){
							$counter = $counter +1;
							$AlbumTitle = $row['Title'];
							print ("<input type = checkbox name = alb[] value = $counter> $AlbumTitle <br>");
						}
					print('<br>
							<input type = "submit" name = "submit" value="Upload photo">
								</p>
							</form>
							</div> 
							<div id = "form2">
							<form action = "AddPhoto.php" method = "post">
								<h3> Select a photo to delete: </h3>
								<select name = "DeletePhoto">
									<option value = "0"></option>');
						//dynamically generate the form based on the albums in the database, choose the default selected
						//based on either get data or url information
						$Photos = $mysqli-> query("SELECT PhotoId, Caption FROM Photos");
						while($row = $Photos -> fetch_assoc()){
							$PhotoId = $row["PhotoId"];
							$Caption = $row["Caption"];
							print("<option value = $PhotoId");
							print(">$Caption</option>");
						}
					print('</select>
								<input type = "submit" name = "delete" value = "Delete Photo">
							</form>
							</div>
							<div id = "form3">
							<form action = "AddPhoto.php" method = "post">
								<h3>Select the Photo you would like to update:</h3>
								<select name = "editPhoto">
									<option value = "0"></option>');
						//dynamically generate the form based on the albums in the database, choose the default selected
						//based on either get data or url information
						$Photos1 = $mysqli-> query("SELECT PhotoId, Caption FROM Photos");
						while($row = $Photos1 -> fetch_assoc()){
							$PhotoId = $row["PhotoId"];
							$Caption = $row["Caption"];
							print("<option value = $PhotoId");
							print(">$Caption</option>");
						}
					print('
				</select>
				<h3> Select Albums you would like the place the existing photo into </h3>');
					//Use php to print out forms in html that allow user to check which albums the uploaded
					//image belongs to.
					$counter = 0;
					require_once 'config.php';
					$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
					$Albums = $mysqli-> query("SELECT Title FROM Albums");
					while($row = $Albums -> fetch_assoc()){
						$counter = $counter +1;
						$AlbumTitle = $row['Title'];
						print ("<input type = checkbox name = albums[] value = $counter> $AlbumTitle <br>");
					}
				print('New Caption: <input type = "text" placeholder = "New Photo Caption" name = "newCaption">
						<br>
						<input type= "submit" name="Edit" value="Edit"> 
					</form> 
					</div>');
				} else {
					print "<p>Please <a href='index.php'>login</a></p>";
				}
			?>
		

			<?php
				//SET THE DATE
				date_default_timezone_set("America/New_York");
				$currentdate = date("Y-m-d");

				//Check if image has been uploaded
				if ( ! empty( $_FILES['newphoto'] ) ) {
					//Upload images file to server folder p3/images/.. path
					$newPhoto = $_FILES['newphoto'];
					$originalName = $newPhoto['name'];
					if ( $newPhoto['error'] == 0 ) {
						$tempName = $newPhoto['tmp_name'];
						move_uploaded_file( $tempName, "images/$originalName");
						$_SESSION['photos'][] = $originalName;
						print("<p>The file $originalName was uploaded successfully!</p>");
					} else {
						print("<p>Error: The file $originalName was not uploaded.</p>");
					}

					//Add photo data to sql database
					if(isset( $_POST['submit'] ) ){
						
						$Photos = $mysqli-> query("SELECT PhotoId FROM Photos");
						while($row = $Photos -> fetch_assoc()){
							$PhotoNum = $row['PhotoId'];
						}
						$PhotoNum+= 1;
						$PhotoCaption = $_POST['PhotoCaption'];
						$insertquery = "INSERT INTO Photos (PhotoId, Caption, ImageUrl, DateTaken) VALUES ($PhotoNum,'$PhotoCaption','images/$originalName','$currentdate')";
						$mysqli -> query($insertquery);
						print("<p>Image uploaded to database with current timestamp</p>");
					}

					//Connect photo to an existing album
					
					if(isset($_POST["alb"])){
						$alblink = $_POST["alb"];
						foreach($alblink as $link){
							$linkquery = "INSERT INTO Connections (AlbumId, PhotoId) VALUES ($link, $PhotoNum)";
							$mysqli -> query($linkquery);
							print("Photo successfully added to Albums $link");
						}
					}
				}
				//Check to see if a photo is deleted
				if(isset($_POST['DeletePhoto'])){
					$DelPho = $_POST['DeletePhoto'];
					$Photos2 = $mysqli -> query("SELECT * FROM Photos");
					$mysqli -> query("DELETE FROM Connections WHERE PhotoId = $DelPho");
					while($row = $Photos2 -> fetch_assoc()){
						$Photocaption = $row['Caption'];
						$PhotoNumber = $row['PhotoId'];
						if($PhotoNumber == $DelPho){
							$mysqli -> query("DELETE FROM Photos WHERE PhotoId = $PhotoNumber");
							echo "Photo was successfully deleted!"; 
						}
					}
				}
				//Check if photo is updated
				if(isset($_POST['Edit'])){
					$newPhotoCaption = $_POST['newCaption'];
					$EditPhotoId = $_POST['editPhoto'];
					if($newPhotoCaption != Null){
						$mysqli -> query("UPDATE Photos SET Caption = '$newPhotoCaption' WHERE PhotoId = $EditPhotoId");
						echo "Photo caption was successfully changed!";
						if(isset($_POST["albums"])){
							$alblink = $_POST["albums"];
							$mysqli -> query("DELETE FROM Connections WHERE Connections.PhotoId = $EditPhotoId");
							foreach($alblink as $link){
								$linkquery = "INSERT INTO Connections (AlbumId, PhotoId) VALUES ($link, $EditPhotoId)";
								$mysqli -> query($linkquery);
								print("<br><br><br>Photo successfully added to Album $link");
							}
						}
					}
					else{
						echo "<br><br><br>Updating Photo caption failed: caption cannot be blank!";
					}
				}
			?>
		</div>
</div><!-- container div tag close-->
</body><!-- body div tag close -->
</html><!-- html div tag close -->