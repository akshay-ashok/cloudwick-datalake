<?php
    include_once "../root/AwsFactory.php";

    $aws = new AwsFactory();

    $bucket = _BUCKET;
    $folders = array("uploads/original/","uploads/masked/","uploads/unmasked/");

    function success($msg){
        print '<p class="text-success"><span class="glyphicon glyphicon-ok-circle"></span> '.$msg.'</p>';
    }

    function error($msg){
        print '<p class="text-danger"><span class="glyphicon glyphicon-remove-circle"></span> '.$msg.'</p>';
    }

    try{
        $s3client = $aws->getS3Client();
        if($s3client->doesBucketExist($bucket)){
            success('S3 Bucket Exists');
            $uploadsFolder = 0;
            foreach ($folders as $folder) {
                if ($s3client->doesObjectExist($bucket, $folder)) {
                    $uploadsFolder++;
                } else {
                    $result = $s3client->putObject([
                        'Bucket' => $bucket,
                        'Key' => $folder,
                        'ServerSideEncryption' => 'AES256'
                    ]);
                    if ($result["ObjectURL"]) {
                        $uploadsFolder++;
                    }
                }
            }

            if($uploadsFolder == count($folders)){
                success('Uploads Folder');
            } else {
                error('Uploads Folder');
            }
        } else {
            error('S3 Bucket Doesn\'t Exists');
        }
    } catch (\Aws\S3\Exception\S3Exception $ex) {
        error($ex->getAwsErrorCode());
    } catch (Exception $ex) {
        error($ex->getMessage());
    }



?>