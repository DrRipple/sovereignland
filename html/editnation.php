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

if (isset($_GET["n"])) {
    $nation = format_input($_GET["n"]);
    $world = format_input($_GET["w"]);
    $world_display = display_input($world);
    $world_url = "world?w=$world";
    $nation_url = "nation?n=$nation&w=$world";

    $basicdata_json = file_get_contents("data/nations/$world/$nation/basic.json");
    if ($basicdata_json == false) {
        echo "That world or nation could not be found.";
        die();
    }
    $basicdata = json_decode($basicdata_json, true);
    $wikifile = "data/nations/$world/$nation/wiki.md";
    $wikidata = file_get_contents($wikifile);
} else {
    $html = "
        <form action='' method='get'>
        <p>Nation Name</p>
        <input type='text' name='n'>
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
		<title>Edit <?php echo display_input($nation) ?> | Sovereign.Land</title>
        <link rel="stylesheet" href="stylesheets/editor.css">
        <link rel="stylesheet" href="stylesheets/tabpage.css">
        <link rel="icon" type="image/png" href="favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
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
                <span id="signin"><a href="#" onclick="showMenu()"><img src="data/icons/menu.png"></a></span>
            </div>
        </div>
        <div id="titlearea">
            <div id="title">
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
                <img src="<?php echo $basicdata['flagURL']?>">
                <h1>
                    <a href="<?php echo $nation_url ?>"><?php echo display_input($nation) ?></a>
                    <span class="extra">(Editing)</span>
                </h1>
            </div>
            <ul id="navbar">
                <li class="active" id="wiki_tab">
                    <a href="#" onclick="showTab('wiki')">
                        <img src="data/icons/nation_wiki.png">
                        <span class="navtext">Edit Wiki Entry</span>
                    </a>
                </li>
                <li id="post_tab">
                    <a href="#" onclick="showTab('post')">
                        <img src="data/icons/nation_news.png">
                        <span class="navtext">Write New Post</span>
                    </a>
                </li>
                <li id="flag_tab">
                    <a href="#" onclick="showTab('flag')">
                        <img src="data/icons/nation_flag.png">
                        <span class="navtext">Edit Flag</span>
                    </a>
                </li>
                <li id="friends_tab">
                    <a href="#" onclick="showTab('friends')">
                        <img src="data/icons/nation_friends.png">
                        <span class="navtext">Edit Friends</span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="wiki" class="content">
            <h3>National Wiki Entry</h3>
            <p>Formatted using <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>.</p>
            <form action="panel/wiki.php" method="post" target="_blank">
                <input type="hidden" name="nation" value="<?php echo $nation ?>">
                <input type="hidden" name="world" value="<?php echo $world ?>">
                <input type="hidden" name="token" class="tokenbox">
                <textarea name="wiki"><?php echo $wikidata ?></textarea>
                <hr>
                <p class="g_signin_text">Sign-In With Google Above</p>
                <div class="finalbutton">
                    <p>Make sure you read over your post before publishing.</p>
                    <button>Publish Wiki Entry</button>
                </div>
            </form>
        </div>

        <div id="post" class="content">
            <h3>Event Posting</h3>
            <p>Formatted using <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>.</p>
            <form action="panel/post.php" method="post" target="_blank">
                <input type="hidden" name="nation" value="<?php echo $nation ?>">
                <input type="hidden" name="world" value="<?php echo $world ?>">
                <input type="hidden" name="token" class="tokenbox">
                <textarea name="post"># Event Title</textarea>
                <hr>
                <p class="g_signin_text">Sign-In With Google Above</p>
                <div class="finalbutton">
                    <p>Make sure you read over your post before publishing.</p>
                    <button>Publish Post</button>
                </div>
            </form>
        </div>

        <div id="flag" class="content">
            <h3>Edit Your Flag</h3>
            <form action="panel/flag.php" method="post" target="_blank">
                <input type="hidden" name="nation" value="<?php echo $nation ?>">
                <input type="hidden" name="world" value="<?php echo $world ?>">
                <input type="hidden" name="token" class="tokenbox">
                <p>Flag URL</p>
                <input type="text" name="flag" onblur="flagPreview()" id="flag_url">
                <p class="helptext">To use a custom flag, upload it to an image sharing site and enter the direct link. Leave the box blank to use the default flag.</p>
                <p>Preview:</p>
                <img src="<?php echo $basicdata['flagURL']?>" id="flagpreview">
                <p class="g_signin_text">Sign-In With Google Above</p>
                <div class="finalbutton">
                    <button>Change Flag</button>
                </div>
            </form>
        </div>

        <div id="friends" class="content">
            <h3>Add Friends and Enemies</h3>
            <p>The option to remove friends and enemies will be added soon.</p>
            <form action="panel/friends.php" method="post" target="_blank">
                <input type="hidden" name="nation" value="<?php echo $nation ?>">
                <input type="hidden" name="world" value="<?php echo $world ?>">
                <input type="hidden" name="token" class="tokenbox">
                <p>Nation Name</p>
                <input type="text" name="target">
                <p>Relation</p>
                <select name="mode">
                    <option value="af">Add Friend</option>
                    <option value="ae">Add Enemy</option>
                </select>
                <p class="g_signin_text">Sign-In With Google Above</p>
                <div class="finalbutton">
                    <p>*Relations can only be between nations in the same world.</p>
                    <button>Declare Relation</button>
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

            function flagPreview() {
                var flagURL = document.getElementById("flag_url").value;
                var previewBox = document.getElementById("flagpreview");
                if (flagURL == "") flagURL = "<?php echo $basicdata['flagURL']?>";
                previewBox.src = flagURL;
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