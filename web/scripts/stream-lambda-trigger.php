<?php
include_once "../root/AwsFactory.php";
try {
    $aws = new AwsFactory();
    $client = $aws->getLambdaClient();

    $result = $client->createEventSourceMapping([
        'BatchSize' => 100,
        'Enabled' => true,
        'EventSourceArn' => _KINESIS_STREAM_ARN,
        'FunctionName' => _STREAM_LAMBDA_NAME,
        'StartingPosition' => 'LATEST'
    ]);
    print_r($result);
} catch(\Aws\S3\Exception\S3Exception $ex){
    print $ex->getAwsErrorCode();
} catch(Exception $ex){
    print $ex->getMessage();
}

?>
