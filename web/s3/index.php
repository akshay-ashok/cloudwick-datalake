<?php
use Aws\S3\Exception\S3Exception;

    include_once "../root/header.php";
    include_once "../root/AwsFactory.php";
    checkSession();

    $aws = new AwsFactory();
    $s3Client = $aws->getS3Client();
    $action = (isset($_GET["bucket"])) ? sanitizeParameter($_GET["action"]) : "listBuckets";
    $bucket = (isset($_GET["bucket"])) ? sanitizeParameter($_GET["bucket"]) : _BUCKET;
    $buckets_list = getCatalogedBuckets();
    $prefix = '';
    $list_object_error = null;
    $bucket_lifecycle_error = null;
    $objects = null;
    $no_objects = true;

    if(isset($_GET["prefix"])){
        if(isset($_GET["source"])){
            if($_GET["source"]=="tag"){
                $prefix = sanitizeParameter($_GET["prefix"]);
            }
        } else {
            $prefix = sanitizeParameter($_GET["prefix"]) . '/';
        }
    }

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
            <a tabindex="0" id="BLCpolicy" class="pull-right '.(($action=="listBuckets") ? 'hidden' : '').'" 
                data-toggle="popover" data-trigger="focus" 
                title="Bucket Life Cycle Policy" 
                data-content="'.$lifecyclepolicy.'">
                Bucket Life Cycle Policy
            </a>
            <br>
            ';
    } catch (S3Exception $ex) {
        $bucket_lifecycle_error = $ex->getAwsErrorCode();
    } catch (Exception $ex){
        $bucket_lifecycle_error = $ex->getMessage();
    }

    print '
        <ol class="breadcrumb">
    ';
        if($action == "listBuckets"){
            print '
                <li class="active">Simple Storage Service</li>';
        } else if($action == "listObjects" && !isset($_GET["prefix"])){
            print '
                <li><a href="../s3/index.php?action=listBuckets">s3</a></li>
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

    if($action == "listBuckets"){
        print '
        <div class="s3UtilitiesBar">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#subscribeBucketModal"  title="Add bucket for cataloging">
                <i class="fa fa-sitemap"></i> Add bucket for cataloging
            </a> &nbsp;
        </div>
        <!-- Start createFolder Modal -->
        <div class="modal fade" id="subscribeBucketModal" tabindex="-1" role="dialog" aria-labelledby="subscribeBucketLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-primary" id="subscribeBucketTitle">Add bucket for cataloging</h4>
              </div>
              <div class="modal-body" style="margin-top:1em;">
                <span id="subscribeBucketMessage"></span>
                <form id="subscribeBucketForm" action="../s3/s3Catalog.php" method="post">
                    <div class="form-group">
                        <label for="bucketname">Select Bucket:</label>
                        <select name="bucketname" id="bucketname" class="form-control" placeholder="Select a Bucket" required>
                            <option value=""></option>';
                        try{
                            $buckets = $s3Client->listBuckets([]);
                            foreach ($buckets["Buckets"] as $bucket_item) {
                                $bucket_region = $s3Client->getBucketLocation([
                                    'Bucket' => $bucket_item["Name"]
                                ]);
                                if($bucket_region["LocationConstraint"] == _REGION && !in_array($bucket_item["Name"],$buckets_list)){
                                    print '<option value="'.$bucket_item["Name"].'">'.$bucket_item["Name"].'</option>
                                    ';
                                }
                            }
                        } catch (S3Exception $ex){

                        } catch (Exception $ex){

                        }
        print '
                        </select>
                        <small class="pull-right"><i>List of buckets in <b class="text-danger">'._REGION.'</b> region</i></small>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add to Catalog" class="btn btn-success btn-lg" id="subscribeBucketSubmit">
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <a href="#" type="button" class="btn btn-default" id="subscribeBucketClose" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
        </div>
        <!-- End createFolder Modal -->
        <br>
            <ul class="list-group">';
        foreach ($buckets_list as $bucket_item) {
            print '
                <li class="list-group-item">
                    <a href="../s3/index.php?action=listObjects&bucket=' . $bucket_item . '" class="s3Bucket">
                        <img src="../resources/images/s3Bucket.png" alt="s3Bucket"/> &nbsp;' . $bucket_item . '
                    </a>
                </li>';
        }
        print '
           </ul>';

    } else if($action == "listObjects" && in_array($bucket,$buckets_list)) {
        print '
        <div class="s3UtilitiesBar">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#uploadObjectModal"  title="Upload Object to this folder">
                <span class="glyphicon glyphicon-cloud-upload"></span> Upload
            </a> &nbsp;
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createFolderModal" title="Create a folder here">
                <span class="glyphicon glyphicon-plus"></span> Create Folder
            </a>
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
        ';
    print '<br>
        <ul class="list-group">';
        try {
            $objects = $s3Client->listObjects([
                'Bucket' => $bucket,
                'Delimiter' => "/",
                'Prefix' => $prefix,
            ]);
        } catch (S3Exception $ex) {
            $list_object_error = $ex->getAwsErrorCode();
        } catch (Exception $ex){
            $list_object_error = $ex->getMessage();
        }

        if (count($objects["CommonPrefixes"]) > 0) {
            $no_objects = false;
            foreach ($objects["CommonPrefixes"] as $folder) {
                $foldername = rtrim($folder["Prefix"], '/');
                if (strlen($foldername) > 0) {
                    print '
                    <li class="list-group-item">
                        <a href="../s3/index.php?bucket=' . $bucket . '&prefix=' . $foldername . '" class="s3Folder">
                            <span class="glyphicon glyphicon-folder-close"></span> &nbsp;' . str_ireplace($prefix, "", $foldername) . '
                        </a>
                    </li>';
                }
            }
        }

        if (count($objects["Contents"]) > 0) {
            $no_objects = false;
            foreach ($objects["Contents"] as $file) {
                $filename = $file["Key"];
                $filesize = $file["Size"];
                if ($filesize > 0) {
                    $fileType = fileTypeIcon($filename);
                    $fileTitle = explode("-",$fileType);
                    print '
                    <li class="list-group-item">
                        <i class="fa fa-'.$fileType.' text-primary " title="'.(isset($fileTitle[1]) ? $fileTitle[1] : $fileTitle[0]).' type"></i> &nbsp;
                        <a href="#" data-toggle="modal" data-target="#downloadObject" 
                            data-bucket="'.$bucket.'" data-key="'.$filename.'" class="s3Object" 
                            title="Click to generate pre-signed URI for '.str_ireplace($prefix, "", $filename).'">
                            ' . str_ireplace($prefix, "", $filename) . '
                        </a>
                    </li>';
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
                    <a href="#" type="button" class="btn btn-primary openlink" 
                        target="_blank" data-copytarget="#object-name">
                        Download Object
                    </a>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>';
        }

        if ($no_objects) {
            print '<li class="list-group-item text-danger">
                There are no objects under this path (or) you do not have required permissions to list Objects under this bucket
            </li>';
        }
        if ($list_object_error != null) {
            print '<li class="list-group-item text-danger">' . $list_object_error . '</li>';
        }
        if ($bucket_lifecycle_error != null) {
            // print '<li class="list-group-item text-danger">' . $bucket_lifecycle_error . '</li>';
            // un prettified aws error-code, ignore noSuchLifeCyclePolicy exception
            // --susheel 04/17/2017
        }
        print '
        </ul>';
    } else {
        print '<ul class="list-group">
                <li class="list-group-item alert-danger">Unauthorized access request for the Bucket \''.$bucket.'\'</li>
               </ul>';
    }
    print '
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';

    include_once "../root/footer.php";
?>