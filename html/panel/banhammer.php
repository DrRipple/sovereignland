<!DOCTYPE html>
<?php
function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function format_input($data) {
	$data = test_input($data);
	$data = strtolower($data);
	$data = str_replace(" ", "_", $data);
	return $data;
}

function display_input($data) {
    $data = str_replace("_", " ", $data);
    $data = ucwords($data);
    return $data;
}

if (isset($_POST["token"])) {
	$t = time();

	$token = test_input($_POST["token"]);
    $token_URL = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token";
    $userID_json = file_get_contents("$token_URL");
    if (strpos($userID_json, "Invalid Value") !== false) {
        echo "Something went wrong with the authentication of your Google Account.";
        die();
    }
    $userID_data = json_decode($userID_json, true);
    $userID = md5($userID_data["sub"]);

    $nation = format_input($_POST["nation"]);
    $world = format_input($_POST["world"]);

    $world_json = file_get_contents("../data/worlds/$world.json");
    $world_data = json_decode($world_json, true);
    if ($userID == $world_data["userID"]) {
        $bannation = format_input($_POST["bannation"]);
        $bantype = format_input($_POST["bantype"]);

        $bannation_json = file_get_contents("../data/nations/$world/$bannation/basic.json");
        if ($bannation_json == false) {
            echo "That nation could not be found. You can only ban nations within your world.";
            die();
        }

        if ($bantype == "ban") {
            $bannation_data = json_decode($bannation_json, true);
            $bannation_userID = $bannation_data["userID"];

            $ban_text = "<$world/$bannation_userID>";
            $ban_file = fopen("../data/bans.txt", "a+");
            fwrite($ban_file, $ban_text);
            fclose($ban_file);
            echo "Nation successfully added to ban list.<br>";
        }

        deleteDir("../data/nations/$world/$bannation");
        echo "Nation successfully deleted.";
    } else {
        echo "You are not signed into the correct Google Account for that nation.";
    }
}
?>