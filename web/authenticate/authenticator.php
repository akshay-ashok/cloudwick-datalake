<?php
    require_once("../root/ConnectionManager.php");
    require_once("../root/functions.php");

    $mysqlConnector = (new ConnectionManager())->getMysqlConnector();

    if(isset($_POST["formSource"])){
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
        $enc_password = md5($password);
        if((!$username) || (!$password)){
            print '<div class="alert alert-warning">Please enter ALL of the information!</div>';
        }
        try {
            $query = "select * from datalake.user where `username`='" . $username . "' and `password`='" . $enc_password . "'";
            $result = $mysqlConnector->query($query);
            if ($result->rowCount() > 0) {
                if (isset($_SESSION)) {
                    $_SESSION["cloudwickDatalakeUser"] = $username;
                    $_SESSION["lastActivity"] = time();
                } else {
                    session_start();
                    $_SESSION["cloudwickDatalakeUser"] = $username;
                    $_SESSION["lastActivity"] = time();
                }
                print '<div class="alert alert-success">Login Successful <a href="../home/">click here</a> if not redirected</div>';
            } else {
                print '<div class="alert alert-warning">Invalid username/password</div>';
            }
        } catch(PDOException $ex){
            print $ex->getMessage();
        }
    } else if(isset($_POST["resetReq"])){
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);

        if((!$username) || (!$email)){
            print '<div class="alert alert-warning">Please enter ALL of the information!</div>';
            return;
        }
        try {
            $query = "select * from datalake.user where `username`='" . $username . "'";
            $result = $mysqlConnector->query($query);
            if ($result->rowCount() > 0) {
                $newpass = substr(md5(microtime()),4,12);
                //print '<div class="alert alert-warning">Logic isn\'t ready yet. Working on it.'.$newpass.'</div>';
                $cp = $mysqlConnector->query("UPDATE `datalake`.`user` SET `password` = '".md5($newpass)."' WHERE `username`='".$username."'");
                if($cp->rowCount() > 0){
                    $subject = "Datalake portal password reset";
                    $message = "Hello ".$username.",<br>At your request, we have reset your password. New password is <b><u>".$newpass."</u></b><br><br>--Cloudwick Datalake Quickstart Portal";
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=UTF-8';
                    $headers[] = 'To: '.$username.' <'.$email.'>';
                    $headers[] = 'From: Cloudwick Datalake Quickstart Portal';
                    $sent = mail($email, $subject, $message, implode("\r\n", $headers));
                    if($sent == true){
                        print '<div class="alert alert-success">Password emailed to '.$email.'. Check your inbox/spam for new password !!</div>';
                    } else {
                        print '<div class="alert alert-success">Email to '.$email.' failed. Your new Password is '.$newpass.'</div>';
                    }

                } else {
                    print '<div class="alert alert-warning">Oh oh, something went wrong, please try again !! </div>';
                }
                // reset logic
            } else {
                print '<div class="alert alert-warning">Specified user does not exist</div>';
            }
        } catch(PDOException $ex){
            print $ex->getMessage();
        }
    } else if(isset($_POST["updatePass"])){
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $current_password = md5(htmlspecialchars($_POST['cpassword'], ENT_QUOTES));
        $new_password = md5(htmlspecialchars($_POST['npassword'], ENT_QUOTES));
        $cf_password = md5(htmlspecialchars($_POST['cfpassword'], ENT_QUOTES));

        if((!$username) || (!$current_password) || (!$new_password) || (!$cf_password)){
            print '<div class="alert alert-danger">Please enter ALL of the information!</div>';
            return;
        } else if($new_password != $cf_password){
            print '<div class="alert alert-danger">New password and confirm password do not match, try again</div>';
            return;
        }
        try {
            $query = "select * from datalake.user where `username`='" . $username . "' and `password`='".$current_password."'";
            $result = $mysqlConnector->query($query);
            if ($result->rowCount() > 0) {
                $cp = $mysqlConnector->query("UPDATE `datalake`.`user` SET `password` = '".$new_password."' WHERE `username`='".$username."'");
                if($cp->rowCount() > 0){
                    print '<div class="alert alert-success">Password updated successfully !!</div>';
                    $_SESSION["cloudwickDatalakeUser"] = null;
                } else {
                    print '<div class="alert alert-warning">Oh oh, something went wrong, please try again !! </div>';
                }
            } else {
                print '<div class="alert alert-danger">Specified user/password combination incorrect</div>';
            }
        } catch(PDOException $ex){
            print $ex->getMessage();
        }
    } else {
        print '<div class="alert alert-danger">Action not allowed at the moment</div>';
    }

?>