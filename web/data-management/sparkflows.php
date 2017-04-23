<?php
include_once "../root/header.php";
include_once "../root/defaults.php";
checkSession();

print '
    <script type="text/javascript">
     $(function(){
        $.ajax({
          type: "POST",
          url: "http://'._IP.':8282/login",
          data: { username: "admin", password: "admin" }
        });
     });
    </script>
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <object data="http://'._IP.':8282" width="100%" height="2000em">
            <param name="bgcolor" value="#ffffff" />
            <embed src="http://'._IP.':8282" width="100%" height="2000em"> </embed>
            Error: Embedded data could not be displayed.
        </object>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';
include_once "../root/footer.php";
?>