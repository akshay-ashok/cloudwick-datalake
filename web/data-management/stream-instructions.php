<?php
    include_once "../root/defaults.php";
    if(isset($_GET)) {
        $type = htmlspecialchars($_GET["type"], ENT_QUOTES);
        if($type == "apicall"){
            print '
<pre><code><em class="text-primary">
 <em class="text-success">POST</em> /?Action=PutRecord HTTP/1.1
 <em class="text-success">Host</em>: kinesis.region.domain
 <em class="text-success">Authorization</em>: AWS4-HMAC-SHA256 Credential=..., ...
 ...
 <em class="text-success">Content-Type</em>: application/x-amz-json-1.1
 <em class="text-success">Content-Length</em>: PayloadSizeBytes
 
 {
    <em class="text-success">"Data"</em>: blob, <em class="text-danger">//name="" required</em>
    <em class="text-success">"ExplicitHashKey"</em>: "string",
    <em class="text-success">"PartitionKey"</em>: "string", <em class="text-danger">//name="" required</em>
    <em class="text-success">"SequenceNumberForOrdering"</em>: "string",
    <em class="text-success">"StreamName"</em>: <em class="text-primary" style="font-weight:bold;">"'._KINESIS_STREAM_NAME.'"</em> <em class="text-danger">//name="" required</em>
 }
</em></code></pre>';
        } else if($type == "sampleStream"){
            print '
                <div id="streamOutput"></div>
                <div class="clearfix"></div>
            	<form class="form-horizontal" id="firehoseSampleStream" action="../scripts/kinesis-firehose-writer.php">
                  <fieldset>                    
                    <div class="form-group">
                      <label for="inputName" class="col-lg-4 control-label">Name:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="name" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputDob" class="col-lg-4 control-label">Date of Birth:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputDob" placeholder="Date of Birth" name="dob" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputStreetAddress" class="col-lg-4 control-label">Street Address:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputStreetAddress" placeholder="Street Address" name="address1" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputCityStateZip" class="col-lg-4 control-label">City, State Zip:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputCityStateZip" placeholder="City, State Zip" name="address2" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputCountry" class="col-lg-4 control-label">Country:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputCountry" placeholder="Country" name="country" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPhone" class="col-lg-4 control-label">Phone Number:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputPhone" placeholder="Phone Number" name="phone" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputUsername" class="col-lg-4 control-label">Username:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword" class="col-lg-4 control-label">Password:</label>
                      <div class="col-lg-8">
                        <input type="text" class="form-control" id="inputPassword" placeholder="Password" name="password" required>
                      </div>
                    </div>            
                    <div class="form-group">
                      <label for="inputEmail" class="col-lg-4 control-label">Email:</label>
                      <div class="col-lg-8">
                        <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-8 col-lg-offset-4">
                        <input type="submit" class="btn btn-success sendToFirehose" value="Send to Firehose">
                      </div>
                    </div>
                  </fieldset>
                </form>
                <script type="text/javascript" src="../resources/js/faker.min.js"></script>
                <script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>
                <script type="text/javascript">
                $(function(){
                    $("#firehoseSampleStream").ajaxForm({
                        target: "#streamOutput",
                        beforeSubmit: function(){
                            $(".sendToFirehose").addClass("disabled");
                        },
                        success: function(){
                            $(".sendToFirehose").removeClass("disabled");
                            fillForm();
                        }
                    });
                    
                    fillForm();
                    
                    function fillForm () {
                      var firstName = faker.name.firstName(),
                          lastName = faker.name.lastName();
        
                      var dob = faker.date.past(50, new Date("Sat Sep 20 1992 21:35:02 GMT+0200 (CEST)"));
                      dob = dob.getFullYear() + "-" + ("0" + (dob.getMonth()+1)).slice(-2) + "-" + ("0" + dob.getDate()).slice(-2);
                      $("#inputName").val(faker.name.findName(firstName, lastName));
                      $("#inputDob").val(dob);
                      $("#inputStreetAddress").val(faker.address.streetAddress());
                      $("#inputCityStateZip").val(faker.address.city() + ", " + faker.address.stateAbbr() + " " + faker.address.zipCode());
                      $("#inputCountry").val(faker.address.country);
                      $("#inputPhone").val(faker.phone.phoneNumber());
                      $("#inputUsername").val(faker.internet.userName(firstName, lastName));
                      $("#inputPassword").val(faker.internet.password());
                      $("#inputEmail").val( faker.internet.email(firstName, lastName));
                    }
                });
                </script>
            ';
        }
    }