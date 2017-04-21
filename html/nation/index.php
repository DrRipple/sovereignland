<!DOCTYPE html>
<?php
require "../data/Parsedown.php";
$Parsedown = new Parsedown();

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function display_input($data) {
    $data = str_replace("_", " ", $data);
    $data = ucwords($data);
    return $data;
}

if (isset($_GET["w"]) && isset($_GET["n"])) {
    $nation = test_input($_GET["n"]);
    $world = test_input($_GET["w"]);
    $world_display = display_input($world);
    $world_url = "../world?w=$world";

    $basicdata_json = file_get_contents("../data/nations/$world/$nation/basic.json");
    if ($basicdata_json == false) {
        echo "Incorrect world or nation code.";
        die();
    }
    $basicdata = json_decode($basicdata_json, true);
    $filepath = "../data/nations/$world/$nation";
    $wikidata = file_get_contents("$filepath/wiki.md");

    $p2_json = file_get_contents("$filepath/phase2.json");
    $p2_data = json_decode($p2_json, true);
} else {
    echo "No nation or world specified.";
    die();
}
?>
<html>
    <head>
        <title>Sovereign.Land</title>
        <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/png" href="../favicon.png">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    </head>
    <body>
        <div id="topbar">
            <div id="topcontainer">
                <h1>
                    <a href="../">sovereign.land</a>
                </h1>
                <span id="signin"><a href="../panel">Nation Panel</a></span>
            </div>
        </div>
        <div id="titlearea">
            <div id="nationtitle">
                <img src="<?php echo $basicdata['flagURL']?>">
                <h1>
                    <a id="world" href="<?php echo $world_url ?>"><?php echo $world_display ?></a> /
                    <?php echo $basicdata["name"]?>
                </h1>
            </div>
            <ul id="navbar">
                <li id="wiki_tab" class="active"><img src="../data/icons/nation_wiki.png"><a href="#" onclick="showTab('wiki')">Wiki Entry</a></li>
                <li id="news_tab"><img src="../data/icons/nation_news.png"><a href="#" onclick="showTab('news')">Latest News</a></li>
                <li id="info_tab"><img src="../data/icons/nation_info.png"><a href="#" onclick="showTab('info')">Information</a></li>
                <li id="stats_tab"><img src="../data/icons/nation_stats.png"><a href="#" onclick="showTab('stats')">Statistics</a></li>
                <li id="friends_tab"><img src="../data/icons/nation_friends.png"><a href="#" onclick="showTab('friends')">Friends</a></li>
            </ul>
        </div>

        <div id="wiki" class="content">
            <?php echo $Parsedown->text($wikidata) ?>
        </div>

        <div id="news" class="content">
            <h3>Coming Soon</h3>
            <p>This feature is not yet available in the Alpha version.</p>
        </div>

        <div id="info" class="content">
            <h3>Nation Information</h3>
            <ul>
                <li><b>Total Area</b>: <?php echo $p2_data["geo"]["area"] ?> sq km</li>
                <li><b>Population Density</b>: <?php echo $p2_data["geo"]["density"] ?> people/sq km</li>
                <li><b>Population</b>: <?php echo $p2_data["geo"]["area"] * $p2_data["geo"]["density"] ?> people</li>
            </ul>
            <ul>
                <li><b>Personal Income Tax</b>: <?php echo $p2_data["econ"]["ptax"] * 100 ?>%</li>
                <li><b>Corporate Income Tax</b>: <?php echo $p2_data["econ"]["ctax"] * 100 ?>%</li>
                <li>More forms of taxation coming soon.</li>
            </ul>
        </div>
        
        <div id="stats" class="content">
            <h3>Budget Statistics</h3>
            <div id="piechart"></div>
        </div>

        <div id="friends" class="content">
            <h3>Friends and Enemies</h3>
        </div>

        <script>
            google.charts.load("current", {"packages": ["corechart"]});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Department", "% Allocated"],
                    ["Military", <?php echo $p2_data["budget"][0] ?>],
                    ["Healthcare", <?php echo $p2_data["budget"][1] ?>],
                    ["Education", <?php echo $p2_data["budget"][2] ?>],
                    ["Energy", <?php echo $p2_data["budget"][3] ?>],
                    ["Science", <?php echo $p2_data["budget"][4] ?>],
                    ["Transportation", <?php echo $p2_data["budget"][5] ?>],
                    ["Business", <?php echo $p2_data["budget"][6] ?>],
                    ["Administration", <?php echo $p2_data["budget"][7] ?>],
                    ["Welfare", <?php echo $p2_data["budget"][8] ?>],
                    ["Security", <?php echo $p2_data["budget"][9] ?>]
                ]);

                var options = {
                    title: "National Budget"
                };

                var chartElement = document.getElementById("piechart");
                var chart = new google.visualization.PieChart(chartElement);
                chart.draw(data, options);
            }

            function showTab(section) {
                var theSection = document.getElementById(section);
                var sectionTab = document.getElementById(section + "_tab");

                var sections = document.getElementsByClassName("content");
                var activeTabs = document.getElementsByClassName("active");

                for (var i = 0; i < sections.length; i++) sections[i].style.display = "none";
                activeTabs[0].className = "";

                theSection.style.display = "block";
                sectionTab.className = "active";
            }
        </script>
    </body>
</html>