<?php
    include "../root/AwsFactory.php";

    try {
        $aws = new AwsFactory();
        $client = $aws->getFirehoseClient();

        $data = json_encode($_GET);

        $result = $client->putRecord([
            'DeliveryStreamName' => _KINESIS_STREAM_NAME,
            'Record' => [
                "Data" => base64_encode($data)
            ]
        ]);
        print '<div class="alert alert-success">
            Record sent to stream.'.(
                !is_null($result["RecordId"])
                ? ' Record Id: <a data-toggle="tooltip" data-placement="bottom" 
                        title="'.$result["RecordId"].'">'.substr($result["RecordId"],0,15).'...</a>'
                : '').'
        </div>
        <script type="text/javascript">
          $(function(){
            $(\'[data-toggle="tooltip"]\').tooltip({
                "selector": "",
                "container": "#CustomMessageModal"
            });
          });
        </script>
        ';
    } catch(\Aws\Firehose\Exception\FirehoseException $ex){
        print '<div class="alert alert-success">
            Stream Error: '.$ex->getAwsErrorCode().'
        </div>';
    } catch(Exception $ex){
        print '<div class="alert alert-success">
            Stream Error: '.$ex->getMessage().'
        </div>';
    }

?>