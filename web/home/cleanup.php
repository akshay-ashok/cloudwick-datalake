<?php

    print '<p>Cleaning up...</p>';
    sleep(1);
    $result = exec("rm -rf /var/www/html/home/welcome.php");
    sleep(3);
    print '<p>Cleaning complete.</p>';
    return;

?>