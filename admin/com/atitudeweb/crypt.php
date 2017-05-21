<?php
/**
 * Crypt - Responsável pela criptografia do sistema
 * @package com.atitudeweb
 * @version 1.1
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

class Crypt{
	
	static public function hash($obj){		
		$a = serialize($obj);
		$a = base64_encode($a);
		return strrev($a);		
	}
	
	static public function decode($hash){
		$a = strrev($hash);
		$a = base64_decode($a);
		return unserialize($a);	
	}
	
}
?>