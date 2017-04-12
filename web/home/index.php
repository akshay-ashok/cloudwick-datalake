<?php
  include_once('../root/header.php');
    print '
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banner">
         <h1>Datalake Quickstart</h1>
         <img src="../resources/images/frontpage_banner.jpg" alt="">
      </div>
      <div class="clearfix"></div><br>
      <div class="col-lg-1 col-md-1"></div>
      <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
          <h1 class="text-success">Hello '.(isset($_SESSION["cloudwickDatalakeUser"])? $_SESSION["cloudwickDatalakeUser"] : "").'!</h1>
          <p class="text-justified">
            Welcome to Cloudwick\'s Datalake Quickstart. <br/><br/>
            Organizations are tasked with managing greater volumes of data, from more sources, than ever before. 
            Whether it be machine data generated from internet of things (IoT) deployments, social media metrics, or distributed data from noSQL databases, 
            many organizations are finding that to deliver timely business insights against massive, heterogeneous volumes of data they need a storage and analytics 
            solution that offers more speed and flexibility than legacy systems. A Data Lake is a new and increasingly popular way to store and analyze data that addresses 
            many of these challenges by allowing an organization to store all of their data in one, centralized repository. With a Data Lake on AWS, you no longer need to know 
            what questions you want to ask of your data before you store it, giving you a flexible platform for data analysis.
          </p><br/>
          <a href="https://aws.amazon.com/big-data/data-lake-on-aws/" target="_blank" class="btn btn-success btn-lg"><i class="fa fa-external-link-square"></i> Learn More</a>
          <a href="#" class="btn btn-warning btn-lg"><i class="fa fa-video-camera"></i> Watch Demo</a><br>
      </div>
      <div class="col-lg-1 col-md-1"></div>
    ';
  include_once('../root/footer.php');
?>

