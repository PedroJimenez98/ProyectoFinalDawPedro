<?php 
include "conexion.php";


		$cantidad_resultados_por_pagina = 7;

	//Comprueba si está seteado el GET de HTTP
if (isset($_GET["pagina"])) {

	//Si el GET de HTTP SÍ es una string / cadena, procede
	if (is_string($_GET["pagina"])) {

		//Si la string es numérica, define la variable 'pagina'
		 if (is_numeric($_GET["pagina"])) {

			 //Si la petición desde la paginación es la página uno
			 //en lugar de ir a 'principal.php?pagina=1' se iría directamente a 'principal.php'
			 if ($_GET["pagina"] == 1) {
				 header("Location: administracion.php");
				 die();
			 } else { //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
				 $pagina = $_GET["pagina"];
			};

		 } else { //Si la string no es numérica, redirige al administracion (por ejemplo: administracion.php?pagina=AAA)
			 header("Location: administracion.php");
			die();
		 };
	};

} else { //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al principal.php o lo que sea)
	$pagina = 1;
};

//Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
$empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;

	$noticias_totales = $connection->query("SELECT * FROM noticias;") ;

	$total_registros = mysqli_num_rows($noticias_totales);

	$total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina);


	$noticias = $connection->query("SELECT * FROM noticias LIMIT $empezar_desde, $cantidad_resultados_por_pagina;");
 ?>
	<table id="tabladatos">
			<tr>
				<th><h2>Título</h2></th>
				<th><h2>Texto</h2></th>
				<th><h2>Imagen</h2></th>
				<th colspan="2"><h2>Acciones</h2></th>
			</tr>
		<?php while ($noticia = $noticias->fetch_object()):  ?>
			<tr id="noticia_<?=$noticia->idnoticia?>" data-idnoticia="<?=$noticia->idnoticia?>">
				<td class="titulo"><?= $noticia->titulo ?></td>
				<td class="texto"><?= $noticia->texto ?></td>
				<td style="width: 10%;"><img alt="" class="avatar width-full rounded-2" height="200" src="avatar.jpg" width="200"></td>
				<td><input type="button" class="modificar btn btn-success" value="Modificar"/></td><td><input type="button" class="borrar btn btn-danger" value="Eliminar"/></td>
			</tr>
	    <?php endwhile; ?>

	</table>
