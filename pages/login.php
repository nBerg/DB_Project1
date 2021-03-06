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

		<title>Food Finder</title>
	</head>

	<body>
		<?php include '../includes/header.php'; 
					include '../includes/functions.php';	?>

		<div class="container-fluid">
			<div class="row-fluid">
				<div id="side-nav" class="span2">
					<div class="well sidebar-nav">
						<?php include '../includes/filter.php'; ?>
					</div>
				</div>

			<div id="body" class="span10">
				<div class="container-fluid">
					<div class="header">
						<h1>Welcome to Food Finder!</h1>
					</div>

					<form id='login' method='post' accept-charset='UTF-8'>
						<fieldset>
							<legend>Login</legend>
							<input type='hidden' name='submitted' id='submitted' value='1'/>
 
							<label for='username' >Username*:</label>
							<input type='text' name='username' id='username'  maxlength="50" />
 
							<label for='password' >Password*:</label>
							<input type='password' name='password' id='password' maxlength="50" />
 							<div>
								<button type='submit' name='Submit' value='Submit' class="btn btn-inverse">Sign in</button>
							</div>
 
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</body>

<?php
	$username = isset($_POST['username']) == true ? $_POST['username'] : '';
	$password= isset($_POST['password']) == true ? $_POST['password'] : '';

	if(empty($username) or empty($password)){
		print "* = Items required!\n";
		print "<br><a href=' /~nb2555/DB_Project1/registration.php'>Register Today!</a></br>";
	}else{
		$db = "w4111b.cs.columbia.edu:1521/adb";
		$conn = oci_connect("nb2555", "nbjc", $db);    
		$query = "SELECT * from USERS WHERE usrid='".$username."' and password='".$password."'";
		$result = oci_parse($conn, $query);
		oci_execute($result);
		$tmpcount = oci_fetch($result);
		if ($tmpcount==1) {
			print "Login Success";
			//header("Location: /~nb2555/DB_Project1/favorites.php?var=$username");
			//header("Location: /~nb2555/DB_Project1/index.php?var=$username");
			//print "<a href='favorites.php?var=$username'>Favorites</a>";
		}else{
			print "Login Failed\n";
			print "<br><a href=' /~nb2555/DB_Project1/registration.php'>Not registered?</a></br>";
		}
	}
?>

</html>
