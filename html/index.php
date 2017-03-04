<!DOCTYPE html>
<html>
	<head>
		<title>Sovereign.Land</title>
		<link rel="icon" type="image/png" href="favicon.png">

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
		</script>
	</head>
	<body>
		<h1>Hello, world!</h1>
		<p>Sovereign.land is currently in development.</p>
		<p>Nation simulator game by Solborg.</p>
		<div class="g-signin2" data-onsuccess="onSignIn"></div>
	</body>
</html>