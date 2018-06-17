<?php 
	session_start();
	include "conexion.php";

	$consulta = $connection->query("INSERT INTO favoritos (idNoticia, idUsuario) VALUES('" . $_GET["idnoticia"] . "','{$_SESSION['idU']}');") ;