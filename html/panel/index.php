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
                <img src="<?php echo $basicdata['flagURL']?>">
                <h1>
                    <?php echo $basicdata["name"]?>
                    <span class="regular">(Editing)</span> 
                </h1>
            </div>
            <ul id="navbar">
                <li class="active" id="wiki_tab"><img src="../data/icons/nation_wiki.png"><a href="#" onclick="showTab('wiki')">Edit Wiki Entry</a></li>
                <li id="post_tab"><img src="../data/icons/nation_news.png"><a href="#" onclick="showTab('post')">Write New Post</a></li>
                <li id="nations_tab"><img src="../data/icons/nation_flag.png"><a href="#" onclick="showTab('nations')">Edit Flag</a></li>
                <li><img src="../data/icons/nation_friends.png"><a href="#">Edit Friends</a></li>
            </ul>
        </div>
        <div id="wiki" class="content">
            <h3>National Wiki Entry</h3>
        </div>

        <div id="post" class="content">
            <h3>Event Posting</h3>
            <p>Formatted using <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>.</p>
            <form action="post.php" method="post">
                <input type="hidden" name="nation" value="<?php echo $nation ?>">
                <input type="hidden" name="world" value="<?php echo $world ?>">
                <input type="hidden" name="token" id="tokenbox">
                <textarea name="post"># Event Title</textarea>
                <hr>
                <p id="g_signin_text">Sign-In With Google</p>
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
                <div id="finalbutton">
                    <p>Make sure you read over your post before publishing.</p>
                    <button>Publish Post</button>
                </div>
            </form>
        </div>

    	<script>
    		function onSignIn(googleUser) {
    			var profile = googleUser.getBasicProfile();
    			var signinText = document.getElementById("g_signin_text");
    			signinText.innerHTML = "Signed-In as: " + profile.getName();

    			var proceedButton = document.getElementById("finalbutton");
    			proceedButton.style.display = "block";

    			var tokenBox = document.getElementById("tokenbox");
    			tokenBox.value = googleUser.getAuthResponse().id_token;
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