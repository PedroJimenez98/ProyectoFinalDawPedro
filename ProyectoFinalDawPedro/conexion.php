<?php
	//Se realiza la conexión a la base de datos

	$connection = @new mysqli("localhost","root","1234","proyectophpfinal");

	$connection->set_charset("UTF8");

	//Comprobamos que no se cometan errores de conexion.
	if ($connection->connect_errno) {
		die("**Error $connection->connect_errno: $connection->connect_error.<br/>") ;
	}

?>