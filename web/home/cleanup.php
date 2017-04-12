<?php

    print '<p>Cleaning up...</p>';
    sleep(1);
    //rename ("./welcome.php", "./welcome_copy.php");
    $result = exec("mv /var/www/html/home/welcome.php /var/www/html/home/welcome_copy.php");
    //unlink("../home/welcome.php");
    sleep(3);
    print '<p>Cleaning complete.</p>';
    return;

?>