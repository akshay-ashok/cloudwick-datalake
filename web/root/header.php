<?php
 ob_start("ob_gzhandler");
 //error_reporting(0);
 require_once("../root/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="index,follow" name="robots" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=0.6667, user-scalable=no" />
	<meta content="cloudwick,datalake,quickstart" name="keywords" />
    <meta content="cloudwick datalake quickstart" name="description" />

    <title>Cloudwick Technologies - Datalake Solution</title>
    <link rel="icon" type="image/ico" href="../resources/images/favicon.ico"/>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600" type="text/css" media="all">
	<link rel="stylesheet" href="../resources/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resources/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../resources/css/customCSS.css">
    <script type="text/javascript" src="../resources/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="../resources/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../resources/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../resources/js/jquery.bootgrid.min.js"></script>
    <script type="text/javascript" src="../resources/js/customScripts.js"></script>
</head>
<body>
	<div class="container-fluid spike">
     <div class="row" style="height: 100%;">
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header">
         <?php
            if (strpos($_SERVER['SCRIPT_FILENAME'], '/home/welcome') !== false) {
                // do not show menu bar
            } else {
                getMenubar();
            }
         ?>
     </div> <!-- End Header -->
     <div class="clearfix"></div>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pathclearer"></div>
		