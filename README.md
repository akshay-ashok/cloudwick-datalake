
# [AWS Data Lake quick start](https://console.aws.amazon.com/cloudformation/home?region=us-east-1#/stacks/new?stackName=CWDLQuickstart&templateURL=https://s3-us-west-2.amazonaws.com/akshaypatil/aws-datalake/aws-datalake-qs.template)

This reference architecture provides a JSON template for deploying Data Lake Quick Start with [AWS CloudFormation](https://aws.amazon.com/cloudformation/).

You can launch this CloudFormation stack in the US East (N. Virginia) / US West (Oregon) Regions in your account:

### Contents:
1. Cloudformation template for one-click data lake deployment on AWS
2. Scripts for configuring the datalake infrastructure
3. AWS Resource specific components:

    3.1 - AWS Lambda function codes (python)

    3.2 - Scripts to update Elastic Search indexes
	
	3.3 - Scripts to create lambda trigger(s)

4. Repository for required software packages and services
5. PHP project for performing backend processing and web user interface

### Quick Start Reference Architecture
![Quick Start Data lake Architecture](https://github.com/akshay-ashok/cloudwick-datalake/raw/datalake-customize/images/aws-dl-qs-arch.jpg)
