<?php
include_once "../root/header.php";
include_once "../root/defaults.php";
checkSession();

print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <!--<iframe src="http://'._IP.':8080" frameborder="0" width="100%" height="100%"></iframe>-->
        <object data="http://'._IP.':8080" width="100%" style="min-height:400rem;">
            <embed src="http://'._IP.':8080" width="100%" style="min-height:400rem;" id="zp"> </embed>
            Error: Embedded data could not be displayed.
        </object>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';
include_once "../root/footer.php";
?>