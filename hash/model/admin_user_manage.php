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
	
	if (isset($_POST["email"]))
		{
			$new_email = $_POST["email"]; 
			$new_email_clean = sanitize_light($new_email); 
			if (strlen($new_email_clean) < 2) {die("Ingrese Email de Usuario");}
		}
	
	else {die("Ingrese Email de Usuario");}
	
	if (isset($_POST["role"]))
		{
			$new_role = $_POST["role"]; 
			$new_role_clean = sanitize_light($new_role);
			if (strlen($new_role_clean) < 2) {die("Ingrese Rol de Usuario");}
			switch($new_role_clean)
			{
				case 'user' : 
				//OK;
				break;
				case 'user2':
				//OK;
				break;
				default:
				die("Ingrese Rol de usuario válido.");
			}
		}
	
	if (isset($_POST["active"]))
		{
			$new_active = $_POST["active"]; 
			$new_active_clean = sanitize_light($new_active); 
			if (strlen($new_active_clean) < 2) {die("Ingrese Estado de Usuario");}
			switch($new_active_clean)
			{
				case 'yes' : 
				//OK;
				break;
				case 'no':
				//OK;
				break;
				default:
				die("Estado de Usuario no válido.");
			}
		}
	
	
    $user_email = $_SESSION['user_email']; // current logged user email.
	
	$server_date = date('Y-m-d H:i:s');

	
	
	//---------------------------------------
	// Database Operation
	
	if (isset($_POST["role"]))
	{
	try // Change Role-----------------------------------------------------------------------------------
		{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$statement = $pdo->prepare("UPDATE user SET role=:role WHERE email=:email");
			$statement->bindValue(':email', $new_email_clean, PDO::PARAM_STR);
			if ($new_role_clean=="user"){$statement->bindValue(':role', "user", PDO::PARAM_STR);}
			else {$statement->bindValue(':role', "user2", PDO::PARAM_STR);}
			$statement->execute(); // execute it
			
			$affected_rows = $statement->rowCount();
			// echo $affected_rows;
			header("Location:../admin/admin_user_search.php");	
			die();
		}
		
	catch (PDOException $e)
		{
			//echo $e->getMessage();
			$error_message = $e->getMessage();
			die($error_message);
		}
	}
	
	// Active Status
	if (isset($_POST["active"]))
	{
	try // Change User Status-----------------------------------------------------------------------------------
		{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			
			//Get Admin Max User Limits
			$statement1 = $pdo->prepare("SELECT * FROM user WHERE email=:email"); // prepare the statement
			$statement1->bindValue(':email', $user_email, PDO::PARAM_STR);
			$statement1->execute(); // execute it
			$row  = $statement1 -> fetch();
			$admin_user_limit = $row["admin_max_users"]; // to variable
			
			//Count Total Users
			$statement2 = $pdo->prepare("SELECT * FROM user WHERE is_active=:yes"); // prepare the statement
			$statement2->bindValue(':yes', "yes", PDO::PARAM_STR);
			$statement2->execute(); // execute it
			$total_users = $statement2->rowCount();
			
			if (($new_active_clean=="yes")&&($total_users > $admin_user_limit)){die("No puede activar más usuarios de lo contratado, contacte a su proveedor.");}
			
			//Change User Status
			$statement3 = $pdo->prepare("UPDATE user SET is_active=:active WHERE email=:email");
			$statement3->bindValue(':email', $new_email_clean, PDO::PARAM_STR);
			if ($new_active_clean=="yes"){$statement3->bindValue(':active', "yes", PDO::PARAM_STR);}
			else {$statement3->bindValue(':active', "no", PDO::PARAM_STR);}
			$statement3->execute(); // execute it
			
			$affected_rows = $statement3->rowCount();
			// echo $affected_rows;
			header("Location:../admin/admin_user_search.php");	
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