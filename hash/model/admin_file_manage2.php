<?php
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
	
	if (isset($_POST["crc32"]))
		{
			$file_crc32 = $_POST["crc32"]; 
			$file_crc32_clean = sanitize_light($file_crc32); 
			if (strlen($file_crc32_clean) < 2) {die("Ingrese CRC32");}
		}
	
	else {die("Ingrese Datos.");}
	
	if (isset($_POST["hash256"]))
		{
			$file_hash256 = $_POST["hash256"]; 
			$file_hash256_clean = sanitize_light($file_hash256);
			if (strlen($file_hash256_clean) < 2) {die("Ingrese SHA256");}
		}
	
	if (isset($_POST["status"]))
		{
			$file_status = $_POST["status"]; 
			$file_status_clean = sanitize_light($file_status); 
			if (strlen($file_status_clean) < 2) {die("Ingrese Estado de Archivo");}
			switch($file_status_clean)
			{
				case 'ok' : 
				//OK;
				break;
				case 'deleted':
				//OK;
				break;
				case 'invalid':
				//OK;
				break;
				default:
				die("Ingrese Estado de Archivo");
			}
		}
	
	
    $user_email = $_SESSION['user_email']; // current logged user email.
	
	$server_date = date('Y-m-d H:i:s');

	
	
	//---------------------------------------
	// Database Operation
	
	if (isset($_POST["hash256"]))
	{
	try // Change Status-----------------------------------------------------------------------------------
		{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$statement = $pdo->prepare("UPDATE filename SET file_status=:status WHERE hash_primary=:sha256 AND hash_secondary=:crc32");
			$statement->bindValue(':status', $file_status_clean, PDO::PARAM_STR);
			$statement->bindValue(':sha256', $file_hash256_clean, PDO::PARAM_STR);
			$statement->bindValue(':crc32', $file_crc32_clean, PDO::PARAM_STR);
			$statement->execute(); // execute it
			
			$affected_rows = $statement->rowCount();
			if ($affected_rows > 0){header("Location:../admin/admin_filename_search.php?msg=1");}
			else {header("Location:../admin/admin_filename_search.php?msg=2");}
			die();
		}
		
	catch (PDOException $e)
		{
			//echo $e->getMessage();
			$error_message = $e->getMessage();
			die($error_message);
		}
	}
		
?>