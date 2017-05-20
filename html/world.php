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

if (isset($_GET["w"])) {
    $world = format_input($_GET["w"]);
    $world_url = "world?w=$world";

    $data_json = file_get_contents("data/worlds/$world.json");
    if ($data_json == false) {
        echo "That world could not be found.";
        die();
    }
    $world_data = json_decode($data_json, true);
    $wikifile = "data/worlds/$world.md";
    $wikidata = file_get_contents($wikifile);

    //Resident nations list

    $residents = scandir("data/nations/$world/");
    $residents_html = "<ul>";
    for ($i = 2; $i < count($residents); $i++) {
        $res_link = "nation?w=$world&n=" . $residents[$i];
        $res_name = display_input($residents[$i]);
        $residents_html .= "<li><a href='$res_link'>$res_name</a></li>";
    }
    $residents_html .= "</ul>";

    //Event posts

    $posts = scandir("data/posts/$world/");
    $posts_html = "";
    for ($i = count($posts) - 1; $i > count($posts) - 4; $i--) {
        $timestamp_unix = substr($posts[$i], 0, 10);
        $timestamp_show = date("d/m/Y H:i:s", $timestamp_unix);
        $time_html = "<span class='timestamp'>Posted at $timestamp_show</span>";
        $post_data = file_get_contents("data/posts/$world/" . $posts[$i]);
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
        <title><?php echo display_input($world) ?> | Sovereign.Land</title>
        <link rel="stylesheet" href="stylesheets/world.css">
        <link rel="stylesheet" href="stylesheets/tabpage.css">
        <link rel="icon" type="image/png" href="favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">
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
                <img src="<?php echo $world_data['banner']?>">
                <h1><?php echo display_input($world) ?> <span class="extra">(world)</span></h1>
            </div>
            <ul id="navbar">
                <li class="active" id="wiki_tab">
                    <a href="#" onclick="showTab('wiki')">
                        <img src="data/icons/nation_wiki.png">
                        <span class="navtext">Wiki Entry</span>
                    </a>
                </li>
                <li id="posts_tab">
                    <a href="#" onclick="showTab('posts')">
                        <img src="data/icons/nation_news.png">
                        <span class="navtext">Latest News</span>
                    </a>
                </li>
                <li id="nations_tab">
                    <a href="#" onclick="showTab('nations')">
                        <img src="data/icons/nation_flag.png">
                        <span class="navtext">Nations</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $world_data['map'] ?>" target="_blank">
                        <img src="data/icons/nation_map.png">
                        <span class="navtext">Map</span>
                    </a>
                </li>
            </ul>
        </div>

        <div id="wiki" class="content">
            <?php echo $Parsedown->text($wikidata) ?>
        </div>

        <div id="posts" class="content">
            <h3>Recent Happenings</h3>
            <ul id="happenings">
                
            </ul>
            <hr>
            <h3>Latest Event Posts</h3>
            <?php echo $posts_html ?>
            <a href="post?w=<?php echo $world ?>">
                <button>Older Posts</button>
            </a>
        </div>

        <div id="nations" class="content">
            <h3>List of Nations</h3>
            <?php echo $residents_html ?>
        </div>

        <ul id="menu">
            <li><a href="#" onclick="closeMenu()">Close Menu</a></li>
            <li>
                <form action="nation" method="get" id="nation_form">
                    <input type="text" name="n" value="Nation Name" onfocus="clearText(this)">
                    <input type="text" name="w" value="World Name" onfocus="clearText(this)">
                    <button>View Nation</button><button onclick="nationForm.action = 'editnation'">Edit Nation</button>
                </form>
            </li>
            <li>
                <form action="" method="get" id="world_form">
                    <input type="text" name="w" value="World Name" onfocus="clearText(this)">
                    <button>View World</button><button onclick="worldForm.action = 'editworld'">Edit World</button>
                </form>
            </li>
            <li><a href="terms">Terms and Privacy Policy</a></li>
            <li id="identifier">World Founder Identifier: <?php echo $world_data["userID"] ?>
            </li>
        </ul>

        <script>
            var nationForm = document.getElementById("nation_form");
            var worldForm = document.getElementById("world_form");

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