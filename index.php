<?php
require_once 'trending.php';
require_once 'database/db_util.php';
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
				<div id="trending">
					Trending Keywords: 
					<?php
						$TRENDING = get_top_search_terms(); 
						for ($i = 0; $i < count($TRENDING); $i++) {
						    echo trim($TRENDING[$i]);
                                                    if($i < count($TRENDING) - 1) {
                                                        echo ",";
                                                    }
                                                    echo " ";
						}
					?>
				</div>
			</center>
			
			<center><div id="loader" style="display:none;margin-top:175px;background: url(img/loading.gif) no-repeat center center; width: 175px;height: 175px;"></div></center>
			<div id="sort">
				Sort by:
				<a id='date'>date</a>
				<a id='relevance'>relevance</a>
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
				$("#loader").show();
				$('#results').html(""); 	
				var description = $("input#description").val();
				var location = $("input#location").val();
				var dataString = 'description='+ description + '&location=' + location + '&sort-by=' + sort;  
				
				$.ajax({  
					type: "GET",  
				  	url: "search.php",  
				  	data: dataString,  
				  	success: function(data) {  
					  	$("#loader").hide();
					  	$("#sort").show();
						$('#results').html(data); 
				  	}  
				});
			}

			$("#search-button").click(function(){	
				getResults()
			});


			$("#date, #relevance").click(function(){
				getResults($(this).html())
			});

			$("#search input[type=text]").keyup(function(event){
			    if(event.keyCode == 13){
			    	$("#search-button").click()
			    }
			});

		</script>
	</body>
</html>