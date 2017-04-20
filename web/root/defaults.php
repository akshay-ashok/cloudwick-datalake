<?php

$inifile = "../root/datalake.ini";
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
define("_WORKER_GROUP_NAME","datalakeworkergroup-"._ACCOUNT_ID."-"._STACK_UID);

define("_RDS_IDENTIFIER",$resourceInfo["rds"]["dbinstanceidentifier"]);
define("_RDS_ENDPOINT",$resourceInfo["rds"]["endpoint"]);
define("_RDS_DATABASE",$resourceInfo["rds"]["database"]);

define("_REDSHIFT_IDENTIFIER",$resourceInfo["redshift"]["clusteridentifier"]);
define("_REDSHIFT_ENDPOINT",$resourceInfo["redshift"]["endpoint"]);
define("_REDSHIFT_ROLE_ARN",$resourceInfo["redshift"]["iamrolearn"]);
define("_REDSHIFT_DATABASE",$resourceInfo["redshift"]["database"]);

define("_KIBANA_URL",$resourceInfo["elasticsearch"]["kibana"]);
define("_ELASTIC_SEARCH_URL",$resourceInfo["elasticsearch"]["elasticsearch"]);

define("_BUCKET",$resourceInfo["s3"]["bucket"]);
define("_BUCKET_ARN",$resourceInfo["s3"]["arn"]);

define("_KINESIS_STREAM_NAME",$resourceInfo["kinesis"]["streamname"]);

define("_TAG_KEY","solution");
define("_TAG_VALUE","datalake-"._ACCOUNT_ID."-"._STACK_UID);


define("_CATLOG_LAMBDA_NAME","datalake-catlamb-"._ACCOUNT_ID."-"._STACK_UID);
define("_CATLOG_LAMBDA_ARN","arn:aws:lambda:"._REGION.":"._ACCOUNT_ID.":function:datalake-catlamb-"._ACCOUNT_ID."-"._STACK_UID);


?>
