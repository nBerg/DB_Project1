<html>
	<head>
		<title>DB Project</title>
	</head>

	<body>
		Create some sort of header<br><br>

		<form method="GET" action="search.php">
			<?php 
				generate_dropdown('Neighborhoods', 'name', 'name');
				generate_dropdown('Cuisines', 'cid', 'name');
				generate_dropdown('Vibes', 'vid', 'name');
			?>			
			<button name="filterSubmit" value="submit" type="submit">
				Search
			</button>			
		</form>

		<?php
			function connect()
			{
				ini_set('display_errors', 'On');
				$db = "w4111b.cs.columbia.edu:1521/adb";
				return oci_connect("nb2555", "nbjc", $db);
			}

			function generate_dropdown($table, $value, $text)
			{
				$conn = connect();
				$query = 'SELECT DISTINCT '.$value.', '.$text.' FROM '.$table;
				$stmt = oci_parse($conn, $query);
				oci_execute($stmt, OCI_DEFAULT);

				echo '<select name="'.$table.'">';
				echo '<option value="">Select From '.$table.'</option>';

				while ($res = oci_fetch_row($stmt))
				{
					echo '<option value="'.trim($res[0]).'" "'.trim($res[1]).'">'.$res[1].'</option>';
				}
				echo '</select>';
				
				oci_close($conn);
			}
		?>

	<body>
</html>
