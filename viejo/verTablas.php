<?php
	require_once ("conexion.php");

	function tablas(){
		return $campos = conectar()->query("SHOW TABLES")->fetch_all(1);
	}
	var_dump(tablas());
?>