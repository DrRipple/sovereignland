<!DOCTYPE html>
<html>
	<head>
		<title>Create Nation | Sovereign.Land</title>
		<link rel="icon" type="image/png" href="../favicon.png">
		<link rel="stylesheet" href="style.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700|Roboto:400,400i,700,700i">
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
    	<form action="phase2.php" method="post">
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
	    		<input type="text" name="flag">
	    		<p class="helptext">To use a custom flag, upload it to an image sharing site and enter the direct link. Leave the box blank to use the default flag.</p>
	    		<p>Preview:</p>
	    		<img src="../data/flag.png" id="flagpreview">
	    	</span>

	    	<hr>

	    	<span class="textfield">
	    		<p><input type="checkbox" name="confirmation" value="true"> I confirm that I have read and will abide by the <a href="#">Terms and Conditions</a>.</p>
	    	</span>
	    	<button>Proceed to Next Step</button>
    	</form>
    </body>
</html>