<?php
    include "../root/header.php";
    $stream_cont = "app/kibana#/discover?_g=()&_a=(columns:!(_source),index:datalakedeliverystream,interval:auto,query:(query_string:(analyze_wildcard:!t,query:'*')),sort:!(_score,desc))";
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
        <object data="'._KIBANA_URL.''.$stream_cont.'" width="100%" height="2000rem">
            <embed src="'._KIBANA_URL.''.$stream_cont.'" width="100%" height="2000rem"> </embed>
            Error: Embedded data requires HTML5 support. Data could not be displayed.
        </object>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';

    include "../root/footer.php";
?>
