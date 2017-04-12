<?php
include_once "../root/AwsFactory.php";

$aws = new AwsFactory();

$bucket = isset($_POST["bucket"]) ? htmlspecialchars($_POST["bucket"], ENT_QUOTES) : null;
$prefix = isset($_POST["prefix"]) ? htmlspecialchars($_POST["prefix"], ENT_QUOTES) : "";

$file_errors = array(
    0=>"Upload Success",
    1=>"The uploaded file(s) exceeds the MAX_FILE_SIZE",
    2=>"The uploaded file(s) exceeds the MAX_POST_SIZE",
    3=>"The uploaded file was only partially uploaded",
    4=>"No file was uploaded",
    6=>"Missing a temporary folder"
);

if(!is_null($bucket)){
    try {
        $s3 = $aws->getS3Client();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES["objectname"]) ){
            foreach ($_FILES['objectname']['tmp_name'] as $key => $tmp_name) {

                $filename = $_FILES['objectname']['name'][$key];
                $tmpfile = $_FILES['objectname']['tmp_name'][$key];

                if ($s3->doesObjectExist($bucket, $prefix . $filename)) {
                    print '<p class="text-warning">** '.$filename.' already exists</p>';
                } else {
                    if ($_FILES['objectname']['error'][$key] == UPLOAD_ERR_OK && is_uploaded_file($tmpfile)) {
                        try {
                            //$upload = $s3->upload(_BUCKET, $prefix.$_FILES['objectname']['name'], fopen($_FILES['objectname']['tmp_name'], 'rb'), 'public-read');
                            $result = $s3->putObject([
                                'Bucket' => $bucket,
                                'Key' => $prefix . $filename,
                                'SourceFile' => $tmpfile,
                                'ServerSideEncryption' => 'AES256'
                            ]);
                            if ($result["ObjectURL"]) {
                                print '<p class="text-success">' . $filename . ' upload successful</a></p>';
                            }
                        } catch (\Aws\S3\Exception\S3Exception $ex) {
                            print '<p class="text-danger">' . $filename . ' upload Failed to ' . $bucket . '. ' . $ex->getAwsErrorCode() . '</p>';
                        } catch (Exception $ex) {
                            print '<p class="text-danger">' . $filename . ' upload Failed to ' . $bucket . '. ' . $ex->getMessage() . '</p>';
                        }
                    } else {
                        print '<p class="text-danger">' . $filename . ' upload Failed to ' . $bucket . '. ' . $file_errors[$_FILES['objectname']['error'][$key]] . '</p>';
                    }
                }
            }
        }

    } catch (\Aws\S3\Exception\S3Exception $ex){
        print '<p class="text-danger">Object upload failed. '.$ex->getAwsErrorCode().'</p>';
    } catch (Exception $ex){
        print '<p class="alert alert-danger">Object Upload failed. '.$ex->getMessage().'</p>';
    }
} else {
    print '<div class="alert alert-danger">Bucket name cannot be null</div>';
}


?>
