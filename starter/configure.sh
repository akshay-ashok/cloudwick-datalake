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
WAITCONDITION="${18}"
REDSHIFTARN="arn:aws:redshift:${REGION}:${ACCOUNT_ID}:cluster:${REDSHIFT_CLUSTERIDENTIFIER}"
TAG_KEY="solution"
TAG_VALUE="cloudwick.datalake.${ACCOUNT_ID}"
WORKERGROUP="datalakeworkergroup-${ACCOUNT_ID}-${STACKPART}"
TASKRUNNER="datalaketaskrunner-${ACCOUNT_ID}-${STACKPART}"

mkdir -p /var/www/html; chown -R apache:apache /var/www/html;
aws configure set default.region ${REGION};


# Setup catalog lambda code
wget -A.zip https://github.com/pogaku9/cloudwick-datalake/raw/datalake-customize/lambdas/writetoES.zip; mkdir -p /var/www/html/lambes; unzip writetoES.zip -d /var/www/html/lambes; sed -ie "s|oldelasticsearchep|${ELASTICSEARCHEP}|g" /var/www/html/lambes/writetoES/lambda_function.py; rm -rf writetoES.zip;cd /var/www/html/lambes/writetoES;zip -r writetoESX.zip *;aws s3 cp writetoESX.zip s3://$BUCKET/lambdas/writetoESX.zip --region $REGION --sse AES256;

/opt/aws/bin/cfn-signal -e 0 ${WAITCONDITION}

##########WebApp configuration########################################
wget -A.zip https://github.com/pogaku9/cloudwick-datalake/raw/datalake-customize/web/datalake.zip; unzip datalake.zip -d /var/www/html; chmod 777 /var/www/html/home/welcome*;
rm -rf /etc/php.ini; mv /var/www/html/configurations/php.ini /etc/php.ini;chown apache:apache /etc/php.ini; chown -R apache:apache /var/www/html;service httpd restart;

#Zeppelin configuration
wget -A.tgz http://apache.claz.org/zeppelin/zeppelin-0.7.0/zeppelin-0.7.0-bin-all.tgz; mkdir -p /var/www/html/zeppelin; tar -xf zeppelin-0.7.0-bin-all.tgz -C /var/www/html/zeppelin; chown -R apache /var/www/html/zeppelin;/var/www/html/zeppelin/zeppelin-0.7.0-bin-all/bin/zeppelin-daemon.sh start

#Sparkflows configuration
yum install -y java-devel;
wget -A.tgz http://archives.sparkflows.io/dist/sparkflows/fire/09252016/fire-1.4.0.tgz; mkdir -p /var/www/html/sparkflows; tar -xf fire-1.4.0.tgz -C /var/www/html/sparkflows; chown -R apache /var/www/html/sparkflows;
rm -rf /var/www/html/sparkflows/fire-1.4.0/conf/application.properties; mv /var/www/html/configurations/application.properties /var/www/html/sparkflows/fire-1.4.0/conf/application.properties;
cd /var/www/html/sparkflows/fire-1.4.0/; ./create-h2-db.sh; ./run-fire-server.sh start > sparkflows.out 2>&1 < /dev/null &

<<EOF

EOF

cd

######TaskRunner#######################################################
mkdir -p /home/ec2-user/TaskRunner; wget -A.jar https://s3.amazonaws.com/datapipeline-us-east-1/us-east-1/software/latest/TaskRunner/TaskRunner-1.0.jar; mv TaskRunner-1.0.jar /home/ec2-user/TaskRunner/.; cd /home/ec2-user/TaskRunner; java -jar TaskRunner-1.0.jar --workerGroup=${WORKERGROUP} --region=${REGION} --logUri=s3://${BUCKET}/TaskRunnerLogs --taskrunnerId ${TASKRUNNER} > TaskRunner.out 2>&1 < /dev/null &


#Create ElasticSearch Indices
curl -XPUT https://${ELASTICSEARCHEP}/metadata-store -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/mappings/metadata-store-mapping.json;
curl -XPUT https://${ELASTICSEARCHEP}/cloudtraillogs -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/mappings/cloudtraillogs-mapping.json;

#Create kibana index patterns
curl -XPUT https://${ELASTICSEARCHEP}/.kibana/index-pattern/metadata-store -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/indexes/metadata-store-index.json;
curl -XPUT https://${ELASTICSEARCHEP}/.kibana/index-pattern/cloudtraillogs -H "Content-Type: application/json" --data @/var/www/html/configurations/kibana/indexes/cloudtraillogs-index.json;
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
mysql -u root -p${PASSWORD} -e "CREATE table datalake.buckets(bucketname varchar(200),statementid varchar(200), PRIMARY KEY (bucketname));" 
mysql -u root -p${PASSWORD} -e "INSERT INTO datalake.user(username,password) VALUES ('${ADMIN_ID}',MD5('${PASSWORD}'));"
mysql -u root -p${PASSWORD} -e "GRANT ALL PRIVILEGES ON *.* TO '${ADMIN_ID}'@'localhost';"
mysql -u root -p${PASSWORD} -e "FLUSH PRIVILEGES;"



cat <<EOT >> /var/www/html/root/datalake.ini
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

[s3]
bucket="${BUCKET}"
arn="arn:aws:s3:${REGION}:${ACCOUNT_ID}:${BUCKET}"

[kinesis]
streamname="${STREAMNAME}"

EOT


#attach iam role to redshift
curl http://${IPADDRESS}/scripts/attach-iam-role-to-redshift.php;

#create kibana visualizations
curl http://${IPADDRESS}/scripts/kibana-visualizations.php;

#wait for lambdas to be created
#sleep 5m

#Sending out email to the Administrator
curl http://${IPADDRESS}/scripts/send-completion-email.php --data "region=${REGION}&username=${ADMIN_ID}&email=${EMAIL_ID}&ip=${IPADDRESS}";

