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

if (isset($_GET["sign"])) {
    setcookie("sl_nation", "", time() - 3600, "/");
    setcookie("sl_world", "", time() - 3600, "/");

    echo "Signed out.";
    die();
} elseif (isset($_COOKIE["sl_nation"])) {
    $nation = test_input($_COOKIE["sl_nation"]);
    $world = test_input($_COOKIE["sl_world"]);

    $world_display = display_input($world);
    $world_url = "../world?w=$world";

    $basicdata_json = file_get_contents("../data/nations/$world/$nation/basic.json");
    if ($basicdata_json == false) {
        echo "Incorrect world or nation code.";
        die();
    }
    $basicdata = json_decode($basicdata_json, true);
    $wikifile = "../data/nations/$world/$nation/wiki.md";
    $wikidata = file_get_contents($wikifile);
} elseif (isset($_GET["n"])) {
    setcookie("sl_nation", test_input($_GET["n"]), time() + (86400 * 30), "/");
    setcookie("sl_world", test_input($_GET["w"]), time() + (86400 * 30), "/");
    header("Refresh:0");
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
		<title>Sign In | Sovereign.Land</title>
        <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/png" href="../favicon.png">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

		<meta name="google-signin-client_id" content="1057622173913-bpi238tov8so32pbm4lj12u4elordq20.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
    </head>
    <body>
    	<div id="topbar">
            <div id="topcontainer">
                <h1>
                    <a href="../">sovereign.land</a>
                </h1>
                <span id="signin"><a href="?sign=out">Sign Out</a></span>
            </div>
        </div>
        <div id="titlearea">
            <div id="nationtitle">
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
                <img src="<?php echo $basicdata['flagURL']?>">
                <h1>
                    <?php echo display_input($nation) ?>
                    <span class="extra">(Editing)</span>
                </h1>
            </div>
            <ul id="navbar">
                <li class="active" id="wiki_tab"><img src="../data/icons/nation_wiki.png"><a href="#" onclick="showTab('wiki')">Edit Wiki Entry</a></li>
                <li id="post_tab"><img src="../data/icons/nation_news.png"><a href="#" onclick="showTab('post')">Write New Post</a></li>
                <li id="flag_tab"><img src="../data/icons/nation_flag.png"><a href="#" onclick="showTab('flag')">Edit Flag</a></li>
                <li id="friends_tab"><img src="../data/icons/nation_friends.png"><a href="#" onclick="showTab('friends')">Edit Friends</a></li>
            </ul>
        </div>
        <div id="wiki" class="content">
            <h3>National Wiki Entry</h3>
            <p>Coming soon</p>
        </div>

        <div id="post" class="content">
            <h3>Event Posting</h3>
            <p>Formatted using <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>.</p>
            <form action="post.php" method="post">
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
            <form action="flag.php" method="post">
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
            <form action="friends.php" method="post">
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
                    <button>Edit Relations</button>
                </div>
            </form>
        </div>

        <div style="height: 300px"></div>
        <div id="footer" style="position: fixed; bottom: 0; width: 100%; padding: 30px 40px; background: #eee">This game is in the alpha stage. <a href="../terms">Terms and Privacy Policy</a></div>

    	<script>
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
    	</script>
    </body>
</html>