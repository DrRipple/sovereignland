<html>
	<head>
		<title>Sovereign.Land</title>
		<link rel="icon" type="image/png" href="favicon.png">
		<link rel="stylesheet" href="stylesheets/homepage.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

        <meta name="google-signin-client_id" content="1057622173913-bpi238tov8so32pbm4lj12u4elordq20.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
    </head>
    <body>
    	<div id="topbar">
            <div id="topcontainer">
                <div id="desc">
    				<h1>Sovereign.Land</h1>
    				<h2>Create a new nation <a href="new/nation.php">here</a>.</h2>
                </div>
            </div>
        </div>
    	
    	<form action="nation" method="get">
    		<h3>Nation Viewer</h3>
    		<span class="textfield">
    			<p>Nation Name</p>
    			<input type="text" name="n">
    			<p class="helptext">Enter the name of the nation you would like to view.</p>
    		</span>
    		<span class="textfield">
    			<p>World Name</p>
    			<input type="text" name="w">
    			<p class="helptext">Enter the name of the world of the nation you would like to view.</p>
    		</span>
    		<span class="textfield">
    			<button>View Nation</button>
    		</span>
    	</form>

        <hr>

    	<div class="content">
            <h3>Current Worlds</h3>
            <div class="world">
                <h4><a href="world?w=the_pacific">The Pacific</a></h4>
                <img src="http://i.imgur.com/pkCfxGV.png">
                <p>Lorem ipsum dolor si amet.</p>
            </div>
            <div class="world">
                <h4><a href="world?w=dreiland">Dreiland</a></h4>
                <img src="http://i.imgur.com/egkgcyT.png">
                <p>Invite only, serious RP world from the founder of SL</p>
            </div>
            <div class="world">
                <h4><a href="world?w=ussd">The USSD</a></h4>
                <img src="http://i.imgur.com/IgEL8Du.png">
                <p>Lorem ipsum dolor si amet.</p>
            </div>
        </div>

        <div style="height: 300px"></div>
        <div id="footer" style="position: fixed; bottom: 0; width: 100%; padding: 30px 40px; background: #eee">This game is in the alpha stage. <a href="terms">Terms and Privacy Policy</a></div>

    	<script>
    		function flagPreview() {
    			var flagURL = document.getElementById("flag_url").value;
    			var previewBox = document.getElementById("flagpreview");
    			if (flagURL == "") flagURL = "data/flag.png";
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