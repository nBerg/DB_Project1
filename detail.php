<html>
	<head>
		<title>Detail Page</title>
	</head>

	<body>
		<?php 
			include 'header.php'; 
			$result = get_info();
		?>

		<div id="restInfo">
			<div id="header">
				<h1><?php echo $result['basic']['name']; ?></h1>
				<?php echo $result['basic']['address']; ?><br>
				Neighborhood: <?php echo $result['basic']['neighborhood']; ?><br>
				<?php echo $result['basic']['phone']; ?>
			</div>
			<br>
			<div id="additionalInfo">
				<dl>
					<dt>Price Range:</dt>
						<dd><?php echo $result['basic']['avgPrice']; ?>
					<dt>Cuisine:</dt>
						<?php print_array($result['cuisines'], '<dd>', '</dd>'); ?>
					<dt>Vibes:</dt>
						<?php print_array($result['vibes'], '<dd>', '</dd>'); ?>
				</dl>
			</div>
		</div>
		<br>
		<div id='reviews'>
			<h3>Reviews:</h3>
			Average Rating: <?php echo round($result['basic']['avgRating']); ?>
			<?php print_reviews($result['reviews']); ?>
		</div>

		<?php
			function get_info()
			{
				$conn = connect();

				$info = array();	
				$info = get_rest_info($conn, $info);
				$info = get_cuisine_info($conn, $info);
				$info = get_vibe_info($conn, $info);
				$info = get_review_info($conn, $info);

				oci_close($conn);

				return $info;
			}

			function get_rest_info($conn, $result)
			{
				$query = 'SELECT R.name, R.street, N.name, R.phone, 
												R.avgPrice, AVG(W.rating) Rating 
								  FROM Restaurants R 
											 JOIN Neighborhoods N ON R.zip = N.zip
											 JOIN Serves S ON R.rid = S.rid
											 JOIN Cuisines C ON S.cid = C.cid
											 JOIN RestVibe RV ON R.rid = RV.rid
											 JOIN Vibes V ON RV.vid = V.vid
											 JOIN Reviews W ON R.rid = W.rid
									WHERE R.rid = '.$_GET['id'].'
									GROUP BY R.rid, R.name, R.street, N.name, R.phone, 
													 R.avgPrice';

				$stmt = oci_parse($conn, $query);
				oci_execute($stmt, OCI_DEFAULT);

				$row = oci_fetch_row($stmt);
				$data = array('name' => $row[0],
											'address' => $row[1],
											'neighborhood' => $row[2],
											'phone' => $row[3],
											'avgPrice' => $row[4],
											'avgRating' => $row[5]);

				$result['basic'] = $data;
				return $result; 
			}

			function get_cuisine_info($conn, $result)
			{
				$query = 'SELECT C.name
									FROM Restaurants R
												JOIN Serves S ON R.rid = S.rid
												JOIN Cuisines C ON C.cid = S.cid
									WHERE R.rid = '.$_GET['id'];

				$stmt = oci_parse($conn, $query);
				oci_execute($stmt, OCI_DEFAULT);

				while ($row = oci_fetch_row($stmt))
				{
					$cuisines[] = $row[0]; 
				}

				$result['cuisines'] = $cuisines;
				return $result;
			}

			function get_vibe_info($conn, $result)
			{
				$query = 'SELECT V.name
									FROM Restaurants R
											JOIN restVibe RV ON R.rid = RV.rid
											JOIN Vibes V on RV.vid = V.vid
									WHERE R.rid = '.$_GET['id'];

				$stmt = oci_parse($conn, $query);
				oci_execute($stmt, OCI_DEFAULT);

				while($row = oci_fetch_row($stmt))
				{
					$vibes[] = $row[0];
				}

				$result['vibes'] = $vibes;
				return $result;
			}

			function get_review_info($conn, $result)
			{
				$query = 'SELECT W.revid, W.rating, W.datewritten, W.text
									FROM Restaurants R
											JOIN Reviews W on R.rid = W.rid
									WHERE R.rid = '.$_GET['id'];

				$stmt = oci_parse($conn, $query);
				oci_execute($stmt, OCI_DEFAULT);

				while($row = oci_fetch_row($stmt))
				{
					$reviews[] = array('rating' => $row[1],
													'date' => $row[2],
													'text' => $row[3]);
				}

				$result['reviews'] = $reviews;
				return $result;
			}

			function print_array($arr, $openTag, $closeTage)
			{
				foreach($arr as $value)
				{
					echo $openTag.$value.$closeTage;
				}
			}

			function print_reviews($reviews)
			{
				echo '<ul>';

				foreach($reviews as $rev)
				{
					echo '<li>';
					echo '<div id="info">
									Rating: '.$rev['rating'].
									' Date: '.date('m/d/Y',strtotime($rev['date'])).'</div>';
					echo '<div id ="review-text">'.$rev['text'].'</div>';
					echo '</li>';
				}

				echo '</ul>';
			}

		?>
	</body>
</html>
