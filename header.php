<div id="header-wrap" class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-inner row-fluid">
			<a href="index.php" class="brand span10">Food Finder</a>
			<a href="login.php" class="navbar-link span1">Log in</a>
		</div>
	</div>
</div>


<?php
	function connect()
	{
		ini_set('display_errors', 'On');
		$db = "w4111b.cs.columbia.edu:1521/adb";
		return oci_connect("nb2555", "nbjc", $db);
	}

?>
