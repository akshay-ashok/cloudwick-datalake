<?php

    include "../root/AwsFactory.php";
    try {
        $aws = new AwsFactory();
        $client = $aws->getRedshiftClient();

        $result = $client->modifyClusterIamRoles([
            'AddIamRoles' => [_REDSHIFT_ROLE_ARN],
            'ClusterIdentifier' => _REDSHIFT_IDENTIFIER
        ]);

        foreach ($result["Cluster"]["IamRoles"] as $role) {
            if ($role["IamRoleArn"] == _REDSHIFT_ROLE_ARN) {
                if ($role["ApplyStatus"] == "in-sync") {
                    print 'Successfully attached IAM role to redshift';
                } else {
                    print 'IAM role attachment to redshift initiated';
                }
            }
        }
    } catch (\Aws\Redshift\Exception\RedshiftException $ex){
        print 'Error: '.$ex->getAwsErrorCode();
    } catch (Exception $ex){
        print 'Error: '.$ex->getMessage();
    }
