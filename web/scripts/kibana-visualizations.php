<?php
    include_once "../root/AwsFactory.php";
    $aws = new AwsFactory();
    try {
        $s3client = $aws->getS3Client();
        $result = $s3client->putObject([
            'Bucket' => _BUCKET,
            'Key' => 'Metadata-index',
            'Body' => 'Hello World',
            'ServerSideEncryption' => 'AES256'
        ]);

        $json = file_get_contents("../configurations/kibana/objects/export.json");

        $json = json_decode($json, true);
        foreach ($json as $key => $value) {
            $url = 'https://'._ELASTIC_SEARCH_URL.'/.kibana/'.$value["_type"].'/'.$value["_id"];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($value["_source"]));

            curl_exec($ch);
        }
    } catch (Exception $ex){
        //no-exception handled
    }
?>