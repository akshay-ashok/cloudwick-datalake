<?php
include_once "../root/header.php";
include_once "../root/defaults.php";
checkSession();

print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10-col-md-10-col-sm-12 col-xs-12">
        <div class="btn-group" role="group" aria-label="...">
          <a href="../visualize/zeppelin.php" class="btn btn-warning"><span class="glyphicon glyphicon-file"></span> Explore Data</a>
          <a href="../data-management/kibana.php" class="btn btn-default"><span class="glyphicon glyphicon-book"></span> Explore Catalogue</a>
          <a href="../visualize/cloudtrail.php" class="btn btn-success customMessage" message="Exploring API calls via Cloudtrail logs is underway" title="Explore API Calls" ><span class="glyphicon glyphicon-console"></span> Explore API calls</a>
        </div>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';
include_once "../root/footer.php";
?>