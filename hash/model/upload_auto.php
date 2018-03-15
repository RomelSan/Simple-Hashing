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
	
	$user_extension = "Document";
	
	$server_date = date('Y-m-d H:i:s');
	
	$file_status = "ok";
		
	
	// If user input is less than 2 chars... DIE
	if (strlen($filename_clean) < 2)
		{
			header("Location:../online-hash/index.php?msg=3");
			die("Ingrese un Documento.");
		}
	if (strlen($sha256_clean) != 64)
		{
			header("Location:../online-hash/index.php?msg=3");
			die("Ingrese SHA256.");
		}
	if (strlen($crc32_clean) != 8)
		{
			header("Location:../online-hash/index.php?msg=3");
			die("Ingrese CRC32.");
		}		
	//---------------------------------------
	// Database Operation
	
	try //Check for duplicates
	{
			$pdo0 = new PDO($dsn, $user, $password);
			$pdo0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement0 = $pdo0->prepare("SELECT * FROM filename WHERE hash_primary=:sha256_clean AND hash_secondary=:crc32_clean"); // prepare the statement
			$statement0->bindValue(':sha256_clean', $sha256_clean, PDO::PARAM_STR);
			$statement0->bindValue(':crc32_clean', $crc32_clean, PDO::PARAM_STR);
			$statement0->execute(); // execute it
			
			$affected_rows0 = $statement0->rowCount();
			if ($affected_rows0 > 0) {header("Location:../online-hash/index.php?msg=2");die();}
			// echo $affected_rows;
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			header("Location:../online-hash/index.php?msg=$error_message");
			die();
	}
	
	try // Upload file hash
		{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement = $pdo->prepare("INSERT INTO filename(filename, hash_primary, hash_secondary, file_extension, date_uploaded, user_email, file_status) VALUES(:filename, :sha256_clean, :crc32_clean, :user_extension, :server_date, :user_email, :file_status)"); // prepare the statement
			$statement->bindValue(':filename', $filename_clean, PDO::PARAM_STR);
			$statement->bindValue(':sha256_clean', $sha256_clean, PDO::PARAM_STR);
			$statement->bindValue(':crc32_clean', $crc32_clean, PDO::PARAM_STR);
			$statement->bindValue(':user_extension', $user_extension, PDO::PARAM_STR);
			$statement->bindValue(':server_date', $server_date, PDO::PARAM_STR);
			$statement->bindValue(':user_email', $user_email, PDO::PARAM_STR);
			$statement->bindValue(':file_status', $file_status, PDO::PARAM_STR);
			$statement->execute(); // execute it
			
			$affected_rows = $statement->rowCount();
			// echo $affected_rows;
			header("Location:../online-hash/index.php?msg=1");	
			die();
		}
		
	catch (PDOException $e)
		{
			//echo $e->getMessage();
			$error_message = $e->getMessage();
			header("Location:../online-hash/index.php?msg=$error_message");
		}

	?>