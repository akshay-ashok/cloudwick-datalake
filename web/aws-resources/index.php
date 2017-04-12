<?php
    include_once("../root/header.php");
    include_once("../root/AwsFactory.php");
    //include_once("../root/defaults.php");
    checkSession();

    $aws = new AwsFactory();

    $s3Client = $aws->getS3Client();
    $redshiftClient = $aws->getRedshiftClient();
    $rdsClient = $aws->getRDSClient();
   // $datapipelineClient = $aws->getDatapipelineClient();

    $s3_error = null;
    $rds_error = null;
    $redshift_error = null;

    $s3object = null;
    $redshiftobject = null;
    $rdsobject = null;

    $bucketcreationdate = "";
    $bucketname = "";

    try {
        $s3object = $s3Client->listBuckets([]);
        foreach ($s3object["Buckets"] as $bucket) {
            if ($bucket["Name"] === _BUCKET) {
                $bucketname = $bucket["Name"];
                $bucketcreationdate = $bucket["CreationDate"];
            }
        }
    } catch (\Aws\S3\Exception\S3Exception $ex){
        $s3_error = $ex->getMessage();
    } catch (Exception $ex){
        $s3_error = $ex->getMessage();
    }

    try {
        $redshiftobject = $redshiftClient->describeClusters([
            'ClusterIdentifier' => _REDSHIFT_IDENTIFIER,
            'TagKeys' => [_TAG_KEY],
            'TagValues' => [_TAG_VALUE]
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
            <li><button class="btn btn-primary" type="button"><span class="badge"><b><span class="glyphicon glyphicon-tags"></span> Tags</b></span> '._TAG_KEY.' => '._TAG_VALUE.'</button></li>
            <li><button class="btn btn-primary" type="button"><span class="badge"><b><span class="glyphicon glyphicon-map-marker"></span> Region</b></span> '._REGION.'</button></li>
            <li><button class="btn btn-primary" type="button"><span class="badge"><b><span class="glyphicon glyphicon-cog"></span> Resource Type</b></span> All</button></li>
        </ul>
        <hr></hr>
        <div class="panel panel-default">
            <div class="panel-heading">S3 Details</div>
            <div class="panel-body">'.
            (($s3_error != null) ? 'Unable to reach S3 Bucket. <p class="text-danger"><br>Error: '.$s3_error.'</p>' :
              '<dl class="dl-horizontal">
                <dt>S3 Bucket Name</dt>
                <dd class="text-primary"><a href="../s3/index.php?action=listObjects&bucket='.$bucketname.'" title="Explore '.$bucketname.' Bucket">'.$bucketname.' <span class="glyphicon glyphicon-share"></span></a></dd>
                
                <dt>Creation Date</dt>
                <dd class="text-primary">'.$bucketcreationdate.'</dd>
              </dl>').'              
            </div>
        </div>
        <div class="clearfix"></div><br>
        <div class="panel panel-info">
            <div class="panel-heading">Redshift Details</div>
            <div class="panel-body">'.
            ( ($redshift_error != null) ? 'Cannot load Redshift details. <p class="text-danger"><br>Error: '.$redshift_error.'</p>' :
              '<dl class="dl-horizontal">
                <dt>Cluster Identifier</dt>
                <dd class="text-primary">'.$redshiftobject["Clusters"][0]["ClusterIdentifier"].'</dd>
                
                <dt>Endpoint</dt>
                <dd class="text-primary"><a href="../aws-resources/redshift.php?explore=table" title="Explore redshift">'.$redshiftobject["Clusters"][0]["Endpoint"]["Address"].':'.$redshiftobject["Clusters"][0]["Endpoint"]["Port"].' <span class="glyphicon glyphicon-share"></span></a></dd>
                
                <dt>NodeType</dt>
                <dd class="text-primary">'.$redshiftobject["Clusters"][0]["NodeType"].'</dd>
                
                <dt>Database Name</dt>
                <dd class="text-primary">'.$redshiftobject["Clusters"][0]["DBName"].'</dd>
                
                <dt>Creation Date</dt>
                <dd class="text-primary">'.$redshiftobject["Clusters"][0]["ClusterCreateTime"].'</dd>
              </dl>').'
            </div>
        </div>
        <div class="clearfix"></div><br>
        <div class="panel panel-success">
            <div class="panel-heading">DynamoDB Details</div>
            <div class="panel-body">
              <dl class="dl-horizontal">
                <dt>DynamoDB Explore</dt>
                <dd class="text-primary"><a href="../aws-resources/dynamodb.php">Explore DynamoDB  <span class="glyphicon glyphicon-share"></span></a></dd>
              </dl>
              
            </div>
        </div>
        <div class="clearfix"></div><br>
        <div class="panel panel-primary">
            <div class="panel-heading">RDS Details</div>
            <div class="panel-body">'.
            ( ($rds_error != null) ? 'Cannot load RDS details. <p class="text-danger"><br>Error: '.$rds_error.'</p>' :
            '<dl class="dl-horizontal">
                <dt>Instance Identifier</dt>
                <dd class="text-primary"><a href="../aws-resources/rds.php?explore=table" title="Explore redshift">'.$rdsobject["DBInstances"][0]["Endpoint"]["Address"].':'.$rdsobject["DBInstances"][0]["Endpoint"]["Port"].' <span class="glyphicon glyphicon-share"></span></a></dd>
                
                <dt>Engine</dt>
                <dd class="text-primary">'.$rdsobject["DBInstances"][0]["Engine"].'</dd>
                
                <dt>Database Name</dt>
                <dd class="text-primary">'.$rdsobject["DBInstances"][0]["DBName"].'</dd>
                
                <dt>Creation Date</dt>
                <dd class="text-primary">'.$rdsobject["DBInstances"][0]["InstanceCreateTime"].'</dd>
              </dl>').'
            </div>
        </div>
        <div class="clearfix"></div><br>
        <div class="panel panel-danger">
            <div class="panel-heading">Lambda Details</div>
            <div class="panel-body">
              <dl class="dl-horizontal">
                <dt>Lambda Identifier</dt>
                <dd class="text-primary"><-- identifier --></dd>
                
                <dt>Detail</dt>
                <dd class="text-primary">detail description</dd>
              </dl>
              
            </div>
        </div>
      </div>
      <div class="col-lg-1 col-md-1"></div>
      <div class="clearfix"></div><br>';
    include_once("../root/footer.php");
?>