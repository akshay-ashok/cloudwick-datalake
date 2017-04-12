<?php
include_once("../root/defaults.php");
require_once("../root/DBConnector.php");

/** Defining class to create PHP DATA OBJECT (PDO)**/

class ConnectionManager extends DBConnector
{
    /**
     * ConnectionManager constructor.
     */
    public function __construct()
    {
        //No constructor definition
    }

    /**
     * @return DBConnector
     */
    function getMysqlConnector(){
        return new DBConnector("Mysql","mysql","localhost","3306",_ADMIN,_PASSWORD,"datalake");
    }

    /**
     * @return DBConnector
     */
    function getRdsConnector(){
        list($rds['url'], $rds['port']) = explode(":",_RDS_ENDPOINT);
        return new DBConnector("RDS","mysql",$rds["url"],$rds["port"],_ADMIN,_PASSWORD,_RDS_DATABASE);
    }

    /**
     * @return DBConnector
     */
    function getRedshiftConnector(){
        list($redshift['url'], $redshift['port']) = explode(":",_REDSHIFT_ENDPOINT);
        return new DBConnector("Redshift","pgsql",$redshift["url"],$redshift["port"],_ADMIN,_PASSWORD,_REDSHIFT_DATABASE);
    }
}
/* End of Class ConnectionManager */
?>
