<!DOCTYPE html>
<html>
	<head>
		<title>Create Nation | Sovereign.Land</title>
		<link rel="icon" type="image/png" href="../favicon.png">
		<link rel="stylesheet" href="style.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">
    </head>
    <body>
    	<?php
    	if (isset($_POST["token"])) {
    		$token_URL = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" . $_POST["token"];
    		$userID_json = file_get_contents("$token_URL");
    		$userID_data = json_decode($userID_json, true);
    		$userID = $userID_data["sub"];

    		$t = time();
    		$dirpath = "../data/nations/" . $t;
    		mkdir($dirpath, "0755");

    		$basics_data = array(
    			"userID" => $userID,
    			"name" => $_POST["name"],
    			"official" => $_POST["official"],
    			"ccode" => $_POST["currency"],
    			"cvalue" => $_POST["cvalue"],
    			"flagURL" => $_POST["flag"]
    		);
    		$basics = json_encode($basics_data);
    		$basicsfile = fopen("$dirpath/basic.json", "w");
    		fwrite($basicsfile, $basics);
    	} else {
    		header("Location: http://sovereign.land/newnation/");
    		die();
    	}
    	?>
    	<div id="topbar">
            <div id="topcontainer">
                <div id="desc">
    				<h1>Create Your Nation</h1>
    				<h2>sovereign.land Nation Simulator</h2>
                </div>
            </div>
        </div>
    	<form action="phase2.php" method="post">
    		<span class="textfield">
	    		<p>Nation Name</p>
	    		<input type="text" name="name">
	    		<p class="helptext">Enter the common name of your nation (ex. Finland).</p>
	    	</span>
	    	<button>Proceed to Next Step</button>
    	</form>
    </body>
</html>