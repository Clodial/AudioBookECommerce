<?php
namespace app\model;

class main{

	public function __construct($db){
		
		$page_request = 'app\view\\' .'pageIndex';
		
		if(!empty($_REQUEST) && isset($_REQUEST['page'])){
			$page_request = 'app\view\\' . $_REQUEST['page'];
			
		}

		$page = new $page_request($db);
		if(isset($_POST)){
			$page->post();
		}else{
			$page->get();
		}
	}

}


?>