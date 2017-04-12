<?php
include_once("../root/header.php");
include_once("../root/AwsFactory.php");
checkSession();

    $aws = new AwsFactory();
    $client = $aws->getDynamoDBClient();
    $dynamodb_error = null;
    $tablename = isset($_GET["table"]) ? $_GET["table"] : null;

    print '<div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">';
        print '<button class="btn btn-warning btn-sm pull-left" onclick="javascript:window.history.back();"><span class="glyphicon glyphicon-chevron-left"></span> go back</button><br>';
        print '<h1 class="text-primary">'.(!is_null($tablename) ? $tablename : 'DynamoDB Tables:').'</h1>';
        if ($tablename == null) {
            try {
                $result = $client->listTables([]);
                $tables = array(_DYNAMODB_T_MASK,_DYNAMODB_T_MAP,_DYNAMODB_T_STREAM);

                print '<ul class="list-group">';
                foreach ($result["TableNames"] as $table) {
                    if (in_array($table, $tables)) {
                        print '<li class="list-group-item"><a href="?table=' . $table . '">' . $table . '</a></li>';
                    }
                }
                print '</ul>';
            } catch (\Aws\DynamoDb\Exception\DynamoDbException $ex){
                print '<div class="alert alert-danger">Error listing DynamoDB tables. <br>Error: '.$ex->getAwsErrorCode().'</div>';
            } catch (Exception $ex){
                print '<div class="alert alert-danger">Error listing DynamoDB tables. <br>Error: '.$ex->getMessage().'</div>';
            }
        } else {
            try {
                $response = $client->scan([
                    'TableName' => $tablename
                ]);
                $head = false;
                print '<table class="table table-responsive table-bordered table-striped">';
                foreach ($response["Items"] as $item) {
                    if (!$head) {
                        print '<tr class="success">';
                        foreach (array_keys($item) as $colname) {
                            print '<td>' . $colname . '</td>';
                        }
                        print '</tr>';
                        $head = true;
                    }
                    print '<tr>';
                    foreach ($item as $field) {
                        print '<td>' . $field["S"] . '</td>';
                    }
                    print '</tr>';
                }
                print '</table>';
            } catch (\Aws\DynamoDb\Exception\DynamoDbException $ex){
                print '<div class="alert alert-danger">Cannot load Table '.$tablename.' <br>Error: '.$ex->getAwsErrorCode().'</div>';
            } catch (Exception $ex){
                print '<div class="alert alert-danger">Cannot load Table '.$tablename.' <br>Error: '.$ex->getMessage().'</div>';
            }
        }
    print '
    </div>
    <div class="col-lg-1 col-md-1"></div>    
    ';


include_once("../root/footer.php");

?>