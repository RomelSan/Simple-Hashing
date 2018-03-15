<?php
	
	header("Access-Control-Allow-Origin: *");
	header('charset=UTF-8');
	require('dbcore.php'); //Call database connection module
	require('injection/injections.php'); //Anti Hacking Module
	
		// If user input is less than 2 chars... DIE
	if (!isset($_POST["email"]))
		{
			die("Please login using the system");
		}
	
	$email_user = $_POST["email"]; // Get user input via form
	$email_clean = sanitize_light($email_user); // Filter the user input
	
	$password_user = $_POST["password"];
	$password_clean = sanitize_light($password_user);
	$hashedPW = hash('sha256', $password_clean); // or use sha512
	
	$login_date = date('Y-m-d H:i:s');
		
	

	//---------------------------------------
	// Database Operation
	try 
		{
			$pdo = new PDO($dsn, $user, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$statement = $pdo->prepare("SELECT * FROM user WHERE email=:user && password=:password"); // prepare the statement
			$statement->bindValue(':user', $email_clean, PDO::PARAM_STR);
			$statement->bindValue(':password', $hashedPW, PDO::PARAM_STR);
			$statement->execute(); // execute it
			
			$result_data = $statement->fetch(PDO::FETCH_ASSOC);			
		}
		
	catch (PDOException $e)
		{
			echo $e->getMessage();
			die();
		}
	//END DATABASE Operation	
	
if($result_data)
		{
		  session_start();		  
		  $_SESSION['user_email']=$result_data['email'];
		  $_SESSION['user_role']=$result_data['role'];
		  $_SESSION['user_active']=$result_data['is_active'];
		  $_SESSION['user_login']="TRUE"; //set login true flag
		  
		  if($_SESSION['user_active'] != "yes") //check if user is active or not
			{
				$_SESSION['user_login']="FALSE";
				header("Location:../index.php?err=3");
				die();
			}
		  
		  //Logs the user last login date
		  try 
		      {
		        $pdo2 = new PDO($dsn, $user, $password);
		        $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		        $statement2 = $pdo->prepare("UPDATE user SET last_login_date=:login_date WHERE email=:email"); // prepare the statement
		        $statement2->bindValue(':login_date', $login_date, PDO::PARAM_STR);
		        $statement2->bindValue(':email', $_SESSION['user_email'], PDO::PARAM_STR);
		        $statement2->execute(); // execute it
		      }
	      catch (PDOException $e)
		      {   
			    $error_message = $e->getMessage();
				echo "SQL last_login error";
				echo $error_message;
			    die();
		      }
		  
		  //Redirects
		  if ($_SESSION['user_role']=="user"){header("Location:../online-hash/");}
		  else if($_SESSION['user_role']=="user2"){header("Location:../online-hash/");}
		  else if($_SESSION['user_role']=="admin"){header("Location:../admin/");}
		  else{session_destroy(); die("Unknown User Role for" . $result_data['email']);}
		}
else
	  {
		header("Location:../index.php?err=1");exit();
	  }

	?>