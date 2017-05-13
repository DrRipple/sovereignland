<!DOCTYPE html>
<?php
require "data/Parsedown.php";
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

function format_input($data) {
    $data = test_input($data);
    $data = str_replace(" ", "_", $data);
    $data = strtolower($data);
    return($data);
}

if (isset($_GET["w"]) && isset($_GET["n"])) {
    $nation = format_input($_GET["n"]);
    $world = format_input($_GET["w"]);
    $world_url = "world?w=$world";

    $basicdata_json = file_get_contents("data/nations/$world/$nation/basic.json");
    if ($basicdata_json == false) {
        echo "That nation or world could not be found.";
        die();
    }
    $basicdata = json_decode($basicdata_json, true);
    $filepath = "data/nations/$world/$nation";
    $wikidata = file_get_contents("$filepath/wiki.md");

    $p2_json = file_get_contents("$filepath/phase2.json");
    $p2_data = json_decode($p2_json, true);

    //Friends and enemies listing

    $rel_json = file_get_contents("$filepath/relations.json");
    $rel_data = json_decode($rel_json, true);

    $friends = $rel_data["friends"];
    $friends_html = "<ul>";
    for ($i = 0; $i < count($friends); $i++) {
        $friend_link = "nation?w=$world&n=" . $friends[$i];
        $friend_name = display_input($friends[$i]);
        $friends_html .= "<li><a href='$friend_link'>$friend_name</a></li>";
    }
    $friends_html .= "</ul>";

    $enemies = $rel_data["enemies"];
    $enemies_html = "<ul>";
    for ($i = 0; $i < count($enemies); $i++) {
        $enemy_link = "nation?w=$world&n=" . $enemies[$i];
        $enemy_name = display_input($enemies[$i]);
        $enemies_html .= "<li><a href='$enemy_link'>$enemy_name</a></li>";
    }
    $enemies_html .= "</ul>";
} else {
    echo "No nation or world specified.";
    die();
}
?>
<html>
    <head>
        <title><?php echo display_input($nation) ?> | Sovereign.Land</title>
        <link rel="stylesheet" href="stylesheets/nation.css">
        <link rel="stylesheet" href="stylesheets/tabpage.css">
        <link rel="icon" type="image/png" href="favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    </head>
    <body>
        <div id="topbar">
            <div id="topcontainer">
                <h1>
                    <a href="/">sovereign.land</a>
                </h1>
                <span id="signin"><a href="#" onclick="showMenu()"><img src="data/icons/menu.png"></a></span>
            </div>
        </div>

        <div id="titlearea">
            <div id="title">
                <img src="<?php echo $basicdata['flagURL'] ?>">
                <h1>
                    <a id="world" href="<?php echo $world_url ?>"><?php echo display_input($world) ?></a> /
                    <?php echo display_input($nation) ?>
                </h1>
            </div>
            <ul id="navbar">
                <li id="wiki_tab">
                    <a href="#" onclick="showTab('wiki')">
                        <img src="data/icons/nation_wiki.png">
                        <span class="navtext">Wiki Entry</span>
                    </a>
                </li>
                <li id="news_tab">
                    <a href="#" onclick="showTab('news')">
                        <img src="data/icons/nation_news.png">
                        <span class="navtext">Latest News</span>
                    </a>
                </li>
                <li id="info_tab">
                    <a href="#" onclick="showTab('info')">
                        <img src="data/icons/nation_info.png">
                        <span class="navtext">Information</span>
                    </a>
                </li>
                <li id="stats_tab" class="active">
                    <a href="#" onclick="showTab('stats')">
                        <img src="data/icons/nation_stats.png">
                        <span class="navtext">Statistics</span>
                    </a>
                </li>
                <li id="friends_tab">
                    <a href="#" onclick="showTab('friends')">
                        <img src="data/icons/nation_friends.png">
                        <span class="navtext">Friends</span>
                    </a>
                </li>
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
            <h3>List of Friends</h3>
            <?php echo $friends_html ?>
            <h3>List of Enemies</h3>
            <?php echo $enemies_html ?>
        </div>

        <ul id="menu">
            <li><a href="#" onclick="closeMenu()">Close Menu</a></li>
            <li>
                <form action="" method="get" id="nation_form">
                    <input type="text" name="n" value="Nation Name" onfocus="clearText(this)">
                    <input type="text" name="w" value="World Name" onfocus="clearText(this)">
                    <button>View Nation</button><button onclick="nationForm.action = 'editnation'">Edit Nation</button>
                </form>
            </li>
            <li>
                <form action="world" method="get" id="world_form">
                    <input type="text" name="w" value="World Name" onfocus="clearText(this)">
                    <button>View World</button><button onclick="worldForm.action = 'editworld'">Edit World</button>
                </form>
            </li>
            <li><a href="terms">Terms and Privacy Policy</a></li>
        </ul>

        <script>
            var nationForm = document.getElementById("nation_form");
            var worldForm = document.getElementById("world_form");

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

            function clearText(element) {
                element.value = "";
                element.style.color = "#4f4f4f";
            }

            function showMenu() {
                document.getElementById("menu").style.display = "block";
            }

            function closeMenu() {
                document.getElementById("menu").style.display = "none";
            }
        </script>
    </body>
</html>