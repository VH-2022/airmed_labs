<?php
$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) == 'cgi')
    header("Status: 404 Not Found");
else
    header("HTTP/1.1 404 Not Found"); ?>
<html>
<head>
<title>404 Page not found</title>

	 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<style>
	.img_full {width: 100%; float: left;}
	.full_div {width: 100%; float: left;}
</style>
</head> 
<body class="">
<div class="container" style="margin-top: 10%;">
    <div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<div class="full_div">
				<img class="img_full" src="<?php echo base_url();?>upload/404_error.png"/>
				<a href="<?php echo base_url(); ?>"> Back to site </a>
			</div>
		</div>
    </div>
</div>
</body>
</html>