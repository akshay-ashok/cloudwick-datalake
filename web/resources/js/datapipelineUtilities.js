$(function(){
    tablename = "";
    pipelineid = "";
    url = "../aws-resources/datapipeline.php";
    region = "us-west-2";
    output = $("#datapipelineresult");
    spinner = $("#datapipelinespinner");
    spinner.toggle();

    $('#runDatapipelineForm').submit(function( event ) {
        event.preventDefault();
        var timer = null;
        var interval = 15000;
        tablename = $("#tabletocopy").val();
        $("#datapipelineInit").hide();
        spinner.toggle();

        $.ajax({
            url: url,
            data: { action: "getRegion"}
        }).done(function(getRegion) {
            region = getRegion;
        });

        $.ajax({
            url: url,
            data: { action: "createPipeline"}
        }).done(function(pid) {
            if(pid.indexOf("text-danger")!==-1){
                output.append(pid);
            } else {
                pipelineid = pid;
                output.append("<p class='text-success'><i class='fa fa-check-square-o'></i> Datapipeline created, Pipeline Id : <b>"+pid+"</b></p>");
                $.ajax({
                    url: url,
                    data: { action: "createPipelineDef", pipelineid:pipelineid, tablename:tablename}
                }).done(function(createPipelineDef) {
                    output.append(createPipelineDef);
                    $.ajax({
                        url: url,
                        data: { action: "putPipelineDef", pipelineid:pipelineid}
                    }).done(function(putPipelineDef) {
                        output.append(putPipelineDef);
                        $.ajax({
                            url: url,
                            data: { action: "activatePipeline", pipelineid:pipelineid}
                        }).done(function(activatePipeline) {
                            output.append(activatePipeline);
                            $.ajax({
                                url: url,
                                data: { action: "TaskrunnerHeartbeat", pipelineid:pipelineid}
                            }).done(function(TaskrunnerHeartbeat) {
                                output.append(TaskrunnerHeartbeat);
                                $.ajax({
                                    url: url,
                                    data: { action: "pollForTask", pipelineid:pipelineid}
                                }).done(function(pollForTask) {
                                    output.append(pollForTask);
                                    output.append("<div id='datapipelinelivestatus'>Datapipeline Status : " +
                                        "<a target='_blank' class='text-warning' " +
                                        "href='https://console.aws.amazon.com/datapipeline/home?region="+region+"#ExecutionDetailsPlace:pipelineId="+pipelineid+"&show=latest'>" +
                                        "<i class='fa fa-circle fa-blink'></i> Running <i class='fa fa-external-link-square'></i></a>" +
                                        "</div>");
                                    $("#datapipelinespinner > i.fa").removeClass("fa-4x").addClass("fa-1x");
                                    $.ajax({
                                        url: url,
                                        data: { action: "pipelineStatus", pipelineid:pipelineid}
                                    }).done(function(pipelineStatus) {
                                        $("#datapipelinestatus").append(pipelineStatus);
                                        spinner.toggle();
                                        startStatusCheck();
                                    });
                                });
                            });
                        });
                    });
                });
            }
        }).fail(function(xhr) {
                console.log('error', xhr);
         });

        function stopStatusCheck() {
            clearTimeout(timer);
        }

        function startStatusCheck() {
            timer = setTimeout(checkDPStatus, interval);
        }

        function checkDPStatus(){
            if (timer == null) return;
            spinner.toggle();
            $.ajax({
                url: url,
                data: { action: "pipelineStatus", pipelineid:pipelineid}
            }).done(function(pipelineStatus) {
                spinner.toggle();
                pipelineStatus = pipelineStatus.replace(new RegExp('FINISHED', 'g'),"<em class='text-success'>FINISHED</em>");
                pipelineStatus = pipelineStatus.replace(new RegExp('WAITING_ON_DEPENDENCIES','g'),"<em class='text-warning'>WAITING_ON_DEPENDENCIES</em>");
                pipelineStatus = pipelineStatus.replace(new RegExp('WAITING_FOR_RUNNER','g'),"<em class='text-warning'>WAITING_FOR_RUNNER</em>");
                pipelineStatus = pipelineStatus.replace(new RegExp('RUNNING','g'),"<em class='text-info'>RUNNING</em>");
                $("#datapipelinestatus").html(pipelineStatus);
                if(pipelineStatus.indexOf("WAIT")!==-1 || pipelineStatus.indexOf("PENDING")!==-1 || pipelineStatus.indexOf("RUNNING")!==-1){
                    startStatusCheck();
                } else {
                    stopStatusCheck();
                    $("#datapipelinespinner").hide();
                    $("#datapipelinelivestatus").hide();
                    $("#datapipelinestatus").html("<br><div class='alert alert-success'>Datapipeline Status : <i class='fa fa-circle fa-blink'></i> FINISHED<br>" +
                        "<a class='btn btn-warning btn-sm' href='../aws-resources/redshift.php?explore=table&table="+tablename+"'>click here</a> " +
                        "to see '"+tablename+"' table in redshift</div>");
                }
            });
        }
    });
});