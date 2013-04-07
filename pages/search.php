<!DOCTYPE html>
<html>
	<head>	
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css" type="text/css" />
		<link rel="stylesheet" href="../css/bootstrap/css/bootstrap-responsive.css" type="text/css">

		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
			.sidebar-nav {
				padding: 9px 0;
			}

			@media (max-width: 980px) {
				.navbar-text.pull-right {
					float: none;
					padding-left: 5px;
					padding-right: 5px;
				}
			}
		</style>

		<script src="../js/functions.js" type="text/javascript"></script>
	
		<title>List</title>
	</head>

	</body>
		<?php include '../includes/header.php'; 
					include '../includes/functions.php'; ?>
		
		<div class="container-fluid">
			<div class="row-fluid">
				<div id="side-nav" class="span2">
					<div class="well sidebar-nav">
						<?php include '../includes/filter.php'; ?>
					</div>
				</div>

				<div id="body" class="span10">
					<div class="container-fluid">
						<div class="page-header">
							<h1>Search Results:</h1>
						</div>
						<div id="search-results">
							<?php
								$result = submit_search_query($_GET);
								build_table($result);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	

	<?php
		function submit_search_query($data) 
		{
			$neighborhoods = 'N.name LIKE \'%\'';
			if (isset($_GET['Neighborhoods']))
			{
				$neighborhoods = 'N.name LIKE \''.$data['Neighborhoods'][0].'%\'';

				$max = count($data['Neighborhoods']);
				for($i = 1; $i < $max; $i++)
				{
					$N = $data['Neighborhoods'][$i];
					$neighborhoods = $neighborhoods.' OR N.name LIKE \''.$N.'%\'';
				}
			}

			$cuisines = 'C.cid LIKE \'%\'';;
			if (isset($_GET['Cuisines']))
			{
				$cuisines = 'C.cid LIKE \''.$data['Cuisines'][0].'%\'';

				$max = count($data['Cuisines']);
				for($i = 1; $i < $max; $i++)
				{
					$C = $data['Cuisines'][$i];
					$cuisines = $cuisines.' OR C.cid LIKE \''.$C.'%\'';
				}
			}

			$vibes = 'V.vid LIKE \'%\'';
			if (isset($_GET['Vibes']))
			{
				$vibes = 'V.vid LIKE \''.$data['Vibes'][0].'%\'';

				$max = count($data['Vibes']);
				for($i = 1; $i < $max; $i++)
				{
					$V = $data['Vibes'][$i];
					$vibes = $vibes.' OR V.vid LIKE \''.$V.'%\'';
				}
			}

			$where = '('.$neighborhoods.') AND ('.$cuisines.') AND ('.$vibes.')';

			$having = '';
			if (isset($data['Ratings'])) {
				$having = 'HAVING ROUND(AVG(W.rating)) >= '.$data['Ratings'];
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
			echo '<table class="table table-striped table-bordered">';
			echo '<thead><tr><th>Restaurant</th>
											 <th>Address</th>
											 <th>Neighborhood</th>
											 <th>Phone Number</th>
											 <th>Price Range</th>
											 <th>Rating</th></tr></thead>';
			$list = array();
			for($i = 1; $i <= 6; $i++){
				array_push($list, $i);
			}
			$j = 0;
			while ($row = oci_fetch_row($data))
			{
				$cuisines = get_cuisine_info($row[0]);
				$cuisine_string = arr_to_string($cuisines);
				$price = format_price_range($row[5]);
				$rating = format_rating($row[6]);

				echo '<tr><td>
							<a href="detail.php?id='.$row[0].'">'.$row[1].'</a>
							<br>'.$cuisine_string.'
							</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].
							'</td><td>'.$price.'</td><td>'.$rating.'</td><td>
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

		function arr_to_string($arr)
		{
			$len = count($arr);
			$str = '';

			if ($len > 0)
				$str = $arr[0];

			for ($i = 1; $i < $len; $i++)
				$str = $str.', '.$arr[$i];

			return $str;
		}
	?>
</html>
