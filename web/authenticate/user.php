<?php
include "../root/header.php";

    print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <div class="jumbotron">
            <h1><span class="text-primary">Hello '.$_SESSION["cloudwickDatalakeUser"].',</span></h1>
            <div class="output"></div>
            <br />
            <form class="form-horizontal" id="updatePasswordForm" action="../authenticate/authenticator.php" method="post">
              <input type="hidden" name="updatePass" value="legit">
              <div class="form-group">
                <label for="username" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="'.$_SESSION["cloudwickDatalakeUser"].'" readonly required>
                </div>
              </div>
              <div class="form-group">
                <label for="cpassword" class="col-sm-2 control-label">Current Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Current Password" required>
                </div>
              </div>
              <div class="form-group">
                <label for="npassword" class="col-sm-2 control-label">New Password</label>
                <div class="col-sm-10">
                  <input type="password" pattern="[A-Za-z0-9\s]{6,12}" title="6-12 alpha-numeric password" class="form-control" id="npassword" name="npassword" placeholder="New Password" required>
                </div>
              </div>
              <div class="form-group">
                <label for="cfpassword" class="col-sm-2 control-label">Confirm Password</label>
                <div class="col-sm-10">
                  <input type="password" pattern="[A-Za-z0-9\s]{6,12}" title="6-12 alpha-numeric password" class="form-control" id="cfpassword" name="cfpassword" placeholder="Confirm Password" required>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <br>
                    <button type="submit" class="btn btn-warning btn-block">Update Password</button>
                </div>
              </div>
            </form>
        </div>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    <script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>
    <script type="text/javascript" src="../resources/js/authentication.js"></script>
    ';

include "../root/footer.php";
?>