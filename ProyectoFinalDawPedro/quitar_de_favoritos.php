<?php 
	include "conexion.php";

	$consulta = $connection->query("DELETE FROM favoritos WHERE idNoticia = ". $_GET["idnoticia"]) ;