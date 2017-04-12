$(function(){
    $("#downloadObject").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget);
        var objkey = button.data("key");
        var bucket = button.data("bucket");
        var modal = $(this);
        $.when(function(){
            objkey = button.data("key");
            alert(objkey);
        }).then(function(){
            $.get("./S3UrlGenerator.php?bucket="+bucket+"&keyname="+objkey,function(presignedurl){
                modal.find(".modal-body input").val(presignedurl);
                modal.find(".modal-body .openlink").attr("href",presignedurl);
            });
        });
    });

    $(".copylink").on("click",function(e){
        $("#downloadObject").find(".modal-body input[type=text]").select();
        document.execCommand("copy");
        $(this).html("Copied").delay(3000).html("Copy Link");
    });

    $(".s3Folder").each(function(){
        $(this).hover(function () {
            $(this).children("span.glyphicon").removeClass("glyphicon-folder-close").addClass("glyphicon-folder-open");
        }, function () {
            $(this).children("span.glyphicon").removeClass("glyphicon-folder-open").addClass("glyphicon-folder-close");
        });
    });

    $('[data-toggle="popover"]').popover({
        "html": true,
        "placement":"bottom"
    });

    $('#createFolderModal').on('shown.bs.modal', function () {
        $('#foldername').focus();
    });

    var createFolder_output = $("#createFolderMessage");
    $("#createFolderForm").ajaxForm({
        target: createFolder_output,
        success: function(createdata, statusText, xhr, $form) {
            if(createdata.indexOf("alert-success") !== -1) {
                $("#createFolderForm").hide();
                location.reload();
            }
        }
    });

    var uploadObject_output = $("#uploadObjectMessage");
    var uploadForm = $("#uploadObjectForm");
    var uploadModal = $("#uploadObjectModal");
    var uploadProgressBar = $("#uploadProgress");

    uploadModal.on('shown.bs.modal',function(){
        $(".progress, .progressText").hide();
        uploadProgressBar.css('width', '0%').attr('aria-valuenow', 0);
    });


    uploadForm.ajaxForm({
        target: uploadObject_output,
        beforeSend: function() {
            uploadForm.hide();
            $(".progress, .progressText").show();
            $(".uploadProgressSR").text('0% complete');
            uploadProgressBar.css('width', '0%').attr('aria-valuenow', 0);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            $(".uploadProgressSR").text(''+ percentComplete + '% complete');
            uploadProgressBar.css('width', percentComplete+'%').attr('aria-valuenow', percentComplete);
        },
        success: function(uploadObjectdata, statusText, xhr, $form) {
            uploadForm.hide();
            $(".progress, .progressText").hide();
            uploadModal.on("hidden.bs.modal", function (e) {
                if(uploadObjectdata.indexOf("text-success") !== -1) {
                    location.reload();
                } else {
                    uploadForm.show();
                    uploadObject_output.html("");
                }
            });
        },
        error: function () {
            $(".progress, .progressText").hide();
        },
        clearForm: true
    });
});
