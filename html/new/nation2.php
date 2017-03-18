<!DOCTYPE html>
<?php
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST["token"])) {
    $t = time();

    $worldcode = md5(test_input($_POST["world"]));
    $worldcode_file = "../data/worldcodes/$worldcode.json";
    $worldcode_json = file_get_contents($worldcode_file);
    if ($worldcode_json !== false) {
        $worldcode_data = json_decode($worldcode_json, true);
        if ($worldcode_data["exp"] > $t) {
            $world = $worldcode_data["name"];
        } else {
            echo "That code has expired.";
            die();
        }
    } else {
        echo "World code not recognized.";
        die();
    }

    $token_URL = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" . $_POST["token"];
    $userID_json = file_get_contents("$token_URL");
    $userID_data = json_decode($userID_json, true);
    $userID = $userID_data["sub"];

    $dirpath = "../data/nations/$world/" . $t;
    mkdir($dirpath, 0755, true);

    $basics_data = array(
        "userID" => $userID,
        "name" => test_input($_POST["name"]),
        "official" => test_input($_POST["official"]),
        "ccode" => test_input($_POST["currency"]),
        "cvalue" => test_input($_POST["cvalue"]),
        "flagURL" => test_input($_POST["flag"])
    );
    $basics = json_encode($basics_data);
    $basicsfile = fopen("$dirpath/basic.json", "w");
    fwrite($basicsfile, $basics);
} else {
    header("Location: http://sovereign.land/newnation/");
    die();
}
?>
<html>
	<head>
		<title>Create Nation | Sovereign.Land</title>
		<link rel="icon" type="image/png" href="../favicon.png">
		<link rel="stylesheet" href="style.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">
    </head>
    <body>
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