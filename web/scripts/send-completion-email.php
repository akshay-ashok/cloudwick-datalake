<?php
    error_reporting(0);
    try {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $region = $_POST["region"];
        $ip = $_POST["ip"];
        $url = "ec2-".str_replace(".","-",$ip).".".$region.".compute.amazonaws.com";
        $hostname = shell_exec("hostname");
        $internal_ip = $hostname.".".$region.".compute.internal";

        $subject = "Data lake portal setup complete";
        $message = "Hello,<br>
    Thank you for your interest in Data Lake Quick Start Implementation. We've setup your Data Lake portal successfully.
    please visit <a href='http://" . $url . "/home/'>http://" . $url . "/home/</a> to access the portal. <br/>
    
    Your access credentials are: <br/>
    login id : <b>".$username."</b> <br/>
    passphrase : <b>".$password."</b> <br/>
    
    <br><br>--Data Lake Quick Start Portal";
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = "X-Priority: 3";
        $headers[] = 'To: ' . $username . ' <' . $email . '>';
        $headers[] = "Return-Path: Data Lake Quick Start Portal <apache@".$internal_ip.">";
        $headers[] = "From: Data Lake Quick Start Portal <apache@".$internal_ip.">";

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