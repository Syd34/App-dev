<?php
include_once '..config/db_config.php';


function getConnection() {
    global $conn;
    return $conn;
}
?>