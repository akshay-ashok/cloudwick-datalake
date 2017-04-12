<?php
    include_once "../root/AwsFactory.php";

    $aws = new AwsFactory();
    $s3Client = $aws->getS3Client();
    if(isset($_GET)) {
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $_GET["bucket"],
            'Key' => $_GET["keyname"]
        ]);
        $request = $s3Client->createPresignedRequest($cmd, '+10 minutes');
        $presignedUrl = (string)$request->getUri();
        print $presignedUrl;
    }
?>