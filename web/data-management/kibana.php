<?php
include_once "../root/header.php";
include_once "../root/defaults.php";
checkSession();

    $metadata_cont = "app/kibana#/dashboard/cloudwick-datalake-quickstart-metadata-dashboard?_g=(refreshInterval%3A(display%3A'5%20seconds'%2Cpause%3A!f%2Csection%3A1%2Cvalue%3A5000)%2Ctime%3A(from%3Anow-5y%2Cmode%3Aquick%2Cto%3Anow))";

print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <a href="#" class="btn btn-danger btn-sm customMessage pull-right" title="Kibana Index Help" 
            message="
            &lt;img src=\'../resources/images/kibana_index_help.png\' class=\'img img-responsive\'&gt;
            &lt;br&gt;
            1. Navigate to \'Management\' menu-item &lt;br&gt;
            2. Uncheck \'Index contains time-based events\' &lt;br&gt;
            3. Enter &lt;b&gt;*&lt;/b&gt; in pattern-box &lt;br&gt;
            4. Click \'Create\' button &lt;br&gt;
            5. Navigate to \'Discover\' menu-item &lt;br&gt;
            "><span class="glyphicon glyphicon-info-sign"></span> &nbsp;instructions</a><br>
        <!--<iframe src="http://'._IP.':8080" frameborder="0" width="100%" height="100%"></iframe>-->
        <object data="'._KIBANA_URL.$metadata_cont.'" width="100%" height="2000rem">
            <embed src="'._KIBANA_URL.$metadata_cont.'" width="100%" height="2000rem"> </embed>
            Error: Embedded data requires HTML5 support. Data could not be displayed.
        </object>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';
include_once "../root/footer.php";
?>