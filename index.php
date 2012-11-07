<?php
require 'search.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>JobLube</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/joblube.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
	  
	  		<center>
				<h1>Welcome to JobLube!</h1><br>
				<div id="search">
					<input type="text" id="description" class="input-large" placeholder="Description / Keywords" name="description" value="<?php if(isset($_GET['description'])) { echo $_GET['description']; }?>">
					<input type="text" id="location" class="input-large" placeholder="Zip Code" name="location" value="<?php if(isset($_GET['location'])) { echo $_GET['location']; }?>">
					<button type="submit" class="btn" id='search-button'>Search</button>
				</div>
			</center>
			
			<center><div id="loader" style="display:none;margin-top:175px;background: url(img/loading.gif) no-repeat center center; width: 175px;height: 175px;"></div></center>
			
			<div id="sort">
				Sort by:
				<a id='date'>date</a>
				<a id='relevance'>relevance</a>
			</div>	
			
			<div id="filter">
				Filter by company:
				<input type = "text" id = "companyName" name="companyName" placeholder="Company">
				<button type = "submit" class="btn" id='filter-button'>Filter</button>
			</div>
			<div id="results"></div>
			
		</div>
	
		
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
		
		<script>
			function getResults() {
 				var sort = ''; 	
			    if (arguments.length == 1) {
			        sort = arguments[0];
			    }

				if( $("#description").val() == '' ) {
					// We can do client side checking here and throw errors
					// Be sure to check on server side too.
					return
				}

				$("#sort").hide();
				$("#filter").hide();
				$("#loader").show();
				$('#results').html(""); 	
				var description = $("input#description").val();
				var location = $("input#location").val();
				var theCompany = $("input#companyName").val();
				var dataString = 'description='+ description + '&location=' + location + '&sort-by=' + sort + '&filter-by-company=' + theCompany;
				
				$.ajax({  
					type: "GET",  
				  	url: "search.php",  
				  	data: dataString,  
				  	success: function(data) {  
					  	$("#loader").hide();
					  	$("#filter").show();
					  	$("#sort").show();
						$('#results').html(data); 
				  	}  
				});
			}
			
			$("#filter").hide();
			$("#search-button").click(function(){	
				getResults()
			});


			$("#date, #relevance").click(function(){
				getResults($(this).html())
			});
			
			$("#filter-button").click(function(){
				getResults()
			});
			
			$("#search input[type=text]").keyup(function(event){
			    if(event.keyCode == 13){
			    	$("#search-button").click()
			    }
			});

		</script>
	</body>
</html>