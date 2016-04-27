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


function bookAPIuse($url, $function, $index, $flNum) {
	$funArr = array('function' => $function, 'index' => $index, 'flNum' => $flNum);
    $postStr = http_build_query($funArr);
	//curl to the api link
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_PORT, 8088);
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
</div>

</body>

	<script type="javascript/text" src="js/jquery-2.1.4.min.js"></script>
	<script type="javascript/text" src="js/bootstrap.min.js"></script>
	<script type="javascript/text" src="js/npm.js"></script>

</html>