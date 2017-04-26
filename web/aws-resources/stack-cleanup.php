<?php
    include_once "../root/AwsFactory.php";

    try {
        $aws = new AwsFactory();
        $cf_console = '
            <a href="https://'._REGION.'.console.aws.amazon.com/cloudformation/home?region='._REGION.'#/stack/detail?stackId='._STACK_ID.'" target="_blank">
                CloudFormation console <i class="fa fa-external-link-square"></i>
            </a>';

        $bucket = $aws->getS3Client();
        $trail = $aws->getCloudTrailClient();
        $stack = $aws->getCFClient();

        $result = $trail->deleteTrail([
            'Name' => _CLOUDTRAIL_NAME
        ]);

        sleep(2);
        print '<p class="text-warning">Cleaning up s3://' . _BUCKET . ' bucket</p>';
        $s3cleanup = exec("aws s3 rb s3://" . _BUCKET . " --force");

        sleep(2);
        if($bucket->doesBucketExist(_BUCKET)){
            $result = $client->deleteBucket([
                'Bucket' => _BUCKET
            ]);
            print '<p class="text-success">Bucket deleted</p><br>';
        } else {
            print '<p class="text-success">Bucket deleted</p><br>';
        }
        print '<p class="text-danger">Goto '.$cf_console.', and click \'Delete Stack\' under other options menu</p>';

    } catch(\Aws\S3\Exception\S3Exception $ex){
        print '<p class="text-danger">
            Cannot Delete bucket, Please use '.$cf_console.' to delete the stack. <br>Error: '.$ex->getAwsErrorCode().'
        </p>';
    } catch(\Aws\CloudFormation\Exception\CloudFormationException $ex){
        print '<p class="text-danger">
            Stack deletion failed, Please use '.$cf_console.' to delete the stack. <br>Error: '.$ex->getAwsErrorCode().'
        </p>';
    } catch(Exception $ex){
        print '<p class="text-danger">
            Stack deletion failed, Please use '.$cf_console.' to delete the stack. <br>Error: '.$ex->getMessage().'
        </p>';
    } finally {
        print '<center>
            <img src="../resources/images/delete_stack.png" class="img img-thumbnail img-responsive centered">
        </center>';
    }
?>
