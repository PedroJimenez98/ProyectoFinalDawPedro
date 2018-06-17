<?php

include "conexion.php";

	$consulta = $connection->query("UPDATE proyectophpfinal.noticias SET
	titulo = '" . $_POST["titulomodificar"] . "',
  	texto = '" . $_POST["textomodificar"] . "'
  	 WHERE idnoticia = " . $_POST["idnoticiamodificar"]);
?>

	<td class="titulo"><?= $_POST["titulomodificar"]?></td>
	<td class="texto"><?= $_POST["textomodificar"]?></td>
	<td style="width: 10%;"><img alt="" class="avatar width-full rounded-2" height="75" src="avatar.jpg" width="75"></td>
    	<td><input type="button" class="modificar btn btn-success" value="Modificar"/></td><td><input type="button" class="borrar btn btn-danger" value="Eliminar"/></td>
