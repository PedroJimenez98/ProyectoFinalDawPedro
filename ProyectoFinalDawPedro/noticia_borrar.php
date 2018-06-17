<?php

	include "conexion.php";

	$consulta = $connection->query("DELETE FROM noticias WHERE idnoticia = ". $_GET["idnoticia"]) ;
			
