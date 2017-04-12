<?php

    include "../root/AwsFactory.php";

    $aws = new AwsFactory();
    $client = $aws->getKinesisClient("us-west-2");

    $data = '{"payer_name":"Live Let Live corp","payer_id":"988552","provider_id":"--","provider_name":"--","provider_service_number":"--","provider_address":"P.O. Box 876, ,Minna, CA, 85088","physician_id":"51352098299","physician_name":"Deborah Strokes","physician_mobile":"158-695-6352","physician_email":"grayladerta@feliseg.org","patient_id":"16600720802","patient_name":"Hope Dillard","patient_ssn":"203030082","patient_address":"P.O. Box 876, ,Minna, CA, 85088"}';


    $result2 = $client->getShardIterator([
        'ShardId' => 'shardId-000000000000',
        'ShardIteratorType' => 'TRIM_HORIZON',
        'StreamName' => 'datalake'
    ]);

    $result = $client->getRecords([
        'Limit' => 200,
        'ShardIterator' => $result2["ShardIterator"],
    ]);

    print_r($result);



?>