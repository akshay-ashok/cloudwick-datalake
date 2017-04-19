<?php
    include "../root/AwsFactory.php";

    $aws = new AwsFactory();
    $client = $aws->getFirehoseClient();

    $data = $_GET["data"];

    $result = $client->putRecord([
        'DeliveryStreamName' => "DatalakeFirehoseStream-123",
        'Record' => [
            "Data" => $data
        ]
    ]);

    if($result["RecordId"] > 0){
        print 'Record Id: '.$result["RecordId"].'<br>';
    }

?>