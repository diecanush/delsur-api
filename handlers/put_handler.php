<?php
// put_handler.php

include 'db_connection.php';
include 'table_structure_handler.php';

// Obtener la ruta: se espera /nombre_tabla/id
//$request = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
$tableName = $request[0];
$recordId = $request[1];

// Obtener la estructura de la tabla para identificar el campo clave
$structure = json_decode(estructura($tableName), true);
$clave = '';
foreach ($structure as $columna) {
    if ($columna['primary_key']) {
        $clave = $columna['nombre'];
        break;
    }
}
if (empty($clave)) {
    echo json_encode(["error" => "No se encontró el campo clave para la tabla $tableName"]);
    exit();
}

// Obtener los datos enviados (con HTTP Method Override se envían como POST)
$data = $_POST;
if (!$data || count($data) == 0) {
    echo json_encode(["error" => "No se recibieron datos"]);
    exit();
}

// Definir la carpeta donde se almacenarán las imágenes (ajusta la ruta según tu estructura)
$uploadDir = dirname(dirname(__DIR__)) . '/imagenes/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Array para almacenar las URLs de las imágenes subidas
$imageUrls = [];

// Procesar la carga de imágenes para el campo "url_imagen"
if (isset($_FILES['url_imagen'])) {
    // Caso de múltiples archivos (notación array)
    if (is_array($_FILES['url_imagen']['name'])) {
        $fileCount = count($_FILES['url_imagen']['name']);
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['url_imagen']['error'][$i] === UPLOAD_ERR_OK) {
                $fileInfo = pathinfo($_FILES['url_imagen']['name'][$i]);
                $extension = strtolower($fileInfo['extension']);
                if (!in_array($extension, $allowedTypes)) {
                    continue;
                }
                if ($_FILES['url_imagen']['size'][$i] > $maxSize) {
                    continue;
                }
                // Generar un nombre único para el archivo
                $newFileName = time() . '_' . uniqid() . '.' . $extension;
                $destination = $uploadDir . $newFileName;
                if (move_uploaded_file($_FILES['url_imagen']['tmp_name'][$i], $destination)) {
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                    $host = $_SERVER['HTTP_HOST'];
                    $baseUrl = dirname(dirname($_SERVER['SCRIPT_NAME']));
                    $baseUrl = str_replace('//', '/', $baseUrl);
                    $imageUrl = $protocol . $host . $baseUrl . "/imagenes/" . $newFileName;
                    $imageUrls[] = $imageUrl;
                }
            }
        }
    } else {
        // Caso de un solo archivo
        if ($_FILES['url_imagen']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $maxSize = 5 * 1024 * 1024;
            $fileInfo = pathinfo($_FILES['url_imagen']['name']);
            $extension = strtolower($fileInfo['extension']);
            if (in_array($extension, $allowedTypes) && $_FILES['url_imagen']['size'] <= $maxSize) {
                $newFileName = time() . '_' . uniqid() . '.' . $extension;
                $destination = $uploadDir . $newFileName;
                if (move_uploaded_file($_FILES['url_imagen']['tmp_name'], $destination)) {
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                    $host = $_SERVER['HTTP_HOST'];
                    $baseUrl = dirname(dirname($_SERVER['SCRIPT_NAME']));
                    $baseUrl = str_replace('//', '/', $baseUrl);
                    $imageUrl = $protocol . $host . $baseUrl . "/imagenes/" . $newFileName;
                    $imageUrls[] = $imageUrl;
                }
            }
        }
    }
}

// Si se subieron imágenes, asignarlas al campo "url_imagen" (guardando el array en JSON)
if (!empty($imageUrls)) {
    $data['url_imagen'] = json_encode($imageUrls);
}

// Construir la consulta UPDATE (nota: este método es simple y vulnerable a inyección SQL)
$updates = [];
foreach ($data as $campo => $valor) {
    $updates[] = "$campo = '$valor'";
}
$query = "UPDATE $tableName SET " . implode(', ', $updates) . " WHERE $clave = $recordId";

// Ejecutar la consulta
$result = $conexion->query($query);
if ($result) {
    echo json_encode(["mensaje" => "Registro actualizado correctamente"]);
} else {
    echo json_encode(["error" => "Error al actualizar el registro: " . $conexion->error]);
}

$conexion->close();
?>
