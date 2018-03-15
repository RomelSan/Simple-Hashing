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
            <li><a href="index.php">Agregar Usuario</a></li>
            <li><a href="admin_user_search.php">Administrar Usuarios</a></li>  
			<li class="active"><a href="admin_filename_search.php">Administrar Archivos</a></li>			
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
	<form class="form-horizontal" action="../model/admin_file_manage.php" method="post">
	<div class="input-group col-md-8">		
	  <input name="filename" type="text" class="form-control" placeholder="Buscar por Nombre de Documento">
	</div>
	<br>
    <div class="input-group col-md-8">
	  <input name="email" type="text" class="form-control" placeholder="Buscar por Email">
	</div>
	<br>
	<div class="input-group col-md-8">
      <input name="hash256" type="text" class="form-control" placeholder="Buscar por Hash (SHA2-256)">
	</div>
	<br>
	<div class="input-group col-md-8">
      <input name="date" type="text" class="form-control" placeholder="Buscar por Fecha ej: 2016-03-22">
	</div>
	<br>
	<!-- Button -->
    <div class="col-md-4">
        <button type="submit" id="send" name="send_hash" class="btn btn-primary">Buscar</button>
    </div>
    <br>
	</form>
	
    </div>
		<div id="alert" class="container">
	    <?php // To display Error messages
			if(isset($_GET['msg']))
			  {
				echo '<div class="alert alert-info text-center" role="alert">';
				echo '<h3>';
				$error_message=$_GET['msg'];
			    if ($error_message==1){echo 'Cambio: OK';}
				else if ($error_message==2){echo 'Error 33';}
				else {die();}
			  }
			  echo '</h3></div>';
		?>
		</div>
	
  </body>
</html>
