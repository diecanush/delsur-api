<?php
    header('Access-Control-Allow-Origin: *');
    require_once('conexion.php');

    function getRegistro($tabla,$registro){
        //obtener el campo clave agregandole id_ y quitandole la s final al nombre de la tabla
        require_once ('verCampos.php');
        $clave = clave($tabla);
        var_dump($clave);
        //devolver un json con el resultado de la consulta usando el nombre de la tabla, la clave y el identificador
        return json_encode(conectar()->query("SELECT * FROM $tabla WHERE $clave = $registro")->fetch_all(1));

    }
    //var_dump(getRegistro('articulos',1));
?>