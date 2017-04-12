<?php
include "../root/header.php";
?>
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
        <div class="jumbotron">
            <h1><b class="text-danger"><i class="fa fa-trash-o"></i> Delete Stack</b></h1>
            <br>
            <p class="text-danger">This action cannot be undone.</p>
            <br>
            <a class="btn btn-danger btn-lg btn-block deleteStack" data-url="../aws-resources/stack-cleanup.php">I understand, delete stack now !!</a></p>
            <br><br><br><br>
        </div>
    </div>
    <div class="col-lg-1 col-md-1"></div>
    <!-- Modal -->
    <div class="modal fade" id="deleteStack" tabindex="-1" role="dialog" aria-labelledby="deleteStackLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="bg-danger modal-header">
                    <h4 class="modal-title" id="deleteStackLabel">Deleting Stack...</h4>
                </div>
                <div class="modal-body" id="deleteStackBody">
                   <center><img src="../resources/images/hourglass.gif" alt="Deleting Stack..." class="img img-responsive"></center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(".deleteStack").on("click",function(){
            $('.modal-footer').hide();
            $('#deleteStack').modal({
                backdrop : "static",
                keyboard : false
            });
        });
        $('#deleteStack').on('show.bs.modal', function (event) {
            $.ajax({
                url: "../aws-resources/stack-cleanup.php",
                success: function(data){
                    $("#deleteStackBody").html(data);
                    if(data.indexOf("Error:") !== -1) {
                        $('.modal-footer').show();
                    }
                }
            });
        });
    </script>
<?php
include "../root/footer.php";
?>