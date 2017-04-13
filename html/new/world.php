<!DOCTYPE html>
<html>
	<head>
		<title>Create World | Sovereign.Land</title>
		<link rel="icon" type="image/png" href="../favicon.png">
		<link rel="stylesheet" href="style.css">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

        <meta name="google-signin-client_id" content="1057622173913-bpi238tov8so32pbm4lj12u4elordq20.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
    </head>
    <body>
        <div id="topbar">
            <div id="topcontainer">
                <div id="desc">
                    <h1>Create Your World</h1>
                    <h2>sovereign.land Nation Simulator</h2>
                </div>
            </div>
        </div>
        <form action="world2.php" method="post">
            <input type="hidden" name="token" id="tokenbox">
        
            <span class="textfield">
                <p>World Name</p>
                <input type="text" name="name">
                <p class="helptext">Enter the name of your world (ex. Earth). This is what will be displayed on the world browser.</p>
            </span>

            <span class="textfield">
                <p>Brief Description</p>
                <input type="text" name="desc" maxlength="70">
                <p class="helptext">Enter a short description of your world, preferably one sentence long. This can be edited later.</p>
            </span>

            <span class="textfield">
                <p>Sharing Options</p>
                <select name="sharing">
                    <option value="public">Public</option>
                    <option value="semi">Semi-Private</option>
                    <option value="private">Private</option>
                </select>
                <p class="helptext">Public: Anyone can join. Semi-Private: Anyone can apply to join. Private: Only people invited can join.</p>
            </span>

            <span class="textfield">
                <p>Flag/Banner URL</p>
                <input type="text" name="flag" onblur="flagPreview()" id="flag_url">
                <p class="helptext">To use a custom flag, upload it to an image sharing site and enter the direct link. Leave the box blank to use the default flag.</p>
                <p>Preview:</p>
                <img src="../data/flag.png" id="flagpreview">
            </span>

            <span class="textfield">
                <p>Map URL</p>
                <input type="text" name="map">
                <p class="helptext">Enter the sharing URL of your world's map. You may leave this blank and add it later.</p>
            <hr>

            <span class="textfield">
                <p id="g_signin_text">Sign-In With Google</p>
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
            </span>

            <span class="textfield" id="finalbutton">
                <p>By clicking the button below, you confirm that you have read and will abide by the <a href="#" target="_blank">Terms and Conditions</a>.</p>
                <button>Proceed to Next Step</button>
            </span>
        </form>

        <script>
            function flagPreview() {
                var flagURL = document.getElementById("flag_url").value;
                var previewBox = document.getElementById("flagpreview");
                if (flagURL == "") flagURL = "../data/flag.png";
                previewBox.src = flagURL;
            }

            function onSignIn(googleUser) {
                var profile = googleUser.getBasicProfile();
                var signinText = document.getElementById("g_signin_text");
                signinText.innerHTML = "Signed-In as: " + profile.getName();

                var proceedButton = document.getElementById("finalbutton");
                proceedButton.style.display = "block";

                var tokenBox = document.getElementById("tokenbox");
                tokenBox.value = googleUser.getAuthResponse().id_token;
            }
        </script>
    </body>
</html>