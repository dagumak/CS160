<?php

function log_search_terms($val) {
    $mysqli = get_job_lube_db_conn();
    $log_terms = $mysqli->query("INSERT INTO search_log (term)
                        VALUES ('$val')");
}

function get_search_terms() {
    $mysqli = get_job_lube_db_conn();
    $terms = $mysqli->query("SELECT distinct(term), 
        count(term) FROM `search_log` where 
        entry_time BETWEEN DATE_SUB(NOW(), INTERVAL 7 day)  
        AND NOW() 
        group by term 
        order by count(term) desc
        LIMIT 0,5");
    $results = array();
    while (($row = $terms->fetch_assoc()) != null) {
        $results[] = $row['term'];
    }
    return $results;
}

?>