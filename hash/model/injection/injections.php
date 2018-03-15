<?php
//Anti Hacking by @RomelSan
	// USAGE: require('injections.php');
	// good_string = sanitize($bad_string);
	// Anti Hacking END
	
/*
* USAGE:

require('injections.php');

* To Sanitize input:

good_string = sanitize_light($bad_string);


--or:

good_string = sanitize($bad_string);
good_string = sanitize_custom($bad_string);




Note: for SQL Statements you should use "Prepared Statements"
	http://www.w3schools.com/php/php_mysql_prepared_statements.asp

*/	
	

//----------------------------------------------------------------------------------
	function sanitize($input) 
		{
			$clean = $input;
			
			$clean = filter_var($clean, FILTER_SANITIZE_STRING); // Removes HTML Tags
			$clean = stripslashes($clean); // Removes Slashes
			$clean = filter_var($clean, FILTER_SANITIZE_ENCODED); // URL-encode string, optionally strip or encode special characters
			$clean = filter_var($clean, FILTER_SANITIZE_MAGIC_QUOTES); //Apply addslashes()
			
			
			$output = $clean;
			return $output;
		}
	
	function sanitize_light($input) 
		{
			$clean = $input;
			
			$clean = filter_var($clean, FILTER_SANITIZE_SPECIAL_CHARS); // HTML-escape '"<>& and characters with ASCII value less than 32
			//FILTER_SANITIZE_EMAIL  // Remove all characters, except letters, digits and !#$%&'*+-/=?^_`{|}~@.[]
			//FILTER_SANITIZE_URL	//Remove all characters, except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=
			
			
			$output = $clean;
			return $output;
		}
	
	// Validate Function:
			// FILTER_VALIDATE_EMAIL  // 	Validate value as e-mail
			// FILTER_VALIDATE_IP  // Validate value as IP address, optionally only IPv4 or IPv6 or not from private or reserved ranges
			// FILTER_VALIDATE_URL  // Validate value as URL, optionally with required components
			
			
//---------------------------------------------------------------------------------------------------------------------
//------------------------CUSTOM SANITIZE------------------------------------------------------------------------------

function cleanInput($input) 
		{
 
			$search = array(
				'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
				'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
				'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
				'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
							);
 
			$output = preg_replace($search, '', $input);
			return $output;
		}

	function sanitize_custom($input) 
		{
			if (is_array($input)) 
				{
					foreach($input as $var=>$val) 
					{
						$output[$var] = sanitize($val);
					}
				}
			else 
				{
					if (get_magic_quotes_gpc()) 
					{
						$input = stripslashes($input);
					}
				$output = cleanInput($input);
				}
		return $output;
		}