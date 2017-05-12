<?php
include_once "../root/AwsFactory.php";

$aws = new AwsFactory();

$bucket = isset($_POST["bucket"]) ? htmlspecialchars($_POST["bucket"], ENT_QUOTES) : null;
$prefix = isset($_POST["prefix"]) ? htmlspecialchars($_POST["prefix"], ENT_QUOTES) : "";
$foldername = isset($_POST["foldername"]) ? htmlspecialchars($_POST["foldername"], ENT_QUOTES) : null;
$folder = isset($_POST["foldername"]) ? $prefix.$foldername."/" : null;

if(!is_null($bucket)){
    if(!is_null($folder)){
        try {
            $s3 = $aws->getS3Client();
            if($s3->doesObjectExist($bucket,$folder)) {
                print '<div class="alert alert-warning">Folder \''.$foldername.'\' already exists</div>';
            } else {
                $result = $s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $folder,
                    'ServerSideEncryption' => 'AES256'
                ]);
                if ($result["ObjectURL"]) {
                    print '<div class="alert alert-success">Folder created</div>';
                }
            }

        } catch (\Aws\S3\Exception\S3Exception $ex){
            print '<div class="alert alert-danger">Folder not created. '.$ex->getAwsErrorCode().'</div>';
        } catch (Exception $ex){
            print '<div class="alert alert-danger">Folder not created. '.$ex->getMessage().'</div>';
        }
    } else {
        print '<div class="alert alert-danger">Folder name cannot be null</div>';
    }
} else {
    print '<div class="alert alert-danger">Bucket name cannot be null</div>';
}


?>