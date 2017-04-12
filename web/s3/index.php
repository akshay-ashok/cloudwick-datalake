<?php
use Aws\S3\Exception\S3Exception;

    include_once "../root/header.php";
    include_once "../root/AwsFactory.php";
    checkSession();

    $aws = new AwsFactory();
    $s3Client = $aws->getS3Client();
    $bucket = (isset($_GET["bucket"])) ? sanitizeParameter($_GET["bucket"]) : _BUCKET;
    $prefix = '';
    $error = null;

    if(isset($_GET["prefix"])){
        if(isset($_GET["source"])){
            if($_GET["source"]=="tag"){
                $prefix = sanitizeParameter($_GET["prefix"]);
            }
        } else {
            $prefix = sanitizeParameter($_GET["prefix"]) . '/';
        }
    }
    $objects = null;

    print '<div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
    
    <script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>
    <script type="text/javascript" src="../resources/js/clipboard-1.60.min.js"></script>
    <script type="text/javascript" src="../resources/js/s3Utilities.js"></script>
    ';

    $lifecyclepolicy = "";
    try{
        $result = $s3Client->getBucketLifecycleConfiguration([
            'Bucket' => $bucket
        ]);

        $lifecyclepolicy = '<b>Id</b> : '.$result["Rules"][0]["ID"].'<br>
            <b>Storage Class</b> : '.$result["Rules"][0]["Transitions"][0]["StorageClass"].'<br>
            <b>Expiration</b> : '.$result["Rules"][0]["Expiration"]["Days"].' Days<br>
            <b>Status</b> : '.$result["Rules"][0]["Status"].'<br>';

        print '
            <a tabindex="0" id="BLCpolicy" class="pull-right" data-toggle="popover" data-trigger="focus" title="Bucket Life Cycle Policy" data-content="'.$lifecyclepolicy.'">Bucket Life Cycle Policy</a><br>
            ';
    } catch (S3Exception $ex) {
        $lifecyclepolicy = $ex->getAwsErrorCode();
    } catch (Exception $ex){
        $lifecyclepolicy = $ex->getMessage();
    }

    print '
        <ol class="breadcrumb">
    ';
        if(!isset($_GET["prefix"])){
            print '
                <li class="active">'.$bucket.'</li>';
        } else {
            $url = "../s3/index.php?bucket=".$bucket."";
            print '
                <li><a href="'.$url.'">'.$bucket.'</a></li>';

            if(substr_count($prefix, "/") > 0){
                $url .= "&source=tag&prefix=";
                $folders = explode("/",$prefix);
                $i=0;
                for($i=0;$i<(count($folders)-2);$i++){
                    $url .= $folders[$i]."/";
                    print '
                <li><a href="'.$url.'">'.$folders[$i].'</a></li>';
                }
                print '
                <li class="active">'.$folders[$i].'</li>';
            } else {
                print '
                <li class="active">'.$prefix.'</li>';
            }
        }

    print '
        </ol>';

    if($bucket === _BUCKET) {
        print '
        <div class="s3UtilitiesBar">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#uploadObjectModal"  title="Upload Object to this folder"><span class="glyphicon glyphicon-cloud-upload"></span> Upload</a> &nbsp;
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createFolderModal" title="Create a folder here"><span class="glyphicon glyphicon-plus"></span> Create Folder</a> &nbsp;
            '.( ($prefix == "uploads/original/") ?
              '<a href="#" class="btn btn-warning" data-toggle="modal" data-target="#instructionsModal" title="Instructions to upload files"><span class="glyphicon glyphicon-info-sign"></span> instructions</a> &nbsp;'
                : ''
            ).'
        </div>
        <!-- Start createFolder Modal -->
        <div class="modal fade" id="createFolderModal" tabindex="-1" role="dialog" aria-labelledby="createFolderLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-primary" id="createFolderTitle">Create Folder</h4>
              </div>
              <div class="modal-body" style="margin-top:1em;">
                <span id="createFolderMessage"></span>
                <form id="createFolderForm" action="../s3/s3Transfer.php" method="post">
                    <div class="form-group">
                        <label for="foldername">New Folder Name:</label>
                        <input type="text" pattern="[a-zA-Z0-9\-\ ]" title="Enter Alpha-numeric Folder name" class="form-control" id="foldername" name="foldername" placeholder="New Folder" required>
                        <input type="hidden" name="bucket" value="'.$bucket.'" required>
                        <input type="hidden" name="prefix" value="'.$prefix.'" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Create" class="btn btn-success btn-lg" id="createFolderSubmit">
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <a href="#" type="button" class="btn btn-default" id="createFolderClose" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
        </div>
        <!-- End createFolder Modal -->
        <!-- Start uploadObject Modal -->
        <div class="modal fade" id="uploadObjectModal" tabindex="-1" role="dialog" aria-labelledby="uploadObjectLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-primary" id="uploadObjectTitle">Upload Object to S3</h4>
              </div>
              <div class="modal-body" style="margin-top:1em;">
                <div class="progress">
                  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" id="uploadProgress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    <span class="uploadProgressSR">0% Complete</span>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <p class="text-primary progressText">upload in progress...</p>
                <span id="uploadObjectMessage"></span>
                <form id="uploadObjectForm" action="../s3/s3Upload.php" method="post" enctype="multipart/form-data" class="dropzone">
                    <div class="form-group">
                        <label for="objectname">Upload Object:</label>
                        <input type="file" title="Upload Object to S3" class="form-control" id="objectname" name="objectname[]" multiple="multiple" placeholder="Upload Object to S3" required>
                        <span id="helpBlock" class="help-block text-right">you can upload upto 20 files of size 10M each</span>
                        <input type="hidden" name="bucket" value="'.$bucket.'" required>
                        <input type="hidden" name="prefix" value="'.$prefix.'" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Upload" class="btn btn-success btn-lg" id="uploadObjectSubmit" data-loading-text="uploading..." autocomplete="off">
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <a href="#" type="button" class="btn btn-default" id="uploadObjectClose" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
        </div>
        <!-- End uploadObject Modal -->
        '.
        (($prefix == "uploads/original/") ? '
        <!-- Start instructionsModal Modal -->
        <div class="modal fade" id="instructionsModal" tabindex="-1" role="dialog" aria-labelledby="instructionsModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-primary" id="instructionsModalTitle">Instructions</h4>
              </div>
              <div class="modal-body" style="margin-top:1em;">
                <span id="instructionsModalMessage"></span>
                some instructions here
              </div>
              <div class="modal-footer">
                <a href="#" type="button" class="btn btn-default" id="instructionsModalClose" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
        </div>
        <!-- End instructionsModal Modal -->
        ' : '').'';
    }

    print '<br>
        <ul class="list-group">';
    if($bucket === _BUCKET) {
        try {
            $objects = $s3Client->listObjects([
                'Bucket' => $bucket,
                'Delimiter' => "/",
                'Prefix' => $prefix,
            ]);
        } catch (S3Exception $ex) {
            $error = $ex->getAwsErrorCode();
        } catch (Exception $ex){
            $error = $ex->getMessage();
        }

        try{
            $lifecyclepolicy = $s3Client->getBucketLifecycleConfiguration([
                'Bucket' => $bucket
            ]);

        } catch (S3Exception $ex) {
            $error = $ex->getAwsErrorCode();
        } catch (Exception $ex){
            $error = $ex->getMessage();
        }

        $min_list = 0;
        if (count($objects["CommonPrefixes"]) > 0) {
            //print_r($objects["CommonPrefixes"]);
            foreach ($objects["CommonPrefixes"] as $folder) {
                $foldername = rtrim($folder["Prefix"], '/');
                if (strlen($foldername) > 0) {
                    print '
                    <li class="list-group-item">
                        <a href="../s3/index.php?bucket=' . $bucket . '&prefix=' . $foldername . '" class="s3Folder">
                            <span class="glyphicon glyphicon-folder-close"></span> &nbsp;' . str_ireplace($prefix, "", $foldername) . '
                        </a>
                    </li>';
                    $min_list++;
                }
            }
        }

        if (count($objects["Contents"]) > 0) {
            foreach ($objects["Contents"] as $file) {
                $filename = $file["Key"];
                $filesize = $file["Size"];
                if ($filesize > 0) {
                    print '
                    <li class="list-group-item">
                        <a href="#" data-toggle="modal" data-target="#downloadObject" data-bucket="'.$bucket.'" data-key="'.$filename.'" class="s3Object" title="Click to download '.str_ireplace($prefix, "", $filename).'">
                            <i class="fa fa-'.fileTypeIcon($filename).' fa-border text-primary "></i> &nbsp;' . str_ireplace($prefix, "", $filename) . '
                        </a>
                    </li>';
                    $min_list++;
                }
            }
            print '           
            <div class="modal fade" id="downloadObject" tabindex="-1" role="dialog" aria-labelledby="downloadObjectLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Download Object</h4>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="form-group">
                        <label for="object-name" class="control-label">Generated Pre-Signed URL for Object:</label>
                        <input type="text" class="form-control" id="object-name">
                        <small class="text-danger pull-right">Valid for 10mins from now</small>
                      </div>
                    </form>                    
                    <button type="button" class="btn btn-success copylink" data-copytarget="#object-name">Copy Link</button>
                    <a href="#" type="button" class="btn btn-primary openlink" target="_blank" data-copytarget="#object-name">Download Object
                    </a>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>';
        }

        if ($min_list == 0) {
            print '<li class="list-group-item text-danger">There are no objects under this path.</li>';
        }
        if ($error != null) {
            print '<li class="list-group-item text-danger">' . $error . '</li>';
        }
    } else {
        print '<li class="list-group-item alert-danger">Unauthorized access request for the Bucket \''.$bucket.'\'</li>';
    }
    print '
        </ul>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';

    include_once "../root/footer.php";
?>