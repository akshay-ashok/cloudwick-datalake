<?php
    include_once "../root/defaults.php";
  if(!isset($_SESSION)) session_start();

function extract_ID_from_URL($url) {
	$filename = end(explode("/",$url));
	$id = explode(".",$filename);
	return strtolower($id[0]);
}

function sanitizeParameter($input) {
	return htmlspecialchars($input, ENT_QUOTES);
}

function printException($ex){
    print '<div class="alert alert-danger">'.$ex->getMessage().'</div>';
}


function checkSession() {
	$relogin = true;
	if(isset($_SESSION["cloudwickDatalakeUser"]) && $_SESSION["cloudwickDatalakeUser"] != "" && $_SESSION["cloudwickDatalakeUser"] != null) {
		if(isset($_SESSION["lastActivity"]) && (time()-$_SESSION["lastActivity"] < 1800)) {
			$_SESSION["lastActivity"] = time();
		} else {
			destroySession($relogin);
		}
	} else {
		destroySession();
	}
}

function destroySession($relogin=false) {
	session_unset();
	session_destroy();
	if($relogin) {
        header("location: ../home/?relogin");
    } else {
	    header("location: ../home");
    }
}

function getMenubar() {
    print '	
		<!-- start of navbar -->
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="../home/">Datalake Quickstart</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	';
    if(isset($_SESSION["cloudwickDatalakeUser"])) {
        checkSession();
        print '
			  <ul class="nav navbar-nav navbar-right">
			    <li class="dropdown">
				  <a href="#" class="dropdown-toggle" title="Data Management" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-oil"></span> Data Management <span class="caret"></span></a>
				  <ul class="dropdown-menu">
                    <li><a class="t1" title="Upload a file to S3" href="../s3/index.php?bucket='._BUCKET.'&prefix=uploads/original"><span class="glyphicon glyphicon-cloud-upload"></span> Upload file to S3</a></li>  
                    <li><a class="t1" title="Explore Metadata" href="../data-management/kibana.php"><span class="glyphicon glyphicon-book"></span> Explore Catalogue</a></li>
                    <li><a class="t1" title="Streaming Data" href="../data-management/streams.php"><span class="glyphicon glyphicon-random"></span> Stream Data</a></li>
                    <li><a class="t1" title="Sparkflows" href="../data-management/sparkflows.php"><span class="glyphicon glyphicon-fire"></span> Sparkflows</a></li>
				  </ul>
				</li>
				<li><a class="t1" title="List all Datalake resources" href="../aws-resources/"><span class="glyphicon glyphicon-tags"></span> Resources</a></li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" title="Visualize data" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-line-chart"></i> Visualize <span class="caret"></span></a>
				  <ul class="dropdown-menu">
                    <li><a class="t1" title="Visualize Data from Redshift or files" href="../visualize/zeppelin.php"><i class="fa fa-rocket"></i> &nbsp;&nbsp;&nbsp;&nbsp;Explore Data</a></li>             
                    <li><a class="t1" title="Explore CloudTrail" href="../visualize/cloudtrail.php"><span class="glyphicon glyphicon-console"></span> Explore API calls</a></li>                   
				  </ul>
				</li>   
				<li><a class="t1" title="Contact Us" href="../contact/"><span class="glyphicon glyphicon-phone-alt"></span> Contact Us</a></li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-o"></i> ' .$_SESSION['cloudwickDatalakeUser'].' <span class="caret"></span></a>
				  <ul class="dropdown-menu">
					<li>
					  <a href="../authenticate/user.php" class="" title="Profile">
					  <i class="fa fa-cogs"></i> &nbsp;Profile</a>
					</li>
					<li>
					<li><a href="#" class="customMessage" message="" data-url="../contact/about-datalake.php" title="About Cloudwick\'s Datalake">
					  <i class="fa fa-info"></i> &nbsp;&nbsp;About Datalake</a>
					</li>
					<li>
					  <a href="#" title="Help">
					  <i class="fa fa-question"></i> &nbsp;&nbsp;Help</a>
					</li>
					<li role="separator" class="divider"></li>
					<li><a href="../aws-resources/stack-destroyer.php" class="text-danger" title="Delete Cloudformation Stack"><span class="glyphicon glyphicon-trash"></span> Delete Stack</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="../authenticate/logout.php" title="Logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				  </ul>
				</li>
			  </ul>';
    } else {
        print '<ul class="nav navbar-nav navbar-right">
				<li><a class="t1" title="Status check" href="../home/crosscheck.php"><i class="fa fa-cog fa-spin fa-1x fa-fw text-white"></i> CWDL Status</a></li>
				<li><a class="t1" title="Contact Us" href="../contact/"><span class="glyphicon glyphicon-phone-alt"></span> Contact Us</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span>&nbsp;Login <span class="caret"></span></a>
				  <ul class="dropdown-menu">
					<li><a href="#" data-toggle="modal" data-target="#myLoginModal" title="Datalake Access Login"><span class="glyphicon glyphicon-user"></span> Login</a></li>
					<li><a href="#" class="customMessage" message="" data-url="../authenticate/register.php" title="Register"><span class="glyphicon glyphicon-edit"></span> Register</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="#" class="customMessage" message="" data-url="../contact/about-datalake.php" title="About Cloudwick\'s Datalake"><span class="glyphicon glyphicon-info-sign"></span> About Cloudwick\'s Datalake</a></li>
				  </ul>
				</li>
			  </ul>
		';
    }
    print '
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
		<!-- end of navbar -->
	';
}

function fileTypeIcon($filename){
    $icons = array(
        "file-image-o" => ['png','gif','tif','jpg','jpeg','iff','ico','tiff','pct','pict','svg'],
        "file-archive-o" => ['a','ar','cpio','shar','iso','lbr','mar','sbx','jar','war','tar','bz2','f','gz','tgz','bz2','tbz2','tlz','lz','lzma','lzo','rz','sfark','sz','?q?','?z?','?xf','xz','z','Z','??_','.7z','.s7z','ace','afa','alz','apk','arc','cab','car','ice','lzh','lzx','pak','rar','zip','sfx','xar','zipx','rev','gzip'],
        "file-word-o" => ['doc','docm','docx','dot','dotx','acl','gdoc','mobi','odt'],
        "file-text-o" => ['text','txt','tsv','tex','pages','stw','sxw','xml','0','1st','600','602','ans','asc','epub','log','nb','css','xsl','xslt','tpl','bib','enl','ris'],
        "file-pdf-o" => ['pdf','dvi','pld','egt','pcl','ps','snp','xps'],
        "file-powerpoint-o" => ['ppt','pptx','afp','gslides','key','keynote','odt','otp','pez','prz','sti','sxi','watch'],
        "file-excel-o" => ['xls','xlsx','123','cell','csv','gsheet','numbers','gnumeric','ods','ots','xlk','xlsb','xlsm','xlr','xlt','xltm','xlw'],
        "file-audio-o" => ['wma','bwf','cdda','wav','flac','dts','mp1','mp2','mp3','aac','vox','ogg','asx','m3u'],
        "file-video-o" => ['aaf','3gp','avi','dat','flv','mpeg','mpg','m4v','mpe','wmv','mp4','swf'],
        "file-code-o" => ['c','cpp','java','py','sh','html','xhtml','phtml','mhtml','shtml','class','bat','cmd','ipynb','js','jsfl','pl','php','ps1','vbs','r','rb','cs','go','lisp','scala','asp','jsp','aspx','sql','sqlite'],
        "key" => ['pem','ppk','key','pub','ssh','gxk','cer','cert','der','bpw','kdb','kdbx']
    );
    $type = end(explode(".",$filename));
    foreach ($icons as $key=>$value){
        if(in_array(strtolower($type),$value)){
            return $key;
        }
    }
    return "file";
}
?>