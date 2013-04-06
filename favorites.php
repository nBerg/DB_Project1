<html>
<body>
Welcome to Favorites!
<br></br>
<body>
</html>

<?php
	$user =  $_GET['var'];
	$db = "w4111b.cs.columbia.edu:1521/adb";
	$conn = oci_connect("nb2555", "nbjc", $db);    
	$query = "SELECT R.name FROM Userfavs UF, Restaurants R WHERE UF.rid=R.rid AND UF.usrid='".$user."'";
	$stmt = oci_parse($conn, $query);
	oci_execute($stmt, OCI_DEFAULT);
	$result = oci_fetch_row($stmt);
	if(empty($result)){
		print "<br>No Favorites! <a href=' /~nb2555/DB_Project1/index.php'>Add some!</a></br>";
	}
	while ($result)
	{
		foreach($result as $key => $value)
		{
	  		echo 'Row: '. $value. '<br>';
		}
		$result = oci_fetch_row($stmt);
	}		
	oci_close($conn);
?>