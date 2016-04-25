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
function bookAPIuse($url, $function) {
    $ch = curl_init();
    $funArr ='function=' . $function;
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $funArr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

echo bookAPIuse("http://audio-book-it490/api/v1/bookApi.php",'test');
//echo file_get_contents("http://localhost/AudioBookIT490/api/v1/bookApi.php", true);
//$file = getter("http://localhost/AudioBookIT490/api/v1/api.php");
//fopen("http://localhost/AudioBookIT490/api/v1/bookApi.php", 'r', 1);
$db = NULL;

try{
	$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch( PDOException $e){
	echo $e->getMessage();
}
//echo test();
use app\model as Model;
use app\view as View;
//$apiTest = new bookApi();
$main = new Model\main($db);
//echo $apiTest->getOrderData();

?>
</div>

</body>

	<script type="javascript/text" src="js/jquery-2.1.4.min.js"></script>
	<script type="javascript/text" src="js/bootstrap.min.js"></script>
	<script type="javascript/text" src="js/npm.js"></script>

</html>