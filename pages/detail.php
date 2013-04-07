<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="styling/bootstrap/css/bootstrap.css" type="text.css" />
		<link rel="stylesheet" href="styling/bootstrap/css/bootstrap-responsive.css" type="text/css" />

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

		<script src="javascript.js" type="text/javascript"></script>

		<title>Detail Page</title>
	</head>

	<body>
		<?php 
			include 'header.php';
			include 'functions.php';
			$result = get_info($_GET['id']);
		?>
		
		<div class="container-fluid">
			<div class="row-fluid">
				<div id="side-nav" class="span2">
					<div class="well sidebar-nav">
						<?php include 'filter.php'; ?>
					</div>
				</div>

			<div id="body" class="span10">
				<div id="restInfo">
					<div class="page-header">
						<h1><?php echo $result['basic']['name']; ?></h1>
					</div>

					<div class="row-fluid">
						<div id="address" class="span2">
							<address style="padding: 1em">
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
				if (strlen($rev['text']) == 0)
					continue;

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
