<?php
/**
 * Session - Gerencia sessão e cookies
 * @package com.atitudeweb
 * @version 1.7
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

class Session{
	
	/**
	 * Inicia sessão
	 * 
	 * @return void
	 */
	public static function start(){	
		session_name(Config::SESSION_NAME.'_admin');
		session_start();		
	}
	
	/**
	 * Salva um parametro na sessão
	 * 
	 * @param string $varName
	 * @param mixed $value
	 */
	public static function save($varName, $value){
		$_SESSION[$varName] = $value;
	}
	
	/**
	 * Adquire um parametro da sessão através do nome do parametro
	 * 
	 * @param string $varName
	 * @return mixed
	 */	
	public static function get($varName){
		return @$_SESSION[$varName];
	}
	
	/**
	 * Remove um parametro na sessão
	 * 
	 * @param string $varName
	 * @param mixed $value
	 */
	public static function remove($varName){
		unset($_SESSION[$varName]);
	}
	
	/**
	 * Fecha a sessão
	 * 
	 * @return void
	 */
	public static function close(){
		session_destroy();
		Controller::redirect('index.php');
	}
	
	/**
	 * Salva um parametro no cookie
	 *
	 * @param string $varName
	 * @param mixed $value
	 * @return void
	 */
	public static function saveCookie($varName, $value){
		if(@$_COOKIE[Config::COOKIE_NAME]){
			$obj = unserialize(stripslashes($_COOKIE[Config::COOKIE_NAME]));
			$obj[$varName] = $value;
			setcookie(Config::COOKIE_NAME, serialize($obj), time()+Config::COOKIE_TIME);
		}
		else{
			$obj = array();
			$obj[$varName] = $value;
			setcookie(Config::COOKIE_NAME, serialize($obj), time()+Config::COOKIE_TIME);
		}
	}
	
	/**
	 * Salva um objeto no cookie
	 *
	 * @param mixed $objeto
	 * @return void
	 */
	public static function saveObjCookie($objeto){
		if(@$_COOKIE[Config::COOKIE_NAME]){
			$obj = unserialize(stripslashes($_COOKIE[Config::COOKIE_NAME]));
			foreach($objeto as $key=>$value){
				$obj[$key] = $value;
			}
			setcookie(Config::COOKIE_NAME, serialize($obj), time()+Config::COOKIE_TIME);
		}
		else{
			$obj = array();
			foreach($objeto as $key=>$value){
				$obj[$key] = $value;
			}
			setcookie(Config::COOKIE_NAME, serialize($obj), time()+Config::COOKIE_TIME);
		}
	}
	
	/**
	 * Adquire o cookie através do nome do parametro
	 *
	 * @param string $varName
	 * @return mixed
	 */
	public static function getCookie($varName){
		if(@$_COOKIE[Config::COOKIE_NAME]){
			$obj = unserialize(stripslashes($_COOKIE[Config::COOKIE_NAME]));
			return $obj[$varName];
		}
		else{
			return false;
		}
	}
	
}
?>