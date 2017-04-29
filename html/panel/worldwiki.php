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

    $world = format_input($_POST["world"]);

    $world_json = file_get_contents("../data/worlds/$world.json");
    $world_data = json_decode($world_json, true);
    if ($userID == $world_data["userID"]) {
    	$post_data = test_input($_POST["wiki"]);
    	$post_file = fopen("../data/worlds/$world.md", "w");
    	fwrite($post_file, $post_data);
    	fclose($post_file);
    	echo "Successfully edited Wiki Entry for " . display_input($world) . ".";
    } else {
        echo "You are not signed into the correct Google Account for that world.";
    }
}
?>