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

            $result = exec("curl ".$url." -H \"Content-Type: application/json\" --data '".json_encode($value["_source"])."'");
 
    } catch (Exception $ex){
        //no-exception handled
    }
?>
