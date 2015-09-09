<!--Created by Youliang Pan-->
<!--Lightbox code obtained from: http://www.dwuser.com/education -->
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type= "text/css" href="css/stylesheet.css" />
	<title>Vincent Pan's Photo Gallery</title>
	<link rel="stylesheet" type="text/css" media="screen" href="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" />
<style type="text/css">
    a.fancybox img {
        border: none;
        box-shadow: 0 1px 7px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }
</style>
</head>
<body>

<div id="container">
	<div id="header">View Photos
	</div>
		<div id="navdiv">
			<ul class="mblinks">
				<li><a href="AddPhoto.php">Edit Photos</a><li>
				<li><a href="AddAlbum.php">Edit Albums</a></li>
				<li><a href="Search.php">Search Photos</a></li>
				<li><a href="Photos.php" class="selected">Photos</a></li>
				<li><a href="Albums.php">Albums</a></li>
				<li><a href="index.php">Home</a></li>
			</ul>
		</div>
		<div id="content">
			<h2>Welcome to my Gallery of Art </h2>
			
			<form method = "post" action = "Photos.php">
				Choose an album to display pictures of:
				<select name = "DisplayAlb"
>					<option value = "0"> - </option>
					<?php
						//dynamically generate the form based on the albums in the database, choose the default selected
						//based on either get data or url information
						require_once 'config.php';
						$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
						$Albums = $mysqli-> query("SELECT AlbumId, Title FROM Albums");
						while($row = $Albums -> fetch_assoc()){
							$AlbumId = $row['AlbumId'];
							$AlbumTitle = $row['Title'];
							print("<option value = $AlbumId"); 
							if(isset($_POST['DisplayAlb'])){
								if($_POST['DisplayAlb'] == $AlbumId){
									print (" selected");
								}
							}
							else if(isset($_GET['AlbumId'])){
								if($_GET['AlbumId']==$AlbumId){
									print(" selected");
								}
							}
							print(">$AlbumTitle</option>");
						}
						//print('<input type="submit" name="Go" value="Go">');
					?>
				</select>
				<input type="submit" name="Go" value="Go">
			</form>
			<br><br>
			<?php
				//If there is a page tag ?AlbumId = something, then display the pictures from that album
				if(isset($_GET["AlbumId"])){
					$AlbumSelected = $_GET["AlbumId"];
					$result = $mysqli-> query("SELECT Caption, ImageUrl, DateTaken FROM Photos LEFT JOIN Connections ON Photos.PhotoId=Connections.PhotoId WHERE Connections.AlbumId = $AlbumSelected");
					print('<ul id="picturelist">');
					while($row = $result -> fetch_assoc()){
						$caption = $row['Caption'];
						$imageurl = $row['ImageUrl'];
						$date = $row['DateTaken'];
						print("<li>");
						print("<img src=$imageurl alt = '$caption' class = 'fancybox' title = '$caption'>");
						print("</li>");
					}
					print ("</ul>");
				}
				//If there is postdata for the user manually selecting a album, display the photos from that page
				else if(isset($_POST["Go"])){
					$AlbumSelected = $_POST["DisplayAlb"];
					if($AlbumSelected !=0){
						$result = $mysqli-> query("SELECT * FROM Photos INNER JOIN Connections ON Photos.PhotoId=Connections.PhotoId WHERE Connections.AlbumId = $AlbumSelected");
						print('<ul id="picturelist">');
						while($row = $result -> fetch_assoc()){
							$caption = $row['Caption'];
							$imageurl = $row['ImageUrl'];
							$date = $row['DateTaken'];
							print("<li>");
							print("<img src=$imageurl alt = '$caption' class = 'fancybox' title = '$caption'>");
							print("</li>");
						}
						print ("</ul>");
					}
				}
			?>
		</div>
</div><!-- container div tag close-->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.min.js"></script>
<script type="text/javascript">
    $(function($){
        var addToAll = false;
        var gallery = false;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict();
</script>
</body><!-- body div tag close -->
</html><!-- html div tag close -->