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
	
	<link rel="stylesheet" type="text/css" href="../js/datatables/datatables.min.css"/>
	<script type="text/javascript" src="../js/datatables/datatables.min.js"></script>
	
	<script>
	setTimeout(function() 
	{
		$('#alert').fadeOut('fast');
	}, 4000); // <-- time in milliseconds
	</script>

	<script>
		$(document).ready(function() {
										$('#data1').DataTable( {
											responsive: true,
											"scrollX": false,
											"language": {"url": "../js/datatables/Spanish.json"},
											dom: 'Bfrtip',
											buttons: ['copy', 'csv']
									 } );
									 });
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
            <li class="active"><a href="admin_user_search.php">Administrar Usuarios</a></li>  
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
		<?php
			require('../model/dbcore.php'); //Call database connection module
			require('../model/injection/injections.php'); //Anti Hacking Module
			//---------------------------------------
			// Database Operation
	
			try
			{
				$pdo0 = new PDO($dsn, $user, $password);
				$pdo0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$statement0 = $pdo0->prepare("SELECT * FROM hash.user WHERE not role=:role"); // prepare the statement
				$statement0->bindValue(':role', "admin" , PDO::PARAM_STR);
				$statement0->execute(); // execute it
				$rows = $statement0->fetchAll(PDO::FETCH_ASSOC);
				$affected_rows0 = $statement0->rowCount();
				if ($affected_rows0 > 0)
					{
						echo '<table width="100%" id="data1" class="table table-bordered table-hover"><thead><tr><th>Nombre</th><th>Email</th><th>Último Login</th><th>Activo</th><th>Rol</th></tr></thead>';
						echo '<tbody>';
						// output data of each row
						foreach($rows as $data) 
							{
								if ($data["is_active"]=="yes"){$display_active="Sí"; $row_active_class='<tr>';}
								else {$display_active="No"; $row_active_class='<tr class="danger">';}
								if ($data["role"]=="user"){$display_role="Normal";}
								else {$display_role="Normal + Búsquedas";}
								echo $row_active_class;
								echo "<td>" . $data["firstname"] . " " . $data["lastname"] . "</td>";
								echo "<td>" . $data["email"] . "</td>";
								echo "<td>" . $data["last_login_date"] . "</td>";
								//Active Yes or No
								echo "<td>" . $display_active;
								echo '
								<br><form class="form-horizontal" action="../model/admin_user_manage.php" method="post">
								<input type="hidden" name="email" value="' . $data["email"] . '">';
								if ($data["is_active"]=="yes")
									{
										echo '<button type="submit" id="send" name="active" value="no" class="btn btn-primary btn-xs">Desactivar</button>';
									}
								else 
									{
										echo '<button type="submit" id="send" name="active" value="yes" class="btn btn-primary btn-xs">Activar</button>';
									}
								echo "</form>";
								echo "</td>";
								//End Active Yes or No
								
								//Rol 	
								echo "<td>" . $display_role;
								echo '
								<br><form class="form-horizontal" action="../model/admin_user_manage.php" method="post">
								<input type="hidden" name="email" value="' . $data["email"] . '">';
								if ($data["role"]=="user")
									{
										echo '<button type="submit" id="send" name="role" value="user2" class="btn btn-primary btn-xs">Aumentar Privilegios</button>';
									}
								else 
									{
										echo '<button type="submit" id="send" name="role" value="user" class="btn btn-primary btn-xs">Quitar Pirvilegios</button>';
									}
								echo "</form>";
								echo "</td>";
								//End Rol
								echo "</tr>";
							}
						echo "</tbody></table>";					
					} 
				else 
					{
						echo '<div class="alert alert-info" role="alert">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
								<span class="sr-only">Info:</span>
								No hay registros.
							</div>';
					}
			}
		catch (PDOException $e)
			{
				//echo $e->getMessage();
				$error_message = $e->getMessage();
				die($error_message);
			}
		?>
		<br>
			Si desea puede ingresar un email para resetear la contraseña:
			<form class="form-horizontal" action="../model/admin_user_reset.php" method="post">
			<div class="input-group col-md-5">
				<input name="email" type="email" class="form-control" placeholder="Email" required>
				<br><br>
				<button type="submit" id="send" name="reset_password" class="btn btn-danger">Resetear Clave</button>
			</div>
			</form>
    </div>
	

</body>
</html>
