<?php
require_once 'database/db_util.php';

if (isset($_POST["url"])) {
	$url = $_POST["url"];
	$mysqli = get_job_lube_db_conn();
	$result = $mysqli->query("SELECT views FROM `viewed_posts` WHERE url='$url'");
	
	if ($result && $result->num_rows > 0) {
		$mysqli->query("UPDATE `viewed_posts` SET views=views+1 WHERE url='$url'");
	} else {
		$mysqli->query("INSERT INTO `viewed_posts` (url) VALUES ('$url')");
	}
	
	$mysqli->close();
}

function get_views($url) {
	$mysqli = get_job_lube_db_conn();
	$query = "SELECT views FROM `viewed_posts` where url='$url' limit 1";
	$result = $mysqli->query($query);
	
	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_row(); 
		$count = $row[0];
		$result->free();
	} else {
		$count = 0;
	}
	$mysqli->close();

	return $count;
}

?>