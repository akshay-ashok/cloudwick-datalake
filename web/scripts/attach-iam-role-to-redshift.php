<?php

    include "../root/AwsFactory.php";
    $aws = new AwsFactory();
    $client = $aws->getRedshiftClient();


    $result = $client->modifyClusterIamRoles([
        'AddIamRoles' => [_REDSHIFT_ROLE_ARN],
        'ClusterIdentifier' => _REDSHIFT_IDENTIFIER
    ]);


    foreach ($result["Cluster"]["IamRoles"] as $role){
        if($role["IamRoleArn"] == _REDSHIFT_ROLE_ARN){
            if($role["ApplyStatus"] == "in-sync"){
                print 'attach success';
            } else {
                print 'attach failed';
            }
        }
    }

    print '<br><br>';

    print_r($result);
