<?php
    require_once 'conexion.php';

    function grabarRegistro($tabla,$datos){
        var_dump($datos);

        $array_datos = get_object_vars($datos);
        var_dump($array_datos);

        $campos = implode(",",array_keys($array_datos));
        var_dump($campos);

        $valores = "'".implode("','",array_values($array_datos))."'";
        var_dump($valores);

        $consulta = "INSERT INTO $tabla ($campos) VALUES ($valores)";
        
        var_dump($consulta);

        $respuesta = json_encode(conectar()->query($consulta));
        echo $respuesta;
        return $respuesta;

    }

    




?>