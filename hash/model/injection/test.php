<?php
require('injections.php'); //Anti Hacking Module

// YOU CAN TRY YOUR OWN HACKING METHODS, EX:
// test.php?string=injectionHere

if (isset($_GET["string"]) && !empty($_GET["string"]))
{
    $test = $_GET["string"];
}

else 
{
	//test.php?string=Error'"<marquee><h2>Hacked!</h2></marquee><br>@RomelSan - .com>>>;;
	$test = 'Something here\'"<marquee><h2>Hacked!</h2></marquee><br>@RomelSan - .com>>>;;';
}


	$filter = sanitize_light($test);
	$filter_full = sanitize($test);
	$filter_custom = sanitize_custom($test);
	$nofilter = $test;
	
echo "Note: for SQL Statements you should use <a href='http://www.w3schools.com/php/php_mysql_prepared_statements.asp' target='_blank'>Prepared Statements</a><br>" . "<br><br>";
echo "<b>Filtered Light: </b>sanitize_light(variable);<br><br>";
echo $filter;
echo "<br><br><br><br>";

echo "<b>Filtered Full: </b>sanitize(variable)<br><br>";
echo $filter_full;
echo "<br><br><br><br>";

echo "<b>Filtered Custom: </b>sanitize_custom(variable)<br><br>";
echo $filter_custom;
echo "<br><br><br><br>";


echo "<b>Not Filtered:</b><br><br>";
echo $nofilter;
die();

//test others
// test.php?string=%27%22%3Cscript%3Ealert%28%27hacker%27%29;%3C/script%3E
// test.php?string=Error%27%22%3Cscript%3Ealert%28%27hacked%20por%20hacker%27%29;%3C/script%3E

?>

