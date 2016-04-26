<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

//database connectivity variables
$apiName = 'it490';
$apiPass = 'root';
$apiUser = 'root';
$apiHost = 'localhost';
if(null != (getenv('JAWSDB_URL'))){
	$url = getenv('JAWSDB_URL');
	$apiparts = parse_url($url);
	$apiName = ltrim($apiparts['path'],'/');
	$apiPass = $apiparts['pass'];
	$apiUser = $apiparts['user'];
	$apiHost = $apiparts['host'];
}
$dbApi = NULL;
try{
	// create the database object capable of doing mysql database calls
	$dbApi = new PDO("mysql:host=$apiHost;dbname=$apiName", $apiUser, $apiPass);
	$dbApi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch( PDOException $e){
	echo $e->getMessage();
}


// route which function the user needs to use
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
}else if(isset($_GET['function'])){
	//route in case the user wants to do things via their web browser without a web front end to connect to it
	switch ($_GET['function']){
		case 'test':
			test(); 
			break;
		case 'addressData':
			addressData($dbApi);
			break;
		case 'updateOrder':
			if(isset($_GET['index']) && $_GET['index'] != 'none'){
				updateOrder($dbApi, intval($_GET['index']));
			}
			break;
	}
}

// the basic test function
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
				and account.account_ID  = `order`.account_ID
				and `order`.flight_num = NULL');
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

function getFullOrder($dbUse){
	$returnStmt = array();
	try{
		$apiStmt = $dbUse->prepare('
			select account.account_address as address,
			account.account_username as user,
			`order`.order_ID
			from account, order_status, `order`
			where order_status.order_status = "in-progress"
				and order_status.order_status_ID = `order`.order_status_ID
				and account.account_ID  = `order`.account_ID
				and `order`.flight_num != NULL');
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

// for the other api
/*
function getSkid($dbUse){
	//meant to check if skid value is valid
	$arr = array();
	$apiStmt2 = $dbUse->prepare("select Skid_ID from CargoTable");
	if($apiStmt2->execute()){
		while($data = $apiStmt2->fetch()){
			array_push($arr, $data[0]);
		}
	}
	return $arr;
}

function addItem($dbUse, $flNum, $ordNum){
	//add an item to a flight with an order number as the order descriptor
	$skid = rand(0, 9999);
	$arrCh = getSkit($dbUse);
	while(in_array($skid, $arrCh)){
		$skid = rand(0,9999);
	}
	try{
		$dbUse->beginTransaction();
		$apiStmt = $dbUse->prepare('
			insert into CargoTable values(:skid, 5, :ord, :fNum)');
		$apiStmt->bindParam(':skid', $skid);
		$apiStmt->bindParam(':ord', $ordNum);
		$apiStmt->bindParam(':fNum', $flNum);
		$apiStmt->execute();
		$apiStmt = $dbUse->prepare('
			update FlightTable set Skid_ID = :skid where Flight_NUM = :fNum');
		$apiStmt->bindParam(':skid', $skid);
		$apiStmt->bindParam(':fNum', $flNum);
		$apiStmt->execute();
		$dbUse->commit();
	}catch(PDOException $e){
		$dbUse->rollBack();
	}
}

//part 1
function getFlight($dbUse){
	$flArr = array();

	$apiStmt = $dbUse->prepare('
		select Flight_NUM, Departure_TO 
		from FlightTable 
		where Skid_ID = NULL
		AND Departure_FROM = "NWK"
		AND Departure_FROM >= CURDATE()');
	if($apiStmt->execute()){
		while($data = $apiStmt->fetch()){
			array_push($flArr, $data[0]);
		}
	}
	echo json_encode($flArr);
} 

// part 4
function searchByOrd($dbUse, $index){
	$ordArr = array();
	$indCh = strval($index);
	$apiStmt = $dbUse->prepare('
		select Flight_NUM from CargoTable where Skid_CONTENT = :ord');
	$apiStmt->bindParam(':ord', $index);
	if($apiStmt->execute()){
		while($data = $apiStmt->fetch()){
			array_push($ordArr, $data[0]);
		}
	}

	echo json_encode($ordArr);
}

function getAddress($dbUse){
	$addArr = array();
	$apiStmt = $dbUse->prepare('
		select destName from flightDest');
	if($apiStmt->execute()){
		while($data = $apiStmt->fetch()){
			array_push($addArr, $data[0]);
		}
	}
	echo json_encode($addArr);
}
*/
?>
