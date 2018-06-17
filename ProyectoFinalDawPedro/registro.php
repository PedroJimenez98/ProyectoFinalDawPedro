<?php
	session_start();

	if ((!isset($_POST['newusername'])) || (!isset($_POST['nombre'])) || (!isset($_POST['newpassword1'])) || (!isset($_POST['newpassword2'])) || (!isset($_POST['email']))) {

		header("location:index.php");
	}

	if (isset($_SESSION['id'])) {
		header("location:profile.php");
	}

		$lnk = @new mysqli("localhost","root","1234","proyectophpfinal");

		$lnk->set_charset("UTF8");

		//Comprobamos que no se cometan errores de conexion.
		if ($lnk->connect_errno) {
			die("**Error $lnk->connect_errno: $lnk->connect_error.<br/>") ;
		}

		// Esacapamos las cadenas correctamente para evitar inyección de código
		$newuser = $lnk->real_escape_string($_POST["newusername"]);
		$pass1 = $lnk->real_escape_string($_POST["newpassword1"]);
		$pass2 = $lnk->real_escape_string($_POST["newpassword2"]);
		$name = $lnk->real_escape_string($_POST["nombre"]);
		$email = $lnk->real_escape_string($_POST["email"]);

		if ($pass1 == $pass2) {
			// Comprobar si existe el usuario en la base de datos
			$sql  = "SELECT * FROM usuarios WHERE user='$newuser';" ;
			$reg = $lnk->query($sql);

			if ($reg->num_rows) {

				$msg1 = "<p class=\"error\">Nombre de usuario ya existente.</p>";
				setcookie("mensaje1", $msg1, time()+15);
				header("location:index.php");
			}

			// Comprobar si existe el email en la base de datos.
			$sql  = "SELECT * FROM usuarios WHERE mail='$email';" ;
			$reg = $lnk->query($sql);

			if ($reg->num_rows) {
				$msg2 = "<p class=\"error\">Correo electrónico ya existente.</p>";
				setcookie("mensaje2", $msg2, time()+15);
				header("location:index.php");
			}

			//Inserta el nuevo usuario en la base de datos
			$sql  = "INSERT INTO usuarios (user,password,nombre,mail,imagen) values ('$newuser', '$pass1', '$name', '$email', 'https://robohash.org/suscipitearumdebitis.bmp?size=200x200&set=set1');" ;
			$reg = $lnk->query($sql);



			// Si he obtenido un resultado correcto...
			if ($reg) {
				$sql  = "SELECT * FROM usuarios WHERE user='$newuser';" ;
				$reg = $lnk->query($sql) or die("Error: " . $reg->error);

				$fila = $reg->fetch_object();


				// Crear las variables de sesión necesarias
				$_SESSION["id"]  = session_id();
				$_SESSION["idU"]  = $fila->idUsuario;
				$_SESSION["usr"] = $_POST["newusername"];
				$_SESSION["name"] = $_POST["nombre"];
				$_SESSION["admin"] = $fila->Admin;


				// Redirigimos
				header("location:profile.php") ;
			}
		} else {
			$msg = "<p class=\"error\">Las contraseñas no coinciden</p>" ;
			setcookie("mensaje", $msg, time()+15);

			header("location:index.php");
		}
		// Cerrar la conexión de la base datos.
		$lnk->close() ;

?>