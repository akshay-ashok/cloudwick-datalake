<?php
    include_once('../root/header.php');
    require_once('../root/AwsFactory.php');
    require_once('../root/ConnectionManager.php');
    checkSession();

    $aws = new AwsFactory();
    print '<div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <div class="panel panel-info">';
        $client = $aws->getS3Client();
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['patientfile']) && $_FILES['patientfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['patientfile']['tmp_name'])) {
            try{
                $upload = $client->upload(_BUCKET, $_FILES['patientfile']['name'], fopen($_FILES['patientfile']['tmp_name'], 'rb'), 'public-read');
                print '<p>
                    Upload <a href="'.htmlspecialchars($upload->get('ObjectURL'),ENT_QUOTES).'">successful</a> :)
                </p>';
            } catch (Exception $e){
                print '<div class="alert alert-danger">
                    Upload Failed to '._BUCKET.'. '.$e->getMessage().'
                </div>';
            }
        }
    print '
              <div class="panel-heading">
               <h3 class="panel-title">Upload File to S3</h3>
              </div>
              <div class="panel-body">
                <form class="form-horizontal" enctype="multipart/form-data" method="post">
                  <div class="form-group">
                    <label class="sr-only" for="patientfile">Upload patient file</label>
                    <div class="input-group col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1">
                      <div class="input-group-addon">Patient&nbsp;&nbsp;&nbsp;&nbsp;</div>
                      <input type="file" data-max-size="2048" class="form-control" id="patientfile" name="patientfile" placeholder="Patient File to upload" accept=".csv,text/csv">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="physicianfile">Upload physician file</label>
                    <div class="input-group col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1">
                      <div class="input-group-addon">Physician</div>
                      <input type="file" data-max-size="2048" class="form-control" id="physicianfile" placeholder="Patient File to upload" accept=".csv,text/csv">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="providerfile">Upload provider file</label>
                    <div class="input-group col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1">
                      <div class="input-group-addon">Provider&nbsp;&nbsp;</div>
                      <input type="file" data-max-size="2048" class="form-control" id="providerfile" placeholder="Patient File to upload" accept=".csv,text/csv">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="billingfile">Upload billing file</label>
                    <div class="input-group col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1">
                      <div class="input-group-addon">Billing&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                      <input type="file" data-max-size="2048" class="form-control" id="billingfile" placeholder="Patient File to upload" accept=".csv,text/csv">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1">
                      <br>
                      <input type="submit" value="Upload Files" class="btn btn-success btn-lg">
                      <input type="reset" value="Reset Form" class="btn btn-danger btn-lg">
                    </div>
                  </div>
                </form>
              </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1"></div>';

include_once "../root/footer.php";
?>