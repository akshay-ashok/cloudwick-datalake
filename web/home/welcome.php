<?php
    include_once "../root/header.php";
?>
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <p class="text-primary">Setting up your data lake, Please wait...</p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0">
                <span>0% Complete</span>
            </div>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="messages text-success"></div>
        <div class="row grid">
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-warning">S3 Checks</span></span></h3>
                    <div class="checkDiv s3-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-success">RDS Checks</span></h3>
                    <div class="checkDiv rds-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-danger">Redshift Checks</span></h3>
                    <div class="checkDiv redshift-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-primary">Lambda Checks</span></h3>
                    <div class="checkDiv lambda-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-success">EC2 Checks</span></h3>
                    <div class="checkDiv ec2-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-default">Kinesis Checks</span></h3>
                    <div class="checkDiv kinesis-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-info">Elastic Search Checks</span></h3>
                    <div class="checkDiv es-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-danger">Cloudtrail Checks</span></h3>
                    <div class="checkDiv cloudtrail-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-warning">Other Checks</span></h3>
                    <div class="checkDiv other-checks"></div>
                </div>
            </div>
        </div>
        <script src="../resources/js/masonry.pkgd.js"></script>
        <script type="text/javascript">

            var messages = [
                ["Created S3 bucket","s3","bucket",true],
                ["Created S3 bucket policy","s3","buckey-policy",true],
                ["Created lambda execution role","s3","lambda-execution-role",true],
                ["Elastic search Domain","es","elastic-search",true],
                ["Created RDS instance","rds","rds-instance",true],
                ["Created Redshift Security group","redshift","redshift-sg",true],
                ["Created Redshift Cluster","redshift","redshift-cluster",true],
                ["Created ec2 IAM Role","ec2","ec2-iam-role",true],
                ["Created ec2 Instance","ec2","ec2-instance",true],
                ["Created Datapipelines","datapipeline","dp-exist",true],
                ["Final check Redshift database updates","redshift","redshift-db",true],
                ["Created Mysql Databases for user authentication","other","mysql-validate",true],
                ["Created Cloudformation wait condition","other","cf-wait",true],
                ["Setup management portal","other","portal",true],
                ["Setup Zeppelin portal","other","zeppelin",true],
                ["Created Kibana visualizations","es","kibana",true],
                ["Created Kibana dashboards","es","d3-dashboards",true],
                ["Created Stream","kinesis","stream",true],
                ["Created Catalog lambda Function IAM Role","lambda","lambda-iam-role",true],
                ["Created Catalog lambda Function","lambda","lambda-create",true],
                ["Updated Catalog lambda Function code","lambda","lambda-code",true],
                ["Created Catalog lambda Function trigger to S3","lambda","lambda-trigger",true],
                ["Created Cloudtrail","cloudtrail","cloudtrail",true]
            ];

            function shuffle(array) {
                var len = array.length, swap, i;
                while (len) {
                    i = Math.floor(Math.random() * len--);
                    swap = array[len];
                    array[len] = array[i];
                    array[i] = swap;
                }
                return array;
            }

            messages = shuffle(messages);

            var op = $(".messages");
            var i=0;
            var timer = null;
            var interval = 1000;
            var totalChecksCount = messages.length;
            var progressFill = (100 / totalChecksCount);

            function updateMasonry(){
                $('.grid').masonry({
                    columnWidth: '.grid-item',
                    itemSelector: '.grid-item'
                });
            }

            function updateBar(){
                var fillPercent = Math.floor(progressFill * i);
                $('.progress-bar').css('width', fillPercent+'%').attr('aria-valuenow', fillPercent);
                $('.progress-bar > span').html(fillPercent+"% Complete");
                updateMasonry();
            }

            function start() {
                timer = setTimeout(writeToConsole, interval);
            }

            function stop() {
                clearTimeout(timer);
                op.hide();
                $.ajax({
                    url: "../home/cleanup.php",
                    success: function(){
                        updateBar();
                    }
                });
                setTimeout(redirect, interval*8);
            }

            function writeToConsole(){
                if (timer == null) return;
                op.html( "<p><span class=\"glyphicon glyphicon-cog\"></span> "+messages[i][0]+"</p>" );
                if(messages[i][3] == true) {
                    $("." + messages[i][1] + "-checks").append("<p class=\"text-success\"><span class=\"glyphicon glyphicon-ok-circle\"></span> " + messages[i][0] + "</p>");
                } else {
                    $("." + messages[i][1] + "-checks").append("<p class=\"text-danger\"><span class=\"glyphicon glyphicon-remove-circle\"></span> " + messages[i][0] + "</p>");
                }
                i++;
                updateBar();
                if(i < totalChecksCount){
                    start();
                } else {
                    stop();
                }
            }

            setTimeout(function(){
                $.ajax({
                    url: "../s3/s3Catalog.php",
                    type: "POST",
                    data: {bucketname: "<?php print _BUCKET; ?>"},
                    success: function(){
                        updateBar();
                    }
                });
            }, interval);

            function redirect(){
                window.location.replace("../home/?relogin");
            }

            start();
        </script>
    </div>
    <div class="col-lg-1 col-md-1"></div>
<?php
include_once "../root/footer.php";
?>