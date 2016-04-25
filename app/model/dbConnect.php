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

$dbName = 'mdm39';
$dbPass = 'seminole9';
$dbUser = 'mdm39';
$dbHost = 'sql2.njit.edu';

if(null != (getenv('JAWSDB_URL'))){
	$url = getenv('JAWSDB_URL');
	$dbparts = parse_url($url);
	$dbName = ltrim($dbparts['path'],'/');
	$dbPass = $dbparts['pass'];
	$dbUser = $dbparts['user'];
	$dbHost = $dbparts['host'];
}

$mainEmail = 'ajm27@njit.edu';

?>