<form id='login' method='post' accept-charset='UTF-8'>
<fieldset>
<legend>Login</legend>
<input type='hidden' name='submitted' id='submitted' value='1'/>
 
<label for='username' >Username*:</label>
<input type='text' name='username' id='username'  maxlength="50" />
 
<label for='password' >Password*:</label>
<input type='password' name='password' id='password' maxlength="50" />
 
<input type='submit' name='Submit' value='Submit' />
 
</fieldset>
</form>

<?php
	$username = isset($_POST['username']) == true ? $_POST['username'] : '';
	$password= isset($_POST['password']) == true ? $_POST['password'] : '';

	if(empty($username) or empty($password)){
		print "* = Items required!\n";
		print "<br><a href=' /~nb2555/DB_Project1/registration.php'>Register Today!</a></br>";
	}else{
		$db = "w4111b.cs.columbia.edu:1521/adb";
		$conn = oci_connect("nb2555", "nbjc", $db);    
		$query = "SELECT * from USERS WHERE usrid='".$username."' and password='".$password."'";
		$result = oci_parse($conn, $query);
		oci_execute($result);
		$tmpcount = oci_fetch($result);
		if ($tmpcount==1) {
			print "Login Success";
			//header("Location: /~nb2555/DB_Project1/favorites.php?var=$username");
			//header("Location: /~nb2555/DB_Project1/index.php?var=$username");
			//print "<a href='favorites.php?var=$username'>Favorites</a>";
		}else{
			print "Login Failed\n";
			print "<br><a href=' /~nb2555/DB_Project1/registration.php'>Not registered?</a></br>";
		}
	}
?>