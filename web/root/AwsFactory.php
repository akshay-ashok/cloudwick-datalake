<?php
    require_once("../root/defaults.php");
    require_once("../aws/aws-autoloader.php");

    class AwsFactory{
        private $version = _VERSION;

        public function AwsFactory(){
            // no constructor defined
        }

        /**
         * @param $region
         * @return array
         */
        public function getConfig($region=null){
            $region = ($region == null ? $region = _REGION : $region);
            $sharedConfig = [
                'version' => '' . $this->version . '',
                'region' => '' . $region . ''
            ];

            return $sharedConfig;
        }

        /**
         * @param null $region
         * @return \Aws\S3\S3Client
         */
        public function getS3Client($region=null){
            $client = new Aws\S3\S3Client($this->getConfig($region));
            return $client;
        }

        /**
         * @param null $region
         * @return \Aws\Redshift\RedshiftClient
         */
        public function getRedshiftClient($region=null){
            return new Aws\Redshift\RedshiftClient($this->getConfig($region));
        }

        /**
         * @param null $region
         * @return \Aws\Rds\RdsClient
         */
        public function getRDSClient($region=null){
            return new Aws\Rds\RdsClient($this->getConfig($region));
        }

        /**
         * @param null $region
         * @return \Aws\DataPipeline\DataPipelineClient
         */
        public function getDatapipelineClient($region=null){
            return new Aws\DataPipeline\DataPipelineClient($this->getConfig($region));
        }

        /**
         * @param null $region
         * @return \Aws\DynamoDb\DynamoDbClient
         */
        public function getDynamoDBClient($region=null){
            return new Aws\DynamoDb\DynamoDbClient($this->getConfig($region));
        }

        /**
         * @param null $region
         * @return \Aws\CloudTrail\CloudTrailClient
         */
        public function getCloudTrailClient($region=null){
            return new Aws\CloudTrail\CloudTrailClient($this->getConfig($region));
        }

        /**
         * @param null $region
         * @return \Aws\Kinesis\KinesisClient
         */
        public function getKinesisClient($region=null){
            return new Aws\Kinesis\KinesisClient($this->getConfig($region));
        }

        /**
         * @param null $region
         * @return \Aws\CloudFormation\CloudFormationClient
         */
        public function getCFClient($region=null){
            return new Aws\CloudFormation\CloudFormationClient($this->getConfig($region));
        }

        public function getLambdaClient($region=null){
            return new Aws\Lambda\LambdaClient($this->getConfig($region));
        }

    }

?>