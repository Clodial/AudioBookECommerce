<?php
namespace app\model;

class payment{

	private $noc;			//name on card
	private $billad			//billing address
	private $ccnum;			//credit card number
	private $expdate;		//expiration date
	private $phNum;			//phone number
	private $db;			//database 

	public function __construct($noc, $billad, $ccnum, $expdate, $phNum, $db){
		
		$this->noc = $noc;
		$this->billad = $billad;
		$this->ccnum = $ccnum;
		$this->expdate = $expdate;
		$this->phNum = $phNum;
		$this->db = $db;

	}

	public function showInfo(){

		return 'noc: ' . $this->noc . ' billad: ' . $this->billad . ' ccnum: ' . $this->ccnum . 'expdate: ' . $this->expdate . ' phone: ' . $this->phNum;

	} 
	public function getNOC(){

		return $this->noc;
	
	}
	public function getBillAd(){

		return $this->billad;
	
	}	
	public function getCcNum(){

		return $this->ccnum;
	
	}
	public function getExpDate(){

		return $this->expdate;
	
	}
	public function getPhone(){

		return $this->phNum;
	
	}

}


?>