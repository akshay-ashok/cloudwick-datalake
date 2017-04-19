<?php
include_once "../root/header.php";
include_once "../root/defaults.php";
checkSession();

    $cloudtrail_cont = "app/kibana#/dashboard/datalake-cloud-trail-logs-dashboard?_g=(refreshInterval%3A(display%3A'5%20seconds'%2Cpause%3A!f%2Csection%3A1%2Cvalue%3A5000)%2Ctime%3A(from%3Anow-24h%2Cmode%3Aquick%2Cto%3Anow))";

print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <object data="'._KIBANA_URL.''.$cloudtrail_cont.'" width="100%" height="2000rem">
            <embed src="'._KIBANA_URL.''.$cloudtrail_cont.'" width="100%" height="2000rem"> </embed>
            Error: Embedded data requires HTML5 support. Data could not be displayed.
        </object>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';
include_once "../root/footer.php";
?>