<!DOCTYPE html>
<?php
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST["token"])) {
    $token = test_input($_POST["token"]);
    $token_URL = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token";
    $userID_json = file_get_contents("$token_URL");
    if (strpos($userID_json, "Invalid Value") !== false) {
        echo "Something went wrong with the authentication of your Google Account.";
        die();
    }
    $userID_data = json_decode($userID_json, true);
    $userID = md5($userID_data["sub"]);

    $nation = test_input($_POST["nation"]);
    $world = test_input($_POST["world"]);
    $dirpath = "../data/nations/$world/$nation";
    if (!file_exists("$dirpath/basic.json")) {
        echo "The nation you are trying to modify does not exist.";
        die();
    }
    $basic_json = file_get_contents("$dirpath/basic.json");
    $basic_data = json_decode($basic_json, true);

    if ($userID == $basic_data["userID"]) {
        $wiki = $_POST["wiki"];
        $wiki_file = fopen("$dirpath/wiki.md", "w");
        fwrite($wiki_file, $wiki);
        fclose($wiki_file);

        $geo_area = intval(test_input($_POST["geo_area"]));
        $geo_density = intval(test_input($_POST["geo_density"]));
        $geo_data = array(
            "area" => $geo_area,
            "density" => $geo_density
        );

        $econ_sys = test_input($_POST["econ_sys"]);
        $econ_ptax = intval(test_input($_POST["econ_ptax"]));
        $econ_ctax = intval(test_input($_POST["econ_ctax"]));
        if ((0 > $econ_ptax) || ($econ_ptax > 100) || (0 > $econ_ctax) || ($econ_ctax > 100)) {
            echo "One or more of your tax values was not within 0-100.";
            die();
        } else {
            $econ_data = array(
                "ptax" => $econ_ptax / 100,
                "ctax" => $econ_ctax / 100
            );
        }

        $bud_list = array(
            intval(test_input($_POST["bud_def"])),
            intval(test_input($_POST["bud_health"])),
            intval(test_input($_POST["bud_edu"])),
            intval(test_input($_POST["bud_ene"])),
            intval(test_input($_POST["bud_sci"])),
            intval(test_input($_POST["bud_trans"])),
            intval(test_input($_POST["bud_busi"])),
            intval(test_input($_POST["bud_admin"])),
            intval(test_input($_POST["bud_wel"])),
            intval(test_input($_POST["bud_sec"]))
        );
        $total = 0;
        $bud_data = array();
        for ($i = 0; $i < count($bud_list); $i++) $total += $bud_list[$i];
        for ($i = 0; $i < count($bud_list); $i++) array_push($bud_data, $bud_list[$i] / $total);

        $phase2_data = array(
            "geo" => $geo_data,
            "econ" => $econ_data,
            "budget" => $bud_data
        );
        $phase2_json = json_encode($phase2_data);
        $phase2_file = fopen("$dirpath/phase2.json", "w");
        fwrite($phase2_file, $phase2_json);
        fclose($phase2_file);
    } else {
        echo "You are not signed into the correct Google Account for that nation.";
        die();
    }
} else {
    $world = "";
}
?>
<html>
	<head>
		<title>Create Nation | Sovereign.Land</title>
		<link rel="icon" type="image/png" href="../favicon.png">
		<link rel="stylesheet" href="style.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

        <meta name="google-signin-client_id" content="1057622173913-bpi238tov8so32pbm4lj12u4elordq20.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
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
    	<p>Your nation has been created!</p>

    	<script>
    		function flagPreview() {
    			var flagURL = document.getElementById("flag_url").value;
    			var previewBox = document.getElementById("flagpreview");
    			if (flagURL == "") flagURL = "../data/flag.png";
    			previewBox.src = flagURL;
    		}

    		function onSignIn(googleUser) {
    			var profile = googleUser.getBasicProfile();
    			var signinText = document.getElementById("g_signin_text");
    			signinText.innerHTML = "Signed-In as: " + profile.getName();

    			var proceedButton = document.getElementById("finalbutton");
    			proceedButton.style.display = "block";

    			var tokenBox = document.getElementById("tokenbox");
    			tokenBox.value = googleUser.getAuthResponse().id_token;
    		}
    	</script>
    </body>
</html>