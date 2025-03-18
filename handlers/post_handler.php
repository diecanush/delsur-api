<?php
// post_handler.php

include 'db_connection.php';

// Definir o recuperar $request (por ejemplo, a partir de la URL)
//$request = explode('/', trim($_SERVER['REQUEST_URI'],'/'));

header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Array para almacenar las URLs de las imágenes
$imageUrls = [];

// Definir la carpeta donde se almacenarán las imágenes (ajusta según tu estructura)
$uploadDir = dirname(dirname(__DIR__)) . '/imagenes/';

// Verificar que la carpeta exista, si no, crearla
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Procesar el campo "url_imagen" si existe
if (isset($_FILES['url_imagen'])) {
    // Caso de múltiples archivos
    if (is_array($_FILES['url_imagen']['name'])) {
        $fileCount = count($_FILES['url_imagen']['name']);
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['url_imagen']['error'][$i] === UPLOAD_ERR_OK) {
                $fileInfo = pathinfo($_FILES['url_imagen']['name'][$i]);
                $extension = strtolower($fileInfo['extension']);
                if (!in_array($extension, $allowedTypes)) {
                    continue; // O puedes manejar el error de otro modo
                }
                if ($_FILES['url_imagen']['size'][$i] > $maxSize) {
                    continue;
                }
                // Generar un nombre único para cada archivo
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
        // Caso de un solo archivo (opcional)
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
} else {
    // Puedes registrar un mensaje o asignar un valor predeterminado si no se envía imagen
}

// Obtener el resto de los datos enviados (se intenta primero JSON, sino se usan $_POST)
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);
if (!$data) {
    $data = $_POST;
}

// Si se subieron imágenes, guardarlas en el campo 'url_imagen' como JSON
if (!empty($imageUrls)) {
    $data['url_imagen'] = json_encode($imageUrls);
} else {
    $data['url_imagen'] = "no se cargo imagen";
}

// Definir la tabla donde se insertará el registro (usando $request[0])
$table = $request[0];

// Construir la consulta de inserción
$column_names = implode(", ", array_keys($data));
$column_values = "'" . implode("', '", array_values($data)) . "'";
$query = "INSERT INTO $table ($column_names) VALUES ($column_values)";

// Ejecutar la consulta
$result = $conexion->query($query);

if ($result) {
    $last_insert_id = $conexion->insert_id;
    $response = array(
        "mensaje" => "Registro insertado correctamente",
        "nuevo_registro_id" => $last_insert_id,
        "url_imagen" => $data['url_imagen']
    );
    echo json_encode($response);
} else {
    $response = array(
        "error" => "Error al insertar el registro: " . $conexion->error
    );
    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
