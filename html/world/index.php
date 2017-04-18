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

if (isset($_GET["w"])) {
    $world = test_input($_GET["w"]);
    $world_display = display_input($world);
    $world_url = "../world?w=$world";

    $data_json = file_get_contents("../data/worlds/$world.json");
    if ($data_json == false) {
        echo "That world could not be found.";
        die();
    }
    $world_data = json_decode($data_json, true);
    $wikifile = "../data/worlds/$world.md";
    $wikidata = file_get_contents($wikifile);

    $residents = scandir("../data/nations/$world/");
    $residents_html = "<ul>";
    for ($i = 2; $i < count($residents); $i++) {
        $res_link = "../nation?w=$world&n=" . $residents[$i];
        $res_name = display_input($residents[$i]);
        $residents_html .= "<li><a href='$res_link'>$res_name</a></li>";
    }
    $residents_html .= "</ul>";
} else {
    echo "No nation or world specified.";
    die();
}

if (isset($_COOKIE["sl_nation"])) {
    $signedin_n = test_input($_COOKIE["sl_nation"]);
    $signedin_w = test_input($_COOKIE["sl_world"]);
    $signin_url = "../panel?n=$signedin_n&w=$signedin_w";

    $signedin_n = display_input($signedin_n);
    $signedin_w = display_input($signedin_w);
} else {
    $signedin_n = "Sign In";
    $signin_url = "../panel";
}
?>
<html>
    <head>
        <title>Sovereign.Land</title>
        <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/png" href="../favicon.png">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">
    </head>
    <body>
        <div id="topbar">
            <div id="topcontainer">
                <h1>
                    <a href="../">sovereign.land</a>
                </h1>
                <span id="signin"><a href="<?php echo $signin_url ?>"><?php echo $signedin_n ?></a></span>
            </div>
        </div>

        <div id="titlearea">
            <div id="worldtitle">
                <img src="<?php echo $world_data['banner']?>">
                <h1><?php echo $world_display ?> <span class="extra" style="font-size: 32px">(world)</span></h1>
            </div>
            <ul id="navbar">
                <li class="active" id="wiki_tab"><img src="../data/icons/nation_wiki.png"><a href="#" onclick="showTab('wiki')">Wiki Entry</a></li>
                <li><img src="../data/icons/nation_news.png"><a href="#">Latest News</a></li>
                <li id="nations_tab"><img src="../data/icons/nation_flag.png"><a href="#" onclick="showTab('nations')">Nations</a></li>
                <li><img src="../data/icons/nation_map.png"><a href="<?php echo $world_data['map'] ?>" target="_blank">Map</a></li>
            </ul>
        </div>

        <div id="wiki" class="content">
            <?php echo $Parsedown->text($wikidata) ?>
        </div>

        <div id="nations" class="content">
            <h3>List of Nations</h3>
            <?php echo $residents_html ?>
        </div>

        <script>
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