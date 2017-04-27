<!DOCTYPE html>
<?php
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_GET["w"])) {
    $world = test_input($_GET["w"]);
} else {
    $world = "";
}
?>
<html>
	<head>
		<title>Create Nation | Sovereign.Land</title>
		<link rel="icon" type="image/png" href="../favicon.png">
		<link rel="stylesheet" href="../stylesheets/creator.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

        <meta name="google-signin-client_id" content="1057622173913-bpi238tov8so32pbm4lj12u4elordq20.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
    </head>
    <body>
    	<div id="topbar">
            <div id="topcontainer">
                <div id="desc">
    				<h1>Create Your Nation</h1>
    				<h2>sovereign.land Nation Simulator</h2>
                </div>
            </div>
        </div>
    	<form action="nation2.php" method="post">
    		<input type="hidden" name="token" id="tokenbox">

            <span class="textfield">
                <p>World Code</p>
                <input type="text" name="world" value="<?php echo $world ?>">
                <p class="helptext">Enter the code for the world you wish to join. The world creator should have given you one.</p>
            </span>
    		
    		<span class="textfield">
	    		<p>Nation Name</p>
	    		<input type="text" name="name">
	    		<p class="helptext">Enter the common name of your nation (ex. Finland).</p>
	    	</span>
	    	<span class="textfield">
	    		<p>Official Name</p>
	    		<input type="text" name="official">
	    		<p class="helptext">Enter the official name of your nation (ex. The Republic of Finland).</p>
	    	</span>

	    	<hr>

	    	<span class="textfield">
	    		<p>Currency Code</p>
	    		<input type="text" name="currency">
	    		<p class="helptext">Enter your nation's three-letter currency code (ex. EUR).</p>
	    	</span>
	    	<span class="textfield">
	    		<p>Currency Value</p>
	    		<input type="number" name="cvalue">
	    		<p class="helptext">Enter the desired value of your nation's currency in US cents (ex. 106).</p>
	    	</span>

	    	<hr>

	    	<span class="textfield">
	    		<p>Flag URL</p>
	    		<input type="text" name="flag" onblur="flagPreview()" id="flag_url">
	    		<p class="helptext">To use a custom flag, upload it to an image sharing site and enter the direct link. Leave the box blank to use the default flag.</p>
	    		<p>Preview:</p>
	    		<img src="../data/flag.png" id="flagpreview">
	    	</span>

	    	<hr>

	    	<span class="textfield">
    			<p id="g_signin_text">Sign-In With Google</p>
    			<div class="g-signin2" data-onsuccess="onSignIn"></div>
    		</span>

	    	<span class="textfield" id="finalbutton">
	    		<p>By clicking the button below, you confirm that you have read and will abide by the <a href="../terms" target="_blank">Terms and Conditions</a>.</p>
	    		<button>Proceed to Next Step</button>
	    	</span>
    	</form>

        <div style="height: 300px"></div>
        <div id="footer" style="position: fixed; bottom: 0; width: 100%; padding: 30px 40px; background: #eee">This game is in the alpha stage. <a href="../terms">Terms and Privacy Policy</a></div>

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