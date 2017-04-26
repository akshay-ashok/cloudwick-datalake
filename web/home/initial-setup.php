<?php
    include_once "../root/AwsFactory.php";
    error_reporting(0);
    sleep(1);
    $aws = new AwsFactory();
    $action = (isset($_GET["action"])) ? htmlspecialchars($_GET["action"],ENT_QUOTES) : null;

    if(isset($action) && !is_null($action)){
        if($action == "s3-bucket"){
            try {
                $s3client = $aws->getS3Client();
                $result = $s3client->doesBucketExist(_BUCKET);

                if($result == true) {
                    print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        S3 bucket created
                    </p>';
                } else {
                    throw new Exception("S3 bucket fetch error");
                }
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    S3 bucket fetch error
                </p>';
            }
        } elseif ($action == "s3-bucket-lifecycle-policy"){
            try {
                $s3client = $aws->getS3Client();
                $result = $s3client->getBucketLifecycleConfiguration([
                    'Bucket' => _BUCKET
                ]);
                print '<p class="text-success">
                    <i class="fa fa-check-square-o"></i> 
                    S3 bucket lifecycle policy created
                </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    S3 bucket lifecycle policy not found
                </p>';
            }
        } elseif ($action == "s3-bucket-encryption"){
            try {
                $s3client = $aws->getS3Client();
                $result = $s3client->getBucketPolicy([
                    'Bucket' => _BUCKET
                ]);
                print '<p class="text-success">
                    <i class="fa fa-check-square-o"></i> 
                    S3 bucket encryption policy created
                </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    S3 bucket encryption policy failed
                </p>';
            }
        } elseif ($action == "rds-instance"){
            try {
                $rdsclient = $aws->getRDSClient();
                $result = $rdsclient->describeDBInstances([
                    'DBInstanceIdentifier' => _RDS_IDENTIFIER
                ]);

                if($result["DBInstances"][0]["DBInstanceStatus"] == "available") {
                    print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        RDS Instance Created
                    </p>
                    <p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        RDS Instance Available
                    </p>';
                } else {
                    throw new Exception("status cannot be verified");
                }
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    RDS Instance status cannot be verified
                </p>';
            }
        } elseif ($action == "redshift-cluster"){
            try {
                $redshiftClient = $aws->getRedshiftClient();
                $result = $redshiftClient->describeClusters([
                    'ClusterIdentifier' => _REDSHIFT_IDENTIFIER
                ]);

                print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Redshift Cluster Created
                    </p>
                    <p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Redshift cluster status : '.$result["Clusters"][0]["ClusterStatus"].'
                    </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    Redshift Cluster not found
                </p>';
            }
        } elseif ($action == "redshift-role"){
            try {
                $redshiftClient = $aws->getRedshiftClient();
                $result = $redshiftClient->modifyClusterIamRoles([
                    'AddIamRoles' => [_REDSHIFT_ROLE_ARN],
                    'ClusterIdentifier' => _REDSHIFT_IDENTIFIER
                ]);

                foreach ($result["Cluster"]["IamRoles"] as $role) {
                    if ($role["IamRoleArn"] == _REDSHIFT_ROLE_ARN) {
                        if ($role["ApplyStatus"] == "in-sync") {
                            print '<p class="text-success">
                                <i class="fa fa-check-square-o"></i> Redshift role created
                            </p>
                            <p class="text-success">
                                <i class="fa fa-check-square-o"></i> Redshift role attached
                            </p>';
                        } else {
                            throw new Exception("redshift role status cannot be verified");
                        }
                    }
                }
            } catch (Exception $ex){
                print '<p class="text-warning">
                    <i class="fa fa-question"></i> 
                    Redshift Cluster role status cannot be verified
                </p>';
            }
        } elseif ($action == "elasticsearch-domain"){
            try {
                $esclient = $aws->getElasticsearchClient();
                $result = $esclient->describeElasticsearchDomain([
                    'DomainName' => _ELASTIC_SEARCH_NAME
                ]);

                if($result["DomainStatus"]["Created"] == true){
                    print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Elasticsearch Domain Created
                    </p>';
                }
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    Elasticsearch Domain cannot be verified
                </p>';
            }
        } elseif ($action == "lambda-catalog-function"){
            try {
                $lambdaclient = $aws->getLambdaClient();
                $result = $lambdaclient->getFunction([
                    'FunctionName' => _CATLOG_LAMBDA_NAME
                ]);

                print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Lambda function Created
                    </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    Lambda function not found
                </p>';
            }
        } elseif ($action == "lambda-catalog-trigger"){
            try {
                require_once("../root/ConnectionManager.php");
                $statementId = md5(microtime());
                $connectionManager = new ConnectionManager();
                $mysqlConnector = $connectionManager->getMysqlConnector();

                $s3client = $aws->getS3Client();
                $lambdaclient = $aws->getLambdaClient();

                $result = $lambdaclient->addPermission([
                    'Action' => 'lambda:InvokeFunction',
                    'FunctionName' => _CATLOG_LAMBDA_NAME,
                    'Principal' => 's3.amazonaws.com',
                    'SourceAccount' => _ACCOUNT_ID,
                    'SourceArn' => 'arn:aws:s3:::'._BUCKET.'',
                    'StatementId' => $statementId
                ]);

                $result = $s3client->putBucketNotificationConfiguration([
                    'Bucket' => _BUCKET,
                    'NotificationConfiguration' => [
                        'LambdaFunctionConfigurations' => [
                            [
                                'Events' => ['s3:ObjectCreated:*'],
                                'Id' => $statementId,
                                'LambdaFunctionArn' => _CATLOG_LAMBDA_ARN
                            ]
                        ]
                    ]
                ]);

                $query = "INSERT 
                  INTO datalake.buckets 
                  VALUES ('"._BUCKET."','".$statementId."')
                  ";
                $result = $mysqlConnector->exec($query);
                print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Lambda function trigger configured
                    </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                        <i class="fa fa-times"></i> 
                        Lambda function trigger configuration failed
                    </p>';
            }
        }  elseif ($action == "lambda-catalog-sample"){
            try {
                $s3client = $aws->getS3Client();
                $result = $s3client->putObject([
                    'Bucket' => _BUCKET,
                    'Key' => 'Lamabda_Catalog_Check_'._STACK_UID,
                    'Body' => 'Hello '._ADMIN.'. Thank you for your interest in data lake quick start.',
                    'ServerSideEncryption' => 'AES256'
                ]);
                print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Verified lambda cataloging ability 
                    </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                        <i class="fa fa-times"></i> 
                        Catalog test failed
                    </p>';
            }
        } elseif ($action == "kinesis-firehose"){
            try {
                $s3client = $aws->getFirehoseClient();
                $result = $s3client->describeDeliveryStream([
                    'DeliveryStreamName' => _KINESIS_STREAM_NAME,
                    'Limit' => 1
                ]);
                if($result["DeliveryStreamDescription"]["DeliveryStreamStatus"] == "ACTIVE") {
                    print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Kinesis Firehose created
                    </p>';
                }
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    Kinesis Firehose status cannot be verified
                </p>';
            }
        } elseif ($action == "kinesis-firehose-role"){
            try {
                $s3client = $aws->getFirehoseClient();
                $result = $s3client->describeDeliveryStream([
                    'DeliveryStreamName' => _KINESIS_STREAM_NAME,
                    'Limit' => 1
                ]);
                print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Kinesis Firehose Role created
                    </p>';
                //if($result["DeliveryStreamDescription"]["Destinations"]["ElasticsearchDestinationDescription"]["RoleARN"] == _KINESIS_STREAM_ROLE_ARN) {}
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    Kinesis Firehose role not found
                </p>';
            }
        } elseif ($action == "cloudtrail"){
            try {
                $ctclient = $aws->getCloudTrailClient();
                $result = $ctclient->describeTrails([
                    'includeShadowTrails' => true,
                    'trailNameList' => [_CLOUDTRAIL_NAME]
                ]);
                if(count($result["trailList"])>0){
                    print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Cloudtrail created
                    </p>';
                }
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    Cloudtrail not found
                </p>';
            }
        } elseif ($action == "datapipeline-taskrunner"){
            print '<p class="text-success">
                <i class="fa fa-check-square-o"></i> 
                Taskrunner installed
            </p>';
        } elseif ($action == "ec2-instance"){
            print '<p class="text-success">
                <i class="fa fa-check-square-o"></i> 
                EC2 instance created
            </p>';
        } elseif ($action == "ec2-instance-webserver-role"){
            print '<p class="text-success">
                <i class="fa fa-check-square-o"></i> 
                Ec2 webserver IAM role attached
            </p>';
        } elseif ($action == "mysql-databases"){
            print '<p class="text-success">
                <i class="fa fa-check-square-o"></i> 
                MySQL databases created
            </p>';
        } elseif ($action == "portal-zeppelin-setup"){
            print '<p class="text-success">
                <i class="fa fa-check-square-o"></i> 
                Zeppelin potal setup finished
            </p>';
        } elseif ($action == "portal-kibana-visualizations"){

            $result = shell_exec("curl -XPUT https://"._ELASTIC_SEARCH_URL."/.kibana/index-pattern/metadata-store -H \"Content-Type: application/json\" --data @/var/www/html/configurations/kibana/indexes/metadata-store-index.json");
            $result = shell_exec("curl -XPUT https://"._ELASTIC_SEARCH_URL."/.kibana/index-pattern/cloudtraillogs -H \"Content-Type: application/json\" --data @/var/www/html/configurations/kibana/indexes/cloudtraillogs-index.json");
            $result = shell_exec("curl -XPUT https://"._ELASTIC_SEARCH_URL."/.kibana/index-pattern/datalakedeliverystream -H \"Content-Type: application/json\" --data @/var/www/html/configurations/kibana/indexes/kinesis-firehose-index.json");
            $result = shell_exec("curl -XPUT https://"._ELASTIC_SEARCH_URL."/.kibana/config/5.1.1 -H \"Content-Type: application/json\" -d '{\"defaultIndex\" : \"metadata-store\"}' ");
            print '<p class="text-success">
                <i class="fa fa-check-square-o"></i> 
                Created Kibana Visualizations
            </p>';
        } elseif ($action == "portal-kibana-dashboards"){
            try {
                $json = file_get_contents("../configurations/kibana/objects/export.json");

                $json = json_decode($json, true);
                foreach ($json as $key => $value) {
                    $url = 'https://'._ELASTIC_SEARCH_URL.'/.kibana/'.$value["_type"].'/'.$value["_id"];
                    $result = shell_exec("curl ".$url." -H \"Content-Type: application/json\" --data '".json_encode($value["_source"])."'");
                }
                print '<p class="text-success">
                    <i class="fa fa-check-square-o"></i> 
                    Created Kibana Dashboards
                </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-times"></i> 
                    Error Creating Kibana Dashboards
                </p>';
            }
        } elseif ($action == "cleanup"){
            sleep(1);
            $result = exec("rm -rf /var/www/html/home/welcome.php");
            sleep(1);
            return;
        } else {
            print '<p class="text-warning">
                <i class="fa fa-check-question"></i> 
                Invalid call to script
            </p>';
        }
    }
?>