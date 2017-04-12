<?php
    include "../root/AwsFactory.php";

    $aws = new AwsFactory();
    $client = $aws->getKinesisClient();

    $data = $_GET["data"];

        $data = json_encode($data);

        $result = $client->putRecord([
            'Data' => $data,
            'PartitionKey' => 'shardId-000000000000',
            'StreamName' => 'edi270stream'
        ]);

        if($result["SequenceNumber"] > 0){
            print 'Insert Success. Seq no: '.$result["SequenceNumber"].'<br>';
        }


?>