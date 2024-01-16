<?php
// delete_handler.php

include 'db_connection.php';
include 'table_structure_handler.php';

// Obtener el nombre de la tabla y el ID del registro de la URL
$tableName = $request[0];
$recordId = $request[1];

// Obtener la estructura de la tabla
$structure = json_decode(estructura($tableName), true);

// Buscar el nombre del campo clave
$clave = '';
foreach ($structure as $columna) {
    if ($columna['primary_key']) {
        $clave = $columna['nombre'];
        break;
    }
}

// Verificar si se encontró el campo clave
if (empty($clave)) {
    echo json_encode(array("error" => "No se encontró el campo clave para la tabla $tableName"));
    exit();
}

// Construir la consulta DELETE
$query = "DELETE FROM $tableName WHERE $clave = $recordId";

// Ejecutar la consulta
$result = $conexion->query($query);

if ($result) {
    // Respuesta de éxito
    $response = array(
        "mensaje" => "Registro eliminado correctamente"
    );

    echo json_encode($response);
} else {
    // Respuesta de error
    $response = array(
        "error" => "Error al eliminar el registro: " . $conexion->error
    );

    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
