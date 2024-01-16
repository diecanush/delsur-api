<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    function conectar(){
        $conexion = new mysqli('localhost', 'root','','delsur' );
        //echo "<h1>Conectado a la base de datos</h1>";
        //var_dump($conexion);
        return $conexion;
        }
?>