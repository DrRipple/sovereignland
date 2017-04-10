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

    $token = test_input($_POST["token"]);
    $token_URL = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token";
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
    $dirpath = "../data/nations/$world/" . $name;
    mkdir($dirpath, 0755, true);

    $officialtitle = test_input($_POST["official"]);

    $basics_data = array(
        "userID" => $userID,
        "name" => test_input($_POST["name"]),
        "official" => $officialtitle,
        "ccode" => test_input($_POST["currency"]),
        "cvalue" => test_input($_POST["cvalue"]),
        "flagURL" => test_input($_POST["flag"])
    );
    $basics = json_encode($basics_data);
    $basicsfile = fopen("$dirpath/basic.json", "w");
    fwrite($basicsfile, $basics);
    fclose($basicsfile);
} else {
    $nation = "Test!";
    $officialtitle = "The Repubic of Test";
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

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
    	<form action="nation3.php" method="post">
            <input type="hidden" name="token" id="tokenbox">
            <input type="hidden" name="nation" value="<?php echo $name ?>">
            <input type="hidden" name="world" value="<?php echo $world ?>">

            <span class="textfield">
                <p>Land Area (km<sup>2</sup>)</p>
                <input type="number" name="geo_area">
                <p class="helptext">Enter the total land area of your country.</p>
            </span>
            <span class="textfield">
                <p>Population Density (people/km<sup>2</sup>)</p>
                <input type="number" name="geo_density">
                <p class="helptext">Enter the overall population density of your country.</p>
            </span>

            <hr>

    		<span class="textfield">
	    		<p>Economic System</p>
	    		<select name="econ_sys" onchange="updateEconFields()" id="econSys">
                    <option value="cap">Capitalist</option>
                    <option value="soc">Socialist</option>
                </select>
	    		<p class="helptext">Choose the basis of your nation's economic system.</p>
	    	</span>
            <span class="textfield">
                <p>Personal Income Tax (%)</p>
                <input type="number" name="econ_ptax">
                <p class="helptext">Enter the personal income tax rate of an average worker in your country.</p>
            </span>
            <span class="textfield" id="corpTax">
                <p>Corporate Income Tax (%)</p>
                <input type="number" name="econ_ctax">
                <p class="helptext">Enter the corporate income tax rate for a medium sized business in your country.</p>
            </span>

            <hr>

            <div id="piechart"></div>
            <div id="budget">
                <p><b>Budget Points</b></p>
                <p style="font-size: 14px; color: #777">Adjust the points to change the percent budget allocated to each department. Only the percentages will be recorded.</p>

                <p>Defense</p>
                <input type="number" name="bud_def" value="10" onchange="drawChart()" class="budgetValue">

                <p>Healthcare</p>
                <input type="number" name="bud_health" value="10" onchange="drawChart()" class="budgetValue">

                <p>Education</p>
                <input type="number" name="bud_edu" value="10" onchange="drawChart()" class="budgetValue">

                <p>Energy</p>
                <input type="number" name="bud_ene" value="10" onchange="drawChart()" class="budgetValue">

                <p>Science</p>
                <input type="number" name="bud_sci" value="10" onchange="drawChart()" class="budgetValue">

                <p>Transportation</p>
                <input type="number" name="bud_trans" value="10" onchange="drawChart()" class="budgetValue">

                <p>Business</p>
                <input type="number" name="bud_busi" value="10" onchange="drawChart()" class="budgetValue">

                <p>Administration</p>
                <input type="number" name="bud_admin" value="10" onchange="drawChart()" class="budgetValue">

                <p>Welfare</p>
                <input type="number" name="bud_wel" value="10" onchange="drawChart()" class="budgetValue">

                <p>Security</p>
                <input type="number" name="bud_sec" value="10" onchange="drawChart()" class="budgetValue">
            </div>

            <hr>

            <div>
                <p><b>Wiki Entry</b></p>
                <p>Formatted using <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>.</p>
                <textarea id="wiki" name="wiki"># <?php echo $officialtitle ?></textarea>
            </div>
	    	
            <hr>

            <span class="textfield">
                <p id="g_signin_text">Sign-In With Google</p>
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
            </span>

            <span class="textfield" id="finalbutton">
                <button>Create My Nation</button>
            </span>
    	</form>

        <script>
            google.charts.load("current", {"packages": ["corechart"]});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var valueElements = document.getElementsByClassName("budgetValue");
                var values = [];
                for (var i = 0; i < valueElements.length; i++) values.push(parseInt(valueElements[i].value));

                var data = google.visualization.arrayToDataTable([
                    ["Department", "% Allocated"],
                    ["Military", values[0]],
                    ["Healthcare", values[1]],
                    ["Education", values[2]],
                    ["Energy", values[3]],
                    ["Science", values[4]],
                    ["Transportation", values[5]],
                    ["Business", values[6]],
                    ["Administration", values[7]],
                    ["Welfare", values[8]],
                    ["Security", values[9]]
                ]);

                var options = {
                    title: "National Budget"
                };

                var chartElement = document.getElementById("piechart");
                var chart = new google.visualization.PieChart(chartElement);
                chart.draw(data, options);
            }

            function updateEconFields() {
                var econSysBox = document.getElementById("econSys");
                var corpTaxBox = document.getElementById("corpTax");
                if (econSysBox.value == "soc") {
                    corpTaxBox.style.display = "none";
                } else {
                    corpTaxBox.style.display = "block";
                }
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