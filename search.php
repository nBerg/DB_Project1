<html>
	<head></head>
	<body>
		Search Results:
		<?php
			$result = submit_search_query($_GET);
			build_table($result);
		?>

		<?php
			function submit_search_query($data) 
			{
				ini_set('display_errors', 'On');
				$db = "w4111b.cs.columbia.edu:1521/adb";
				$conn = oci_connect("nb2555", "nbjc", $db);

				$useAnd = false;
				if (strlen($data['Neighborhoods']) > 0)
				{
					$N = 'N.name LIKE\''.$data['Neighborhoods'].'%\'';
					$useAnd = true;
				} else $N = '';
			
				if (strlen($data['Cuisines']) > 0)
				{
					if ($useAnd) $C = 'AND ';
					else $C = '';

					$C = $C.'C.cid LIKE\''.$data['Cuisines'].'%\'';
					$useAnd = true;
				} else $C = '';

				if (strlen($data['Vibes']) > 0)
				{
					if ($useAND) $V = 'AND ';
					else $V = '';
			
					$V = $V.'V.vid LIKE\''.$data['Vibes'].'%\'';
				} else $V = '';

				$query = 'SELECT R.name, R.street, N.name, R.phone, R.avgPrice
								  FROM Restaurants R 
												 JOIN Neighborhoods N ON R.zip = N.zip
												 JOIN Serves S ON R.rid = S.rid
												 JOIN Cuisines C ON S.cid = C.cid
												 JOIN RestVibe RV ON R.rid = RV.rid
												 JOIN Vibes V ON RV.vid = V.vid, Neighborhoods N2
									WHERE '.$N.' '.$C.' '.$V.'
									GROUP BY R.rid, R.name, R.street, N.name, R.phone, R.avgPrice';

				$stmt = oci_parse($conn, $query);
				oci_execute($stmt, OCI_DEFAULT);

/*				while ($res = oci_fetch_row($stmt))
				{
					echo 'Row: '.print_r($res).'<br>';
				}
	*/			
				oci_close($conn);
				return $stmt;
			}

			function build_table($data) 
			{
				echo '<table border="1">';
				echo "<thead><tr><th>Restaurant</th>
												 <th>Address</th>
												 <th>Neighborhood</th>
												 <th>Phone Number</th>
												 <th>AvgPrice</th></tr></thead>";
				while ($row = oci_fetch_row($data))
				{
					echo "<tr><td>".$row[0]."</td><td>".$row[1]
								."</td><td>".$row[2]."</td><td>".$row[3]
								."</td><td>".$row[4]."</td></tr>";
				}
				echo "</table>";
			}
		?>
	</body>
</html>
