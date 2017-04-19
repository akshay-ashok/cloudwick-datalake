<?php
    include_once "../root/defaults.php";
    if(isset($_GET)) {
        $type = htmlspecialchars($_GET["type"], ENT_QUOTES);
        if($type == "apicall"){
            print '
<pre><code><em class="text-primary">
 <em class="text-success">POST</em> /?Action=PutRecord HTTP/1.1
 <em class="text-success">Host</em>: kinesis.region.domain
 <em class="text-success">Authorization</em>: AWS4-HMAC-SHA256 Credential=..., ...
 ...
 <em class="text-success">Content-Type</em>: application/x-amz-json-1.1
 <em class="text-success">Content-Length</em>: PayloadSizeBytes
 
 {
    <em class="text-success">"Data"</em>: blob, <em class="text-danger">//required</em>
    <em class="text-success">"ExplicitHashKey"</em>: "string",
    <em class="text-success">"PartitionKey"</em>: "string", <em class="text-danger">//required</em>
    <em class="text-success">"SequenceNumberForOrdering"</em>: "string",
    <em class="text-success">"StreamName"</em>: <em class="text-primary" style="font-weight:bold;">"'._KINESIS_STREAM_NAME.'"</em> <em class="text-danger">//required</em>
 }
</em></code></pre>';
        } else if($type == "streamForm"){
            // todo: stream form 04/18/2017
        }
    }