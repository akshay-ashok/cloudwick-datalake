<?php
    include "../root/header.php";
    print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        
     <div class="well well-lg">
        Kinesis Firehose Delivery Stream Name : <b class="text-primary">'._KINESIS_STREAM_NAME.'</b> <br><br>
        
        <a class="btn btn-primary customMessage" 
            title="Stream Sample data to Kinesis Firehose \''._KINESIS_STREAM_NAME.'\'" 
            data-url="../data-management/stream-instructions.php?type=sampleStream">
            Stream sample data to Kinesis
        </a> &nbsp;
        <a class="btn btn-warning customMessage" 
            title="API call to add data to your Stream:" 
            data-url="../data-management/stream-instructions.php?type=apicall">
            API call to add data to your Stream
        </a><br>
     </div>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';

    include "../root/footer.php";
?>
