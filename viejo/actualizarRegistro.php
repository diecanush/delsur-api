<?php
    require_once 'conexion.php';
    require_once 'verCampos.php';

    function actualizarRegistro($tabla,$id,$datos){
        //var_dump($datos);

        $array_datos = get_object_vars($datos);
        //var_dump($array_datos);

        $clave = clave($tabla);

        $consulta = "UPDATE $tabla SET ";

        foreach($array_datos as $campo => $valor){
            $consulta .= "$campo ='$valor',";
        }

        $consulta = rtrim($consulta,',');

        $consulta .= " WHERE $clave = $id";
 
        var_dump($consulta);

        if(conectar()->query($consulta)){
           echo("Los datos fueron grabados"); 
        }else{
            echo("No se pudieron grabar los datos");
        }
        

    }

    




?>