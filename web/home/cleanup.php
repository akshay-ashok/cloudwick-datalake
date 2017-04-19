<?php

    print '<p>Cleaning up...</p>';
    sleep(1);
    $result = exec("mv /var/www/html/home/welcome.php /var/www/html/home/welcome_copy.php");
    sleep(3);
    print '<p>Cleaning complete.</p>';
    return;

?>