Create some sort of header<br><br>

<form method="GET" action="">
	<?php 
		$data = array('' => 'Select minimum rating',
									'1' => '>= 1',
									'2' => '>= 2',
									'3' => '>= 3',
									'4' => '>= 4',
									'5' => '5');

		generate_dropdown_db('Neighborhoods', 'name', 'name');
		generate_dropdown_db('Cuisines', 'cid', 'name');
		generate_dropdown_db('Vibes', 'vid', 'name');
		generate_dropdown('Ratings', $data);
	?>			
	<button name="filterSubmit" value="submit" type="submit">
		Search
	</button>			
</form>

<?php include 'login.php' ?>
<br><br>


<?php
	function connect()
	{
		ini_set('display_errors', 'On');
		$db = "w4111b.cs.columbia.edu:1521/adb";
		return oci_connect("nb2555", "nbjc", $db);
	}

	function generate_dropdown_db($table, $value, $text)
	{
		$conn = connect();
		$query = 'SELECT DISTINCT '.$value.', '.$text.' FROM '.$table;
		$stmt = oci_parse($conn, $query);
		oci_execute($stmt, OCI_DEFAULT);

		echo '<select name="'.$table.'">';
		echo '<option value="">Select From '.$table.'</option>';

		while ($res = oci_fetch_row($stmt))
		{
			echo '<option value="'.trim($res[0]).'">'.$res[1].'</option>';
		}
		echo '</select>';
				
		oci_close($conn);
	}

	function generate_dropdown($name, $data)
	{
		echo '<select name="'.$name.'">';
		foreach ($data as $value => $text)
		{
			echo '<option value="'.$value.'">'.$text.'</option>';
		}
		echo '</select>';
	}
?>
