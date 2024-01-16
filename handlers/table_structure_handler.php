<?php
// table_structure_handler.php

function estructura($tabla){
    include 'db_connection.php';
    $query = "
            SELECT
                COLUMNS.COLUMN_NAME AS nombre,
                COLUMNS.COLUMN_TYPE AS tipo, 
                COLUMNS.COLUMN_KEY AS primary_key, 
                KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME AS tabla_foranea, 
                KEY_COLUMN_USAGE.REFERENCED_COLUMN_NAME AS campo_foraneo 
            FROM 
                INFORMATION_SCHEMA.COLUMNS 
            LEFT JOIN 
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            ON 
                COLUMNS.TABLE_NAME = KEY_COLUMN_USAGE.TABLE_NAME 
            AND 
                COLUMNS.COLUMN_NAME = KEY_COLUMN_USAGE.COLUMN_NAME 
            WHERE 
                COLUMNS.TABLE_NAME = '$tabla' 
            AND
                COLUMNS.TABLE_SCHEMA = '$base_de_datos'
            GROUP BY 
                COLUMNS.COLUMN_NAME, 
                COLUMNS.COLUMN_TYPE";

    $result = $conexion->query($query);

    if ($result) {
        $tableStructure = array();
        while ($row = $result->fetch_assoc()) {
            $field = array(
                'nombre' => $row['nombre'],
                'tipo' => $row['tipo'],
                'primary_key' => (strpos($row['primary_key'], 'PRI') !== false),
                'foreign_key' => null
            );

            // Verificar si es una clave foránea
            if (!empty($row['tabla_foranea']) && !empty($row['campo_foraneo'])) {
                $field['foreign_key'] = array(
                    'tabla_referenciada' => $row['tabla_foranea'],
                    'campo_referenciado' => $row['campo_foraneo']
                );
            }

            $tableStructure[] = $field;
        }

        return json_encode($tableStructure);
    } else {
        return json_encode(array("error" => $conexion->error));
    }
}
