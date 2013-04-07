<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Register</title>
</head>
<FORM ACTION="registration.php" METHOD=get>
	<h1>Registration Page</h1>
	Please input the registration details to create an account here<br>
	<table border="2">
		<tr>
			<td>User Name :</td><td><input name="regname" type="text" size"20"></input></td>
		</tr>
		<tr>
			<td>email :</td><td><input name="regemail" type="text" size"20"></input></td>
		</tr>
		<tr>
			<td>password :</td><td><input name="regpass1" type="password" size"20"></input></td>
		</tr>
		<tr>
			<td>retype password :</td><td><input name="regpass2" type="password" size"20"></input></td>
		</tr>
	</table>
<input type="submit" value="register me!"></input>
</FORM>
</body>
</html>

<?php
	if($_GET["regname"] && $_GET["regemail"] && $_GET["regpass1"] && $_GET["regpass2"] )
	{
		if($_GET["regpass1"]==$_GET["regpass2"])
		{
			$db = "w4111b.cs.columbia.edu:1521/adb";
			$conn = oci_connect("nb2555", "nbjc", $db);    
			$query = "INSERT INTO USERS (usrid, password) values('$_GET[regname]','$_GET[regpass1]')";
			$result = oci_parse($conn, $query);
			oci_execute($result);
			print "<h1>you have registered sucessfully</h1>";
			print "<a href=' /~nb2555/DB_Project1/login.php'>go to login page</a>";
		}else{
			print "Passwords do not match!";
		}
	}
?>