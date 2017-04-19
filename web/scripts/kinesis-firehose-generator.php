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
    var invokes = 0;


    function writeToConsole(){
        //if (timer == null) return;
        //var randomElement = myarray[Math.floor(Math.random()*myarray.length)];
        var datax = JSON.stringify('{"name":"NASH", "type":"HEALTHCARE", "change":-0.05, "price":84.51}');
        $.ajax({
            url: "../scripts/kinesis-firehose-writer.php",
            data: { data: datax },
            async: true,
            error: function(datar){
                $(".streamresult2").append( datar + "<br><br>");
                alert("not sent");
            },
            success: function(datar){
                $(".streamresult2").append( datar + "<br><br>");
                alert("sent");
            },
            type: 'GET'
        });
        invokes++;
        //$(".streamresult2").append( datax + "<br>");
        writeToConsole();
    }
</script>