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

    $relation_json = file_get_contents("../data/nations/$world/$nation/relations.json");
    $relation_data = json_decode($relation_json, true);
    if ($userID == $nation_data["userID"]) {
    	if ($_POST["mode"] == "af") {
            array_push($relation_data["friends"], format_input($_POST["target"]));
        } else {
            array_push($relation_data["enemies"], format_input($_POST["target"]));
        }
        $new_rel_json = json_encode($relation_data);
        $relation_file = fopen("../data/nations/$world/$nation/relations.json", "w");
        fwrite($relation_file, $new_rel_json);
        fclose($relation_file);

        echo "Successfully added relation.";
    } else {
        echo "You are not signed into the correct Google Account for that nation.";
    }
}
?>