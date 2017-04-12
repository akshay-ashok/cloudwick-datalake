<?php
    include_once("../root/header.php");
    session_unset();
    session_destroy();
    sleep(1);
    //rename("../home/welcome_copy.php","../home/welcome.php");
    print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs 12 contentBody centered text-primary">
       <h1>
           <i class="fa fa-cog fa-spin fa-fw fa-3x text-primary"></i>
           <i class="fa fa-cog fa-spin fa-fw fa-5x text-primary"></i>
           <i class="fa fa-cog fa-spin fa-fw fa-3x text-primary"></i>
       </h1>
       Logging out, please wait...
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';
    print '<script>
        setTimeout(function(){location.href="../home/"} , 2000);
    </script>';
    include_once("../root/footer.php");
?>