<?php

if(isset($_POST['function'])){
	switch ($_POST['function']){
		case 'test':
			test(); 
	}

}

function test(){
	echo "hi";
}



?>