<?php
// put_handler.php

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

// Obtener los datos del cuerpo de la solicitud
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

// Construir la consulta UPDATE
$updates = array();
foreach ($data as $campo => $valor) {
    $updates[] = "$campo = '$valor'";
}

$query = "UPDATE $tableName SET " . implode(', ', $updates) . " WHERE $clave = $recordId";

// Ejecutar la consulta
$result = $conexion->query($query);

if ($result) {
    // Respuesta de éxito
    $response = array(
        "mensaje" => "Registro actualizado correctamente"
    );

    echo json_encode($response);
} else {
    // Respuesta de error
    $response = array(
        "error" => "Error al actualizar el registro: " . $conexion->error
    );

    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
