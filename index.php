<?php
require_once 'trending.php';
require_once 'database/db_util.php';
?>
<!DOCTYPE html>

<html>
	<head>
		<title>JobLube | Helping you slip into a job easier!</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/joblube.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
	  
	  		<center>
				<img src="img/logo.png" id='logo'/>
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
			
			<center><div id="loader" style="display:none;margin-top:10px;background: url(img/loading.gif) no-repeat center center; width: 175px;height: 175px;"></div></center>
			<div id="refine">
				 <legend>Result Refinement</legend>
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
				</pre>
			</div>
			<div id="results"></div>
			
		</div>
	
		
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
		
		<script>
			// Twitter
            
            
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

				$("#refine").hide();
				$("#loader").show();
				$('#results').html(""); 	
				
				var description = encodeURIComponent($("input#description").val());
				//The above encoding is neccesary for handling specific input such as '#'
				//Which is reserved according to URI rules.
				
				var location = $("input#location").val();
				var theCompany = $("input#companyName").val();
				var dataString = '&description='+ description + '&location=' + location + '&sort-by=' + sort + '&filter-by-company=' + theCompany;
				
				$.ajax({  
					type: "GET",  
				  	url: "search.php",  
				  	data: dataString,  
				  	success: function(data) {  
					  	$("#loader").hide();
					  	$("#logo").css("margin-top", "10px");
					  	$("#refine").show();
						$('#results').html(data); 
						edit_links(); // attach custom behavior for job links
				  	}  
				});
			}

			/* Attach custom behavior for job links */
			function edit_links() {
				$(".post_link").click(function(e) {
					e.preventDefault();
					var url = $(this).attr("href")
					
					$.ajax({  
            		type: "POST",  
            	  	url: "views.php",  
            	  	data: { url: url },
            	  	success: increment_views($(this))
	            });

	            window.open(url);
				});
			}
			
			/* Increments views counter by one */	
			function increment_views(el) {
				el.parent().next().text(parseInt(el.parent().next().text()) + 1);
			}

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
