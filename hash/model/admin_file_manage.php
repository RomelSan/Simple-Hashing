<?php
// This file "admin_file_manage" shows the table.
// The changes occurs on "admin_file_manage2".
session_start();
if($_SESSION['user_login']=="TRUE"){}
else{
	$_SESSION['error_message'] = "Ingrese sus credenciales.";
	session_destroy();
	header("Location:../index.php");
	}

// Check Role "need to have admin" to access admin panel.
if($_SESSION['user_role']!="admin"){header("Location:../online-hash/index.php?msg=4");die();}
	
	header("Access-Control-Allow-Origin: *");
	header('charset=UTF-8');
	require('dbcore.php'); //Call database connection module
	require('injection/injections.php'); //Anti Hacking Module
	
	function html_noregistry() 
		{
			include 'pre_content.html';		
			echo '<div class="alert alert-info" role="alert">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<span class="sr-only">Info:</span>
							No hay registros.
						  </div>';
			echo "</div></div></body></html>"; //close container and html
		}
	
	if (isset($_POST["filename"])) 
		{
			$filename_user = $_POST["filename"]; // Get user input via form
			$filename_clean = sanitize_light($filename_user); // Filter the user input
		}
	
	if (isset($_POST["hash256"]))
		{
			$sha256_user = $_POST["hash256"];
			$sha256_clean = sanitize_light($sha256_user);
		}
	
	if (isset($_POST["email"]))
		{
			$search_email = $_POST["email"];
			$search_email_clean = sanitize_light($search_email);
		}	
		
	if (isset($_POST["date"]))
		{
			$search_date = $_POST["date"];
			$search_date_clean = sanitize_light($search_date);
		}	
		
	if (empty($_POST["filename"]) and empty($_POST["hash256"]) and empty($_POST["email"]) and empty($_POST["date"])) // if nothing is entered then die
		{
			html_noregistry();
			die();
		}
		
	if (!empty($_POST["filename"]) and strlen($filename_clean) < 4)
		{
			html_noregistry();
			die();
		}
	
    $user_email = $_SESSION['user_email'];
		
	//---------------------------------------
	// Database Operation
	
	try
	{
			$pdo0 = new PDO($dsn, $user, $password);
			$pdo0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			if (!empty($_POST["email"]) and !empty($_POST["date"]))
				{
				  $statement0 = $pdo0->prepare("SELECT * FROM filename WHERE user_email=:email AND date_uploaded LIKE :date ORDER BY date_uploaded DESC LIMIT 500"); // prepare the statement
				  $statement0->bindValue(':email', $search_email_clean, PDO::PARAM_STR);
				  $statement0->bindValue(':date', $search_date_clean . "%", PDO::PARAM_STR);
				}
			
			else if (!empty($_POST["filename"]))
				{
				  $statement0 = $pdo0->prepare("SELECT * FROM filename WHERE filename LIKE :filename ORDER BY date_uploaded DESC LIMIT 500"); // prepare the statement
				  $statement0->bindValue(':filename', "%" . $filename_clean . "%", PDO::PARAM_STR);
				}
				
			else if (!empty($_POST["email"]))
				{
				  $statement0 = $pdo0->prepare("SELECT * FROM filename WHERE user_email=:email ORDER BY date_uploaded DESC LIMIT 500"); // prepare the statement
				  $statement0->bindValue(':email', $search_email_clean, PDO::PARAM_STR);
				}
				
			else if (!empty($_POST["date"]))
				{
				  $statement0 = $pdo0->prepare("SELECT * FROM filename WHERE date_uploaded LIKE :date ORDER BY date_uploaded DESC LIMIT 500"); // prepare the statement
				  $statement0->bindValue(':date', $search_date_clean . "%", PDO::PARAM_STR);
				}
				
			else if (!empty($_POST["hash256"]))
				{
				  $statement0 = $pdo0->prepare("SELECT * FROM filename WHERE hash_primary=:hash ORDER BY date_uploaded DESC LIMIT 500"); // prepare the statement
				  $statement0->bindValue(':hash', $sha256_clean , PDO::PARAM_STR);
				}
			
			$statement0->execute(); // execute it
			
			$rows = $statement0->fetchAll(PDO::FETCH_ASSOC);
			$affected_rows0 = $statement0->rowCount();
			include 'pre_content.html';
			if ($affected_rows0 > 0)
				{
					echo '<table width="100%" id="data1" class="table table-bordered table-hover"><thead><tr><th>Fecha</th><th>Email</th><th>Archivo Original</th><th>Estado</th><th>Hash 256</th></tr></thead>';
					echo '<tbody>';
					// output data of each row
					foreach($rows as $data) 
						{
							if ($data["file_status"]=="invalid"){$status_description="Inválido"; $row_status_class='<tr class="danger">';}
							else if ($data["file_status"]=="deleted"){$status_description="Eliminado"; $row_status_class='<tr class="warning">';}
							else {$status_description="OK"; $row_status_class='<tr>';}
							echo $row_status_class; //Equivalent = "<tr>";
							echo "<td>" . $data["date_uploaded"] . "</td>";
							echo "<td>" . $data["user_email"] . "</td>";
							echo "<td>" . $data["filename"] . "</td>";
							// begin status_description "change status button."
							echo "<td>" . $status_description;
							echo '
								<br><form class="form-horizontal" action="../model/admin_file_manage2.php" method="post">
								<input type="hidden" name="crc32" value="' . $data["hash_secondary"] . '">
								<input type="hidden" name="hash256" value="' . $data["hash_primary"] . '">
								';
								
								if ($data["file_status"]=="ok")
									{
										echo '<button type="submit" id="send" name="status" value="deleted" class="btn btn-primary btn-xs">Eliminado</button>&nbsp';
										echo '<button type="submit" id="send" name="status" value="invalid" class="btn btn-primary btn-xs">Inválido</button>';
									}
								else if ($data["file_status"]=="deleted")
									{
										echo '<button type="submit" id="send" name="status" value="ok" class="btn btn-primary btn-xs">Válido</button>&nbsp';
										echo '<button type="submit" id="send" name="status" value="invalid" class="btn btn-primary btn-xs">Inválido</button>';
									}
								else 
									{
										echo '<button type="submit" id="send" name="status" value="deleted" class="btn btn-primary btn-xs">Eliminado</button>&nbsp';
										echo '<button type="submit" id="send" name="status" value="ok" class="btn btn-primary btn-xs">Válido</button>';										
									}
								echo "</form>";
								echo "</td>";
								//End status_description
							
							echo "<td>" . $data["hash_primary"] . "</td>";
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
				echo "</div></div></body></html>"; //close container and html
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			die($error_message);
	}
?>