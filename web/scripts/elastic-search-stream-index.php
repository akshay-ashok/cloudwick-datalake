
<script type="text/javascript" src="../resources/js/jquery-3.2.0.min.js"></script>
<script type="text/javascript" src="../resources/js/jquery.form-4.20.min.js"></script>
<script type="text/javascript">
    var myarray = new Array();
    myarray[0] ={"record_id":"indexEdi270","payer_name":"-","payer_id":"-","provider_id":"-","provider_name":"-","provider_service_number":"-","provider_address":"-","physician_id":"-","physician_name":"-","physician_mobile":"-","physician_email":"-","patient_id":"-","patient_name":"-","patient_ssn":"-","patient_address":"-","record_ts":"2017-01-01T00:00:01"};
    $.ajax({
        url: "../data-management/kinesis-stream-writer.php",
        data: { data: JSON.stringify(myarray[0]) },
        async: true,
        error: function(datar){
            //$(".streamresult2").append( datar + "<br><br>");
        },
        success: function(datar){
            //$(".streamresult2").append( datar + "<br><br>");
        },
        type: "GET"
    });
</script>
