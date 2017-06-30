#quickstart-data-lake-cloudwick
=======
# [AWS Data Lake quick start](https://console.aws.amazon.com/cloudformation/home?region=us-east-1#/stacks/new?stackName=CWDLQuickstart&templateURL=https://s3-us-west-2.amazonaws.com/akshaypatil/aws-datalake/aws-datalake-qs.template)

This reference architecture provides a JSON template for deploying Data Lake Quick Start with [AWS CloudFormation](https://aws.amazon.com/cloudformation/).

You can launch this CloudFormation stack in the US East (N. Virginia) / US West (Oregon) Regions in your account.

## Data Lake Solution on the AWS Cloud

### Quick Start Reference Deployment

#### **Overview**

**Data Lake on AWS** A Data Lake is a storage that holds a large amount of raw data in its native format until it is needed and Data Lake Solution is an integration of various Amazon Web Services (AWS) Cloud components to meet business requirements. This Quick Start reference deployment will help you rapidly build a Data Lake Solution by automating configuration and deployment tasks. [](https://aws.amazon.com/quickstart/)Quick Starts are automated reference deployments that use AWS CloudFormation templates to launch, configure, and run the AWS compute, network, storage, and other services required to deploy a specific workload on AWS. **Cost and Licenses** This deployment launches Data Lake Solution automatically into a configuration of your choice. You are responsible for the cost of the AWS services used while running this Quick Start reference deployment. There is no additional cost for using the Quick Start. The cost will vary depending on the storage and compute configuration of the cluster you deploy. See the pricing pages for each AWS service you will be using for full details. **AWS Services** The core AWS components used by this Quick Start include the following AWS services. (If you are new to AWS, see the Getting Started section of the AWS documentation.)

*   [Amazon EC2](https://aws.amazon.com/documentation/ec2/) – The Amazon Elastic Compute Cloud (Amazon EC2) service enables you to launch virtual machine instances with a variety of operating systems. You can choose from existing Amazon Machine Images (AMIs) or import your own virtual machine images
*   [Amazon VPC](https://aws.amazon.com/documentation/vpc/) – The Amazon Virtual Private Cloud (Amazon VPC) service lets you provision a private, isolated section of the AWS Cloud where you can launch AWS services and other resources in a virtual network that you define. You have complete control over your virtual networking environment, including selection of your own IP address range, subnet creation, and configuration of route tables and network gateways.
*   [AWS CloudFormation](https://aws.amazon.com/documentation/cloudformation/) – AWS CloudFormation gives you an easy way to create and manage a collection of related AWS resources, and provision and update them in an orderly and predictable way. You use a template to describe all the AWS resources (e.g., Amazon EC2 instances) that you want. You don't have to create and configure the resources or figure out dependencies; AWS CloudFormation handles all of that.
*   [Amazon RDS](https://aws.amazon.com/documentation/rds/) – Amazon Relational Database Service (Amazon RDS) makes it easy to set up, operate, and scale MySQL deployments in the cloud. With Amazon RDS, you can deploy scalable MySQL deployments in minutes with cost-efficient and resizable hardware capacity.
*   [IAM](https://aws.amazon.com/documentation/iam/) – AWS Identity and Access Management (IAM) enables you to securely control access to AWS services and resources for your users. With IAM, you can manage users, security credentials such as access keys, and permissions that control which AWS resources users can access, from a central location.
*   [Amazon Redshift](#) – AWS Redshift makes it simple and cost-effective to analyze all your data using standard SQL and your existing Business Intelligence (BI) tools.
*   [Amazon Simple Storage Service (S3)](#) – Central storage location where Data Lake is stored
*   [Lambda](#) – Lambda is used to run code without provisioning or manage servers which can be triggered based on some event.
*   [Amazon Kinesis](#) – AWS Kinesis makes it simple to analyze stream data and provide ability to build your custom streaming data applications for specialized needs.
*   [Amazon Elasticsearch](#) – Amazon Elasticsearch makes it easy to deploy, operate, and scale Elasticsearch for log analytics, full text search, application monitoring and more
*   [Amazon CloudTrail](#) – AWS CloudTrail enables governance, compliance, operational auditing and risk auditing of your AWS account. With CloudTail, you can log, continuously monitor and retain events related to API calls across your AWS infrastructure.
*   [Kibana](#) – kibana is a web interface for elasticsearch and provides visualization capabilities on top of the content indexed on an elasticsearch cluster.

**Architecture** Deploying this Quick Start with default parameters for end-to-end deployment builds the following Data Lake environment in AWS cloud. 

![Architecture Diagram](/images/aws-dl-qs-arch.jpg "Architecture Diagram")

### **Deployment Steps**

**Step 1:** Prepare an AWS Account If you dont already one at [http://aws.amazon.com](http://aws.amazon.com) by following on screen instructions. **Step 2:** Launch the Quick Start

1.  Deploy the AWS CloudFormation template into your AWS account <stack link="">. This stack takes 20-25 minutes to create. You can also download the template to use it as a starting point for your own implementation.</stack>
2.  Open AWS account and go to **CloudFormation** under AWS Services.
3.  On the next page Click on **Create Stack**.
4.  On the **Select Template** page, **Choose File** option to upload the template, keep the default setting for the template URL and then choose **Next**.
5.  On the Specify Details page, review the parameters for the template. Enter values for the parameters require input. For all other parameters, you can customize the default settings provided by the template.  
    **Required parameters to be filled by user**  

    *   Stack name
    *   Administrator Email
    *   Database Password  
    **Default parameters**  

    *   Administrator Name : AdminName
    *   Database Name: datalake
    *   Database User Name: admin
    *   RDS database class: db.t2.small **[** Options: db.t2.micro, db.t2.medium, db..t2.large, db.m4.large, db.m4.xlarge, db.m4.2xlarge, db.m4.4xlarge, db.m4.10xlarge **]**
    *   RDS database storage: 5
    *   Redshift cluster type: single-node **[** Options: multi-node **]**
    *   Redshift node class: dc1.large **[** Options: dc1.large, dw.hs1.xlarge, dw1.xlarge, dw1.8xlarge, dw2.large, dw2.8xlarge **]**
    *   NumberOfNodes: 1
    *   EC2 webserver class: m1.medium **[** Options: m1.large, m1.xlarge, m2.xlarge, m2.2xlarge, m2.4xlarge, m3.medium, m3.large, m3.xlarge, m3.2xlarge, c1.medium, c1.xlarge, c3.large, c3.xlarge, c3.2xlarge, c3.4xlarge, c3.8xlarge **]**
6.  On the **Options** page choose Tags, Permissions and Advanced Options if required you can leave it default.
7.  On the **Review page** check all the details and acknowledge CloudFormation and create.
8.  Monitor the status of the stack. When the status is **CREATE_COMPLETE**, the deployment is complete.

**Step 3:** Open Data Lake

1.  Once the deployment is completed Email ID provided while creating stack will receive a link with login ID and Password.
2.  Go to your email, check for datalake email and click on the link it will take you to the page below.  

    ![Login page](/images/aws-qs-dl-login.png "Login page")
    
3.  Login with the credentials.
4.  From the data lake home page you can Manage Data, check resources and visualize data.
5.  Under Data Management there are two options to manage data  
    a) AWS S3  
    
    ![S3 Explorer](/images/aws-qs-dl-s3-explorer.png "S3 Explorer")  
    
    b) AWS Kinesis
6.  In Resources tab you can check all the AWS Resources used in data lake  

    ![Resources](/images/aws-qs-dl-resources.png "Resources")
    
7.  In Visualize tab you can visualize data from the following sources  
    a) AWS S3 data using Zeppelin b) Streaming data using Kibana
