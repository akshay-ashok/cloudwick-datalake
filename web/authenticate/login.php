<?php
include_once "../root/defaults.php";
include_once "../root/ConnectionManager.php";

    print '
        <!-- Start #myLoginModal Modal -->
        <script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>
        <script type="text/javascript" src="../resources/js/authentication.js"></script>
        <div class="modal fade" id="myLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myLoginModalTitle"><b><i class="fa fa-sign-in"></i> Login</b></h4>
                    </div>
                    <div class="modal-body" style="margin-top:1em;">
                        <div id="auth_output"></div>
                        <div class="clearfix"></div>
                        <form class="form-horizontal" id="modalLoginForm" method="post" action="../authenticate/authenticator.php">
                            <input type="hidden" name="formSource" value="legit">
                            <div class="form-group">
                                <label for="username" class="col-sm-1 control-label"><i class="fa fa-user fa-lg"></i></label>
                                <div class="col-sm-11">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="'._ADMIN.'" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-1 control-label"><i class="fa fa-key fa-lg"></i></label>
                                <div class="col-sm-11">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <small><a href="#" class="pull-right" data-toggle="modal" data-target="#myResetPasswordModal">Forgot Password ?</a></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <br />
                                    <button type="submit" class="btn btn-lg btn-success btn-block"><i class="fa fa-sign-in"></i> Sign in</button>
                                    <br />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End #myLoginModal Modal -->
        
        <!-- End #myResetPasswordModal Modal -->
        <div class="modal fade" id="myResetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myResetPasswordLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myResetPasswordModalTitle"><b>Password Reset</b></h4>
                </div>
                <div class="modal-body" style="margin-top:1em;">
                    <div id="reset_output"></div>
                    <div class="clearfix"></div>
                    <form class="form-horizontal" id="modalResetForm" method="post" action="../authenticate/authenticator.php">
                        <input type="hidden" name="resetReq" value="legit">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="'._ADMIN.'" readonly required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="'._EMAIL.'" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <br/>
                                <button type="submit" class="btn btn-lg btn-warning btn-block" id="resetPassBtn">Reset Password</button>
                                <br/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End #myResetPasswordModal Modal -->
    ';

    // todo: registration form & handle - abandoned after datalake 2nd status call
    // --susheel 04/11/2017
?>
