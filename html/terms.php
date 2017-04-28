<!DOCTYPE html>
<?php
require "data/Parsedown.php";
$Parsedown = new Parsedown();

$term_content = file_get_contents("data/terms.md");
?>
<html>
	<head>
		<title>Terms and Conditions | Sovereign.Land</title>
		<link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/png" href="../favicon.png">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

        <style>
        	body {
				font-family: "Roboto", sans-serif;
				color: #4f4f4f;
				font-size: 18px;
				margin: 0;
			}

			#term_container {
				width: 900px;
				height: 100%;
				margin: 80px auto;
			}

			li {padding: 4px 0}
        </style>
    </head>
    <body>
    	<div id="term_container"><?php echo $Parsedown->text($term_content) ?></div>
    </body>
</html>