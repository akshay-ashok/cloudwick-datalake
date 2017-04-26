$(function(){
    $("p.links > a").addClass("btn btn-default btn-sm");
    $("table").addClass("table table-responsive table-striped table-hover table-bordered");
    $("form").addClass("form-inline");
    $("input,select").addClass("form-control");
    $("input[type='submit']").addClass("btn btn-default btn-sm btn-primary");
    $("input[type='checkbox']").addClass("checkbox2");
    $("input[name='logout']").hide();
    $(".wayoff").hide();
    $("input[id='all-page']").parent("td").css("width", "3rem");
    $("#fieldset-select").parent("fieldset").parent("form").hide();
    $('#dbs > select').addClass("form-control").children('option:not(:selected)').remove();
    $('.createOps').css("color", "white");
    $("#schema > .table").removeAttr("style").append("<div class='clearfix'></div><br>");

});

function helpMouseout(element,event) {}
function helpMouseover(element,event) {}
function focus(input){}


