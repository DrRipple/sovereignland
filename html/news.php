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

    $data_json = file_get_contents("data/worlds/$world.json");
    if ($data_json == false) {
        echo "That world could not be found.";
        die();
    }

    $posts = scandir("data/posts/$world/");
    $posts_html = "";
    for ($i = count($posts) - 1; $i > 1; $i--) {
        $timestamp_unix = substr($posts[$i], 0, 10);
        $timestamp_show = date("d/m/Y H:i:s", $timestamp_unix);
        $time_html = "<span class='timestamp'>Posted at $timestamp_show</span>";
        $post_data = file_get_contents("data/posts/$world/" . $posts[$i]);
        $post_html = $Parsedown->text($post_data);

        $posts_html .= "<div class='post'>$time_html<div class='postcontent'>$post_html</div></div>";
    }
}
?>
<html>
	<head>
		<title>Newsfeed | Sovereign.Land</title>
		<link rel="stylesheet" href="stylesheets/news.css">
		<link rel="icon" type="image/png" href="favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700|Roboto+Slab:400,700">
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
		<div class="content">
			<h1 id="title"><?php echo display_input($world) ?> Newsfeed</h1>
			<?php echo $posts_html ?>
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