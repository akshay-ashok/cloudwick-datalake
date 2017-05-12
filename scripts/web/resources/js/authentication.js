$(function(){
    var auth_output;
    $('#myLoginModal').on('shown.bs.modal', function () {
        $('#username').focus();
    });
    auth_output = $("#auth_output");
    $("#modalLoginForm").ajaxForm({
        target: auth_output,
        success: function(data, statusText, xhr, $form) {
            if(data.indexOf("alert-success") !== -1) {
                $.when(function(){
                    auth_output.removeClass("alert-danger").addClass("alert-success")
                        .delay(3000);
                }).then(function() {
                    window.location.replace("../home/");
                });
            }
        }
    });
});

$(function(){
    $('#myResetPasswordModal').on('shown.bs.modal', function () {
        $('#email').focus();
        $('#myLoginModal').modal("hide");
    });
    $('#myResetPasswordModal').on('hidden.bs.modal', function () {
        $("#reset_output").html("");
    });
    $("#modalResetForm").ajaxForm({
        target: $("#reset_output"),
        success: function(responseText, statusText, xhr, $form){
            if(responseText.indexOf("alert-success") !== -1) {
                $("#resetPassBtn").addClass("disabled");
            }
        },
        resetForm: true
    });
});

$(function(){
    var output = $(".output");
    $("#updatePasswordForm").ajaxForm({
        target: output,
        success: function(responseText, statusText, xhr, $form){
            if(responseText.indexOf("alert-success") !== -1) {
                window.location.replace("../home/?password_change");
            }
        },
        resetForm: true
    });
});