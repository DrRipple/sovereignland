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

    //Resident nations list

    $residents = scandir("../data/nations/$world/");
    $residents_html = "<ul>";
    for ($i = 2; $i < count($residents); $i++) {
        $res_link = "../nation?w=$world&n=" . $residents[$i];
        $res_name = display_input($residents[$i]);
        $residents_html .= "<li><a href='$res_link'>$res_name</a></li>";
    }
    $residents_html .= "</ul>";

    //Event posts

    $posts = scandir("../data/posts/$world/");
    $posts_html = "";
    for ($i = 2; $i < count($posts) && $i < 7; $i++) {
        $timestamp_unix = substr($posts[$i], 0, 10);
        $timestamp_show = date("d/m/Y H:i:s", $timestamp_unix);
        $time_html = "<span class='timestamp'>Posted at $timestamp_show</span>";
        $post_data = file_get_contents("../data/posts/$world/" . $posts[$i]);
        $post_html = $Parsedown->text($post_data);

        $posts_html .= "<div class='post'>$time_html<div class='postcontent'>$post_html</div></div>";
    }
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
                <span id="signin"><a href="../panel">Nation Panel</a></span>
            </div>
        </div>

        <div id="titlearea">
            <div id="worldtitle">
                <img src="<?php echo $world_data['banner']?>">
                <h1><?php echo $world_display ?> <span class="extra" style="font-size: 32px">(world)</span></h1>
            </div>
            <ul id="navbar">
                <li class="active" id="wiki_tab"><img src="../data/icons/nation_wiki.png"><a href="#" onclick="showTab('wiki')">Wiki Entry</a></li>
                <li id="posts_tab"><img src="../data/icons/nation_news.png"><a href="#" onclick="showTab('posts')">Latest News</a></li>
                <li id="nations_tab"><img src="../data/icons/nation_flag.png"><a href="#" onclick="showTab('nations')">Nations</a></li>
                <li><img src="../data/icons/nation_map.png"><a href="<?php echo $world_data['map'] ?>" target="_blank">Map</a></li>
            </ul>
        </div>

        <div id="wiki" class="content">
            <?php echo $Parsedown->text($wikidata) ?>
        </div>

        <div id="posts" class="content">
            <h3>Most Recent Events</h3>
            <?php echo $posts_html ?>
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