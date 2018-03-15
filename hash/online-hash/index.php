<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebApplication">
<?php
session_start();
if($_SESSION['user_login']=="TRUE"){}
else{
	session_destroy();
	header("Location:../index.php?err=2");
	}
?>
<head>
   <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Simple Hashing 1.0">
    <meta name="author" content="Romel Vera Cadena">
    <link rel="icon" href="../favicon.ico">

    <title>Enviar Hash</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="../bootstrap-3.3.6-dist/css/custom.css" rel="stylesheet">
    
	<link type="text/css" href="css/ui-redmond/jquery-ui.css" rel="stylesheet" />
    <link type="text/css" href="html5hash.css" rel="stylesheet" />

    <script type="text/javascript" src="js/md5.js"></script>
    <script type="text/javascript" src="js/sha1.js"></script>
    <script type="text/javascript" src="js/sha256.js"></script>
    <script type="text/javascript" src="js/sha512.js"></script>
    <script type="text/javascript" src="js/sha3.js"></script>
    <script type="text/javascript" src="js/ripemd160.js"></script>
    <script type="text/javascript" src="js/crc32.js"></script>
    <script type="text/javascript" src="jquery/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="jquery/jquery-ui-1.11.4.min.js"></script>
	<script src="../bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="html5hash.js"></script>
</head>

<body id="drop_zone">

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
          <a class="navbar-brand" href="index.php">Simple Hashing 1.0</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="../search.php">Buscar</a></li>
            <li><a href="../upload.php">Manual</a></li>            
			<li class="active"><a href="index.php" target="_self">Automático</a></li> 
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
		<br>
        <header>
            <fieldset id="algo_selection">
                <legend>Seleccionar Algoritmo:</legend>
                <form>
                    <table>
                        <tr>
                            <td><input type="checkbox" name="crc32switch" checked="checked" />CRC-32</td>
                            <td><input type="checkbox" name="MD5-switch" />MD5</td>
                            <td><input type="checkbox" name="SHA1-switch" />SHA1   </td>
                            <td id="algosshow"><a href="#">más</a></td>
                        </tr>
                        <tr class="additionalalgos">
                            <td><input type="checkbox" name="RIPEMD-160-switch" />RIPEMD-160</td>
                            <td><input type="checkbox" name="SHA256-switch" checked="checked" />SHA256</td>
                            <td><input type="checkbox" name="SHA512-switch" />SHA512</td>
                        </tr>
                        <tr class="additionalalgos">
                            <td><input type="checkbox" name="SHA3-224-switch" />SHA3-224</td>
                            <td><input type="checkbox" name="SHA3-256-switch" />SHA3-256</td>
                            <td><input type="checkbox" name="SHA3-384-switch" />SHA3-384</td>
                        </tr>
                        <tr class="additionalalgos">
                            <td><input type="checkbox" name="SHA3-512-switch" />SHA3-512</td>
                            <td>&nbsp;</td>
                            <td id="algoshide"><a href="#">menos</a></td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </header>
        <article>
                    <b>Seleccionar Archivo</b>
                    <div id="placeholder">
						<img src="img/upload-cloud-flat.png" class="img-responsive center-block">
                        <br><b>Arrastre</b> el archivo aquí o <b>haga click aquí</b>
                    </div>
                    <input type="file" id="hiddenFilesSelector"> <!-- deleted word "multiple"... add multiple to allow multiple file selection-->
			<ul id="list">
			</ul>
		</article>
		
		<div id="alert" class="container">
			<?php // To display Success or Failure
			if(isset($_GET['msg']))
			  {
				echo '<div class="alert alert-info text-center" role="alert">';
				echo '<h3>';
				require('../model/injection/injections.php'); //Anti Hacking Module
				$user_message = sanitize_light($_GET['msg']); // Filter the user input
			    if ($user_message==1){echo "Archivo Ingresado Correctamente.";}
			    else if ($user_message==2){echo "El Archivo ya existe en la Base de Datos.";}
				else if ($user_message==3){echo "Por favor seleccionar un archivo.";}
				else if ($user_message==4){echo "No tiene privilegios para realizar operaciones manuales.";}
				else if ($user_message==5){echo "No tiene privilegios de Administrador.";}
				else{echo $user_message;}
				echo '</h3></div>';
			  }
		?>
		</div>
		<br>
		<div class="container">
		<form class="form-horizontal" action="../model/upload_auto.php" method="post">
			<div class="input-group col-md-8 hidden">
				<span class="input-group-addon" id="basic-addon1">Nombre de Archivo</span>
				<input id="filename" name="filename" type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
				<!--<input id="filename" name="filename" type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" value="" oninvalid="this.setCustomValidity('Por favor elija un archivo.')" required>-->
			</div>
			<!--<br>-->
			<div class="input-group col-md-8 hidden">
				<span class="input-group-addon" id="basic-addon1">SHA256</span>
				<input id="sha256" name="sha256" type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
			</div>
			<!--<br>-->
			<div class="input-group col-md-8 hidden">
				<span class="input-group-addon" id="basic-addon1">CRC32</span>
				<input id="crc32" name="crc32" type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
			</div>
			<!-- Button -->
			<!--<br>-->
			<div class="center-block">
				<button type="submit" id="send" name="send_hash" class="btn btn-primary">Guardar</button>&nbsp&nbsp
				<button type="submit" class="btn btn-primary" formmethod="post" formaction="../model/search_auto.php" formtarget="_self">Verificar</button>
			</div>
		</form>
		</div>

        <aside id="explanation">
            <span itemprop="description">Los hashes son computados localmente, el archivo no es enviado al servidor.</span>
        </aside>

    </div>

    <footer>
        Copyright © 2016 Romel Vera C.
    </footer>


    <div id="overlay">

    </div>

    <div id="overlaytextbox">
        <h3>Sorry</h3><br />
        <div id="nojavascript">Este sitio requiere <a href="http://www.enable-javascript.com/" target="_blank">JavaScript</a>.</div>
        <div id="missingfeatures"></div>
    </div>

	<script>
		setTimeout(function() {
		$('#alert').fadeOut('fast');
		}, 4000); // <-- time in milliseconds
	</script>
</body>
</html>



