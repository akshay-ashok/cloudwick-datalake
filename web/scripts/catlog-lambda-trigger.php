<?php
    include_once "../root/AwsFactory.php";
    try {
        $aws = new AwsFactory();
        $s3client = $aws->getS3Client();
        $result = $s3client->putBucketNotificationConfiguration([
            'Bucket' => _BUCKET,
            'NotificationConfiguration' => [
                'LambdaFunctionConfigurations' => [
                    [
                        'Events' => ['s3:ObjectCreated:*'],
                        'LambdaFunctionArn' => _CATLOG_LAMBDA_ARN,
                    ]
                ]
            ]
        ]);
        print_r($result);
    } catch(\Aws\S3\Exception\S3Exception $ex){
        print $ex->getAwsErrorCode();
    } catch(Exception $ex){
        print $ex->getMessage();
    }

?>
