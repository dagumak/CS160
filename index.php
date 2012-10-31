<!DOCTYPE html>
<html>
	<head>
		<title>JobLube</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
	  
	  		<center>
				<h1>Welcome to JobLube!</h1><br>
				
				<div>
					<input type="text" id="description" class="input-large" placeholder="Description / Keywords" name="description" value="<?php if(isset($_GET['description'])) { echo $_GET['description']; }?>">
					<input type="text" id="location" class="input-large" placeholder="Zip Code" name="location" value="<?php if(isset($_GET['location'])) { echo $_GET['location']; }?>">
					<button type="submit" class="btn">Search</button>
				</div>
			</center>
			
			<center><div id="loader" style="display:none;margin-top:175px;background: url(img/loading.gif) no-repeat center center; width: 175px;height: 175px;"></div></center>
			<div id="results"></div>
			
			<?php require_once('./monster.php'); ?>
		</div>
	
	
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
		
		<script>
			
			$(".btn").click(function() 
			{		
				$("#loader").show();
				$('#results').html(""); 	
				var description = $("input#description").val();
				var location = $("input#location").val();
				var dataString = 'description='+ description + '&location=' + location;  
				
				$.ajax({  
					type: "GET",  
				  	url: "monster.php",  
				  	data: dataString,  
				  	success: function(data) {  
					  	$("#loader").hide();
						$('#results').html(data); 
						
				  	}  
				}); 
			});
		</script>
	</body>
</html>