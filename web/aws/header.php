<?php
 ob_start("ob_gzhandler");
 error_reporting(0);
 require_once("../root/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="index,follow" name="robots" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=0.6667, user-scalable=no" />
	<meta content="cloudwick,datalake" name="keywords" />
	<meta content="cloudwick datalake decription" name="description" />

	<title>Cloudwick technologies - Datalake</title>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Candal">
	<link rel="stylesheet" href="../resources/css/bootstrap.min.css">
	<link rel="stylesheet" href="../resources/css/customCSS.css">
	<script type="text/javascript" src="../resources/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="../resources/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../resources/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../resources/js/jquery.bootgrid.min.js"></script>
	<script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>
	<!--<script type="text/javascript" src="../resources/js/MobileRequireFieldValidator_v3.js"></script>-->
</head>
<body>
	<div class="container spike">
     <div class="row">
	 <div class="col-md-12 header">
	    <div class="pull-left header">
            <!--<a href="../home/"><img src="../resources/images/cloudwick_logo.png" alt="" class="img img-responsive img-rounded headerLogo"></a>-->
            <h3 class="headerLogo">Cloudwick - Datalake</h3>
        </div>
		<div class="clearfix"></div>
		<div class="headerButtons col-md-12">
            <?php
              getMenubar();
            ?>
		</div><br>
	 </div> <!-- End Header -->
     </div>
        <div class="clearfix"></div><br>
        <!-- start of body-->
     <div class="row contentBody">

		