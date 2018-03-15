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
	
	if (isset($_POST["before"])) //current password
		{
			$password_user = $_POST["before"]; // Get user input via form
			$password_user_clean = sanitize_light($password_user); // Filter the user input
		}
	
	if (isset($_POST["after"])) // new password
		{
			$new_password = $_POST["after"];
			$new_password_clean = sanitize_light($new_password);
		}
	
	if (isset($_POST["after2"])) // new password repetition
		{
			$check_password = $_POST["after2"];
			$check_password_clean = sanitize_light($check_password);
		}	
		
	if (empty($_POST["before"]))
		{
			header("Location:../change_password.php?msg=2");
			die();
		}
	
	if (empty($_POST["after"]))
		{
			header("Location:../change_password.php?msg=2");
			die();
		}
		
	if (empty($_POST["after2"]))
		{
			header("Location:../change_password.php?msg=2");
			die();
		}

	if ($new_password_clean != $check_password_clean)
		{
			header("Location:../change_password.php?msg=3");
			die();
		}
	
	if (strlen($new_password_clean) < 8)
		{
			header("Location:../change_password.php?msg=4");
			die();
		}
	
    $user_email = $_SESSION['user_email'];
	
	$current_hashedPW = hash('sha256', $password_user_clean);
	$hashedPW = hash('sha256', $new_password_clean);
	

		
	//---------------------------------------
	// Database Operation
	// CHECK Current PASSWORD
	try
	{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
				  $statement = $pdo->prepare("SELECT * FROM user WHERE email = :email AND password = :password"); // prepare the statement
				  $statement->bindValue(':password', $current_hashedPW, PDO::PARAM_STR);
				  $statement->bindValue(':email', $user_email, PDO::PARAM_STR);
			
			$statement->execute(); // execute it
			
			$affected_rows = $statement->rowCount();
			
			if ($affected_rows < 1)
				{
					header("Location:../change_password.php?msg=2");
					die();
				} 
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			die($error_message);
	}
	
	// UPDATE PASSWORD
	
	try
	{
			$pdo0 = new PDO($dsn, $user, $password);
			$pdo0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
				  $statement0 = $pdo0->prepare("UPDATE user SET password = :password WHERE email = :email"); // prepare the statement
				  $statement0->bindValue(':password', $hashedPW, PDO::PARAM_STR);
				  $statement0->bindValue(':email', $user_email, PDO::PARAM_STR);
			
			$statement0->execute(); // execute it
			
			$affected_rows0 = $statement0->rowCount();
			
			if ($affected_rows0 > 0)
				{
					header("Location:../change_password.php?msg=1");
					die();
				} 
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			die($error_message);
	}
?>