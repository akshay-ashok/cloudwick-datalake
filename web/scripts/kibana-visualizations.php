<?php
    print '<script type="text/javascript" src="../resources/js/jquery-3.2.0.min.js"></script>';
    print '<script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>';
    $json = file_get_contents("../configurations/kibana/objects/export.json");

    $json = json_decode($json, true);
    print '<div class="result"></div>
    <script type="text/javascript">';
    foreach ($json as $key => $value) {
        $url = 'https://search-edistreamtest03-qtspir5pdo4qvzgzt5t3bxfq34.us-west-2.es.amazonaws.com/.kibana/'.$value["_type"].'/'.$value["_id"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($value["_source"]));

        curl_exec($ch);
    }
    print '</script>';
?>