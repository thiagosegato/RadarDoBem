<?php
/**
 * Radar do Bem
 * @version 1.0
 * @copyright gluecatcode
*/

define('EXEC', 1);
require_once('framework.php');

header('Content-Type: application/json');

if(!@$_POST['lat'] || !@$_POST['lng']){
	echo "[]";
	exit;
}

$lat = $_POST['lat'];
$lng = $_POST['lng'];
//$page = $_POST['page'];
//$limit = $_POST['limit'];

$sql =
"SELECT *
	FROM (SELECT ci_local, nm_local, fl_ativo, nr_lat, nr_lng, (((acos(sin(($lat *pi()/180)) *
	sin((nr_lat*pi()/180))+cos(( $lat *pi()/180)) *
	cos((nr_lat*pi()/180)) * cos((( $lng -
	nr_lng)*pi()/180))))*180/pi())*60*1.1515*1.609344)
	as distance
	FROM tb_local)myTable
	WHERE fl_ativo = true
	  and distance <= 100
	order by distance asc";
	//limit ".($page * $limit).",".$limit

$query = query($sql);
$result = $query->fetchAll();
echo json_encode($result);
?>
