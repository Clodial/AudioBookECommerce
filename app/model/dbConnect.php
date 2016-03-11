<?php

/**
*
* @brief -> This file is responsible for all database testing 
* 			and editing
*
**/

error_reporting(E_ALL);
ini_set('display_errors','On');
include('app/autoloader.php');
spl_autoload_register('autoloader::load');

if(null != (getenv('DATABASE_URL'))){
	echo extract(parse_url(getenv("DATABASE_URL")));
}

$dbName = 'it490';
$dbPass = 'root';
$dbUser = 'root';
$dbHost = 'localhost';
$mainEmail = 'ajm27@njit.edu';

?>