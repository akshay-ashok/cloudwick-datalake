<?php
    include_once "../root/AwsFactory.php";

    try {
        $aws = new AwsFactory();
        $cf_console = '<a href="https://'._REGION.'.console.aws.amazon.com/cloudformation/home?region='._REGION.'#/stack/detail?stackId='._STACK_ID.'" target="_blank">CloudFormation console screen</a>';

        $bucket = $aws->getS3Client();
        $stack = $aws->getCFClient();

        print '<p class="text-warning">Cleaning up s3://' . _BUCKET . ' bucket</p>';
        //$s3cleanup = exec("aws s3 rb s3://" . _BUCKET . " --force");
        sleep(5);
        //print '<p class="text-success">Bucket deleted</p><br>';

        $result = $stack->deleteStack([
            'StackName' => _STACK_NAME
        ]);
        print '<p class="text-success">Stack deletion initiated...</p>';
        print '<p class="text-danger">Check '.$cf_console.' for deletion status</p>';
    } catch(\Aws\S3\Exception\S3Exception $ex){
        print '<p class="text-danger">Cannot Delete bucket, Please use '.$cf_console.' to delete the stack. <br>Error: '.$ex->getAwsErrorCode().'</p>';
    } catch(\Aws\CloudFormation\Exception\CloudFormationException $ex){
        print '<p class="text-danger">Stack deletion failed, Please use '.$cf_console.' to delete the stack. <br>Error: '.$ex->getAwsErrorCode().'</p>';
    } catch(Exception $ex){
        print '<p class="text-danger">Stack deletion failed, Please use '.$cf_console.' to delete the stack. <br>Error: '.$ex->getMessage().'</p>';
    }
?>
