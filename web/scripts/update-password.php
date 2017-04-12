<?php

require_once("../root/ConnectionManager.php");
$mysqlConnector = (new ConnectionManager())->getMysqlConnector();

if(isset($_GET)){
    $username = htmlspecialchars($_GET['username'], ENT_QUOTES);
    $new_password = md5(htmlspecialchars($_GET['npassword'], ENT_QUOTES));

    try {
        $cp = $mysqlConnector->query("UPDATE `datalake`.`user` SET `password` = '".$new_password."' WHERE `username`='".$username."'");
        if($cp->rowCount() > 0){
            print '<div class="alert alert-success">Password updated successfully !!</div>';
        } else {
            print '<div class="alert alert-warning">Oh oh, something went wrong, please try again !! </div>';
        }
    } catch(PDOException $ex){
        print $ex->getMessage();
    }
}