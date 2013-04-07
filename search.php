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
				$where = 'N.name LIKE \''.$data['Neighborhoods'].'%\'
									AND C.cid LIKE \''.$data['Cuisines'].'%\'
									AND V.vid LIKE \''.$data['Vibes'].'%\'';

				$having = '';
				if (strlen($data['Ratings']) > 0) {
					$having = 'HAVING AVG(W.rating) >= '.$data['Ratings'];
				}

				$query = 'SELECT R.rid, R.name, R.street, N.name, R.phone, 
												R.avgPrice, AVG(W.rating) Rating 
								  FROM Restaurants R 
												 JOIN Neighborhoods N ON R.zip = N.zip
												 JOIN Serves S ON R.rid = S.rid
												 JOIN Cuisines C ON S.cid = C.cid
												 JOIN RestVibe RV ON R.rid = RV.rid
												 JOIN Vibes V ON RV.vid = V.vid
												 JOIN Reviews W ON R.rid = W.rid
									WHERE '.$where.'
									GROUP BY R.rid, R.name, R.street, N.name, 
													 R.phone, R.avgPrice '.$having;

				$conn = connect();
				$stmt = oci_parse($conn, $query);
				oci_execute($stmt, OCI_DEFAULT);
				oci_close($conn);

				return $stmt;
			}

			function build_table($data) 
			{
				echo '<table border="1">';
				echo '<thead><tr><th>Restaurant</th>
												 <th>Address</th>
												 <th>Neighborhood</th>
												 <th>Phone Number</th>
												 <th>AvgPrice</th>
												 <th>Rating</th></tr></thead>';
				$list = array();
				for($i = 1; $i <= 6; $i++){
					array_push($list, $i);
				}
				$j = 0;
				while ($row = oci_fetch_row($data))
				{
					echo '<tr><td>
								<a href="detail.php?id='.$row[0].'">'.$row[1].'</a>
								</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].
								'</td><td>'.$row[5].'</td><td>'.round($row[6]).'</td><td>
								<form  method="post">';
					echo '<input name='.$list[$j].' type="submit" value='.$list[$j].'>
								</form>
								</tr>';
					$j++;
				}

				$count = count($list);
				for($i = 0; $i < $count; $i++){
					if (isset($_POST[$list[$i]])) {
						echo 'Hit button: ';
						echo $list[$i];
						break;
					}
				}

				echo '</table>';
			}
		?>
	</body>
</html>
