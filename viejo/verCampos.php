<?php
    require_once ("conexion.php");

    function clave($tabla){
        $campos = conectar()->query("SHOW COLUMNS FROM $tabla")->fetch_all(1);
        foreach($campos as $campo){
            if ($campo['Key']=='PRI') return $campo['Field'];
        }
    }

    //var_dump(clave('categorias'));

?>