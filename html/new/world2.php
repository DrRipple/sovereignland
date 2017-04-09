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
    if (strpos($userID_json, "Invalid Value") !== false) {
        echo "Something went wrong with the authentication of your Google Account.";
        die();
    }
    $userID_data = json_decode($userID_json, true);
    $userID = md5($userID_data["sub"]);

    $name = test_input($_POST["name"]);

    $name = strtolower($name);
    $name = str_replace(" ", "_", $name);
    $exist_testing = file_get_contents("../data/worlds/$name.json");
    if ($exist_testing !== false) {
        echo "The region $name already exists.";
        die();
    }

    $wiki_text = "# Welcome to $name!\n\nIf you are the world owner, you can change this message in the world panel.";
    $wiki_file = fopen("../data/worlds/$name.md", "w");
    fwrite($wiki_file, $wiki_text);
    fclose($wiki_file);

    $dirpath = "../data/nations/$name";
    mkdir($dirpath, 0755, true);

    $info_data = array(
        "userID" => $userID,
        "banner" => test_input($_POST["flag"]),
        "sharing" => test_input($_POST["sharing"]),
        "desc" => test_input($_POST["desc"])
    );
    $info_json = json_encode($info_data);
    $info_file = fopen("../data/worlds/$name.json", "w");
    fwrite($info_file, $info_json);
    fclose($info_file);

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
    fclose($code_file);
} else {
    $code = "1234567890";
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
        <div id="content">
            <p>Your world has successfully been created.</p>
            <h3>Permanent Codes</h3>
            <p>Save these somewhere; you will not be able to retrive them later.</p>
            <ul>
                <li>World Code: <code><?php echo $code ?></code></li>
                <li>Invite Link: <code>http://sovereign.land/new/nation.php?w=<?php echo $code ?></code></li>
            </ul>
        </div>
    </body>
</html>