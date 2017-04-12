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
                print '<p>Upload <a href="'.htmlspecialchars($upload->get('ObjectURL')).'">successful</a> :)</p>';
            } catch (Exception $e){
                print '<div class="alert alert-danger">Upload Failed to '._BUCKET.'. '.$e->getMessage().'</div>';
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
die();

    if(isset($_GET)) {
        if(isset($_GET["action"]) && $_GET["action"]=="listBuckets") {
            $object = $client->listBuckets([]);

            foreach($object["Buckets"] as $bucket){
                if(substr($bucket["Name"],0,strlen(_BUCKET))===_BUCKET){
                    print $bucket["Name"];
                }
            }
            /*
            print '
            <table class="table table-bordered table-striped table-hover" id="gridTable">
            <thead>
              <tr class="success centered">
                <td>S.no</td>
                <td>Bucket Name</td>
                <td>Owner</td>
              </tr>  
            </thead>
          ';
                $i = 1;
                $owner = $buckets["Owner"]["DisplayName"];
                foreach ($buckets["Buckets"] as $bucket) {
                    print '<tr>
                <td>' . $i++ . '</td>
                <td>' . $bucket["Name"] . '</td>
                <td>' . $owner . '</td>
            </tr>';
                }
                print  '</table>
           <script type="text/javascript">
             //$("#gridTable").bootgrid();
            </script>
          ';
            */
        }
        else if(isset($_GET["action"]) && $_GET["action"]=="uploadFile"){
            print '<div class="panel panel-info">
                    <div class="panel-heading">
                       <h3 class="panel-title">Upload File to S3</h3>
                    </div>
                  <div class="panel-body">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <div class="col-sm-12">
                          <input type="file" class="form-control" id="file" placeholder="File to upload">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <input type="file" class="form-control" id="file" placeholder="File to upload">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <input type="file" class="form-control" id="file" placeholder="File to upload">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <input type="file" class="form-control" id="file" placeholder="File to upload">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <input type="file" class="form-control" id="file" placeholder="File to upload">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <input type="submit" value="Upload Files" class="btn btn-success btn-lg">
                          <input type="reset" value="Reset Form" class="btn btn-danger btn-lg">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
            ';
            /*

            global $rdsConnector;

            print '<br><br><br>';
            $command = "copy usertable from 's3://_SOURCE_LOCATION_/configuration/userdata.csv' IAM_ROLE '"._REDSHIFT_ARN."'";
            try {
                $rdsConnector->exec($command);
            } catch (\PDOException $e) {
                $query = $rdsConnector->query("SELECT * FROM stl_load_errors WHERE query = pg_last_query_id();");
                if (count($result)) {
                    while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        print_r($row);
                    }
                } else {
                    $message = "Query failed: " . $e->getMessage();
                }
            }

            print '<br><br><br>';
            try{
            $query3 = "select * from usertable";
            $result3 = $rdsConnector->query($query3);
            if ($result3->rowCount() > 0) {
                while($row3 = $result3->fetch(PDO::FETCH_ASSOC)){
                    print_r($row3);
                }
            } else {
                print 'nothing here bicth';
            }
            }catch(PDOException $ex){
                print $ex->getMessage();
            }
          */
        }
        else {
            print '<div class="alert alert-danger"><h3>Please choose from menu</h3></div>';
            //$output = shell_exec('aws s3 ls _SOURCE_LOCATION_/configuration/ --region us-east-2');
            //echo "<pre>$output</pre>";
        }
    }

include_once('../root/footer.php');
?>
