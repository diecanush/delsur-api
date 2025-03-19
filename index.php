<?php
header("Access-Control-Allow-Origin: *");
// Se agregan PUT y DELETE a los métodos permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-HTTP-Method-Override");

include 'config.php';

// Detectar el método HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];
// Si se envía POST con el encabezado X-HTTP-Method-Override, se sobrescribe el método
if ($requestMethod === 'POST' && isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    $requestMethod = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
}

$apiPath = '/api';
$requestUri = $_SERVER['REQUEST_URI'];
$apiPosition = strpos($request<?php
header("Access-Control-Allow-Origin: *");
// Se agregan PUT y DELETE a los métodos permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-HTTP-Method-Override");

include 'config.php';

// Detectar el método HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];
// Si se envía POST con el encabezado X-HTTP-Method-Override, se sobrescribe el método
if ($requestMethod === 'POST' && isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    $requestMethod = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
}

$apiPath = '/api';
$requestUri = $_SERVER['REQUEST_URI'];
$apiPosition = strpos($requestUri, $apiPath);
$request = explode('/', trim(substr($requestUri, $apiPosition + strlen($apiPath)), '/'));

// Si se solicita la lista de tablas
if ($request[0] === 'tables') {
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

    $conexion->close();
    exit();
}

if (isset($request[1])) {
    if ($requestMethod === 'GET' && $request[1] === 'table_structure') {
        include 'handlers/table_structure_handler.php';
        echo estructura($request[0]);
    } elseif ($requestMethod === 'GET' && $request[1] === 'captions') {
        include 'handlers/captions_handler.php';
        echo getCaptions($request[0]);
    } else {
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
} else {
    switch ($requestMethod) {
        case 'GET':
            include 'handlers/get_handler.php';
            break;
    
        case 'POST':
            if ($request[0] === 'upload') {
                include 'handlers/upload.php';
                handleFileUpload();
            } else {
                include 'handlers/post_handler.php';
            }
            break;
    }
}
?>
Uri, $apiPath);
$request = explode('/', trim(substr($requestUri, $apiPosition + strlen($apiPath)), '/'));

// Si se solicita la lista de tablas
if ($request[0] === 'tables') {
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

    $conexion->close();
    exit();
}

if (isset($request[1])) {
    if ($requestMethod === 'GET' && $request[1] === 'table_structure') {
        include 'handlers/table_structure_handler.php';
        echo estructura($request[0]);
    } elseif ($requestMethod === 'GET' && $request[1] === 'captions') {
        include 'handlers/captions_handler.php';
        echo getCaptions($request[0]);
    } else {
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
} else {
    switch ($requestMethod) {
        case 'GET':
            include 'handlers/get_handler.php';
            break;
    
        case 'POST':
            if ($request[0] === 'upload') {
                include 'handlers/upload.php';
                handleFileUpload();
            } else {
                include 'handlers/post_handler.php';
            }
            break;
    }
}
?>
