<?php
include "db_connection.php";

$tableName = $request[0]; // Obtener el nombre de la tabla desde la solicitud GET

$query = "SELECT * FROM $tableName";

if (isset($request[1])) {
    include "table_structure_handler.php";
    foreach (json_decode(estructura($tableName), true) as $columna) {
        if ($columna['primary_key']) $clave = $columna['nombre'];
    }
    $query .= " WHERE $clave = $request[1]";
}

$result = $conexion->query($query);

if ($result) {
    $rows = array();
    while ($row = $result->fetch_array(1)) {
        $rows[] = $row;
    }
    echo json_encode($rows);
} else {
    echo json_encode(array("error" => $conexion->error));
}
