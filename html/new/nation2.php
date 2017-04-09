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

    $dirname = test_input($_POST["name"]);
    $dirname = strtolower($dirname);
    $dirname = str_replace(" ", "_", $dirname);
    $dirpath = "../data/nations/$world/" . $dirname;
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
            <span class="textfield">
                <p>Land Area (km<sup>2</sup>)</p>
                <input type="number" name="civ_area">
                <p class="helptext">Enter the total land area of your country.</p>
            </span>
            <span class="textfield">
                <p>Population Density (people/km<sup>2</sup>)</p>
                <input type="number" name="civ_density">
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
                <input type="number" name="bud_def" value="10" onchange="updateBudgetChart()" class="budgetValue">

                <p>Healthcare</p>
                <input type="number" name="bud_health" value="10" onchange="updateBudgetChart()" class="budgetValue">

                <p>Education</p>
                <input type="number" name="bud_edu" value="10" onchange="updateBudgetChart()" class="budgetValue">

                <p>Energy</p>
                <input type="number" name="bud_ene" value="10" onchange="updateBudgetChart()" class="budgetValue">

                <p>Science</p>
                <input type="number" name="bud_sci" value="10" onchange="updateBudgetChart()" class="budgetValue">

                <p>Transportation</p>
                <input type="number" name="bud_trans" value="10" onchange="updateBudgetChart()" class="budgetValue">

                <p>Business</p>
                <input type="number" name="bud_bus" value="10" onchange="updateBudgetChart()" class="budgetValue">

                <p>Administration</p>
                <input type="number" name="bud_admin" value="10" onchange="updateBudgetChart()" class="budgetValue">
            </div>

            <hr>

            <div>
                <p><b>Wiki Entry</b></p>
                <p>Formatted using <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>.</p>
                <textarea id="wiki"># <?php echo $officialtitle ?></textarea>
            </div>
	    	
            <hr>

            <span class="textfield">
                <p id="g_signin_text">Sign-In With Google</p>
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
            </span>

            <span class="textfield" id="finalbutton">
                <p>By clicking the button below, you confirm that you have read and will abide by the <a href="#" target="_blank">Terms and Conditions</a>.</p>
                <button>Proceed to Next Step</button>
            </span>
    	</form>

        <script>
            google.charts.load("current", {"packages": ["corechart"]});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Department", "% Allocated"],
                    ["Defense", 10],
                    ["Healthcare", 10],
                    ["Education", 10],
                    ["Energy", 10],
                    ["Science", 10],
                    ["Transportation", 10],
                    ["Business", 10],
                    ["Administration", 10]
                ]);

                var options = {
                    title: "National Budget"
                };

                var chartElement = document.getElementById("piechart");
                var chart = new google.visualization.PieChart(chartElement);
                chart.draw(data, options);
            }

            function updateBudgetChart() {
                var valueElements = document.getElementsByClassName("budgetValue");
                var values = [];
                for (var i = 0; i < valueElements.length; i++) values.push(parseInt(valueElements[i].value));

                var data = google.visualization.arrayToDataTable([
                    ["Department", "% Allocated"],
                    ["Defense", values[0]],
                    ["Healthcare", values[1]],
                    ["Education", values[2]],
                    ["Energy", values[3]],
                    ["Science", values[4]],
                    ["Transportation", values[5]],
                    ["Business", values[6]],
                    ["Administration", values[7]]
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