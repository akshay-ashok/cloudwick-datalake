<?php
    include_once("../root/header.php");
    include_once("../root/AwsFactory.php");
    checkSession();

    $aws = new AwsFactory();

    $s3Client = $aws->getS3Client();
    $redshiftClient = $aws->getRedshiftClient();
    $rdsClient = $aws->getRDSClient();

    $s3_error = null;
    $rds_error = null;
    $redshift_error = null;

    $s3object = null;
    $redshiftobject = null;
    $rdsobject = null;

    $bucketcreationdate = "";
    $bucketname = "";
    $bucketlifecyclepolicy = "";

    try {
        $s3object = $s3Client->listBuckets([]);
        foreach ($s3object["Buckets"] as $bucket) {
            if ($bucket["Name"] === _BUCKET) {
                $bucketname = $bucket["Name"];
                $bucketcreationdate = $bucket["CreationDate"];
            }
        }
    } catch (\Aws\S3\Exception\S3Exception $ex){
        $s3_error = $ex->getAwsErrorCode();
    } catch (Exception $ex){
        $s3_error = $ex->getMessage();
    }

    try{
        $result = $s3Client->getBucketLifecycleConfiguration([
            'Bucket' => _BUCKET
        ]);

        $lifecyclepolicy = '<b>Id</b> : '.$result["Rules"][0]["ID"].'<br>
                <b>Storage Class</b> : '.$result["Rules"][0]["Transitions"][0]["StorageClass"].'<br>
                <b>Expiration</b> : '.$result["Rules"][0]["Expiration"]["Days"].' Days<br>
                <b>Status</b> : '.$result["Rules"][0]["Status"].'<br>';

        $bucketlifecyclepolicy = '
        <div class="btn-group">
          <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="pointer">
            Bucket Life Cycle Policy <i class="fa fa-hand-pointer-o"></i>
          </a>
          <ul class="dropdown-menu">
            <li style="padding: 1rem;">'.$lifecyclepolicy.'</li>
          </ul>
        </div><br>';
    } catch (Aws\S3\Exception\S3Exception $ex) {
        //$lifecyclepolicy = $ex->getAwsErrorCode();
    } catch (Exception $ex){
        //$lifecyclepolicy = $ex->getMessage();
    }

    try {
        $redshiftobject = $redshiftClient->describeClusters([
            'ClusterIdentifier' => _REDSHIFT_IDENTIFIER
        ]);
    } catch (\Aws\Redshift\Exception\RedshiftException $ex){
        $redshift_error = $ex->getAwsErrorCode();
    } catch (Exception $ex){
        $s3_error = $ex->getMessage();
    }

    try {
        $rdsobject = $rdsClient->describeDBInstances([
            'DBInstanceIdentifier' => _RDS_IDENTIFIER
        ]);
    } catch (\Aws\Rds\Exception\RdsException $ex){
        $rds_error = $ex->getAwsErrorCode();
    } catch (Exception $ex){
        $s3_error = $ex->getMessage();
    }

    print '
      <div class="clearfix"></div><br>
      <div class="col-lg-1 col-md-1"></div>
      <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <ul class="list-inline">
            <li class="dltag text-info">Datalake</li>
            <li>
                <button class="btn btn-primary" type="button">
                    <span class="badge">
                        <b><span class="glyphicon glyphicon-tags"></span> Tags</b>
                    </span> 
                    '._TAG_KEY.' => '._TAG_VALUE.'
                </button>
            </li>
            <li>
                <button class="btn btn-primary" type="button">
                    <span class="badge">
                        <b><span class="glyphicon glyphicon-map-marker"></span> Region</b>
                    </span> 
                    '._REGION.'
                </button>
            </li>
            <li>
                <button class="btn btn-primary" type="button">
                    <span class="badge">
                        <b><span class="glyphicon glyphicon-cog"></span> Resource Type</b>
                    </span> 
                    All
                </button>
            </li>
        </ul>
        <hr/>
        <div class="panel panel-warning">
            <div class="panel-heading">S3 Details</div>
            <div class="panel-body">'.
            (($s3_error != null) ? 'Unable to reach S3 Bucket. <p class="text-danger"><br>Error: '.$s3_error.'</p>' :
              '<dl class="dl-horizontal">
                <dt>
                    S3 Bucket Name
                </dt>
                <dd class="text-primary">
                    <a href="../s3/index.php?action=listObjects&bucket='.$bucketname.'" title="Explore '.$bucketname.' Bucket">
                        '.$bucketname.' <i class="fa fa-link"></i>
                    </a>
                </dd>
                
                <dt>
                    Creation Date
                </dt>
                <dd class="text-primary">
                    '.$bucketcreationdate.'
                </dd>
                
                <dt>
                    Bucket Policy
                </dt>
                <dd class="text-primary">
                    '.$bucketlifecyclepolicy.'
                </dd>
              </dl>').'              
            </div>
        </div>
        <div class="clearfix"></div><br>
        <div class="panel panel-info">
            <div class="panel-heading">Redshift Details</div>
            <div class="panel-body">'.
            ( ($redshift_error != null) ? 'Cannot load Redshift details. <p class="text-danger"><br>Error: '.$redshift_error.'</p>' :
              '<dl class="dl-horizontal">
                <dt>
                    Cluster Identifier
                </dt>
                <dd class="text-primary">
                    '.$redshiftobject["Clusters"][0]["ClusterIdentifier"].'
                </dd>
                
                <dt>
                    Endpoint
                </dt>
                <dd class="text-primary">
                    <a href="../aws-resources/redshift.php?explore=table" title="Explore redshift">
                        '.$redshiftobject["Clusters"][0]["Endpoint"]["Address"].':'.$redshiftobject["Clusters"][0]["Endpoint"]["Port"].' 
                        <i class="fa fa-link"></i>
                    </a>
                </dd>
                
                <dt>
                    NodeType
                </dt>
                <dd class="text-primary">
                    '.$redshiftobject["Clusters"][0]["NodeType"].'
                </dd>
                
                <dt>
                    Database Name
                </dt>
                <dd class="text-primary">
                    '.$redshiftobject["Clusters"][0]["DBName"].'
                </dd>
                
                <dt>
                    Creation Date
                </dt>
                <dd class="text-primary">
                    '.$redshiftobject["Clusters"][0]["ClusterCreateTime"].'
                </dd>
                
                <dt>
                    Connect
                </dt>
                <dd class="text-primary">
                    <div class="btn-group">
                      <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Select Option <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="customMessage" 
                                title="Instructions to connect to Tableau*" 
                                data-url="../aws-resources/connection-instructions.php?sw=tableau">
                                <i class="fa fa-clone"></i> Tableau <small class="text-danger">*</small>
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="#" class="customMessage" 
                                title="Instructions to connect to SQL Workbench/J*" 
                                data-url="../aws-resources/connection-instructions.php?sw=sqlworkbench">
                                <i class="fa fa-clone"></i> SQL Workbench/J <small class="text-danger">*</small>
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="#" class="customMessage" 
                                title="Instructions to connect to other Connectors" 
                                data-url="../aws-resources/connection-instructions.php?sw=otherredshift">
                                <i class="fa fa-clone"></i> Other Connectors
                            </a>
                        </li>
                      </ul>
                    </div>
                </dd>
              </dl>
              ').'
            </div>
        </div>
        <div class="clearfix"></div><br>
        <div class="panel panel-success">
            <div class="panel-heading">RDS Details</div>
            <div class="panel-body">'.
            ( ($rds_error != null) ? 'Cannot load RDS details. <p class="text-danger"><br>Error: '.$rds_error.'</p>' :
            '<dl class="dl-horizontal">
                <dt>
                    Instance Identifier
                </dt>
                <dd class="text-primary">
                    <a href="../aws-resources/rds.php?explore=table" title="Explore redshift">
                        '.$rdsobject["DBInstances"][0]["Endpoint"]["Address"].':'.$rdsobject["DBInstances"][0]["Endpoint"]["Port"].' 
                        <i class="fa fa-link"></i>
                    </a>
                </dd>
                
                <dt>
                    Engine
                </dt>
                <dd class="text-primary">
                    '.$rdsobject["DBInstances"][0]["Engine"].'
                </dd>
                
                <dt>
                    Database Name
                </dt>
                <dd class="text-primary">
                    '.$rdsobject["DBInstances"][0]["DBName"].'
                </dd>
                
                <dt>
                    Creation Date
                </dt>
                <dd class="text-primary">
                    '.$rdsobject["DBInstances"][0]["InstanceCreateTime"].'
                </dd>
                
                <dt>
                    Connect
                </dt>
                <dd class="text-primary">
                    <div class="btn-group">
                      <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Select Option <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="customMessage" 
                                title="Instructions to connect to MySQL Workbecnk*" 
                                data-url="../aws-resources/connection-instructions.php?sw=mysqlworkbench">
                                <i class="fa fa-clone"></i> MySQL Workbench <small class="text-danger">*</small>
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="#" class="customMessage" 
                                title="Instructions to connect via Shell*" 
                                data-url="../aws-resources/connection-instructions.php?sw=shell">
                                <i class="fa fa-clone"></i> Connect Via Shell <small class="text-danger">*</small>
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="http://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/Welcome.html" target="_blank">
                               <i class="fa fa-external-link-square"></i> More on RDS 
                            </a>
                        </li>
                      </ul>
                    </div>
                </dd>
              </dl>
              ').'
            </div>
        </div>
        <div class="clearfix"></div><br>
        <div class="panel panel-primary">
            <div class="panel-heading">Datapipeline Details</div>
            <div class="panel-body">
              <dl class="dl-horizontal">
                <dt>
                    Worker Group
                </dt>
                <dd class="text-primary">
                    '._WORKER_GROUP_NAME.'
                </dd>
              </dl>  
                <a class="btn btn-warning btn-lg btn-block customMessage" 
                    data-url="../aws-resources/connection-instructions.php?sw=datapipeline" 
                    title="Run a datapipeline">
                    Run a datapipeline
                </a>
            </div>
        </div>
      </div>
      <div class="col-lg-1 col-md-1"></div>
      <div class="clearfix"></div><br>';
    include_once("../root/footer.php");
?>