<?php
    header('Access-Control-Allow-Origin: *');
    require_once('conexion.php');
    //echo 'listado: <br>';
    function getTabla($tabla){
        return json_encode(conectar()->query("SELECT * FROM $tabla")->fetch_all(1));
    }
?>