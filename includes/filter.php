<div id="filter" class="container-fluid">
	<div class="row-fluid">
		<form method="GET" action="search.php" class="">
			<ul id="filters" class="nav nav-list">
				<li class="nav-header"><h5>Search Filters</h5></li>
				<li class="divider"></li>
				<?php 
					$data = array('1' => '>= 1',
												'2' => '>= 2',
												'3' => '>= 3',
												'4' => '>= 4',
												'5' => '5');

					generate_cb_db('Neighborhoods', 'name', 'name');
					generate_cb_db('Cuisines', 'cid', 'name');
					generate_cb_db('Vibes', 'vid', 'name');
					generate_radio('Ratings', $data);
				?>			
				</ul>
			<button class="btn btn-inverse" name="filterSubmit" value="submit" type="submit">Search</button>			
		</form>
	</div>
</div>

<?php

	function generate_cb_db($table, $value, $text)
	{
		$conn = connect();
		$query = 'SELECT DISTINCT '.$value.', '.$text.' FROM '.$table;
		$stmt = oci_parse($conn, $query);
		oci_execute($stmt, OCI_DEFAULT);
		oci_close($conn);

		echo '<li name="list-container" class="">';
		echo '<div class="nav-header" onclick="toggle(id'.$table.');">'
					.$table.'</div>';
		echo '<div id="id'.$table.'" style="display:none">';

		while ($res = oci_fetch_row($stmt))
		{
			echo '<label class="checkbox">
							<input type="checkbox" name="'.$table.'[]" value="'.trim($res[0]).'">'
							.$res[1].'</label>';
		}

		echo '</div></li><br>';
	}

	function generate_radio($name, $data)
	{
		echo '<li name="list-container" class="nav">';
		echo '<div class="nav-header" onclick="toggle(id'.$name.');">'
					.$name.'</div>';

		echo '<div id="id'.$name.'" style="display:none">';

		foreach ($data as $value => $text)
		{
			echo '<label class="radio">
							<input type="radio" name="'.$name.'" value="'.$value.'">'
							.$text.'</label>';
		}
		echo '</div></li>';
	}
?>

