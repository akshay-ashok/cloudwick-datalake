<?php
    error_reporting(0);
    try {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $region = $_POST["region"];
        $ip = $_POST["ip"];
        $url = "ec2-".str_replace(".","-",$ip).".".$region.".compute.amazonaws.com";

        $subject = "Data lake portal setup complete";
        $message = "Hello " . $username . ",<br>
    Thank you for your interest in Cloudwick's Data Lake Quick Start Implementation. We've setup you Data Lake portal successfully.
    please visit <a href='http://" . $url . "/home/'>http://" . $url . "/home/</a> to access the portal.
    
    <br><br>--Cloudwick Data Lake Quick Start Portal";
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'To: ' . $username . ' <' . $email . '>';
        $headers[] = 'From: Cloudwick Data Lake Quick Start Portal';
        $sent = mail($email, $subject, $message, implode("\r\n", $headers));
        if ($sent == true) {
            print '<div class="alert alert-success">Sent Email to ' . $email . '.</div>';
        } else {
            print '<div class="alert alert-success">Email to ' . $email . ' failed.</div>';
        }
    } catch(Exception $ex){
        print $ex->getMessage();
    }

?>