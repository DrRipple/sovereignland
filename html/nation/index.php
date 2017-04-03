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

if (isset($_GET["w"]) && isset($_GET["n"])) {
    $nation = test_input($_GET["n"]);
    $world = test_input($_GET["w"]);
    $world_display = str_replace("_", " ", $world);
    $world_url = "../world?w=$world";

    $basicdata_json = file_get_contents("../data/nations/$world/$nation/basic.json");
    if ($basicdata_json == false) {
        echo "Incorrect world or nation code.";
        die();
    }
    $basicdata = json_decode($basicdata_json, true);
    $wikifile = "../data/nations/$world/$nation/wiki.md";
    $wikidata = file_get_contents($wikifile);
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
    </head>
    <body>
        <div id="topbar">
            <div id="topcontainer">
                <h1>
                    <a href="../">sovereign.land</a>
                </h1>
                <span id="signin"><a href="../signin">Sign In</a> or <a href="../new/nation.php">Sign Up</a></span>
            </div>
        </div>
        <div id="titlearea">
            <div id="nationtitle">
                <img src="<?php echo $basicdata['flagURL']?>">
                <h1>
                    <a id="world" href="<?php echo $world_url ?>"><?php echo $world_display ?></a> /
                    <?php echo $basicdata['name']?>
                </h1>
            </div>
            <ul id="navbar">
                <li class="active"><img src="../data/icons/nation_wiki.png"><a href="#">Wiki Entry</a></li>
                <li><img src="../data/icons/nation_news.png"><a href="#">Latest News</a></li>
                <li><img src="../data/icons/nation_info.png"><a href="#">Information</a></li>
                <li><img src="../data/icons/nation_stats.png"><a href="#">Statistics</a></li>
                <li><img src="../data/icons/nation_friends.png"><a href="#">Friends</a></li>
            </ul>
        </div>
        <div id="wiki">
            <?php echo $Parsedown->text($wikidata) ?>
        </div>
        <div id="boxes">
            <div id="vitals" class="box">
                <span class="boxtitle">Vitals</span>
                <ul>
                    <li><b>World: </b>Norrland</li>
                    <li><b>Population: </b>19,802,411</li>
                    <li><b>Unemployment: </b><span class="greatstat">2.9%</span></li>
                    <li><b>Happiness: </b><span class="greatstat">7.504</span></li>
                    <li><b>Development: </b><span class="greatstat">0.951</span></li>
                    <li><a href="#">More Statistics</a></li>
                </ul>
            </div>
            <div id="overview" class="box">
                <span class="boxtitle">Overview</span>
                <p>Testing Information</p>
            </div>
        </div>
    </body>
</html>