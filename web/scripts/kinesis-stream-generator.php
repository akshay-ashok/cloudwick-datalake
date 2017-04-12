    <div class="contentBody">
        <button type="button" id="start" data-loading-text="Streaming..." class="btn btn-success" autocomplete="off">Start Streaming</button> &nbsp;
        <button id="stop" type="button" class="btn btn-danger disabled">Stop Streaming</button> &nbsp;
        <button id="clean" type="button" class="btn btn-primary">Clear Output</button> &nbsp; <br><br>
        <div class="streamresult2"></div>
    </div>
    <link rel="stylesheet" href="../resources/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resources/css/customCSS.css">
    <script type="text/javascript" src="../resources/js/jquery-3.2.0.min.js"></script>
    <script type="text/javascript" src="../resources/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../resources/js/customScripts.js"></script>
    <script type="text/javascript">
        var myarray = new Array();
        var timer = null;
        var interval = 1000;
        $.when($.get("../data-management/streamGenerator.php",function(data){
            eval(data.toString());
        })).then(function(){
            //after array load operations if any
            alert(myarray.length);
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
            $("#clean").bind("click", clean);
        });

        function clean() {
            $(".streamresult2").html("");
        }

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
                url: "../scripts/kinesis-stream-writer.php",
                data: { data: datax },
                async: true,
                error: function(datar){
                    $(".streamresult2").append( datar + "<br><br>");
                },
                success: function(datar){
                    $(".streamresult2").append( datar + "<br><br>");
                },
                type: 'GET'
            });
            //$(".streamresult2").append( datax + "<br>");
            start();
        }
    </script>