<?php
    include "../root/header.php";
    $stream_dashboard = "app/kibana#/dashboard/cloudwick-datalake-quickstart-EDI-stream-dashboard?_g=(refreshInterval:(display:'5%20seconds',pause:!f,section:1,value:5000),time:(from:now-5y,mode:quick,to:now))&_a=(filters:!(),options:(darkTheme:!t),panels:!((col:1,id:Provider-Hot-List,panelIndex:1,row:1,size_x:12,size_y:3,type:visualization),(col:1,id:requests-received,panelIndex:6,row:4,size_x:3,size_y:2,type:visualization),(col:1,id:processed-requests,panelIndex:7,row:6,size_x:3,size_y:2,type:visualization),(col:4,id:Request-Per-Hospital,panelIndex:8,row:4,size_x:5,size_y:4,type:visualization),(col:9,id:edi-271-status,panelIndex:10,row:4,size_x:4,size_y:4,type:visualization)),query:(query_string:(analyze_wildcard:!t,query:'*')),title:cloudwick-datalake-quickstart-EDI-stream-dashboard,uiState:(P-10:(spy:(mode:(fill:!f,name:!n))),P-8:(vis:(legendOpen:!f))))";
    print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <button type="button" id="start" data-loading-text="Streaming..." class="btn btn-success" autocomplete="off"><i class="fa fa-play"></i> &nbsp;Start Streaming EDI-270</button> &nbsp;
        <button id="stop" type="button" class="btn btn-danger disabled"><i class="fa fa-stop"></i> &nbsp;Stop Streaming</button> &nbsp; <br><br>
        <object data="'._KIBANA_URL.''.$stream_dashboard.'" width="100%" height="2000em">
            <embed src="'._KIBANA_URL.''.$stream_dashboard.'" width="100%" height="2000em"> </embed>
            Error: Embedded data requires HTML5 support. Data could not be displayed.
        </object>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    <script type="text/javascript">
        var myarray = new Array();
        var timer = null;
        var interval = 200;
        $.when($.get("../data-management/streamGenerator.php",function(data){
            eval(data.toString());
            //$(".streamdata").append( data);
        })).then(function(){
            //alert(myarray.length);
        }).then(function(){
            $("#start").bind("click", function(){
                start();
                var $btn = $(this).button("loading");
                $("#stop").removeClass("disabled");
                $(".legend").removeClass("hidden");
            });
            $("#stop").bind("click", function(){
                stop();
                $("#start").button("loading").button("reset");
                $("#stop").addClass("disabled");
            });
        });

        function start() {
            timer = setTimeout(writeToConsole, interval);
        }

        function stop() {
            clearTimeout(timer);
        }

        function writeToConsole(){
            if (timer == null) return;
            var randomElement = myarray[Math.floor(Math.random()*myarray.length)];
            var datax = JSON.stringify(randomElement);
            $.ajax({
                url: "../data-management/kinesis-stream-writer.php",
                data: { data: datax },
                async: true,
                error: function(datar){
                    //$(".streamresult").append( datar + "<br><br>");
                },
                success: function(datar){
                    //$(".contentBody").append( datar + "<br><br>");
                },
                type: "GET"
            });
            //$(".contentBody").append(datax);
            start();
        }
    </script>
    ';

    include "../root/footer.php";
?>
