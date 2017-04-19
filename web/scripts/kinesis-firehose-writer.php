<?php
    include "../root/AwsFactory.php";

    $aws = new AwsFactory();
    $client = $aws->getFirehoseClient();

    //$data = $_GET["data"];
    $data = json_encode("{
    \"status\": \"Accepted\",
    \"provider_id\": \"6635\",
    \"physician_email\": \"egestas@vitae.co.uk\",
    \"patient_ssn\": \"344757319\",
    \"provider_service_number\": \"4396552145\",
    \"payer_name\": \"Live Let Live corp\",
    \"payer_id\": \"988552\",
    \"physician_mobile\": \"594-886-1277\",
    \"patient_address\": \"152 Amet Avenue P.O. Box 272 Lugo OR 37532\",
    \"record_ts\": \"2017-04-11T17:35:47\",
    \"physician_name\": \"Igor Klein\",
    \"record_id\": \"TS20e8143d85b96\",
    \"patient_name\": \"Keane Wise\",
    \"provider_name\": \"Cloud Hospital\",
    \"patient_id\": \"16891018394\",
    \"physician_id\": \"10655681299\",
    \"provider_address\": \"152 Amet Avenue P.O. Box 272 Lugo OR 37532\"
  }");

    $result = $client->putRecord([
        'DeliveryStreamName' => 'DatalakeFirehoseStream-123',
        'Record' => [
            "Data" => $data
        ]
    ]);

    if($result["RecordId"] > 0){
        print 'Record Id: '.$result["RecordId"].'<br>';
    }

?>