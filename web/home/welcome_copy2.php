<?php
if(isset($_GET)){
    if(isset($_GET["do"])) {
        if ($_GET["do"] == "datapipeline") {
            print '<p><span class="glyphicon glyphicon-ok"></span> Updated Datapipeline definitions</p>';
            sleep(3);
            return;
        } else if ($_GET["do"] == "lambda") {
            print '<p><span class="glyphicon glyphicon-ok"></span> Updated lambda code</p>';
            sleep(3);
            return;
        } else if ($_GET["do"] == "lambda2") {
            print '<p><span class="glyphicon glyphicon-ok"></span> Updated lambda triggers</p>';
            sleep(3);
            return;
        } else if ($_GET["do"] == "cleanup") {
            print '<p><span class="glyphicon glyphicon-ok"></span> Cleaning up</p>';
            sleep(3);
            return;
        } else if ($_GET["do"] == "finalcheck") {
            print '<p><span class="glyphicon glyphicon-ok"></span> Final checks</p>';
            sleep(3);
            chmod("/var/www/html/home/welcome.php",0777);
            rename ("./welcome.php", "./welcome.php");
            return;
        }
    }
}
include_once "../root/header.php";
?>
    <script type="text/javascript">
        $(".navbar").hide().html("");
    </script>
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <p class="text-primary">Setting up your datalake, Please wait...</p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
                <span>90% Complete</span>
            </div>
        </div>
        <div class="messages text-success"></div>
        <script type="text/javascript">
            var op = $(".messages");
            var i=0;
            var url = "../home/welcome.php?do=";

            function updateBar(){
                i++;
                $('.progress-bar').css('width', (90+(i*2))+'%').attr('aria-valuenow', (90+(i*2)));
                $('.progress-bar > span').html((90+(i*2))+"% Complete");
            }

            $.when(function(){
                $.get(url+"datapipeline",function(data){
                    op.append(data);
                });
            }).then(function(){
                $.get(url+"datapipeline",function(data){
                    op.append(data);
                    updateBar();
                });
            }).then(function(){
                $.get(url+"lambda",function(data){
                    op.append(data);
                    updateBar();
                });
            }).then(function(){
                $.get(url+"lambda2",function(data){
                    op.append(data);
                    updateBar();
                });
            }).then(function() {
                $.get(url+"cleanup",function(data){
                    op.append(data);
                    updateBar();
                });
            }).then(function(){
                $.get(url+"finalcheck",function(data){
                    op.append(data);
                    updateBar();
                });
            }).then(function(){
                setTimeout(redirect,10000);
            });

            function redirect(){
                window.location.replace("../home/?relogin");
            }
        </script>
    </div>
    <div class="col-lg-1 col-md-1"></div>
<?php
include_once "../root/footer.php";
?>