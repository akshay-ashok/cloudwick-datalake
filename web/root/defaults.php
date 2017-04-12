<?php

$inifile = "../root/cloudwick.datalake.ini";
$resourceInfo = parse_ini_file($inifile,true);

define("_SCHEME","http");

define("_VERSION",$resourceInfo["defaults"]["version"]);

define("_REGION",$resourceInfo["defaults"]["region"]);
define("_ACCOUNT_ID",$resourceInfo["defaults"]["accountid"]);
define("_ADMIN",$resourceInfo["defaults"]["adminid"]);
define("_PASSWORD",$resourceInfo["defaults"]["password"]);
define("_EMAIL",$resourceInfo["defaults"]["email"]);
define("_IP",$resourceInfo["defaults"]["ipaddress"]);

define("_STACK_ID",$resourceInfo["stack"]["stackid"]);
define("_STACK_NAME",$resourceInfo["stack"]["stackname"]);
define("_STACK_UID",end(explode("-",_STACK_ID)));

define("_RDS_IDENTIFIER",$resourceInfo["rds"]["dbinstanceidentifier"]);
define("_RDS_ENDPOINT",$resourceInfo["rds"]["endpoint"]);
define("_RDS_DATABASE",$resourceInfo["rds"]["database"]);

define("_REDSHIFT_IDENTIFIER",$resourceInfo["redshift"]["clusteridentifier"]);
define("_REDSHIFT_ENDPOINT",$resourceInfo["redshift"]["endpoint"]);
define("_REDSHIFT_ROLE_ARN",$resourceInfo["redshift"]["iamrolearn"]);
define("_REDSHIFT_DATABASE",$resourceInfo["redshift"]["database"]);
define("_TASK_RUNNER_PID",$resourceInfo["taskrunner"]["taskrunnerpid"]);

define("_KIBANA_URL",$resourceInfo["elasticsearch"]["kibana"]);

define("_DYNAMODB_T_MASK",$resourceInfo["dynamodb"]["masktable"]);
define("_DYNAMODB_T_MAP",$resourceInfo["dynamodb"]["maptable"]);
define("_DYNAMODB_T_STREAM",$resourceInfo["dynamodb"]["streamtable"]);


define("_BUCKET",$resourceInfo["s3"]["bucket"]);
define("_BUCKET_ARN",$resourceInfo["s3"]["arn"]);

define("_KINESIS_STREAM_NAME",$resourceInfo["kinesis"]["streamname"]);
define("_KINESIS_STREAM_ARN","arn:aws:kinesis:"._REGION.":"._ACCOUNT_ID.":stream/"._KINESIS_STREAM_NAME."");

define("_TAG_KEY","solution");
define("_TAG_VALUE","cloudwick.datalake."._ACCOUNT_ID);


define("_CATLOG_LAMBDA_NAME","cloudwick-datalake-catlamb-"._ACCOUNT_ID."-"._STACK_UID);
define("_CATLOG_LAMBDA_ARN","arn:aws:lambda:"._REGION.":"._ACCOUNT_ID.":function:cloudwick-datalake-catlamb-"._ACCOUNT_ID."-"._STACK_UID);

define("_STREAM_LAMBDA_NAME","cloudwick-datalake-strlamb-"._ACCOUNT_ID."-"._STACK_UID);
define("_STREAM_LAMBDA_ARN","arn:aws:lambda:"._REGION.":"._ACCOUNT_ID.":function:cloudwick-datalake-strlamb-"._ACCOUNT_ID."-"._STACK_UID);

?>
