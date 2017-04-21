<?php
include_once "../root/header.php";
include_once "../root/defaults.php";
checkSession();

function adminer_object() {

    class AdminerSoftware extends Adminer {
        function name(){
            return 'DLQS';
        }

        function credentials() {
            return array(_RDS_ENDPOINT, _ADMIN, _PASSWORD);
        }

        function database() {
            return _RDS_DATABASE;
        }

        function tablesPrint($tables) {
            echo "<ul id='tables'>\n";
            foreach ($tables as $table => $status) {
                //echo '<li><a href="' . h(ME) . 'select=' . urlencode($table) . '"' . bold($_GET["select"] == $table || $_GET["edit"] == $table, "select") . ">" . lang('select') . "</a> ";
                $name = $this->tableName($status);
                echo (support("table") || support("indexes")
                        ? '<a href="' . h(ME) . 'table=' . urlencode($table) . '"'
                        . bold(in_array($table, array($_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"])), (is_view($status) ? "view" : "structure"))
                        . " title='" . lang('Show structure') . "'><i class='fa fa-table'></i> $name</a><br>"
                        : "<span>$name</span>"
                    ) . "\n";
            }
            echo "</ul>\n";
        }
    }

    return new AdminerSoftware;
}
$_GET['username'] = _ADMIN;
$_GET['db'] = _RDS_DATABASE;
print '
    <div class="clearfix"></div><br>
    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 contentBody">
    <br><br>
        ';
    include_once "../aws-resources/mysql-adminer.php";
print '
    </div>
    <div class="col-lg-1 col-md-1"></div>
    ';
include_once "../root/footer.php";
?>