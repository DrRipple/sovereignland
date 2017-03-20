<!DOCTYPE html>
<?php
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST["token"])) {
    $token_URL = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" . $_POST["token"];
    $userID_json = file_get_contents("$token_URL");
    $userID_data = json_decode($userID_json, true);
    $userID = $userID_data["sub"];

    $name = test_input($_POST["name"]);
    $name = strtolower($name);
    $name = str_replace(" ", "_", $name);
    $dirpath = "../data/nations/$name";
    mkdir($dirpath, 0755, true);

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $max = strlen($chars) - 1;
    $code = "";
    for ($i = 0; $i < 12; $i++) {
        $code .= $chars[mt_rand(0, $max)];
    }

    $code_data = array(
        "name" => $name,
        "exp" => 9999999999
    );
    $code_json = json_encode($code_data);
    $code_hash = md5($code);
    $code_file = fopen("../data/worldcodes/$code_hash.json", "w");
    fwrite($code_file, $code_json);
}
?>
<html>
	<head>
		<title>Create World | Sovereign.Land</title>
		<link rel="icon" type="image/png" href="../favicon.png">
		<link rel="stylesheet" href="style.css">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">
    </head>
    <body>
        <div id="topbar">
            <div id="topcontainer">
                <div id="desc">
                    <h1>Create Your World</h1>
                    <h2>sovereign.land Nation Simulator</h2>
                </div>
            </div>
        </div>
        <form action="world2.php" method="post">
            <span class="textfield">
                <p>World Name</p>
                <input type="text" name="name">
                <p class="helptext">Enter the name of your world (ex. Earth). This is what will be displayed on the world browser.</p>
            </span>
        </form>
    </body>
</html>