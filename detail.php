<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="styling/bootstrap/css/bootstrap.css" type="text.css" />
		<link rel="stylesheet" href="styling/bootstrap/css/bootstrap-responsive.css" type="text/css" />

		<style>
			.padding15 {
				padding: 1em;
			}
		</style>

		<title>Detail Page</title>
	</head>

	<body>
		<?php 
			include 'header.php';
			$result = get_info();
		?>
		
		<div class="container-fluid">
			<div class="row-fluid">
				<div id="side-nav" class="span2">
					<?php include 'filter.php'; ?>
				</div>

			<div id="body" class="span9">
				<div id="restInfo">
					<div class="page-header">
						<h1><?php echo $result['basic']['name']; ?></h1>
					</div>

					<div class="row-fluid">
						<div id="address" class="span2">
							<address class="padding15">
								<?php echo $result['basic']['address']; ?><br>
								<?php echo $result['basic']['neighborhood']; ?><br>
								<abbr title="Phone">P:</abbr>
								<?php echo $result['basic']['phone']; ?>
							</address>
						</div>

						<div id="additionalInfo" class="span8">
							<dl class="dl-horizontal">
								<dt>Price Range:</dt>
								<dd><?php echo $result['basic']['avgPrice']; ?></dd>
								<dt>Cuisine:</dt>
								<?php print_array($result['cuisines'], '<dd>', '</dd>'); ?>
								<dt>Vibes:</dt>
								<?php print_array($result['vibes'], '<dd>', '</dd>'); ?>
							</dl>
						</div>
					</div>
				</div>
				
				<div id="reviews">
					<div id="header" class="row-fluid">
							<h2>Reviews:</h2>
							<p>Average Rating: <?php echo round($result['basic']['avgRating']); ?></p>	
					</div>
					<div id="reviews" class="row-fluid">
						<?php print_reviews($result['reviews']); ?>
					</div>
				</div>

			</div>
		</div>
	</body>

	<?php
		function get_rest_name()
		{
			$query = 'SELECT R.name
								FROM Restaurants R
								WHERE R.rid = '.$_GET['id'];
			$conn = connect();
			$stmt = oci_parse($conn, $query);
			oci_execute($stmt, OCI_DEFAULT);
			oci_close($conn);
			$row = oci_fetch_row($stmt);
			return $row[0];
		}

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
			echo '<table class="table">';
			echo '<thead>
							<tr>
								<th style="width: 20%"></th>
								<th style="width: 80%"></th>
							</tr>
						</thead><tbody>';

			foreach($reviews as $rev)
			{
				echo '<tr>';
				echo '<td id="info">
								Rating: '.$rev['rating'].'<br>
								Date: '.date('m/d/Y',strtotime($rev['date'])).'</td>';
				echo '<td id ="review-text">'.$rev['text'].'</td>';
				echo '</tr>';
			}

			echo '</tbody></table>';
		}

	?>
</html>
