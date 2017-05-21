<?php
/**
 * Radar do Bem
 * @version 1.0
 * @copyright gluecatcode 
*/

$localhosts = array('::1', '127.0.0.1', 'localhost');
if( in_array($_SERVER['SERVER_ADDR'], $localhosts) === false 
		&& $_SERVER["HTTPS"] != "on") {
	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	exit();	
}

define('EXEC', 1);
define('ATITUDE_BASE', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
require_once(ATITUDE_BASE.DS.'includes'.DS.'framework.php');

ob_start();
require('includes/header.php');

	if(!@$_GET['page']){
		include('includes/home.php');
	}
	else{
		if(file_exists('modules/'.$_GET['page'].'.php')){
			include('modules/'.$_GET['page'].'.php');
		}
		else{
			echo 'Módulo não encontrado: <b>'.'modules/'.$_GET['page'].'.php</b>';
		}				
	}
	echo '</div>';
	include('includes/footer.php'); 			


$data = ob_get_contents();
ob_end_clean();	
echo $data;		
if(@$_POST['info']){
	Util::notice($_POST['title'], $_POST['text'], $_POST['type']);
}		

?>