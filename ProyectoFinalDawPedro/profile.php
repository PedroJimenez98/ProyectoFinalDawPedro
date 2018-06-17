<?php
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

	// Si se nos indica, destruimos la sesión.
	if (isset($_GET["destroy"])) {
		destruir_session() ; 
	}

include "conexion.php";



	$imagenes = $connection->query("SELECT imagen FROM usuarios WHERE idUsuario = '{$_SESSION["idU"]}';");
	$imagen = $imagenes->fetch_object();

	$noticias = $connection->query("SELECT * FROM favoritos WHERE idUsuario = '{$_SESSION["idU"]}';");
	


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mi Perfil</title>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style type="text/css">
		.col-sm-8 {
			height: 500px;
		}
		.avatar {
			top: 20px;
			left: 20px;
			border: 1px solid black;
			opacity: .8;
		}
		.titulo {
			text-align: center;
		}
		.avatar {
			width: 200px;
			height: 200px;
		}
		.imagen {
			width: 50px;
			height: 50px;
		}
	</style>
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
	    <div class="col-sm-2 col-sm-offset-2" >
	    	
	    	<img alt="" class="avatar width-full rounded-2" height="200" src="<?=$imagen->imagen ?>" width="200">

	    	<h1 class="card-names">
	    		<span class="fullname" itemprop="name"><?= $_SESSION["name"] ?></span>
	    	</h1>
	    	<h4>
	    		<span class="username" itemprop="additionalName"><?= $_SESSION["usr"] ?></span>
			</h4>
		</div>
	    <div class="col-sm-6">
	    	<div class="favs">
	    		<h1 class="text-center">Favoritos</h1>
	    	</div>
	    	<?php while ($noticia = $noticias->fetch_object()):  
	    		$noticias1 = $connection->query("SELECT * FROM noticias WHERE idNoticia = '$noticia->idNoticia';");
	    		$noticia1 = $noticias1->fetch_object();
			?>
				<h2 class="titulo"><span class="title" ><?= $noticia1->titulo ?></span></h2><br>
		    	<content><?= $noticia1->texto ?></content>
			<?php endwhile; ?>
	    </div>
	</div>
    </body>
</html>