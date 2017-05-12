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
                    <h3><span class="label label-warning">EC2 Checks</span></h3>
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
                    <h3><span class="label label-danger">Other Checks</span></h3>
                    <div class="checkDiv portal-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-default">Elastic Search Checks</span></h3>
                    <div class="checkDiv elasticsearch-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-success">Cloudtrail Checks</span></h3>
                    <div class="checkDiv cloudtrail-checks"></div>
                </div>
            </div>
            <div class="grid-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="crosscheckPanel">
                    <h3><span class="label label-primary">Datapipeline Checks</span></h3>
                    <div class="checkDiv datapipeline-checks"></div>
                </div>
            </div>
        </div>
        <script src="../resources/js/masonry.pkgd.min.js"></script>
        <script type="text/javascript">

            var messages = [
                ["s3","s3-bucket"],
                ["s3","s3-bucket-lifecycle-policy"],
                ["s3","s3-bucket-encryption"],
                ["rds","rds-instance"],
                ["redshift","redshift-cluster"],
                ["redshift","redshift-role"],
                ["elasticsearch","elasticsearch-domain"],
                ["lambda","lambda-catalog-function"],
                ["lambda","lambda-catalog-trigger"],
                ["lambda","lambda-catalog-sample"],
                ["kinesis","kinesis-firehose"],
                ["kinesis","kinesis-firehose-role"],
                ["cloudtrail","cloudtrail"],
                ["datapipeline","datapipeline-taskrunner"],
                ["ec2","ec2-instance"],
                ["ec2","ec2-instance-webserver-role"],
                ["portal","mysql-databases"],
                ["portal","portal-zeppelin-setup"],
                ["portal","portal-kibana-visualizations"],
                ["portal","portal-kibana-dashboards"]
            ];

            var op = $(".messages");
            var i=0;
            var timer = null;
            var url = "../home/initial-setup.php";
            var totalChecksCount = messages.length;
            var progressFill = (100 / totalChecksCount);

            function updateMasonry(){
                $('.grid').masonry({
                    columnWidth: '.grid-item',
                    itemSelector: '.grid-item'
                });

                if(i>= totalChecksCount){
                    $.ajax({
                        url: url,
                        data: { action: "cleanup"}
                    }).done(function(msg) {
                        setTimeout(function () {
                            window.location.replace("../home/?welcome");
                        }, 5000);
                    });
                }
            }

            function updateBar(){
                i++;
                var fillPercent = Math.floor(progressFill * i);
                $('.progress-bar').css('width', fillPercent+'%').attr('aria-valuenow', fillPercent);
                $('.progress-bar > span').html(fillPercent+"% Complete");
                updateMasonry();
            }


            $(function(){
                // s3 checks & lambda checks
                // lambda dependency on s3 --susheel 04/22/2017
                $.ajax({
                    url: url,
                    data: { action: "s3-bucket"}
                }).done(function(msg) {
                    $(".s3-checks").append(msg);
                    updateBar();
                    $.ajax({
                        url: url,
                        data: { action: "s3-bucket-lifecycle-policy"}
                    }).done(function(msg) {
                        $(".s3-checks").append(msg);
                        updateBar();
                        $.ajax({
                            url: url,
                            data: { action: "s3-bucket-encryption"}
                        }).done(function(msg) {
                            $(".s3-checks").append(msg);
                            updateBar();
                            $.ajax({
                                url: url,
                                data: { action: "lambda-catalog-function"}
                            }).done(function(msg) {
                                $(".lambda-checks").append(msg);
                                updateBar();
                                $.ajax({
                                    url: url,
                                    data: { action: "lambda-catalog-trigger"}
                                }).done(function(msg) {
                                    $(".lambda-checks").append(msg);
                                    updateBar();
                                    $.ajax({
                                        url: url,
                                        data: { action: "lambda-catalog-sample"}
                                    }).done(function(msg) {
                                        $(".lambda-checks").append(msg);
                                        updateBar();
                                    });
                                });
                            });
                        });
                    });
                });

                // elasticsearch checks
                $.ajax({
                    url: url,
                    data: { action: "elasticsearch-domain"}
                }).done(function(msg) {
                    $(".elasticsearch-checks").append(msg);
                    updateBar();
                });

                // kinesis checks
                $.ajax({
                    url: url,
                    data: { action: "kinesis-firehose"}
                }).done(function(msg) {
                    $(".kinesis-checks").append(msg);
                    updateBar();
                    $.ajax({
                        url: url,
                        data: { action: "kinesis-firehose-role"}
                    }).done(function(msg) {
                        $(".kinesis-checks").append(msg);
                        updateBar();
                    });
                });

                // ec2 checks
                $.ajax({
                    url: url,
                    data: { action: "ec2-instance"}
                }).done(function(msg) {
                    $(".ec2-checks").append(msg);
                    updateBar();
                    $.ajax({
                        url: url,
                        data: { action: "ec2-instance-webserver-role"}
                    }).done(function(msg) {
                        $(".ec2-checks").append(msg);
                        updateBar();
                    });
                });

                // cloudtrail checks
                $.ajax({
                    url: url,
                    data: { action: "cloudtrail"}
                }).done(function(msg) {
                    $(".cloudtrail-checks").append(msg);
                    updateBar();
                });

                // datapipeline taskrunner checks
                $.ajax({
                    url: url,
                    data: { action: "datapipeline-taskrunner"}
                }).done(function(msg) {
                    $(".datapipeline-checks").append(msg);
                    updateBar();
                });

                // rds checks
                $.ajax({
                    url: url,
                    data: { action: "rds-instance"}
                }).done(function(msg) {
                    $(".rds-checks").append(msg);
                    updateBar();
                });

                // redshift checks
                $.ajax({
                    url: url,
                    data: { action: "redshift-cluster"}
                }).done(function(msg) {
                    $(".redshift-checks").append(msg);
                    updateBar();
                    $.ajax({
                        url: url,
                        data: { action: "redshift-role"}
                    }).done(function(msg) {
                        $(".redshift-checks").append(msg);
                        updateBar();
                    });
                });

                // web ui portal checks
                $.ajax({
                    url: url,
                    data: { action: "mysql-databases"}
                }).done(function(msg) {
                    $(".portal-checks").append(msg);
                    updateBar();
                    $.ajax({
                        url: url,
                        data: { action: "portal-zeppelin-setup"}
                    }).done(function(msg) {
                        $(".portal-checks").append(msg);
                        updateBar();
                        $.ajax({
                            url: url,
                            data: { action: "portal-kibana-visualizations"}
                        }).done(function(msg) {
                            $(".portal-checks").append(msg);
                            updateBar();
                            $.ajax({
                                url: url,
                                data: { action: "portal-kibana-dashboards"}
                            }).done(function(msg) {
                                $(".portal-checks").append(msg);
                                updateBar();
                            });
                        });
                    });
                });
            });
        </script>
    </div>
    <div class="col-lg-1 col-md-1"></div>
<?php
include_once "../root/footer.php";
?>