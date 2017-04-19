<?php
    require_once "../root/AwsFactory.php";

    $aws = new AwsFactory();

    $s3client = $aws->getS3Client();

    $result = $s3client->putBucketNotificationConfiguration([
        'Bucket' => $name, // REQUIRED
        'NotificationConfiguration' => [
            'LambdaFunctionConfigurations' => [
                [
                    'Events' => ['s3:ObjectCreated:*'], // REQUIRED
                    'Id' => md5(microtime()),
                    'LambdaFunctionArn' => 'arn:aws:lambda:us-east-2:167270772459:function:dynamic-lambda-trigger-test', // REQUIRED
                ]
            ],
        ],
    ]);

?>