<?php
	session_start() ;
	error_reporting(E_ALL ^ E_NOTICE);
	function destruir_session() {

		// Destruir variables de sesión
		$_SESSION[] = array() ;

		// Destruimos la sesión
		session_destroy() ;

		header("location:index.php") ;
	}

	//Comprobamos si existe una sesión previa
	if (!isset($_SESSION["id"])) { 
		header("location:index.php") ;
	}

	// Si se nos indica, destruimos la sesión.
	if (isset($_GET["destroy"])) {
		destruir_session() ; 
	}

	//Se realiza la conexión a la base de datos

include "conexion.php";



	$cantidad_resultados_por_pagina = 5;

	//Comprueba si está seteado el GET de HTTP
if (isset($_GET["pagina"])) {

	//Si el GET de HTTP SÍ es una string / cadena, procede
	if (is_string($_GET["pagina"])) {

		//Si la string es numérica, define la variable 'pagina'
		 if (is_numeric($_GET["pagina"])) {

			 //Si la petición desde la paginación es la página uno
			 //en lugar de ir a 'principal.php?pagina=1' se iría directamente a 'principal.php'
			 if ($_GET["pagina"] == 1) {
				 header("Location: principal.php");
				 die();
			 } else { //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
				 $pagina = $_GET["pagina"];
			};

		 } else { //Si la string no es numérica, redirige al principal (por ejemplo: principal.php?pagina=AAA)
			 header("Location: principal.php");
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

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Inicio</title>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="ui-lightness/jquery-ui-1.10.3.custom.css"/>
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
	<style type="text/css">

		h1, h4 {
			width: 300px;
		}
		h1 {
			width: 100%;
			text-align: center;
			float: left;
		}
		#pager {
			padding-left: 50px;
		}
		button {
			position: absolute;
		}
		#quitaFav {
		    background-color: #e22f3b; /* Rojo */
		    border: none;
		    color: white;
		    padding: 15px 32px;
		    text-align: center;
		    text-decoration: none;
		    font-size: 12px;
		    margin: 4px 2px;
		    cursor: pointer;
		}
		#addFav {
		    background-color: #008CDD; /* Azul */
		    border: none;
		    color: white;
		    padding: 15px 32px;
		    text-align: center;
		    text-decoration: none;
		    font-size: 12px;
		    margin: 4px 2px;
		    cursor: pointer;
		}
		#addFav:hover {
			background-color: #23a3ed;
		}
		#quitaFav:hover {
			background-color: #d11f2b;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function() {		
			var idtipo;
			var idnoticia;

//---------------------------------------------------
//Quitar favorito

//Boton Quitar favorito	
$(document).on("click","#quitaFav",function(){
	//Obtenemos el idnoticia del contenedor
	idnoticia = $(this).parents("div").data("idnoticia");

	//Ajax con get
			$.get("quitar_de_favoritos.php", {"idnoticia":idnoticia},function(){				
				$(".quitaFav_" + idnoticia).fadeOut(500,function(){$(this);});
				$(".addFav_" + idnoticia).fadeIn(500,function(){$(this);});
			})//get

	});

//---------------------------------------------------
//Añadir favorito

//Boton Añadir favorito	
$(document).on("click","#addFav",function(){
	//Obtenemos el idnoticia del contenedor
	idnoticia = $(this).parents("div").data("idnoticia");

	//Ajax con get
		$.get("add_to_favoritos.php", {"idnoticia":idnoticia},function(){				
			$(".quitaFav_" + idnoticia).fadeIn(500,function(){$(this);});
			$(".addFav_" + idnoticia).fadeOut(500,function(){$(this);});
		})//get

});

		});//ready
	</script>
</head>
<body>
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="principal.php">Noticias</a>
	    </div>
	    <ul class="nav navbar-nav navbar-left">
	    	<li class="nav-item">
	        	<a class="nav-link" href="index.php">Mi perfil <span class="sr-only">(current)</span></a>
	      	</li>
	      	<?php 
	      		if($_SESSION["admin"]) {
	      			echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"administracion.php\">Admin<span class=\"sr-only\">(current)</span></a></li>";
	      		}
	      	 ?>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	    	
	    	<li><a href="profile.php?destroy"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
	    </ul>
	  </div>
	</nav>
	<div id="pager">
		<?php
		for ($i=1; $i<=$total_paginas; $i++) {
			//En el bucle, muestra la paginación
			echo "<a href='?pagina=".$i."'>".$i."</a> | ";
		}; ?>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php while ($noticia = $noticias->fetch_object()):  ?> 
				<h1><span class="title" ><?= $noticia->titulo ?></span></h1><br>
		    	<div class="row">
			    	<div class="col-sm-8 col-sm-offset-1" >
						<content><?= $noticia->texto ?></content>


					</div>
			    	<div class="col-sm-2">
			    		<img alt="" class="avatar width-full rounded-2" height="200" src="avatar.jpg" width="200"> 
			    		<br>
			    		<div id="noticia_<?=$noticia->idnoticia?>" data-idnoticia="<?=$noticia->idnoticia?>">
			    			<?php 
			    			$idN = $noticia->idnoticia;
							$favoritos = $connection->query("SELECT * FROM favoritos WHERE idNoticia = '$idN' AND idUsuario = '{$_SESSION['idU']}';");

		    					$favorito = $favoritos->fetch_object();
								
								if ($favorito->idUsuario === $_SESSION["idU"]) {
			    					echo "<button id=\"quitaFav\" class=\"quitaFav_$noticia->idnoticia\">Quitar de favoritos</button>";
			    					echo "<button id=\"addFav\" hidden=\"hidden\" class=\"addFav_$noticia->idnoticia \">Añadir a favoritos</button>";
			    				} else {
			    					echo "<button id=\"quitaFav\" hidden=\"hidden\" class=\"quitaFav_$noticia->idnoticia \">Quitar de favoritos</button>";
			    					echo "<button id=\"addFav\" class=\"addFav_$noticia->idnoticia \">Añadir a favoritos</button>";
			    				}

							?>
						</div>
					</div>
				</div>
	    	<?php endwhile; ?>
		</div>
	</div>
    <div class="row">
    	<div class="col-md-12">
    		<div id="pager">
				<?php
				for ($i=1; $i<=$total_paginas; $i++) {
					//En el bucle, muestra la paginación
					echo "<a href='?pagina=".$i."'>".$i."</a> | ";
				}; ?>
			</div>
    	</div>
    </div>
</body>
</html>
