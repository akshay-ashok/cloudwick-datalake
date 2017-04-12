<?php
    include_once("../root/header.php");
    include_once("../root/ConnectionManager.php");
    checkSession();

    $rdsConnector = (new ConnectionManager())->getRdsConnector();
    $get_explore = (isset($_GET["explore"])) ? sanitizeParameter($_GET["explore"]) : null;
    $get_schema = (isset($_GET["schema"])) ? sanitizeParameter($_GET["schema"]) : null;
    $get_table = (isset($_GET["table"])) ? sanitizeParameter($_GET["table"]) : null;

    print '<div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">        
        <button class="btn btn-warning btn-sm pull-left" onclick="javascript:window.history.back();"><span class="glyphicon glyphicon-chevron-left"></span> go back</button><br><br>
        <div class="btn-group" role="group" aria-label="...">
            <a href="../aws-resources/rds.php?explore=table" class="btn btn-info">Explore Table(s)</a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Explore Table(s) Schema
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="../aws-resources/rds.php?explore=schema&schema=patient">Patient Table</a></li>
                  <li><a href="../aws-resources/rds.php?explore=schema&schema=physician">Physician Table</a></li>
                  <li><a href="../aws-resources/rds.php?explore=schema&schema=provider">Provider Table</a></li>
                  <li><a href="../aws-resources/rds.php?explore=schema&schema=billing">Billing Table</a></li>
                </ul>
            </div>
        </div><br><br>';

        if(!is_null($get_explore)) {
            if($get_explore == "schema") {
                try {
                    if(!is_null($get_schema)) {
                        $query = "desc ".$get_schema."";
                    } else {
                        $query = "SELECT * FROM pg_table_def WHERE schemaname = 'public' ORDER BY tablename";
                    }
                    $result = $rdsConnector->query($query);
                    if ($result->rowCount() > 0) {
                        print '<table class="table table-responsive table-bordered table-striped">
                        <tr class="info">
                            <td>Table Name</td>
                            <td>Field Name</td>
                            <td>Type</td>
                            <td>is Null</td>
                        </tr>';

                        if(!is_null($get_schema)){
                            $explore_type = "table&table";
                        } else {
                            $explore_type = "schema&schema";
                        }

                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            print '<tr>
                                <td><a href="../aws-resources/rds.php?explore='.$explore_type.'=' .  $get_schema . '">' . $get_schema . '</a></td>
                                <td>' . $row["Field"] . '</td>
                                <td>' . $row["Type"] . '</td>
                                <td>' . $row["Null"] . '</td>
                            </tr>';
                        }
                        print '</table>';
                    } else {
                        print '<div class="alert alert-danger">No Tables/schemas found</div>';
                    }
                } catch (PDOException $ex) {
                    printException($ex);
                }
            } else if($get_explore == "table"){
                if(!is_null($get_table)){
                    $query = "SELECT * FROM ".$get_table." ";
                } else {
                    $query = "show tables";
                }
                try {
                    $result = $rdsConnector->query($query);
                    if ($result->rowCount() > 0) {
                        if(!is_null($get_table)) {
                            $count = $result->columnCount();
                            print '<table class="table table-responsive table-bordered table-striped">
                            <tr class="info">';
                            $cols = 0;
                            while ($cols < $count) {
                                $colis = $result->getColumnMeta($cols++);
                                print '<td>' . $colis["name"] . '</td>';
                            }
                            print '</tr>';

                            while ($row = $result->fetch(PDO::FETCH_BOTH)) {
                                print '<tr>';
                                $rows = 0;
                                while ($rows < $count) {
                                    print '<td>' . $row[$rows++] . '</td>';
                                }
                                print '</tr>';
                            }
                            print '</table>';
                        } else {
                            print '<table class="table table-responsive table-bordered table-striped">
                            <tr class="info">
                                <td>Schemaname</td>
                                <td>Table</td>
                            </tr>';
                            while ($row = $result->fetch(PDO::FETCH_ASSOC) ){
                                print '<tr>
                                    <td>'._RDS_DATABASE.'</td>
                                    <td><a href="../aws-resources/rds.php?explore=table&table='.$row["Tables_in_datalake"].'">'.$row["Tables_in_datalake"].'</a></td>
                                </tr>';
                            }
                            print '</table>';
                        }
                    } else {
                        print '<div class="alert alert-danger">No data found !!</div>';
                    }
                } catch (PDOException $ex) {
                    printException($ex);
                }
            }
        } else {
            print '<div class="alert alert-info"><h3>Please Choose from above menu</h3></div>';
        }



    print '</div>
    <div class="col-lg-1 col-md-1"></div>';

    include_once("../root/footer.php");

?>