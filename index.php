<html>
	<head>
		<title>DB Project</title>
	</head>

	<body>
		<?php 
			include 'header.php';

			if (isset($_GET['filterSubmit']))
			{
				include 'search.php';
			}

			else
			{
				echo '<h1>Welcome to Food Finder!!!</h1>';
			}

		?>
	<body>
</html>
