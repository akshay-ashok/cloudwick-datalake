<?php
    include "../root/ConnectionManager.php";

    $redshiftConnector = (new ConnectionManager())->getRedshiftConnector();

    $query ="SELECT 
    pr.name as payer_name, pr.provider_id as payer_id, 
    ph.physician_id as physician_id,(ph.firstname+' '+ph.lastname) as physician_name, ph.phone as physician_mobile, ph.email as physician_email,
    patient_id as patient_id,(p.firstname+' '+p.lastname) as patient_name, (p.address1+' '+p.address2+' '+p.city+' '+p.state+' '+p.zipcode) as patient_address, p.ssn as patient_ssn 
    FROM
    patient p,provider pr,physician ph
    WHERE
    p.insurance_provider=pr.provider_id and p.primary_physician_id=ph.physician_id
    ";

    $result = $redshiftConnector->query($query);

    $provider = array();

    $provider[] = array("provider_id"=>"6635", "provider_name"=>"Cloud Hospital", "provider_service_number"=>"4396552145");
    $provider[] = array("provider_id"=>"6636", "provider_name"=>"Cloud Clinic", "provider_service_number"=>"6591548963");
    $provider[] = array("provider_id"=>"6637", "provider_name"=>"Cloud Care-center", "provider_service_number"=>"1569301240");
    $provider[] = array("provider_id"=>"6638", "provider_name"=>"Cloud Institute", "provider_service_number"=>"1248963780");
    $provider[] = array("provider_id"=>"6639", "provider_name"=>"Cloud Nursing home", "provider_service_number"=>"1659301485");
    //date_default_timezone_set('America/Los_Angeles');

    $i=0;
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $prov = $provider[array_rand($provider)];
            $date = new DateTime('now', new DateTimeZone("PST8PDT"));
            $date->modify("+7 hours");
            print 'myarray['.$i.']= {record_id:"TS'.$i.''.substr(md5(microtime()),6,11).'",payer_name:"'.$row["payer_name"].'",payer_id:"'.$row["payer_id"].'",provider_id:"'.$prov["provider_id"].'",provider_name:"'.$prov["provider_name"].'",provider_service_number:"'.$prov["provider_service_number"].'",provider_address:"'.$row["patient_address"].'",physician_id:"'.$row["physician_id"].'",physician_name:"'.$row["physician_name"].'",physician_mobile:"'.$row["physician_mobile"].'",physician_email:"'.$row["physician_email"].'",patient_id:"'.$row["patient_id"].'",patient_name:"'.$row["patient_name"].'",patient_ssn:"'.$row["patient_ssn"].'",patient_address:"'.$row["patient_address"].'",record_ts:"'.$date->format("Y-m-d").'T'.$date->format("H:i:s").'"};';
            $i++;
        }
    }

?>