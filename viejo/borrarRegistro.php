<?php

    require_once('conexion.php');
    require_once('verCampos.php');
    header('Access-Control-Allow-Origin: *');

    

    function borrarRegistro($tabla,$id){
        $clave = clave($tabla);
        //echo $clave;
        $consulta = "DELETE FROM $tabla WHERE $clave = $id";
        echo $consulta;
        if(conectar()->query($consulta)){

            echo("El registro fue eliminado"); 
         }else{
             echo("No se pudo eliminar el registro");
         }
    }
    //borrarRegistro('articulos',6);
?>