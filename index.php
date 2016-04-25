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

$apiLink = curl_init("https://localhost/AudioBookIT490/api/v1/");

include("app/model/dbConnect.php");
//include("https://localhost/AudioBookIT490/api/v1/bookApi.php");

$db = NULL;

try{
	$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch( PDOException $e){
	echo $e->getMessage();
}

use app\model as Model;
use app\view as View;
//$apiTest = new $apiLink->bookApi();
$main = new Model\main($db);
//echo $apiTest->getOrderData();
?>
</div>

</body>

	<script type="javascript/text" src="js/jquery-2.1.4.min.js"></script>
	<script type="javascript/text" src="js/bootstrap.min.js"></script>
	<script type="javascript/text" src="js/npm.js"></script>

</html>