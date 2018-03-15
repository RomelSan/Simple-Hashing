<!DOCTYPE html>
<?php
session_start();
if($_SESSION['user_login']=="TRUE"){}
else{
	session_destroy();
	session_unset(); 
	header("Location:../index.php?err=2");
	}
// Check Role "need to have admin" to access admin stuff.
if($_SESSION['user_role']!="admin"){header("Location:../online-hash/index.php?msg=5");die();}
?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Simple Hashing 1.0">
    <meta name="author" content="Romel Vera Cadena">
    <link rel="icon" href="../favicon.ico">

    <title>Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="../bootstrap-3.3.6-dist/css/custom.css" rel="stylesheet">
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="../bootstrap-3.3.6-dist/jquery-1.12.2.min.js"></script>
    <script src="../bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
	<script>
	setTimeout(function() 
	{
		$('#alert').fadeOut('fast');
	}, 4000); // <-- time in milliseconds
	</script>

  </head>

  <body>

    <!-- Nav Bar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../online-hash/index.php">Simple Hashing 1.0</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Agregar Usuario</a></li>
            <li><a href="admin_user_search.php">Administrar Usuarios</a></li>  
			<li><a href="admin_filename_search.php">Administrar Archivos</a></li>			
          </ul>
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="../change_password.php"><?php echo $_SESSION["user_email"]; ?></a></li>
			<li><a href="../index.php?err=5">Salir</a></li>
		  </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	<!-- END Nav Bar -->

    <div class="container">

      <div class="starter-template">

    </div> 

    </div><!-- /.container -->
	
	<div class="container">
	<form class="form-horizontal" action="../model/admin_user_add.php" method="post">
	<div class="input-group col-md-8">		
	  <input name="firstname" type="text" class="form-control" placeholder="Nombre" required>
	</div>
	<br>
    <div class="input-group col-md-8">
	  <input name="lastname" type="text" class="form-control" placeholder="Apellido" required>
	</div>
	<br>
	<div class="input-group col-md-8">
      <input name="social_id" type="text" class="form-control" placeholder="Cédula" required>
	</div>
	<br>
	<div class="input-group col-md-8">
      <input name="email" type="email" class="form-control" placeholder="Email" required>
	</div>
	<br>
	<div class="input-group col-md-8">
      <input name="password" type="password" class="form-control" placeholder="Clave" required>
	</div>
	<br>
	<h4>Rol:</h4>
	<label class="radio-inline">
      <input type="radio" name="role" value="user" checked>Usuario Normal
    </label>
    <label class="radio-inline">
      <input type="radio" name="role" value="user2">Usuario con capacidad de búsquedas y entradas manuales.
    </label>
	<br>
	<br>
	<!-- Button -->
    <div class="col-md-4">
        <button type="submit" id="send" name="send_hash" class="btn btn-primary">Crear</button>
    </div>
    <br>
	<br>
	<br>
	<div id="alert" class="container">
	<?php // To display Success or Failure
			if(isset($_GET['msg']))
			  {
				echo '<div class="alert alert-info text-center" role="alert">';
				echo '<h3>';
				require('../model/injection/injections.php'); //Anti Hacking Module
				$error_message = sanitize_light($_GET['msg']); // Filter the user input
			    if ($error_message==1){echo "Usuario Creado Correctamente.";}
			    else if ($error_message==2){echo "El Usuario ya existe en la Base de Datos.";}
				else{echo $error_message;}
				echo '</h3></div>';
			  }
		?>
	</div>
	</form>
    </div>
	

</body>
</html>
