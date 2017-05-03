<?php
    include_once "../root/AwsFactory.php";
    require_once("../root/ConnectionManager.php");

    $bucket = htmlspecialchars($_POST["bucketname"], ENT_QUOTES);
    $statementId = md5(microtime());
    $connectionManager = new ConnectionManager();
    $mysqlConnector = $connectionManager->getMysqlConnector();

    function addToDatabase($bucketname,$statementid,$mysqlConnector){
        $query = "INSERT 
                  INTO "._RDS_DATABASE.".buckets 
                  VALUES ('".$bucketname."','".$statementid."')
                  ";
        $result = $mysqlConnector->exec($query);
    }

    function removeFromDatabase($bucketname,$statementid,$mysqlConnector){
        $query = "DELETE 
                  FROM "._RDS_DATABASE.".buckets 
                  WHERE bucketname = '".$bucketname."' AND statementid = '".$statementid."'
                  ";
        $result = $mysqlConnector->exec($query);
    }

    if(isset($bucket)) {
        try {
            $aws = new AwsFactory();
            $s3client = $aws->getS3Client();
            $lambdaclient = $aws->getLambdaClient();

            $result = $lambdaclient->addPermission([
                'Action' => 'lambda:InvokeFunction',
                'FunctionName' => _CATLOG_LAMBDA_NAME,
                'Principal' => 's3.amazonaws.com',
                'SourceAccount' => _ACCOUNT_ID,
                'SourceArn' => 'arn:aws:s3:::'.$bucket.'',
                'StatementId' => $statementId
            ]);

            $result = $s3client->putBucketNotificationConfiguration([
                'Bucket' => $bucket,
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

            addToDatabase($bucket,$statementId,$mysqlConnector);
            print '<p class="text-success">Trigger Created for the <b>\'' . $bucket . '\'</b> Bucket</p>';
        } catch (\Aws\S3\Exception\S3Exception $ex) {
            removeFromDatabase($bucket,$statementId,$mysqlConnector);
            print '<p class="text-danger">
            Failed to create trigger on <b>\'' . $bucket . '\'</b> Bucket. <br><br>Error: ' . $ex->getAwsErrorCode() . ''.$ex->getMessage().'
        </p>';
        } catch (Exception $ex) {
            removeFromDatabase($bucket,$statementId,$mysqlConnector);
            print '<p class="text-danger">
            Failed to create trigger on <b>\'' . $bucket . '\'</b> Bucket. <br><br>Error: ' . $ex->getMessage() . '
        </p>';
        }
    } else {
        print '<p class="text-danger">
            Bucket not found
        </p>';
    }

?>
