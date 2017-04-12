<?php
include_once "../root/defaults.php";
include_once "../root/ConnectionManager.php";
$mysqlConnector = (new ConnectionManager())->getMysqlConnector();

if(isset($_POST["inputLegit3"])){

    function sanitizeParameter($input) {
        return htmlspecialchars($input, ENT_QUOTES);
    }

    $accountid = sanitizeParameter($_POST["inputAccount3"]);
    $company = sanitizeParameter($_POST["inputCompany3"]);
    $contact = sanitizeParameter($_POST["inputContactName3"]);
    $phone = sanitizeParameter($_POST["inputContactNumber3"]);
    $email = sanitizeParameter($_POST["inputEmail3"]);
    $username = sanitizeParameter($_POST["inputUsername3"]);
    $password = sanitizeParameter($_POST["inputPassword3"]);
    $confirmpassword = sanitizeParameter($_POST["inputConfirmPassword3"]);
    print '<p class="text-danger">Not just yet, wheel is still spinning</p>';
} else {

    $query = "select * from datalake.user where `username`='" . _ADMIN . "'";
    $result = $mysqlConnector->query($query);
    if ($result->rowCount() > 0) {
        print '<div class="alert alert-danger">You are already registered, New registrations are disabled by administrator</div>';
    } else {
        ?>
        <span id="registration_output"></span>
        <form class="form-horizontal" method="post" action="../authenticate/register.php" id="registrationForm">
            <input type="hidden" name="inputLegit3" value="<?= microtime() ?>">
            <span class="pull-right text-danger">* required fields</span>
            <div class="clearfix"></div>
            <br/>
            <div class="form-group">
                <label for="inputAccount3" class="col-sm-4 control-label">Account Id <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control disabled" name="inputAccount3" id="inputAccount3" title="AWS Account ID"
                           placeholder="Account Id" value="<?= _ACCOUNT_ID ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="inputCompany3" class="col-sm-4 control-label">Company Name <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="inputCompany3" id="inputCompany3" title="Your Company Name"
                           placeholder="Your Company Name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputContactName3" class="col-sm-4 control-label">Contact Name <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="inputContactName3" id="inputContactName3" title="Contact Person name"
                           placeholder="Your Name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputContactNumber3" class="col-sm-4 control-label">Contact Number</label>
                <div class="col-sm-8">
                    <input type="tel" pattern="(?:\(\d{3}\)|\d{3})[- ]?\d{3}[- ]?\d{4}" title="10 digit phone number" class="form-control" name="inputContactNumber3" id="inputContactNumber3"
                           placeholder="Your Contact Number">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">Email Address <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" name="inputEmail3" id="inputEmail3" title="Contact Person Email Address"
                           placeholder="you@yourcompany.tld"
                           required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputUsername3" class="col-sm-4 control-label">Username <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="email" class="form-control disabled" name="inputUsername3" id="inputUsername3" title="Portal Username"
                           placeholder="Username" value="<?= _ADMIN ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-4 control-label">Password <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="inputPassword3" id="inputPassword3" title="Password"
                           placeholder="Password" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputConfirmPassword3" class="col-sm-4 control-label">Confirm Password <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="inputConfirmPassword3" id="inputConfirmPassword3" title="Confirm Password"
                           placeholder="Confirm Password" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8"><br/>
                    <button type="submit" class="btn btn-success">Register</button>
                    <button type="reset" class="btn btn-danger">Reset form</button>
                </div>
            </div>
        </form>
        <script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>
        <script type="text/javascript">
            registration_output = $("#registration_output");
            $("#registrationForm").ajaxForm({
                target: registration_output,
                success: function(data, statusText, xhr, $form) {
                    if(data.indexOf("text-success") !== -1) {
                        $.when(function(){
                            registration_output.removeClass("bg-danger").addClass("text-success");
                        }).then(function() {
                            //window.location.replace("../home/");
                        });
                    } else if(data.indexOf("text-warning") !== -1) {
                        registration_output.html("Working on it")
                            .addClass("bg-danger");
                    } else {
                        registration_output.addClass("bg-danger");
                    }
                }
            });
        </script>

        <?php
    }
}
?>