<?php
/**
 * Radar do Bem
 * @version 1.0
 * @copyright gluecatcode 
*/

//Definindo constantes
defined('EXEC') or die();
define('DS', DIRECTORY_SEPARATOR);

//Ajustando para se basear no diretуrio do framework 
$currentFolder = getcwd();
$parts = explode(DS, $currentFolder);
array_pop($parts);
$correctFolder = implode(DS, $parts).DS.'admin';
define('ATITUDE_CONFIG', implode(DS, $parts));
define('ATITUDE_BASE', $correctFolder);
define('ATITUDE_LIBRARIES', ATITUDE_BASE.DS.'com'.DS.'atitudeweb');
require_once(ATITUDE_CONFIG.DS.'config.php');
require_once(ATITUDE_LIBRARIES.DS.'loader.php');
Loader::import('com.atitudeweb.database.Connection');
Connection::open();

/**
 * Realiza uma consulta na conexгo padrгo do banco de dados
 *
 * @param string $id - Identificador da conexгo
 * @return com.atitudeweb.database.IResult
 */
function query($sql, $id=null){
	return Connection::query($sql, $id);
}

/**
 * Executa uma instruзгo no banco de dados
 *
 * @param string $id - Identificador da conexгo
 * @return boolean
 */
function execute($sql, $id=null){
	return Connection::exec($sql, $id);
}
?>