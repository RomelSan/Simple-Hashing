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
	
	$new_firstname = $_POST["firstname"]; // Get user input via form
	$new_firstname_clean = sanitize_light($new_firstname); // Filter the user input
	
	$new_lastname = $_POST["lastname"]; 
	$new_lastname_clean = sanitize_light($new_lastname); 
	
	$new_social_id = $_POST["social_id"]; 
	$new_social_id_clean = sanitize_light($new_social_id); 
	
	$new_email = $_POST["email"]; 
	$new_email_clean = sanitize_light($new_email); 
	
	$new_password = $_POST["password"]; 
	$new_password_clean = sanitize_light($new_password); 
	$hashedPW = hash('sha256', $new_password_clean); // or use sha512

	$new_role = $_POST["role"]; 
	$new_role_clean = sanitize_light($new_role); 
	
	$new_is_active = "yes";
	$admin_max_users = 0;
	
    $user_email = $_SESSION['user_email']; // current logged user email.
	
	$server_date = date('Y-m-d H:i:s');
		
	
	// If user input is less than 2 chars... DIE
	if (strlen($new_firstname_clean) < 2) {die("Ingrese nombre de usuario");}
	if (strlen($new_lastname_clean) < 2) {die("Ingrese apellido de usuario");}
	if (strlen($new_social_id_clean) < 2) {die("Ingrese cédula de usuario");}
	if (strlen($new_email_clean) < 2) {die("Ingrese email de usuario");}
	if (strlen($new_password_clean) < 8) {die("Ingrese Clave de usuario y mínimo 8 caracteres");}
	if (strlen($new_role_clean) < 2) {die("Ingrese Rol de usuario");}
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
	
	//---------------------------------------
	// Database Operation
	
	try //Check for email duplicates
	{
			$pdo0 = new PDO($dsn, $user, $password);
			$pdo0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement0 = $pdo0->prepare("SELECT * FROM user WHERE email=:email"); // prepare the statement
			$statement0->bindValue(':email', $new_email_clean, PDO::PARAM_STR);
			$statement0->execute(); // execute it
			
			$affected_rows0 = $statement0->rowCount();
			if ($affected_rows0 > 0) {header("Location:../admin/index.php?msg=2");die();}
			// echo $affected_rows;
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			header("Location:../admin/index.php?msg=$error_message");
			die();
	}
	
	try //Check for social_id duplicates
	{
			$pdo1 = new PDO($dsn, $user, $password);
			$pdo1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement1 = $pdo1->prepare("SELECT * FROM user WHERE social_id=:social_id"); // prepare the statement
			$statement1->bindValue(':social_id', $new_social_id, PDO::PARAM_STR);
			$statement1->execute(); // execute it
			
			$affected_rows1 = $statement1->rowCount();
			if ($affected_rows1 > 0) {header("Location:../admin/index.php?msg=2");die();}
			// echo $affected_rows;
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			header("Location:../admin/index.php?msg=$error_message");
			die();
	}
	
	try //Check for Max Users and user counts
	{
			$pdo2 = new PDO($dsn, $user, $password);
			$pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//Check Admin Max User Limits
			$statement2 = $pdo2->prepare("SELECT * FROM user WHERE email=:email"); // prepare the statement
			$statement2->bindValue(':email', $user_email, PDO::PARAM_STR);
			$statement2->execute(); // execute it
			$row  = $statement2 -> fetch();
			$admin_user_limit = $row["admin_max_users"]; // to variable
			
			//Check Total Users "Active"
			$statement3 = $pdo2->prepare("SELECT * FROM user WHERE is_active=:yes"); // prepare the statement
			$statement3->bindValue(':yes', "yes", PDO::PARAM_STR);
			$statement3->execute(); // execute it
			$total_users = $statement3->rowCount();
			
			if ($total_users > $admin_user_limit){die("No puede crear más usuarios, contacte a su proveedor.");}
			
			//Check Total Users "Active" and "Not Active"
			$statement4 = $pdo2->prepare("SELECT * FROM user"); // prepare the statement
			$statement4->execute(); // execute it
			$total_users2 = $statement4->rowCount();			
			if ($total_users2 > 499){die("No puede crear más de 500 usuarios.");}			
	}
	catch (PDOException $e)
	{
		//echo $e->getMessage();
			$error_message = $e->getMessage();
			header("Location:../admin/index.php?msg=$error_message");
			die();
	}
	
	try // Create USER-----------------------------------------------------------------------------------
		{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement = $pdo->prepare("INSERT INTO user(firstname, lastname, social_id, email, password, creation_date, is_active, role, admin_max_users) VALUES(:firstname, :lastname, :social_id, :email, :password, :creation_date, :is_active, :role, :admin_max_users)");
			$statement->bindValue(':firstname', $new_firstname_clean, PDO::PARAM_STR);
			$statement->bindValue(':lastname', $new_lastname_clean, PDO::PARAM_STR);
			$statement->bindValue(':social_id', $new_social_id_clean, PDO::PARAM_STR);
			$statement->bindValue(':email', $new_email_clean, PDO::PARAM_STR);
			$statement->bindValue(':password', $hashedPW, PDO::PARAM_STR);
			$statement->bindValue(':creation_date', $server_date, PDO::PARAM_STR);
			$statement->bindValue(':is_active', $new_is_active, PDO::PARAM_STR);
			$statement->bindValue(':role', $new_role_clean, PDO::PARAM_STR);
			$statement->bindValue(':admin_max_users', $admin_max_users, PDO::PARAM_STR);
			$statement->execute(); // execute it
			
			$affected_rows = $statement->rowCount();
			// echo $affected_rows;
			header("Location:../admin/index.php?msg=1");	
			die();
		}
		
	catch (PDOException $e)
		{
			//echo $e->getMessage();
			$error_message = $e->getMessage();
			header("Location:../admin/index.php?msg=$error_message");
		}
		
?>