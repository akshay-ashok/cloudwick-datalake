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
        $taskrunner_pid = shell_exec("ps aux |grep TaskRunner|grep -v grep|awk -F\" \" '{print $2}'");
        $kill_taskrunner = shell_exec("sudo kill -9 ".$taskrunner_pid);

        $cmd = "echo '#!/bin/bash' > deleteBucketScript.sh && aws --output text s3api list-object-versions --bucket "._BUCKET." | grep -E \"^VERSIONS\" | awk '{print \"aws s3api delete-object --bucket "._BUCKET." --key \"$4\" --version-id \"$8\";\"}' >> deleteBucketScript.sh && . deleteBucketScript.sh; rm -f deleteBucketScript.sh; echo '#!/bin/bash' > deleteBucketScript.sh && aws --output text s3api list-object-versions --bucket "._BUCKET." | grep -E \"^DELETEMARKERS\" | grep -v \"null\" | awk '{print \"aws s3api delete-object --bucket "._BUCKET." --key \"$3\" --version-id \"$5\";\"}' >> deleteBucketScript.sh && . deleteBucketScript.sh; rm -f deleteBucketScript.sh;";
        $version_delete = shell_exec($cmd);

        $s3cleanup = shell_exec("aws s3 rb s3://" . _BUCKET . " --force");
        sleep(1);

        if($bucket->doesBucketExist(_BUCKET)){
            $result = $bucket->deleteBucket([
                'Bucket' => _BUCKET
            ]);
            $bucket->waitUntil('BucketNotExists', array('Bucket' => _BUCKET));

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
