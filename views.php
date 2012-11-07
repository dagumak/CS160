<?php

function get_views($url) {
	$mysqli = get_job_lube_db_conn();
	$query = "SELECT views FROM `viewed_posts` where url='$url' limit 1";
	$result = $mysqli->query($query);
	
	if ($result && $result->num_rows > 0) {
		$count = $result->fetch_row()[0];
		$result->free();
	} else {
		$count = 0;
	}
	$mysqli->close();

	return $count;
}

?>