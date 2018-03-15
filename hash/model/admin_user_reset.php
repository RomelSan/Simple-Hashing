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
	
	header("Access-Control-Allow-Origin: *");
	header('charset=UTF-8');
	require('dbcore.php'); //Call database connection module
	require('injection/injections.php'); //Anti Hacking Module
	
	// Random Pasword Function
	function generate_password($length = 8)
	{
		$chars =  'abcdefghijklmnopqrstuvwxyz'.'0123456789';
		$str = '';
		$max = strlen($chars) - 1;
		for ($i=0; $i < $length; $i++)
		$str .= $chars[random_int(0, $max)];
		return $str;
	} // END Password Gen
		
	$reset_email = $_POST["email"]; 
	$reset_email_clean = sanitize_light($reset_email); 
	$random_password = generate_password();
	$hashedPW = hash('sha256', $random_password);
	
	
	// If user input is less than 2 chars... DIE
	if (strlen($reset_email_clean) < 2) {die("Ingrese email de usuario");}
?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Hash Center 1.0">
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
          <a class="navbar-brand" href="../online-hash/index.php">Hash Center 1.0</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="../admin/index.php">Agregar Usuario</a></li>
            <li class="active"><a href="../admin/admin_user_search.php">Administrar Usuarios</a></li>  
			<li><a href="../admin/admin_filename_search.php">Administrar Archivos</a></li>			
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
	  <?php
	//---------------------------------------
	// Database Operation
	try // Reset Password------------------------
		{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement = $pdo->prepare("UPDATE user SET password=:password WHERE NOT role='admin' AND email=:email");
			$statement->bindValue(':email', $reset_email_clean, PDO::PARAM_STR);
			$statement->bindValue(':password', $hashedPW, PDO::PARAM_STR);
			$statement->execute(); // execute it
			$affected_rows = $statement->rowCount();
			if ($affected_rows > 0)
			{
				echo "Se ha reseteado la contraseña para: <br>" . "$reset_email_clean" . "<br>";
				echo "La contraseña nueva generada es: <br>" . "$random_password" . "<br>";
				echo "<b>Por favor entregar contraseña y notificar al usuario que la debe cambiar.</b>";
			}
			else
			{
				echo "No se ha podido realizar ningún cambio.";
			}
		}
	catch (PDOException $e)
		{
			//echo $e->getMessage();
				$error_message = $e->getMessage();
				echo $error_message;
		}
	?>

    </div><!-- /.container -->

</body>
</html>