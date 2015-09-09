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
	<div id="header">Search Page
	</div>
		<div id="navdiv">
			<ul class="mblinks">
				<li><a href="AddPhoto.php">Edit Photos</a><li>
				<li><a href="AddAlbum.php">Edit Albums</a></li>
				<li><a href="Search.php" class="selected">Search Photos</a></li>
				<li><a href="Photos.php">Photos</a></li>
				<li><a href="Albums.php">Albums</a></li>
				<li><a href="index.php">Home</a></li>			
			</ul>
		</div>
		<div id="content">
			<h2>Search through photos here </h2>
			<br>
			<form method = "post" action = "Search.php">
				Search photo captions: <input type = "text" name = "keyword">
				<input type = "submit" name = "submit" value = "submit">
			</form>
			<br><br>
			<?php
				require_once 'config.php';
				$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
				if(isset($_POST["submit"])){
					if(isset($_POST["keyword"])){
						if(preg_match ("/^[a-zA-Z\s]+$/", $_POST["keyword"])){
							$keyword = $_POST["keyword"];
						}else{
							$keyword = null;
						}
					}
					$Photos = $mysqli-> query("SELECT * FROM Photos WHERE Caption LIKE '%$keyword%'");
					
					if($keyword != null){
						print('<ul id="picturelist">');
						while($row = $Photos -> fetch_assoc()){
							$PhotoId = $row['PhotoId'];
							$caption = $row['Caption'];
							$imageurl = $row['ImageUrl'];
							$date = $row['DateTaken'];
							print("<li>");
							print("<img src=$imageurl alt = '$caption' class = 'fancybox' title = '$caption'>");
							$Albums = $mysqli-> query("SELECT * FROM Albums INNER JOIN Connections on Albums.AlbumId = Connections.AlbumId WHERE Connections.PhotoId = $PhotoId");
							while($anAlbum = $Albums ->fetch_assoc()){
								$Title = $anAlbum['Title'];
								print("$Title");
							}
							print("</li>");
						}
						print ("</ul>");
					}
					else{
						print "Search keyword cannot be blank!";
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