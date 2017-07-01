<?php
/**
 * Radar do Bem
 * @version 1.0
 * @copyright gluecatcode 
*/

define('EXEC', 1);
require_once('framework.php');

if(!@$_GET['hash'])
	die();

$hash = $_GET['hash'];
$thumb = (@$_GET['thumb'] ? 1 : 0);
$sql = "select * from tb_foto where ds_hash = '$hash'";
$query = query($sql);
if($query->rowCount() > 0){
	
	$file = $query->fetch();	
	header('Content-Type: image/jpeg');
	header('Content-Disposition: inline; filename="'.$hash.'"');
	header('Pragma: no-cache');
	readfile($file['ds_path'].$file['ci_foto'].($thumb ? '_thumb' : '').'.jpg');	
	
}

?>
