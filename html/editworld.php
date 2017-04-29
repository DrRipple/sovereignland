<!DOCTYPE html>
<?php
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
    $world_display = display_input($world);
    $world_url = "world?w=$world";

    $world_json = file_get_contents("data/worlds/$world.json");
    if ($world_json == false) {
        echo "That world could not be found.";
        die();
    }
    $world_data = json_decode($world_json, true);
    $wiki_file = "data/worlds/$world.md";
    $wiki_data = file_get_contents($wiki_file);
} else {
    $html = "
        <form action='' method='get'>
        <p>World Name</p>
        <input type='text' name='w'>
        <br>
        <button>View Panel</button>
        </form>
    ";
    echo $html;
    die();
}
?>
<html>
	<head>
		<title>Sign In | Sovereign.Land</title>
        <link rel="stylesheet" href="stylesheets/editnation.css">
        <link rel="icon" type="image/png" href="favicon.png">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

		<meta name="google-signin-client_id" content="1057622173913-bpi238tov8so32pbm4lj12u4elordq20.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
    </head>
    <body>
    	<div id="topbar">
            <div id="topcontainer">
                <h1>
                    <a href="/">sovereign.land</a>
                </h1>
                <span id="signin"><a href="#" onclick="showMenu()">Menu</a></span>
            </div>
        </div>
        <div id="titlearea">
            <div id="nationtitle">
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
                <img src="<?php echo $world_data['banner']?>">
                <h1>
                    <a href="<?php echo $world_url ?>"><?php echo $world_display ?></a>
                    <span class="extra">(Editing)</span>
                </h1>
            </div>
            <ul id="navbar">
                <li class="active"><img src="data/icons/nation_wiki.png"><a href="#">Edit Wiki Entry</a></li>
            </ul>
        </div>
        <div id="wiki" class="content">
            <h3><?php echo $world_display ?> Wiki Entry</h3>
            <p>Formatted using <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>.</p>
            <form action="panel/worldwiki.php" method="post" target="_blank">
                <input type="hidden" name="nation" value="<?php echo $nation ?>">
                <input type="hidden" name="world" value="<?php echo $world ?>">
                <input type="hidden" name="token" class="tokenbox">
                <textarea name="wiki"><?php echo $wiki_data ?></textarea>
                <hr>
                <p class="g_signin_text">Sign-In With Google Above</p>
                <div class="finalbutton">
                    <p>Make sure you read over your post before publishing.</p>
                    <button>Publish Wiki Entry</button>
                </div>
            </form>
        </div>

        <ul id="menu">
            <li><a href="#" onclick="closeMenu()">Close Menu</a></li>
            <li>
                <form action="" method="get" id="nation_form">
                    <input type="text" name="n" value="Nation Name" onfocus="clearText(this)">
                    <input type="text" name="w" value="World Name" onfocus="clearText(this)">
                    <button onclick="nationForm.action = 'nation'">View Nation</button><button>Edit Nation</button>
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

    		function onSignIn(googleUser) {
    			var profile = googleUser.getBasicProfile();
    			var signinTexts = document.getElementsByClassName("g_signin_text");
    			var proceedButtons = document.getElementsByClassName("finalbutton");
    			var tokenBoxes = document.getElementsByClassName("tokenbox");

                for (var i = 0; i < signinTexts.length; i++) {
                    signinTexts[i].innerHTML = "Signed-In as: " + profile.getName();
                    proceedButtons[i].style.display = "block";
                    tokenBoxes[i].value = googleUser.getAuthResponse().id_token;
                }
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