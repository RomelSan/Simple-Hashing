<!DOCTYPE html>
<?php
session_start();
if($_SESSION['user_login']=="TRUE"){}
else{
	session_destroy();
	session_unset(); 
	header("Location:index.php?err=2");
	}
?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Simple Hashing 1.0">
    <meta name="author" content="Romel Vera Cadena">
    <link rel="icon" href="favicon.ico">

    <title>Enviar Hash</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="bootstrap-3.3.6-dist/css/custom.css" rel="stylesheet">

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
          <a class="navbar-brand" href="#">Simple Hashing 1.0</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="search.php">Buscar</a></li>
            <li class="active"><a href="upload.php">Manual</a></li>            
			<li><a href="online-hash/" target="_self">Automático</a></li> 
          </ul>
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="change_password.php"><?php echo $_SESSION["user_email"]; ?></a></li>
			<li><a href="index.php?err=5">Salir</a></li>
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
	<form class="form-horizontal" action="model/upload.php" method="post">
	<div class="input-group col-md-8">
		<span class="input-group-addon" id="basic-addon1">Nombre de Archivo</span>
		<input name="filename" type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" value="Documento.docx" required>
	</div>
	<br>
    <div class="input-group col-md-8">
		<span class="input-group-addon" id="basic-addon1">SHA256</span>
		<input name="sha256" type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" value="" required>
	</div>
	<br>
	<div class="input-group col-md-8">
		<span class="input-group-addon" id="basic-addon1">CRC32</span>
		<input name="crc32" type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" value="" required>
	</div>
	<br>
	<!-- Button -->
    <div class="col-md-4">
        <button type="submit" id="send" name="send_hash" class="btn btn-primary">Enviar</button>
    </div>
    <br>
	<br>
	<div id="alert" class="container">
	<?php // To display Success or Failure
			if(isset($_GET['msg']))
			  {
				echo '<div class="alert alert-info text-center" role="alert">';
				echo '<h3>';
				require('model/injection/injections.php'); //Anti Hacking Module
				$error_message = sanitize_light($_GET['msg']); // Filter the user input
			    if ($error_message==1){echo "Archivo Ingresado Correctamente.";}
			    else if ($error_message==2){echo "El Archivo ya existe en la Base de Datos.";}
				else if ($error_message==3){echo "El formato del Hash no es correcto.";}
				else{echo $error_message;}
				echo '</h3></div>';
			  }
		?>
	</div>
	</form>
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
