#!/bin/bash
##Cloudformation User data configuration script
IPADDRESS=`curl ifconfig.co`
ACCOUNT_ID="$1"
REGION="$2"
ADMIN_ID="$3"
PASSWORD="$4"
EMAIL_ID="$5"
RDS_ENDPOINT="$6"
REDSHIFT_ENDPOINT="$7"
REDSHIFT_IAM_ARN="$8"
RDS_DBIDENTIFIER="$9"
RDS_DATABASE="${10}"
REDSHIFT_CLUSTERIDENTIFIER="${11}"
REDSHIFT_DATABASE="${12}"
ELASTICSEARCHEP="${13}"
BUCKET="${14}"
STACKID="${15}"
STACKPART="${16}"
STACKNAME="${17}"
STREAMNAME="${18}"
PIPELINEID_1="${19}"
PIPELINEID_2="${20}"
PIPELINEID_3="${21}"
PIPELINEID_4="${22}"
DYNAMOMAPTABLE="cloudwick-datalake-map-${1}-${16}"
DYNAMOMASKTABLE="cloudwick-datalake-mask-${1}-${16}"
DYNAMOSTREAMTABLE="cloudwick-datalake-stream-${1}-${16}"
DYNAMODBEP="https://dynamodb.${REGION}.amazonaws.com"
REDSHIFTARN="arn:aws:redshift:${REGION}:${ACCOUNT_ID}:cluster:${REDSHIFT_CLUSTERIDENTIFIER}"
TAG_KEY="solution"
TAG_VALUE="cloudwick.datalake.${ACCOUNT_ID}"
WORKERGROUP="datalakeworkergroup-${ACCOUNT_ID}-${STACKPART}"
TASKRUNNER="datalaketaskrunner-${ACCOUNT_ID}-${STACKPART}"

mkdir -p /var/www/html; chown -R apache:apache /var/www/html;
aws configure set default.region ${REGION};


REDSHIFTHOST=(${REDSHIFT_ENDPOINT//:/ })

wget -A.zip https://s3.us-east-2.amazonaws.com/_SOURCE_LOCATION_/configuration/lambdas/writetoES.zip; mkdir -p /var/www/html/lambes; unzip writetoES.zip -d /var/www/html/lambes; sed -ie "s|oldelasticsearchep|${ELASTICSEARCHEP}|g" /var/www/html/lambes/writetoES/lambda_function.py; sed -ie "s|oldawsregion|${REGION}|g;s|olddynamodbep|${DYNAMODBEP}|g;s|olddynamodbmasktable|${DYNAMOMASKTABLE}|g;s|olddynamodbmaptable|${DYNAMOMAPTABLE}|g;s|oldredshiftdbname|${REDSHIFT_DATABASE}|g;s|oldredshiftadmin|${ADMIN_ID}|g;s|oldredshiftpassword|${PASSWORD}|g;s|oldredshiftep|${REDSHIFTHOST[0]}|g;s|oldredshiftiamarn|${REDSHIFT_IAM_ARN}|g;" /var/www/html/lambes/writetoES/lambdas3dynamo.py; rm -rf writetoES.zip;cd /var/www/html/lambes/writetoES;zip -r writetoESX.zip *;aws s3 cp writetoESX.zip s3://$BUCKET/lambdas/writetoESX.zip --region $REGION --sse AES256;
wget -A.zip https://s3.us-east-2.amazonaws.com/_SOURCE_LOCATION_/configuration/lambdas/writetoDY.zip; unzip writetoDY.zip -d /var/www/html/lambes; sed -ie "s|oldawsregion|${REGION}|g;s|olddynamodbstreamtable|${DYNAMOSTREAMTABLE}|g;s|oldelasticsearchep|${ELASTICSEARCHEP}|g;" /var/www/html/lambes/writetoDY/lambda_function.py; rm -rf writetoDY.zip;cd /var/www/html/lambes/writetoDY;zip -r writetoDYN.zip *;aws s3 cp writetoDYN.zip s3://$BUCKET/lambdas/writetoDYN.zip --region $REGION --sse AES256;

#WebApp configuration
wget -A.zip https://s3.us-east-2.amazonaws.com/_SOURCE_LOCATION_/configuration/web/datalake.zip; unzip datalake.zip -d /var/www/html; chmod 777 /var/www/html/home/welcome*;
rm -rf /etc/php.ini; mv /var/www/html/configurations/php.ini /etc/php.ini;chown apache:apache /etc/php.ini; chown -R apache:apache /var/www/html;service httpd restart;

#Zeppelin configuration
wget -A.tgz http://apache.claz.org/zeppelin/zeppelin-0.7.0/zeppelin-0.7.0-bin-all.tgz; mkdir -p /var/www/html/zeppelin; tar -xf zeppelin-0.7.0-bin-all.tgz -C /var/www/html/zeppelin; chown -R apache /var/www/html/zeppelin;/var/www/html/zeppelin/zeppelin-0.7.0-bin-all/bin/zeppelin-daemon.sh start

#Sparkflows configuration
yum install -y java-devel;
wget -A.tgz http://archives.sparkflows.io/dist/sparkflows/fire/09252016/fire-1.4.0.tgz; mkdir -p /var/www/html/sparkflows; tar -xf fire-1.4.0.tgz -C /var/www/html/sparkflows; chown -R apache /var/www/html/sparkflows;
rm -rf /var/www/html/sparkflows/fire-1.4.0/conf/application.properties; mv /var/www/html/configurations/application.properties /var/www/html/sparkflows/fire-1.4.0/conf/application.properties;
cd /var/www/html/sparkflows/fire-1.4.0/; ./create-h2-db.sh; ./run-fire-server.sh start;
<<EOF

EOF

cd

#populate RDS Data
RDS=(${RDS_ENDPOINT//:/ })
mysql -u ${ADMIN_ID} -p${PASSWORD} -h ${RDS[0]} ${RDS_DATABASE} < /var/www/html/configurations/datalake.sql

#populate DynamoDB tables
mkdir -p /home/ec2-user/DynamoDB;
wget -A.json https://s3.us-east-2.amazonaws.com/_SOURCE_LOCATION_/configuration/dynamodbconf/DynamoMap.json; mv DynamoMap.json /home/ec2-user/DynamoDB/.;
wget -A.json https://s3.us-east-2.amazonaws.com/_SOURCE_LOCATION_/configuration/dynamodbconf/DynamoMask.json; mv DynamoMask.json /home/ec2-user/DynamoDB/.;
sed -ie "s|olddynamodbmaptable|${DYNAMOMAPTABLE}|g;s|olddynamodbmasktable|${DYNAMOMASKTABLE}|g;" /home/ec2-user/DynamoDB/*;

aws dynamodb batch-write-item --request-items file:///home/ec2-user/DynamoDB/DynamoMap.json
aws dynamodb batch-write-item --request-items file:///home/ec2-user/DynamoDB/DynamoMask.json

########################
mkdir -p /home/ec2-user/TaskRunner; wget -A.jar https://s3.amazonaws.com/datapipeline-us-east-1/us-east-1/software/latest/TaskRunner/TaskRunner-1.0.jar; mv TaskRunner-1.0.jar /home/ec2-user/TaskRunner/.; cd /home/ec2-user/TaskRunner; java -jar TaskRunner-1.0.jar --workerGroup=${WORKERGROUP} --region=${REGION} --logUri=s3://${BUCKET}/TaskRunnerLogs --taskrunnerId ${TASKRUNNER} > TaskRunner.out 2>&1 < /dev/null &
mkdir -p /home/ec2-user/Pipelines; wget -A.zip https://s3.us-east-2.amazonaws.com/_SOURCE_LOCATION_/configuration/pipelines/pipelines.zip; unzip pipelines.zip -d /home/ec2-user/Pipelines;
sed -ie "s|oldredshiftadmin|${ADMIN_ID}|g;s|olds3stagingloc|${BUCKET}|g;s|oldredshiftpassword|${PASSWORD}|g;s|oldredshiftep|${REDSHIFT_ENDPOINT}|g;s|oldrdsep|${RDS_ENDPOINT}|g;s|oldrdsadmin|${ADMIN_ID}|g;s|oldrdspassword|${PASSWORD}|g;s|oldworkergroup|${WORKERGROUP}|g;s|oldredshiftdbname|${REDSHIFT_DATABASE}|g;s|oldrdsdbname|${RDS_DATABASE}|g;" /home/ec2-user/Pipelines/*;
TaskRunnerPID=`ps aux |grep TaskRunner|grep -v grep|awk -F" " '{print $2}'`


aws datapipeline put-pipeline-definition --pipeline-id ${PIPELINEID_1} --pipeline-definition file:///home/ec2-user/Pipelines/dl_pipeline_def_1.json;
aws datapipeline activate-pipeline --pipeline-id ${PIPELINEID_1};

aws datapipeline put-pipeline-definition --pipeline-id ${PIPELINEID_2} --pipeline-definition file:///home/ec2-user/Pipelines/dl_pipeline_def_2.json;
aws datapipeline activate-pipeline --pipeline-id ${PIPELINEID_2};

aws datapipeline put-pipeline-definition --pipeline-id ${PIPELINEID_3} --pipeline-definition file:///home/ec2-user/Pipelines/dl_pipeline_def_3.json;
aws datapipeline activate-pipeline --pipeline-id ${PIPELINEID_3};

aws datapipeline put-pipeline-definition --pipeline-id ${PIPELINEID_4} --pipeline-definition file:///home/ec2-user/Pipelines/dl_pipeline_def_4.json;
aws datapipeline activate-pipeline --pipeline-id ${PIPELINEID_4};

#Create S3 objects
aws s3api put-object --bucket $BUCKET --key uploads/original/ --region $REGION --server-side-encryption AES256;
aws s3api put-object --bucket $BUCKET --key uploads/masked/ --region $REGION --server-side-encryption AES256;
aws s3api put-object --bucket $BUCKET --key uploads/unmasked/ --region $REGION --server-side-encryption AES256;
aws s3api put-object --bucket $BUCKET --key uploads/unprocessed/ --region $REGION --server-side-encryption AES256;

#Create ElasticSearch Indices
curl -XPUT https://${ELASTICSEARCHEP}/edi-270 -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/mappings/edi-270-mapping.json;
curl -XPUT https://${ELASTICSEARCHEP}/edi-271 -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/mappings/edi-271-mapping.json;
curl -XPUT https://${ELASTICSEARCHEP}/metadata-store -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/mappings/metadata-store-mapping.json;

#Create kibana index patterns
curl -XPUT https://${ELASTICSEARCHEP}/.kibana/index-pattern/metadata-store -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/indexes/metadata-store-index.json;
curl -XPUT https://${ELASTICSEARCHEP}/.kibana/index-pattern/edi-270 -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/indexes/edi-270-index.json;
curl -XPUT https://${ELASTICSEARCHEP}/.kibana/index-pattern/edi-271 -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/indexes/edi-270-index.json;
curl -XPUT https://${ELASTICSEARCHEP}/.kibana/config/5.1.1 -H "Content-Type: application/json" -d '{"defaultIndex" : "metadata-store"}';


#Mysql configuration
setenforce 0;chkconfig httpd on;chkconfig mysqld on;
/usr/bin/mysql_secure_installation<<EOF

y
${PASSWORD}
${PASSWORD}
y
y
y
y
EOF

mysql -u root -p${PASSWORD} -e "CREATE DATABASE datalake;"
mysql -u root -p${PASSWORD} -e "CREATE USER ${ADMIN_ID}@localhost IDENTIFIED BY '${PASSWORD}';"
mysql -u root -p${PASSWORD} -e "CREATE table datalake.user(username varchar(200),password varchar(200));"
mysql -u root -p${PASSWORD} -e "INSERT INTO datalake.user(username,password) VALUES ('${ADMIN_ID}',MD5('${PASSWORD}'));"
mysql -u root -p${PASSWORD} -e "GRANT ALL PRIVILEGES ON *.* TO '${ADMIN_ID}'@'localhost';"
mysql -u root -p${PASSWORD} -e "FLUSH PRIVILEGES;"



cat <<EOT >> /var/www/html/root/cloudwick.datalake.ini
[defaults]
version="latest"
region="${REGION}"
accountid="${ACCOUNT_ID}"
adminid="${ADMIN_ID}"
password="${PASSWORD}"
email="${EMAIL_ID}"
ipaddress="${IPADDRESS}"

[stack]
stackid="${STACKID}"
stackname="${STACKNAME}"

[rds]
dbinstanceidentifier="${RDS_DBIDENTIFIER}"
endpoint="${RDS_ENDPOINT}:3306"
database="${RDS_DATABASE}"

[redshift]
clusteridentifier="${REDSHIFT_CLUSTERIDENTIFIER}"
endpoint="${REDSHIFT_ENDPOINT}:5439"
iamrolearn="${REDSHIFT_IAM_ARN}"
database="${REDSHIFT_DATABASE}"

[elasticsearch]
kibana="https://${ELASTICSEARCHEP}/_plugin/kibana/"
elasticsearch="${ELASTICSEARCHEP}"

[dynamodb]
masktable="${DYNAMOMAPTABLE}"
maptable="${DYNAMOMASKTABLE}"
streamtable="${DYNAMOSTREAMTABLE}"

[s3]
bucket="${BUCKET}"
arn="arn:aws:s3:${REGION}:${ACCOUNT_ID}:${BUCKET}"

[kinesis]
streamname="${STREAMNAME}"

[taskrunner]
taskrunnerpid="${TaskRunnerPID}"
EOT

#Sending out email to the Administrator
sendmail ${EMAIL_ID} <<EOM
From: DataLake@cloudwick.com
Subject: Welcome to Cloudwick's DataLake Quickstart
Content-Type: multipart/mixed; boundary="-unique-str"

---unique-str
Content-Type: text/html
Content-Disposition: inline

Please click http://${IPADDRESS}  to visit your datalake webUI

---unique-str
EOM
