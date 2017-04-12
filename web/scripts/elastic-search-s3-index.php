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
    } catch (Exception $ex){
        //no-exception handled
    }
?>