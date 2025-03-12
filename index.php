<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


// router.php

include 'config.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
//var_dump ($requestMethod);
//var_dump($_SERVER);
$apiPath = '/api';
$requestUri = $_SERVER['REQUEST_URI'];
$apiPosition = strpos($requestUri, $apiPath);
$request = explode('/', trim(substr($requestUri, $apiPosition + strlen($apiPath)), '/'));
//var_dump($request);

if ($request[0] === 'tables') {
    // Caso especial para obtener el nombre de las tablas
    include 'db_connection.php';
    $result = $conexion->query("SHOW TABLES");
    
    if ($result) {
        $tables = array();
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
        
        echo json_encode($tables);
    } else {
        echo json_encode(array("error" => $conexion->error));
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
    exit();
}

if (isset($request[1])){
    if ($requestMethod === 'GET' && $request[1] === 'table_structure') {
        include 'handlers/table_structure_handler.php';
        echo estructura($request[0]);
    } else {
        // Resto del enrutamiento para las otras tablas/endpoints
        switch ($requestMethod) {
            case 'GET':
                include 'handlers/get_handler.php';
                break;
                
            case 'PUT':
                include 'handlers/put_handler.php';
                break;
        
            case 'DELETE':
                include 'handlers/delete_handler.php';
                break;
        
            default:
                header("HTTP/1.0 405 Method Not Allowed");
                break;
        }
    }
}else{
    switch ($requestMethod) {
        case 'GET':
            include 'handlers/get_handler.php';
            break;
    
        case 'POST':
            include 'handlers/post_handler.php';
            break;
    }
}
