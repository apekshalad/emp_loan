<html>
<head>
	<title>9lessons tutorials</title>
	<style>
	.box
	{
	font-family:'Georgia', Times New Roman, Times;
	font-size:18px;
	padding:10px;
	
	}
	.box a
	{
	color:#000;
	
	}
	.box a:hover
	{
	color:#96BC43;
	
	}
	
	</style>
	<script src="jquery-1.7.1.min.js" type="text/javascript"></script>


	<script type="text/javascript">
$(function() 
{

$(document).ready(function()
	{
	
	$.ajax({ type: 'GET', url: 'json_data.php?class=index&method=allcategories',	contentType: "application/json",dataType: 'json',
	success: function(data)
	{	
		//$('#allcats').hide().html(data).show('slow');	
		alert('');
		$.each(data.posts, function(i,data){
					var div_data ="<div class='box'><a href='"+data.url+"'>"+data.title+"</a></div>"
						
					$(div_data).appendTo("#9lessonsLinks");
				});
	},
	});
		
		/*$.getJSON("json_data.php",function(data)
		{//alert('');
				$.each(data.posts, function(i,data){
					var div_data ="<div class='box'><a href='"+data.url+"'>"+data.title+"</a></div>"
						
					$(div_data).appendTo("#9lessonsLinks");
				});
			}
		);*/
		return false;
	});


});
</script>

	
	
	
</head>
<body>
		
	<div id="9lessonsLinks"></div>
	
	
</body>
</html>