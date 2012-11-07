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
?>