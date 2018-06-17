<?php 
include "conexion.php";



$consulta = $connection->query("INSERT INTO noticias (titulo, texto) VALUES('" . $_POST["titulonuevo"] . "','" . $_POST["textonuevo"] . "')") ;


$consulta = $connection->query("SELECT LAST_INSERT_ID() AS idnoticia FROM noticias");


?>
<tr id="noticia_<?=$fila->idnoticia?>" data-idnoticia="<?=$noticia->idnoticia?>">
	<td class="titulo"><?= $_POST["titulonuevo"]?></td>
	<td class="texto"><?= $_POST["textonuevo"]?></td>
	<td style="width: 10%;"><img alt="" class="avatar width-full rounded-2" height="75" src="avatar.jpg" width="75"></td>
	<td><input type="button" class="modificar" value="Modificar"/>&nbsp;<input type="button" class="borrar" value="Eliminar"/></td>
</tr>
