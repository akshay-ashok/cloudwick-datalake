
    <!-- Start CustomMessage Modal -->
	<div class="modal fade" id="CustomMessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="CustomTitle">-----</h4>
		  </div>
		  <div class="modal-body" style="margin-top:1em;">
            <div class="text-primary text-center centered customMessagePreloader">
                <i class="fa fa-cog fa-spin fa-2x fa-fw"></i> Loading content...
            </div>
		    <span id="CustomMessage"></span>
		  </div>
		  <div class="modal-footer">
			<a href="#" type="button" class="btn btn-default" id="customMessageModalClose" data-dismiss="modal">Close</a>
		  </div>
		</div>
	  </div>
	</div>
	<!-- End CustomMessage Modal -->
    <?php
    if(!isset($_SESSION["DatalakeUser"])) {
    include_once "../authenticate/login.php";
      if(isset($_GET["relogin"]) || isset($_GET["password_change"]) || isset($_GET["welcome"])){
        print '
        <script type="text/javascript">
        $(function(){ 
          $("#myLoginModal").modal("show");
          $("#auth_output").html("<div class=\'alert alert-'.(isset($_GET["relogin"]) ? 'danger\'>Session expired, please login again !!' : (isset($_GET["password_change"]) ? 'success\'>Password updated, please login with your new password' : (isset($_GET["welcome"]) ? 'success\'>Welcome to Data Lake Portal' : ''))).'</div>");
        });
        </script>';
      }
    }
    ?>