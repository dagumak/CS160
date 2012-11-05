<?php
include 'search.php';
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
				<a href='#'>date</a>
				<a href='#'>relevance</a>
			</div>	
			<div id="results"></div>
			
			
		</div>
	
	
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
		
		<script>
			var x = 0
			$("#search input[type=text]").keyup(function(event){
			    if(event.keyCode == 13){
			    	$("#search-button").click()
			    }
			});

			$("#search-button").click(function() 
			{	
				$("#sort").hide();	

				if( $("#description").val() == '' ) {
					// We can do client side checking here, be sure to check on server side too.
					return
				}

				$("#loader").show();
				$('#results').html(""); 	
				var description = $("input#description").val();
				var location = $("input#location").val();
				var dataString = 'description='+ description + '&location=' + location;  
				
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
			});
		</script>
	</body>
</html>