<?php
	ini_set('display_errors', 'On');
	$db = "w4111b.cs.columbia.edu:1521/adb";
	$conn = oci_connect("nb2555", "nbjc", $db);
	$stmt = oci_parse($conn, "select * from Restaurants");
	oci_execute($stmt, OCI_DEFAULT);
	while ($res = oci_fetch_row($stmt))
	{
		echo "Restaurant Name: ". $res[1]."<br>" ;
	}
	oci_close($conn);
?>
