<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Simple Hashing 1.0">
    <meta name="author" content="Romel Vera Cadena">
    <link rel="icon" href="favicon.ico">

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="bootstrap-3.3.6-dist/css/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="model/login.php" method="post">
        <h2 class="form-signin-heading">Entrar al Sistema:</h2>
        <label for="inputEmail" class="sr-only">Email:</label>
        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Contraseña:</label>
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
			  
		<br>
      </form>
    </div> <!-- /container -->
	
	<div id="alert" class="container">
	    <?php // To display Error messages
			if(isset($_GET['err']))
			  {
				echo '<div class="alert alert-info text-center" role="alert">';
				echo '<h3>';
				require('model/injection/injections.php'); //Anti Hacking Module
				$error_message = sanitize_light($_GET['err']); // Filter the user input
			    if ($error_message==1){echo "Credenciales inválidas.";}
			    else if($error_message==5)
				  {
					  session_start();
					  if (isset($_SESSION['user_login'])){$_SESSION['user_login']="FALSE";}
					  session_unset();
					  session_destroy();
					  echo "Ud. Ha salido del sistema correctamente.";
				  }
			    else if ($error_message==2){echo "Acceso no autorizado, por favor ingrese al sistema.";}
				else if ($error_message==3){echo "Cuenta desactivada, por favor contactar al administrador.";}
			  }
			  echo '</h3></div>';
		?>
		</div>
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap-3.3.6-dist/jquery-1.12.2.min.js"></script>
    <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
	
	<script>
		setTimeout(function() {
		$('#alert').fadeOut('fast');
		}, 4000); // <-- time in milliseconds
	</script>
	
  </body>
</html>
