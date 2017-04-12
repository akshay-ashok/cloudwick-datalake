<?php
/** Defining class to create PHP DATA OBJECT (PDO)**/
class DBConnector extends PDO
{
    const charset="utf8";

    public function __construct($name,$driver,$hostname,$port,$username,$password,$database)
    {
        try{
            if($driver == "pgsql"){
                $url=''.$driver.':host='.$hostname.';port='.$port.';dbname='.$database.'';
            } else {
                $url = '' . $driver . ':host=' . $hostname . ';port=' . $port . ';dbname=' . $database . ';charset=' . DBConnector::charset . '';
            }
            parent::__construct($url,$username,$password);
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            parent::setAttribute(PDO::ATTR_PERSISTENT, true); // for persistent database connection !
            parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            parent::setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
        catch(PDOException $ex)
        {
            print ('<div class="alert alert-danger">Error Connecting to '.$name.' Database !! <br>'.$ex->getMessage().'<br>'.$url.'</div>');
        }
    }
}
/* End of Class DBConnector */
?>