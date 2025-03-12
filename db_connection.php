<?php
// db_connection.php

$host = 'localhost';
$usuario = 'u253295415_delsur';
$contrasena = 'Bobo4660';
$base_de_datos = 'u253295415_delsur';

$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

