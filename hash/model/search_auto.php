<?php
session_start();
if($_SESSION['user_login']=="TRUE"){}
else{
	session_destroy();
	header("Location:../index.php?err=2");
	}

	header("Access-Control-Allow-Origin: *");
	header('charset=UTF-8');
	require('dbcore.php'); //Call database connection module
	require('injection/injections.php'); //Anti Hacking Module
	
	
	$filename_user = $_POST["filename"]; // Get user input via form
	$filename_clean = sanitize_light($filename_user); // Filter the user input
	
	$sha256_user = $_POST["sha256"];
	$sha256_clean = sanitize_light($sha256_user);
	
	$crc32_user = $_POST["crc32"];
	$crc32_clean = sanitize_light($crc32_user);
	
    $user_email = $_SESSION['user_email'];
		
	// If user input is less than 2 chars... DIE
	if (strlen($filename_clean) < 2)
		{
			header("Location:../online-hash/index.php?msg=3");
			die("Ingrese un Documento.");
		}
	if (strlen($sha256_clean) < 2)
		{
			header("Location:../online-hash/index.php?msg=3");
			die("Ingrese SHA256.");
		}
	if (strlen($crc32_clean) < 2)
		{
			header("Location:../online-hash/index.php?msg=3");
			die("Ingrese CRC32.");
		}		
	//---------------------------------------
	// Database Operation
	
	try
	{
			$pdo0 = new PDO($dsn, $user, $password);
			$pdo0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement0 = $pdo0->prepare("SELECT * FROM filename WHERE hash_primary=:sha256_clean AND hash_secondary=:crc32_clean"); // prepare the statement
			$statement0->bindValue(':sha256_clean', $sha256_clean, PDO::PARAM_STR);
			$statement0->bindValue(':crc32_clean', $crc32_clean, PDO::PARAM_STR);
			$statement0->execute(); // execute it
			
			$rows = $statement0->fetchAll(PDO::FETCH_ASSOC);
			$affected_rows0 = $statement0->rowCount();
			include 'pre_content.html';
			if ($affected_rows0 > 0)
				{
					echo '<table class="table table-bordered table-hover"><thead><tr><th>Fecha</th><th>Email</th><th>Archivo Original</th><th>Estado</th><th>Hash 256</th></tr></thead>';
					echo '<tbody>';
					// output data of each row
					foreach($rows as $data) 
						{
							if ($data["file_status"]=="invalid"){$status_description="Inválido";}
							else if ($data["file_status"]=="deleted"){$status_description="Eliminado";}
							else {$status_description="OK";}
							echo "<tr>";
							echo "<td>" . $data["date_uploaded"] . "</td>";
							echo "<td>" . $data["user_email"] . "</td>";
							echo "<td>" . $data["filename"] . "</td>";
							echo "<td>" . $status_description . "</td>";
							echo "<td>" . $data["hash_primary"] . "</td>";
							echo "</tr>";
						}
					echo "</tbody></table>";
					
					if ($data["file_status"]=="invalid") //Invalid Hash
						{
					echo '<div class="alert alert-danger" role="alert">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							<span class="sr-only">Inválido:</span>
							El archivo ha sido marcado como inválido por el administrador.
						  </div>';
						}
					else if ($data["file_status"]=="deleted") //deleted Hash
						{
					echo '<div class="alert alert-warning" role="alert">
							<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
							<span class="sr-only">Eliminado:</span>
							El archivo ha sido eliminado por el administrador.
						  </div>';
						}
					else // Good Hash
						{
							echo '<div class="alert alert-success" role="alert">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<span class="sr-only">OK:</span>
							La integridad del archivo corresponde al hash de la base de datos.
						  </div>';
						}
				} 
				
			else //Hash not found
				{
					echo '<div class="alert alert-danger" role="alert">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<span class="sr-only">Advertencia:</span>
							El archivo no existe o ha sido modificado.
						  </div>' . "<b>Archivo: </b>$filename_clean<br><b>SHA256: </b>$sha256_clean";
				}
				echo "</div></div></body></html>"; //close container and html
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			header("Location:../online-hash/index.php?msg=$error_message");
			die();
	}
?>