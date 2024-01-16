<?php

require_once ('conexion.php');

function obtenerInfoTabla($nombreTabla) {
    // Conectarse a la base de datos usando la función conectar() (asegúrate de tenerla definida)
    $conexion = conectar();

    // Verificar si la conexión fue exitosa
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta para obtener la información de los campos y la clave primaria de la tabla
    $consulta = "DESCRIBE $nombreTabla";
    $resultado = $conexion->query($consulta);

    if (!$resultado) {
        die("Error en la consulta: " . $conexion->error);
    }
    //var_dump($resultado->fetch_all());
    // Obtener la clave primaria de la tabla
    $clavePrimaria = "";
    $campos = [];
    $relaciones = [];

    while ($fila = $resultado->fetch_assoc()) {
        if ($fila['Key'] === 'PRI') {
            $clavePrimaria = $fila['Field'];
        }else{

            $consulta = "SELECT DISTINCTROW
                        COLUMN_NAME,
                        REFERENCED_TABLE_NAME,
                        REFERENCED_COLUMN_NAME
                    FROM
                        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE
                        TABLE_NAME = '".$nombreTabla."'
                        AND COLUMN_NAME = '".$fila['Field']."'";
        
            //echo $consulta;

            $relacion = conectar()->query($consulta)->fetch_assoc();
            
            if (!$relacion) {
                //echo " no hay relacion en ".$fila['Field'];
            }else{
                $fila['foreign_key'] = true;
                $fila['foreign_table'] = $relacion['REFERENCED_TABLE_NAME'];
                $fila['foreign_column'] = $relacion['REFERENCED_COLUMN_NAME'];
                $fila['valores'] = obtenerValoresPosibles($fila['foreign_table'],$fila['foreign_column']);
            }

        }

        
        $campos[] = $fila;
    }

    // Crear un arreglo asociativo con la información
    $infoTabla = [
        'nombre_tabla' => $nombreTabla,
        'clave_primaria' => $clavePrimaria,
        'campos' => $campos,//,
        //'relaciones' => $relaciones
    ];

    // Convertir el arreglo asociativo a formato JSON
    $jsonInfoTabla = json_encode($infoTabla);

    // Cerrar la conexión a la base de datos
    $conexion->close();

    return $jsonInfoTabla;
}

// Función para obtener los valores posibles de una clave foránea
function obtenerValoresPosibles($tablaRelacionada, $columnaNombre) {
        // Conectarse a la base de datos usando la función conectar() (asegúrate de tenerla definida)
    $conexion = conectar();

    // Verificar si la conexión fue exitosa
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta para obtener los valores posibles de la columna deseada en la tabla relacionada
    $consulta = "SELECT $columnaNombre FROM $tablaRelacionada";
    $resultado = $conexion->query($consulta);

    if (!$resultado) {
        die("Error en la consulta: " . $conexion->error);
    }

    $valores = [];

    // Obtener los valores de la consulta y almacenarlos en un array
    while ($fila = $resultado->fetch_assoc()) {
        $valores[] = $fila[$columnaNombre];

    }

    // Cerrar la conexión a la base de datos
    $conexion->close();

    return $valores;
}

// Ejemplo de uso
$nombreTabla = "detalles_pedidos";
$infoTabla = obtenerInfoTabla($nombreTabla);
echo $infoTabla;

?>