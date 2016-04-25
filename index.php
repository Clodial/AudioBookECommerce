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

//include (htmlentities(file_get_contents("http://localhost/AudioBookIT490/api/v1/bookApi.php")));
include("app/model/dbConnect.php");

function getter($url) {
    $ch = curl_init();
    echo $url;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

echo getter("http://localhost/AudioBookIT490/api/v1/bookApi.php");
//echo file_get_contents("http://localhost/AudioBookIT490/api/v1/bookApi.php");
//fopen("http://localhost/AudioBookIT490/api/v1/bookApi.php", 'r', 1);

$db = NULL;

try{
	$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch( PDOException $e){
	echo $e->getMessage();
}
echo test();
use app\model as Model;
use app\view as View;
$apiTest = new bookApi();
$main = new Model\main($db);
//echo $apiTest->getOrderData();

?>
</div>

</body>

	<script type="javascript/text" src="js/jquery-2.1.4.min.js"></script>
	<script type="javascript/text" src="js/bootstrap.min.js"></script>
	<script type="javascript/text" src="js/npm.js"></script>

</html>