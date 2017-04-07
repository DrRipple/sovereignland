<!DOCTYPE html>
<html>
	<head>
		<title>Sign In | Sovereign.Land</title>
        <link rel="stylesheet" href="signin.css">
        <link rel="icon" type="image/png" href="../favicon.png">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">

		<meta name="google-signin-client_id" content="1057622173913-bpi238tov8so32pbm4lj12u4elordq20.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        <script>
            function onSignIn(googleUser) {
                var profile = googleUser.getBasicProfile();
                console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
                console.log('Name: ' + profile.getName());
                console.log('Image URL: ' + profile.getImageUrl());
                console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
            }

            function signOut() {
                var auth2 = gapi.auth2.getAuthInstance();
                auth2.signOut().then(function () {
                    console.log("User signed out.");
                });
            }
        </script>
    </head>
    <body>
    	<div id="boxtop">
    		<h1>Sovereign.Land Sign-In</h1>
    		<h2>Don't have a nation? <a href="../new/nation.php">Sign Up</a></h2>
    	</div>
    	<div id="boxbottom">
    		<form action="nation.php" method="post">
    			<input type="hidden" id="tokenbox" name="token">
    			<p>Nation Name</p>
    			<input type="text" name="nation">

    			<p>World Name</p>
    			<input type="text" name="world">

    			<p id="g_signin_text">Google Sign-In</p>
    			<div class="g-signin2" data-onsuccess="onSignIn"></div>

    			<button id="finalbutton">View Nation Control</button>
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
    	</script>
    </body>
</html>