<?php
/**
 * Loader - Carrega classes do framework
 * @package com.atitudeweb
 * @version 1.0
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

class Loader{
	
	/**
	 * Carrega classes a partir de pacotes informados
	 * 
	 * @param string $classPath
	 * @return boolean
	 */
	public static function import($classPath){
		$result = true;
		$path = str_replace('.', DS, $classPath);
		$path = strtolower($path);		
		if(!include_once(ATITUDE_BASE.DS.$path.'.php')){
			$result = false;			
		}		
		return $result;		
	}	
}
?>