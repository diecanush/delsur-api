<?php
// post_handler.php

include 'db_connection.php';

// Obtener el JSON del cuerpo de la solicitud
$json_data = file_get_contents("php://input");

// Decodificar el JSON
$data = json_decode($json_data, true);

// Construir la consulta de inserción
$column_names = implode(", ", array_keys($data));
$column_values = "'" . implode("', '", array_values($data)) . "'";

$query = "INSERT INTO $request[0] ($column_names) VALUES ($column_values)";

// Ejecutar la consulta
$result = $conexion->query($query);

if ($result) {
    // Obtener el ID del nuevo registro insertado
    $last_insert_id = $conexion->insert_id;

    // Respuesta de éxito
    $response = array(
        "mensaje" => "Registro insertado correctamente",
        "nuevo_registro_id" => $last_insert_id
    );

    echo json_encode($response);
} else {
    // Respuesta de error
    $response = array(
        "error" => "Error al insertar el registro: " . $conexion->error
    );

    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conexion->close();
