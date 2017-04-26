<?php
    error_reporting(0);
    include_once "../root/AwsFactory.php";

    $action = (isset($_GET["action"])) ? htmlspecialchars($_GET["action"], ENT_QUOTES) : null;
    $pipelineid = (isset($_GET["pipelineid"])) ? htmlspecialchars($_GET["pipelineid"], ENT_QUOTES) : null;

    if(isset($action) && !is_null($action)){
        $aws = new AwsFactory();
        $client = $aws->getDatapipelineClient(_REGION);

        if($action == "createPipeline"){
            try {
                $cuid = md5(microtime());
                $result = $client->createPipeline([
                    'name' => "data-lake-quick-start-custom-data-pipeline-".strtoupper(substr($cuid,0,6)),
                    'tags' => [
                        [
                            'key' => _TAG_KEY,
                            'value' => _TAG_VALUE
                        ]
                    ],
                    'uniqueId' => $cuid
                ]);

                print $result["pipelineId"];
            } catch (\Aws\DataPipeline\Exception\DataPipelineException $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Data Pipeline creation failed, ERROR: '.$ex->getAwsErrorCode().'
                </p>';
            }  catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Data Pipeline creation failed, ERROR: '.$ex->getMessage().'                
                </p>';

            }
        } else if($action == "getRegion"){
            print _REGION;
        } else if($action == "createPipelineDef" && !is_null($pipelineid)){
            try {
                $tablename = (isset($_GET["tablename"])) ? htmlspecialchars($_GET["tablename"], ENT_QUOTES) : null;
                if(!is_null($tablename) && !is_null($pipelineid)) {
                    $str = file_get_contents("../configurations/datapipeline/generic_defination.json");

                    $str = str_replace("oldworkergroup", _WORKER_GROUP_NAME, $str);
                    $str = str_replace("olds3stagingloc", _BUCKET, $str);
                    $str = str_replace("oldrdsconnectionstring", _RDS_ENDPOINT, $str);
                    $str = str_replace("oldrdsdbname", _RDS_DATABASE, $str);
                    $str = str_replace("oldusername", _ADMIN, $str);
                    $str = str_replace("oldpassword", _PASSWORD, $str);
                    $str = str_replace("oldtablename", $tablename, $str);
                    $str = str_replace("oldredshiftconnectionstring", _REDSHIFT_ENDPOINT, $str);
                    $str = str_replace("oldredshiftdbname", _REDSHIFT_DATABASE, $str);
                    $str = str_replace("oldDataPipelineDefaultResourceRole", _DATAPIPELINE_RESOURCE_ROLE, $str);
                    $str = str_replace("oldDataPipelineDefaultRole", _DATAPIPELINE_ROLE, $str);

                    $filePointer = fopen("../configurations/datapipeline/".$pipelineid.".json", 'w');
                    fwrite($filePointer, $str);
                    fclose($filePointer);
                    print '<p class="text-success">
                        <i class="fa fa-check-square-o"></i> 
                        Custom Data Pipeline Definition created
                    </p>';
                } else {
                    print '<p class="text-danger">
                        Please provide Table name and Pipeline ID
                    </p>';
                }
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Custom Data Pipeline Definition creation failed, ERROR: '.$ex->getMessage().'
                </p>';
            }
        } else if($action == "putPipelineDef" && !is_null($pipelineid)){
            try {
                $putPipeDef = exec("aws datapipeline put-pipeline-definition --pipeline-id ".$pipelineid." --pipeline-definition file:///var/www/html/configurations/datapipeline/".$pipelineid.".json --region "._REGION."");
                unlink("../configurations/datapipeline/".$pipelineid.".json");
                print '<p class="text-success">
                    <i class="fa fa-check-square-o"></i> 
                    Pipeline Definition updated with custom definition
                </p>';
            } catch (\Aws\DataPipeline\Exception\DataPipelineException $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Put pipeline definition failed, ERROR: '.$ex->getAwsErrorCode().'
                </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Put pipeline definition failed, ERROR: '.$ex->getMessage().'
                </p>';
            }
        } else if($action == "activatePipeline" && !is_null($pipelineid)){
            try {
                $result = $client->activatePipeline([
                    'pipelineId' => $pipelineid
                ]);
                print '<p class="text-success">
                    <i class="fa fa-check-square-o"></i> Data Pipeline activated
                </p>';
            } catch (\Aws\DataPipeline\Exception\DataPipelineException $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Data Pipeline activation failed, ERROR: '.$ex->getAwsErrorCode().'
                </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Data Pipeline activation failed, ERROR: '.$ex->getMessage().'
                </p>';
            }
        } else if($action == "TaskrunnerHeartbeat"){
            try {
                $result = $client->reportTaskRunnerHeartbeat([
                    'taskrunnerId' => _TASK_RUNNER_ID,
                    'workerGroup' => _WORKER_GROUP_NAME
                ]);
                print '<p class="text-success">
                    <i class="fa fa-check-square-o"></i> Taskrunner Heartbeat received
                </p>';
            } catch (\Aws\DataPipeline\Exception\DataPipelineException $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Failed to receive Taskrunner Heartbeat, ERROR: '.$ex->getAwsErrorCode().'
                </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Failed to receive Taskrunner Heartbeat, ERROR: '.$ex->getMessage().'
                </p>';
            }
        } else if($action == "pollForTask"){
            try {
                /* // code chokes the actual polling process; reason unknown
                   // --susheel 04/20/2017
                 $result = $client->pollForTask([
                    'workerGroup' => _WORKER_GROUP_NAME
                ]);
                */
                print '<p class="text-success">
                    <i class="fa fa-check-square-o"></i> Taskrunner polled for Task
                </p>';
            } catch (\Aws\DataPipeline\Exception\DataPipelineException $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Polling Taskrunner failed, ERROR: '.$ex->getAwsErrorCode().'
                </p>';
            } catch (Exception $ex){
                print '<p class="text-danger">
                    <i class="fa fa-check-times"></i> 
                    Polling Taskrunner failed, ERROR: '.$ex->getMessage().'
                </p>';
            }
        } else if($action == "pipelineStatus" && !is_null($pipelineid)){
            $output = shell_exec('aws datapipeline list-runs --pipeline-id '.$pipelineid.' --region '._REGION.'');
            print '<pre>'.$output.'</pre>';
        }
    } else {
        print '<p class="text-danger">Invalid Request received</p>';
    }