<?php
include "../root/header.php";
include "../root/AwsFactory.php";
checkSession();

$aws = new AwsFactory();
$ctclient = $aws->getCloudTrailClient();

$result = $ctclient->lookupEvents([
   'MaxResults' => 50
]);

    print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <table class="table table-striped table-responsive table-hover" style="font-size: 1.6rem;">
        <thead>
            <tr class="success">
                <td></td>
                <td>EventName</td>
                <td>EventTime</td>
                <td>Username</td>
                <td>Resource type</td>
                <td>Resource name</td>
            </tr>
        </thead>
        <tbody>';
        foreach ($result["Events"] as $event){
            $resources = $event["Resources"];
            $rtype = array();
            $rname = array();
            foreach ($resources as $resource) {
                if(array_key_exists('ResourceType', $resource)) {
                    $resourceType = explode("::", $resource["ResourceType"]);
                    $rtype[] = (sizeof($resourceType) > 1) ? $resourceType[1] . " " . $resourceType[2] : $resourceType[0];
                    $rname[] = $resource["ResourceName"];
                }
            }
            $resourceName = ((sizeof($rname) > 1) ? $rname[0].' and '.(sizeof($rname)-1).' more' : implode(",",$rname));

            $trail = (array) json_decode($event["CloudTrailEvent"]);
            $uidentity = (array)($trail["userIdentity"]);
            print '<tr data-toggle="collapse" data-target="#'.$event["EventId"].'" aria-expanded="false" aria-controls="collapse-'.$event["EventId"].'" style="cursor: pointer; cursor: hand;">
                <td><span class="glyphicon glyphicon-chevron-right"></span></td>
                <td>'.$event["EventName"].'</td>
                <td>'.$event["EventTime"].'</td>
                <td>'.$event["Username"].'</td>
                <td>'.((sizeof($rtype) > 1) ? $rtype[0].' and '.(sizeof($rtype)-1).' more' : implode(",",$rtype)).'</td>
                <td>'.substr($resourceName, 0, 30).'...</td>
            </tr>
            <tr class="collapse bg-info" id="'.$event["EventId"].'">
                <td colspan="6 bg-info">
                '.$event["CloudTrailEvent"].'
                 <table class="table table-hover" style="background-color:transparent;">
                    <tr>
                        <td class="text-right"><b>Access key</b></td><td>'.(array_key_exists('accessKeyId', $uidentity) ? $uidentity["accessKeyId"] : '').'</td>
                        <td class="text-right"><b>Event source</b></td><td>'.(array_key_exists('EventSource', $event) ? $event["EventSource"] : '' ).'</td>
                    </tr>
                    <tr>
                        <td class="text-right"><b>AWS region</b></td><td></td>    
                        <td class="text-right"><b>Event time</b></td><td></td>
                    </tr>
                    <tr>
                        <td class="text-right"><b>Error code</b></td><td></td>
                        <td class="text-right"><b>Request ID</b></td><td></td>
                    </tr>
                    <tr>
                        <td class="text-right"><b>Event ID</b></td><td></td>
                        <td class="text-right"><b>Source IP address</b></td><td></td>
                    </tr>
                    <tr>
                        <td class="text-right"><b>Event name</b></td><td></td>
                        <td class="text-right"><b>User name</b></td><td></td>
                    </tr>
                 </table>
                </td>    
            </tr>';
    }
    print '
    </tbody>
    </table>
    </div>
    <div class="col-lg-1 col-md-1"></div>';
include "../root/footer.php";
?>