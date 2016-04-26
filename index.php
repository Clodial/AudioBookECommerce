<?php

ob_start();
session_start();

?>
<!DOCTYPE html>
<html>
<head>

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<title>Book Sellers</title>
</head>
<body>

<div class="container">
<?php
include("app/model/dbConnect.php");

function bookAPIuse($url, $function, $index) {
	$funArr = array('function' => $function, 'index' => $index);
    $postStr = http_build_query($funArr);
	//curl to the api link
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

echo bookAPIuse('https://web.njit.edu/~cmn6/IT490/testApi.php', 'test', 0);
//echo bookAPIuse("http://localhost/AudioBookIT490/api/v1/bookApi.php", 'addressData', 0);

$db = NULL;
try{
	$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch( PDOException $e){
	echo $e->getMessage();
}
use app\model as Model;
use app\view as View;
$main = new Model\main($db);
//echo bookAPIuse("http://localhost/AudioBookIT490/api/v1/bookApi.php", 'updateOrder', 10);
//echo bookAPIuse("http://localhost/AudioBookIT490/api/v1/bookApi.php", 'addressData', 0);

?>
</div>

</body>

	<script type="javascript/text" src="js/jquery-2.1.4.min.js"></script>
	<script type="javascript/text" src="js/bootstrap.min.js"></script>
	<script type="javascript/text" src="js/npm.js"></script>

</html>