<?php
    include_once "../root/defaults.php";

    if(isset($_GET)) {
        $sw = htmlspecialchars($_GET["sw"], ENT_QUOTES);
        $no_affiliation = '
                <br><br>
                <small class="text-danger pull-right">* we have no affiliation nor do we endorse the said company/software in any manner </small>
                <br>
                ';
        $hide_loader_script = '
        <script type="text/javascript">
			$(function(){
              $(".imageItself").one("load",function (){
                $(this).parent().find(".imagePreloader").hide();
              }).each(function() {
				  if(this.complete) $(this).load();
		      });
           });
        </script>
        ';

        if($sw == "tableau"){
            list($redshift['url'], $redshift['port']) = explode(":",_REDSHIFT_ENDPOINT);
            print '
            <a class="btn btn-primary pull-right" 
                href="https://www.tableau.com/solutions/workbook/exploring-big-data-cloud" 
                target="_blank">
                Learn More <i class="fa fa-external-link-square"></i>
            </a>
            <div class="clearfix"></div><br/>
            <div class="centered">
                <span class="text-primary imagePreloader"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></span>
                <img class="img img-responsive img-thumbnail imageItself" src="../resources/images/tableau_connect.png" alt="Tableau connection" /><br>
            </div>
                <div class="clearfix"></div><br>
                <ol>
                    <li><b>Server name:</b> <i class="text-primary">'.$redshift['url'].'</i></li>
                    <li><b>Port:</b> <i class="text-primary">'.$redshift['port'].'</i></li>
                    <li><b>Database:</b> <i class="text-primary">'._REDSHIFT_DATABASE.'</i></li>
                    <li><b>Username:</b> <i class="text-primary">'._ADMIN.'</i></li>
                    <li><b>Password:</b> <i class="text-primary"> **chose during stack launch**</i></li>
                </ol>
            '.$no_affiliation.$hide_loader_script;
        } else if($sw == "sqlworkbench") {
            print '
            <a class="btn btn-primary pull-right" 
                href="http://docs.aws.amazon.com/redshift/latest/mgmt/connecting-using-workbench.html" 
                target="_blank">
                Learn More <i class="fa fa-external-link-square"></i>
            </a>
            <div class="clearfix"></div><br/>
            <div class="centered">
                <span class="text-primary imagePreloader"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></span>
                <img class="img img-responsive img-thumbnail imageItself" src="../resources/images/sqlworkbench_connect.png" alt="SQL Workbench/J connection" /><br>
            </div>
                <div class="clearfix"></div><br>
                <ol>
                    <li>
                        <b>Driver:</b>
                        <i class="text-primary">
                            <a href="http://docs.aws.amazon.com/redshift/latest/mgmt/configure-jdbc-connection.html#download-jdbc-driver" 
                                title="Amazon Redshift JDBC Driver - External Link" 
                                target="_blank">
                                AWS Redshift JDBC Driver <i class="fa fa-external-link-square"></i>
                            </a>
                        </i>
                    </li>
                    <li><b>URL:</b> <i class="text-primary">jdbc:redshift://'._REDSHIFT_ENDPOINT.'/'._REDSHIFT_DATABASE.'</i></li>
                    <li><b>User Name:</b> <i class="text-primary">'._ADMIN.'</i></li>
                    <li><b>Password:</b> <i class="text-primary"> **chose during stack launch**</i></li>
                </ol>
            '.$no_affiliation.$hide_loader_script;
        } else if($sw == "otherredshift") {
            print '<div class="centered">
                 <a class="btn btn-primary" 
                    href="http://docs.aws.amazon.com/redshift/latest/mgmt/configure-jdbc-connection.html" 
                    target="_blank">
                    JDBC Connection <i class="fa fa-external-link-square"></i>
                 </a>
                 <a class="btn btn-warning" 
                    href="http://docs.aws.amazon.com/redshift/latest/mgmt/configure-odbc-connection.html" 
                    target="_blank">
                    ODBC Connection <i class="fa fa-external-link-square"></i>
                 </a>
                 <br><br>
                 <a class="btn btn-success" 
                    href="https://aws.amazon.com/redshift/partners/" 
                    target="_blank">
                    AWS Partner tools <i class="fa fa-external-link-square"></i>
                 </a>
             </div>
            ';
        } else if($sw == "mysqlworkbench") {
            list($rds['url'], $rds['port']) = explode(":",_RDS_ENDPOINT);
            print '
            <a class="btn btn-primary pull-right" 
                href="https://dev.mysql.com/doc/workbench/en/wb-getting-started-tutorial-create-connection.html" 
                target="_blank">
                Learn More <i class="fa fa-external-link-square"></i>
            </a>
            <div class="clearfix"></div><br/>
            <div class="centered">
                <span class="text-primary imagePreloader"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></span>
                <img class="img img-responsive img-thumbnail imageItself" src="../resources/images/mysql_workbench_connect.png" alt="MySQL Workbench connection" /><br>
            </div>
                <div class="clearfix"></div><br>
                <ol>
                    <li><b>Hostname:</b> <i class="text-primary">' .$rds['url'].'</i></li>
                    <li><b>Port:</b> <i class="text-primary">'.$rds['port'].'</i></li>
                    <li><b>Username:</b> <i class="text-primary">'._ADMIN.'</i></li>
                    <li><b>Password:</b> <i class="text-primary"> **chose during stack launch**</i></li>
                </ol>
            '.$no_affiliation.$hide_loader_script;
        } else if($sw == "shell") {
            list($rds['url'], $rds['port']) = explode(":",_RDS_ENDPOINT);
            print '
            <a class="btn btn-primary pull-right" 
                href="https://dev.mysql.com/doc/refman/5.7/en/connecting.html" 
                target="_blank">
                Learn More <i class="fa fa-external-link-square"></i>
            </a>
            <div class="clearfix"></div><br/>
            <div class="centered">
                <span class="text-primary imagePreloader"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></span>
                <img class="img img-responsive img-thumbnail imageItself" src="../resources/images/shell_connect.png" alt="Shell connection" /><br>
            </div>
                <div class="clearfix"></div><br>
                <ol>
                    <li><b>Hostname:</b> <i class="text-primary">'.$rds["url"].'</i></li>
                    <li><b>Port:</b> <i class="text-primary">'.$rds["port"].'</i></li>
                    <li><b>User Name:</b> <i class="text-primary">'._ADMIN.'</i></li>
                    <li><b>Password:</b> <i class="text-primary"> **chose during stack launch**</i></li>
                </ol>
            '.$hide_loader_script;
        } else if($sw == "datapipeline") {
            print '
              <script type="text/javascript" src="../resources/js/datapipelineUtilities.js"></script>
              <div id="datapipelineresult"></div>
              <div id="datapipelinespinner" class="centered text-primary"><i class="fa fa-spinner fa-spin fa-4x fa-fw"></i><span class="sr-only">Loading...</span></div>
              <div id="datapipelinestatus"></div>
              <div id="datapipelineInit">  
                <p class="text-success">Run a Full-load datapipeline to copy RDS table to Redshift</p><br>
                <form class="form-horizontal" method="post" action="#" id="runDatapipelineForm" >
                    <div class="form-group">
                        <label for="wgroup" class="col-sm-4 control-label">Worker Group</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="wgroup" name="wgroup"  placeholder="Worker Group" value="datalakeworkergroup-'._ACCOUNT_ID.'-'._STACK_UID.'" required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rdsconn" class="col-sm-4 control-label">RDS Connection String</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rdsconn" name="rdsconn" placeholder="RDS Connection String" required  value="jdbc:mysql://'._RDS_ENDPOINT.'/'._RDS_DATABASE.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rdsuser" class="col-sm-4 control-label">RDS Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rdsuser" name="rdsuser" placeholder="RDS username" required  value="'._ADMIN.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rdspass" class="col-sm-4 control-label">RDS Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="rdspass" name="rdspass" placeholder="RDS Password" required  value="'._PASSWORD.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tabletocopy" class="col-sm-4 control-label">Table to Copy</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tabletocopy" name="tabletocopy" placeholder="RDS Table to be copied" required >
                            <small class="text-danger pull-right">*table must have a primary key for proper loading</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="redconn" class="col-sm-4 control-label">Redshift Connection String</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="redconn" name="redconn"  placeholder="Redshift Connection String" required readonly value="jdbc:redshift://'._REDSHIFT_ENDPOINT.'/'._REDSHIFT_DATABASE.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reduser" class="col-sm-4 control-label">Redshift Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="reduser" name="reduser" placeholder="Redshift username" required readonly value="'._ADMIN.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="redpass" class="col-sm-4 control-label">Redshift Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="redpass" name="redpass" placeholder="Redshift Password" required readonly value="'._PASSWORD.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <br/>
                            <input type="submit" id="runDatapipelineSubmit" class="btn btn-success btn-block" value="Run Datapipeline"/>
                        </div>
                    </div>
                </form>
              </div>  
            ';
        } else {
            print 'You aren\'t looking at the right place';
        }
    }
?>