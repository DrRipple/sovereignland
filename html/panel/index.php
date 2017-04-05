<!DOCTYPE html>
<?php
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

if (isset($_POST["token"])) {
	$token = test_input($_POST["token"]);
	$nation = format_input($_POST["nation"]);
	$world = format_input($_POST["world"]);

	$token_URL = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token";
	$userID_json = file_get_contents("$token_URL");
    if (strpos($userID_json, "Invalid Value") !== false) {
        echo "Something went wrong with the authentication of your Google Account.";
        die();
    }
    $userID_data = json_decode($userID_json, true);
    $userID = md5($userID_data["sub"]);

    $basic_json = file_get_contents("../data/nations/$world/$nation/basic.json");
    $basic_data = json_decode($basic_json, true);
    if ($userID == $basic_data["userID"]) {
    	setcookie("sl_signed_in", $nation);
    	echo "Login success!";
    } else {
    	echo "Invalid login parameters. Please try again.";
    	die();
    }
} else {
	echo "Missing parameters.";
	die();
}
?>