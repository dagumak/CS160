<?php

if ( isset($_GET['location']) && isset($_GET['description']) ) {
	
	$URL = "http://jobsearch.monster.com/search/". str_replace(' ', '-', $_GET['description']) ."_5?where=".$_GET['location'];

	include('simple_html_dom.php');
	$html = file_get_html( $URL );
	
	
	$JOBS = array();
	
	
	foreach($html->find('tr.odd') as $e) {
		
		
		$location = $e->find('div.jobLocationSingleLine');
		$location = $location[0]->plaintext;
	
		$description = $e->find('div.jobTitleContainer');
		$description = $description[0]->plaintext;
	
		$company = $e->find('div.companyContainer');
		$company = $company[0]->plaintext;
		
		$date = $e->find('div.fnt20');
		$date = $date[0]->plaintext;
		
		$JOBS[] = array('location' => $location, 'description' => $description, 'company' => $company, 'date' => $date, 'domain' => 'monster');
	}
	
	foreach($html->find('tr.even') as $e) {
	
	
		$location = $e->find('div.jobLocationSingleLine');
		$location = $location[0]->plaintext;
	
		$description = $e->find('div.jobTitleContainer');
		$description = $description[0]->plaintext;
	
		$company = $e->find('div.companyContainer');
		$company = $company[0]->plaintext;
	
		$date = $e->find('div.fnt20');
		$date = $date[0]->plaintext;
	
		$JOBS[] = array('location' => $location, 'description' => $description, 'company' => $company, 'date' => $date, 'domain' => 'monster');
	}
	
	//
	// Any sorting can be done here
	//
	
	//sort by date
	usort($JOBS, function($a, $b) {
			return $a['date'] - $b['date'];
	});
	
	
	echo '<table class="table table-striped">
			<thead>
			  <tr>
				<th>Description</th>
				<th>Location</th>
				<th>Company</th>
				<th>Date</th>
			  </tr>
			</thead>
			<tbody>';
	foreach( $JOBS as $job ) {
		
		echo "<tr>
				<td>".$job['description']."</td>
				<td>".$job['location']."</td>
				<td>".$job['company']."</td>
				<td>".$job['date']."</td>
		  	  </tr>";
	}
	
	echo '</tbody>
	  </table>
	</div>';

}