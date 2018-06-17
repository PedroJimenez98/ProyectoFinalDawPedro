<?php
	
	$msg1 = "";
	$msg2 = "";

	session_start();

	if (isset($_SESSION['id'])) {
		header("location:profile.php");
	}

	if (isset($_POST["username"])) {
		//Nos conectamos a la base de datos
		$lnk = @new mysqli("localhost","root","1234","proyectophpfinal");

		$lnk->set_charset("UTF8");

		//Comprobamos que no se cometan errores de conexion.
		if ($lnk->connect_errno) {
			die("**Error $lnk->connect_errno: $lnk->connect_error.<br/>") ;
		}

		// Esacapamos las cadenas correctamente para evitar inyección de código
		$user = $lnk->real_escape_string($_POST["username"]);
		$pass = $lnk->real_escape_string($_POST["pass"]);

		// Comprobar si existe el usuario en la base de datos
		$sql  = "SELECT * FROM usuarios WHERE user='$user' AND password='$pass';" ;
		$reg = $lnk->query($sql) or die("Error: " . $reg->error);
		// Si he obtenido un resultado correcto...
		if ($reg->num_rows) {

			$fila = $reg->fetch_object();

			// Crear las variables de sesión necesarias
			$_SESSION["id"]  = session_id();
			$_SESSION["idU"] = $fila->idUsuario;
			$_SESSION["usr"] = $_POST["username"];
			$_SESSION["name"] = $fila ->nombre;
			$_SESSION["admin"] = $fila->Admin;
			$_SESSION["avatar"] = $fila->imagen;


			// Redirigimos
			header("location:profile.php") ;
		} else {
			$msg = "<p class=\"error\">El usuario y/o la contraseña son incorrectos.</p>" ;
		}

		// Cerrar la conexión de la base datos.
		$lnk->close() ;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Main Page</title>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
		#error { color: #ff0000; font-weight: bold; }
	</style>
</head>
<body>
	<nav class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php">Noticias</a>
		</div>
	</nav>
	<?php if(isset($_COOKIE['mensaje'])) { ?>
		<p id="error">La contraseñas no coinciden</p>
	<?php } ?>
	<form method="POST">
		<div class="col-sm-1"></div>
		<div id="iniciarSesion" class="col-sm-5">
			<h2>Iniciar Sesión</h2><br>
			Nombre de usuario: <input type="text" name="username" placeholder="Nombre de usuario" required><br>
			Contraseña: <input type="password" name="pass" placeholder="Contraseña" required><br><br>
			<button type="submit">Enviar</button>
		</div>
	</form>
	<div class="col-sm-1"></div>
	<form method="POST" action="registro.php">
		<div class="col-sm-4" id="registro">
				<?php if(isset($_COOKIE['mensaje1'])) { ?>
					<p id="error">Nombre de usuario ya existente.</p>
				 
				<?php } ?>

				<?php if(isset($_COOKIE['mensaje2'])) { ?>
					<p id="error">Correo electrónico ya existente.</p>
				<?php } ?>
			<h2>Registrate</h2>
			Nombre de usuario: <input type="text" name="newusername" placeholder="Nombre de usuario" required><br>
			Nombre completo: <input type="text" name="nombre" placeholder="Nombre completo" required><br>
			Contraseña: <input type="password" name="newpassword1" placeholder="Contraseña" required><br>
			Repite la contraseña: <input type="password" name="newpassword2" placeholder="contraseña" required><br>
			Correo electrónico: <input type="text" name="email" placeholder="correo electrónico" required><br>
			<button type="submit">Enviar</button>
		</div>
		</form>
	</body>
</html>