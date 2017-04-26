$(function() {
    var initpage = "../home/welcome.php";
    $(this).bind("contextmenu", function(e) {
        // update: allowed right-click 04/14/2017
        // e.preventDefault();
    });

    setTimeout(function(){
        $(".databaseConnectionError").hide();
    },20000);

    $('.headerLogo').on("click",function(e){
        window.location = "../home/";
    });

    $('.customMessage').each(function(){
        $(this).on("click",function(e){
            e.preventDefault();
            var preload = $(".customMessagePreloader");
            var msg = "<div class='alert alert-warning'>"+$(this).attr("message")+"</div>";
            var title = $(this).attr("title");
            var dataurl = $(this).attr("data-url");
            var location = $(this).attr('location');
            if (typeof location !== typeof undefined && location !== false) {
                setTimeout(function(){ window.location = location; }, 1500);
                $("#customMessageModalClose").attr("href", location);
                $("#customMessageModalClose").attr("data-dismiss", "");
            }
            if (typeof dataurl !== typeof undefined && dataurl !== false) {
                $("#CustomMessage").load(dataurl,function () {
                    preload.hide();
                });
            } else {
                $("#CustomMessage").html(msg);
                preload.hide();
            }
            $("#CustomTitle").html(title).addClass("text-primary");
            $("#CustomMessageModal").modal();
        });
    });

    $("#CustomMessageModal").on("hidden.bs.modal", function (e) {
        $(".customMessagePreloader").show();
        $("#CustomMessage").html("")
    });

    $.ajax({
        url: initpage,
        type:'HEAD',
        async: true,
        success: function() {
            var loc = document.location.href;
            if(loc.indexOf("/home/welcome") == -1){
                window.location.replace(initpage);
            }
        }
    });
});