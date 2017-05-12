<?php
if (file_exists("./home/welcome.php")) {
    header("LOCATION: ./home/welcome.php");
} else {
    header("LOCATION: ./home/");
}

?>

