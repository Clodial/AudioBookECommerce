<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

$apiName = 'it490';
$apiPass = 'root';
$apiUser = 'root';
$apiHost = 'localhost';
if(null != (getenv('JAWSDB_URL'))){
	$url = getenv('JAWSDB_URL');
	$apiparts = parse_url($url);
	$apiName = ltrim($apiparts['path'],'/');
	$apiPass = $dbparts['pass'];
	$apiUser = $dbparts['user'];
	$apiHost = $dbparts['host'];
}
$dbApi = NULL;
try{
	$dbApi = new PDO("mysql:host=$apiHost;dbname=$apiName", $apiUser, $apiPass);
	$dbApi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch( PDOException $e){
	echo $e->getMessage();
}

if(isset($_POST['function'])){
	switch ($_POST['function']){
		case 'test':
			test(); 
			break;
		case 'addressData':
			addressData($dbApi);
			break;
		case 'updateOrder':
			if(isset($_POST['index']) && $_POST['index'] != 'none'){
				updateOrder($dbApi, intval($_POST['index']));
			}
			break;
	}

}

function test(){
	$testArr = array('test' => 'works');
	$testVal = json_encode($testArr);
	echo $testVal;
}

function addressData($dbUse){
	//Gets all of the necessary data, including the user, their address and the order number that is to be updated
	$returnStmt = array();
	try{
		$apiStmt = $dbUse->prepare('
			select account.account_address as address,
			account.account_username as user,
			`order`.order_ID
			from account, order_status, `order`
			where order_status.order_status = "in-progress"
				and order_status.order_status_ID = `order`.order_status_ID
				and account.account_ID  = `order`.account_ID');
		if($apiStmt->execute()){
			while($data = $apiStmt->fetch()){
				$newRow = array('address' => $data[0], 'user' => $data[1], 'order_ID' => $data[2]);
				array_push($returnStmt, $newRow);	
			}
		}
	}catch(PDOException $e){
		echo "problem";
		echo $e->getMessage();
	}
	echo json_encode($returnStmt);
}

function updateOrder($dbUse, $index){
	//Updates order to complete based on an index
	try{
		$dbUse->beginTransaction();
		$apiStmt = $dbUse->prepare('
			update `order`, order_status 
			set `order`.order_status_ID = (select order_status_ID 
											from order_status 
											where order_status.order_status = "complete")
			where `order`.order_ID = :ord
			and order_status.order_status = "in-progress"
			and `order`.order_status_ID = order_status.order_status_ID;
			');
		$apiStmt->bindParam(':ord', $index);
		$apiStmt->execute();
		$dbUse->commit();
	}catch(PDOException $e){
		$dbUse->rollBack();
	}
}

?>