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

    $nation = format_input($_POST["nation"]);
    $world = format_input($_POST["world"]);

    $nation_json = file_get_contents("../data/nations/$world/$nation/basic.json");
    $nation_data = json_decode($nation_json, true);
    if ($userID == $nation_data["userID"]) {
    	$post_data = test_input($_POST["post"]) . "\n\n Posted by: " . display_input($nation);
    	$post_name = $t . "_$nation.md";
    	$post_file = fopen("../data/posts/$world/$post_name", "w");
    	fwrite($post_file, $post_data);
    	fclose($post_file);
    	echo "Successfully posted.";
    } else {
        echo "You are not signed into the correct Google Account for that nation.";
    }
}
?>