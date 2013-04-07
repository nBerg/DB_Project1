<?php

	function connect()
	{
		ini_set('display_errors', 'On');
		$db = 'w4111b.cs.columbia.edu:1521/adb';
		return oci_connect('nb2555', 'nbjc', $db);
	}

	function get_rest_name($rid)
	{
		$query = 'SELECT R.name
							FROM Restaurants R
							WHERE R.rid = '.$rid;
		$conn = connect();
		$stmt = oci_parse($conn, $query);
		oci_execute($stmt, OCI_DEFAULT);
		oci_close($conn);
		$row = oci_fetch_row($stmt);
		return $row[0];
	}

	function get_info($rid)
	{
		$info['basic'] = get_rest_info($rid);
		$info['cuisines'] = get_cuisine_info($rid);
		$info['vibes'] = get_vibe_info($rid);
		$info['reviews'] = get_review_info($rid);

		return $info;
	}

	function get_rest_info($rid)
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
							WHERE R.rid = '.$rid.'
							GROUP BY R.rid, R.name, R.street, N.name, R.phone, 
											 R.avgPrice';

		$conn = connect();
		$stmt = oci_parse($conn, $query);
		oci_execute($stmt, OCI_DEFAULT);
		oci_close($conn);

		$row = oci_fetch_row($stmt);
		$data = array('name' => $row[0],
									'address' => $row[1],
									'neighborhood' => $row[2],
									'phone' => $row[3],
									'avgPrice' => $row[4],
									'avgRating' => $row[5]);

		return $data; 
	}

	function get_cuisine_info($rid)
	{
		$query = 'SELECT C.name
							FROM Restaurants R
										JOIN Serves S ON R.rid = S.rid
										JOIN Cuisines C ON C.cid = S.cid
							WHERE R.rid = '.$rid;

		$conn = connect();
		$stmt = oci_parse($conn, $query);
		oci_execute($stmt, OCI_DEFAULT);
		oci_close($conn);

		while ($row = oci_fetch_row($stmt))
		{
			$cuisines[] = $row[0]; 
		}

		return $cuisines;
	}

	function get_vibe_info($rid)
	{
		$query = 'SELECT V.name
							FROM Restaurants R
									JOIN restVibe RV ON R.rid = RV.rid
									JOIN Vibes V on RV.vid = V.vid
							WHERE R.rid = '.$rid;

		$conn = connect();
		$stmt = oci_parse($conn, $query);
		oci_execute($stmt, OCI_DEFAULT);
		oci_close($conn);

		while($row = oci_fetch_row($stmt))
		{
			$vibes[] = $row[0];
		}

		return $vibes;
	}

	function get_review_info($rid)
	{
		$query = 'SELECT W.revid, W.rating, W.datewritten, W.text
							FROM Restaurants R
									JOIN Reviews W on R.rid = W.rid
							WHERE R.rid = '.$rid;

		$conn = connect();
		$stmt = oci_parse($conn, $query);
		oci_execute($stmt, OCI_DEFAULT);
		oci_close($conn);

		while($row = oci_fetch_row($stmt))
		{
			$reviews[] = array('rating' => $row[1],
												'date' => $row[2],
												'text' => $row[3]);
		}

		return $reviews;
	}
?>
