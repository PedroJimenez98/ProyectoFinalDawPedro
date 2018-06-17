<?php
include "conexion.php";
	session_start() ;

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

	if ($_SESSION["admin"] != 1) {
		header("location:principal.php") ;
	}

	// Si se nos indica, destruimos la sesión.
	if (isset($_GET["destroy"])) {
		destruir_session() ; 
	}


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

		h4 {
			width: 200px;
		}
		table{
			border-collapse: collapse;
			margin: 1em;
		}
		table, tr, th, td {
			border: 1px solid black;
			text-align: center;
		}
		.titulo {
			font-size: 20px;
		}
		.btn, .btn-info {
			margin: 2px;
		}
		#nuevo {
			margin-left: 10px;
		}
		#pager {
			padding-left: 15px;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function() {			
			var idtipo;
			var idnoticia;


//---------------------------------------------------
//DIALOGO DE BORRADO
	$( "#dialogoborrar" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		//BOTON DE BORRAR
		"Eliminar": function() {			
			//Ajax con get
			$.get("noticia_borrar.php", {"idnoticia":idnoticia},function(){				
				$("#noticia_" + idnoticia).fadeOut(500,function(){$(this).remove();});
			})//get
			//Cerrar la ventana de dialogo				
			$(this).dialog("close");												
		},
		"Cancelar": function() {
				//Cerrar la ventana de dialogo
				$(this).dialog("close");
		}
		}//buttons

	});	

//Evento click que pulsa el boton borrar
$(document).on("click",".borrar",function(){
	//Obtenemos el idnoticia a eliminar
	//a traves del atributo idrecord del tr
	idnoticia = $(this).parents("tr").data("idnoticia");
	//Accion para mostrar el dialogo de borrar
	 $( "#dialogoborrar" ).dialog("open");		
});



//---------------------------------------------------
//MODIFICAR
$( "#dialogomodificar" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Guardar": function() {			
			$.post("noticia_modificar.php", {
				idnoticiamodificar : idnoticia,
				titulomodificar : $("#titulomodificar").val(),
				textomodificar: $("#textomodificar").val(),
			},function(data,status){
				$("#noticia_" + idnoticia).html(data);
				
			})//get
					
			$(this).dialog( "close" );										
					},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});				

//Boton Modificar	
$(document).on("click",".modificar",function(){
	//Obtenemos el idnoticia de la fila
	idnoticia = $(this).parents("tr").data("idnoticia");
	//Para que ponga el campo modelo con su valor
	$("#titulomodificar").val($(this).parent().siblings("td.titulo").html());
	
	//Para que ponga el campo modelo con su valor
	$("#textomodificar").val($(this).parent().siblings("td.texto").html());

	
	//Muestro el dialogo
	$( "#dialogomodificar").dialog("open");
	
});

//---- NUEVO --------------
//Boton de nuevo inmueble 
//Crea nueva fila al final de la tabla
//Con dos nuevos botones (guardarnuevo y cancelarnuevo)
$(document).on("click","#nuevo",function(){	
	$.post("noticia_formulario_nuevo.php",function(data){
	//Añade a la tabla de datos una nueva fila
	$("#tabladatos").append(data);
			//Ocultamos boton de nuevo inmueble
			//Para evitar añadir mas de uno 
			//a la vez
			$("#nuevo").hide();			
	})//get	
});

//Boton de cancelar nuevo
$(document).on("click","#cancelarnuevo",function(){	
		//Elimina la nueva fila creada
		$("#filanueva").remove();
		//vuelve a mostrar el botón de nuevo
		$("#nuevo").show();
		
});			

//Boton de guardar nuevo
$(document).on("click","#guardarnuevo",function(){
	$.post("noticia_insertar_nuevo.php", {
				"titulonuevo":$("#titulonuevo").val(),
				"textonuevo":$("#textonuevo").val()
			},function(data){
				window.location.href = "administracion.php";
			})//post	
});

});
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
	<div class="row">
		<div class="col-md-12">
			<div id="pager">
				<?php
				for ($i=1; $i<=$total_paginas; $i++) {
					//En el bucle, muestra la paginación
					echo "<a href='?pagina=".$i."'>".$i."</a> | ";
				}; ?>
			</div>
			<?php include "noticia_tabla.php" ?>
						<div class="text-center">
				<input id="nuevo" type="button" value="Nuevo" class="btn btn-info" />
			</div>
			<!-- CAPA DE DIALOGO ELIMINAR noticia -->
			<div id="dialogoborrar" title="Eliminar noticia">
			  <p>¿Esta seguro que desea eliminar la noticia?</p>
			</div>

			<!-- CAPA DE DIALOGO MODIFICAR noticia -->
			<div id="dialogomodificar" title="Modificar noticia">
				<?php include "formulario_modificar_noticia.php" ?>
			</div>
		</div>
	</div>
</body>
</html>