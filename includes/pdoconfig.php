<?php

$dbhost = 'localhost';
$dbuser = 'homepla5_rufus';
$dbpass = '!Pls2Gv$DbRts+';
$dbname = 'homepla5_hpdb1';

$dsn = "mysql:host=localhost;dbname=$dbname";

try
{
	$connection = new PDO($dsn, $dbuser, $dbpass);
}
catch (PDOException $exception)
{
	echo "Exception error: ".$exception->getMessage();
}
