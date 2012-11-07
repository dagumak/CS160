<?php

/**
 * Returns a Mysqli connectiion to the job_lube database
 * @return \mysqli
 */
function get_job_lube_db_conn() {
    $credentials = parse_ini_file("db_credentials.ini", true);
    $host = $credentials["job_lube_credentials"]["host"];
    $username = $credentials["job_lube_credentials"]["username"];
    $password = $credentials["job_lube_credentials"]["password"];
    $db_name = $credentials["job_lube_credentials"]["db_name"];
    $mysqli = new mysqli($host, $username, $password, $db_name);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') '
                . $mysqli->connect_error);
    }
    return $mysqli;
}

?>
