<?php
/**
 * Driver de conexão com o banco de dados postgres
 * @package com.atitudeweb.database.driver
 * @version 1.3
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */
 
//Carregando interfaces
Loader::import('com.atitudeweb.database.IDriver');
Loader::import('com.atitudeweb.database.IResult');

class DriverMssql implements IDriver{
	
	private $connection;
	private $config;
	
	public function DriverMssql($config=null){
		$this->config = $config;	
	}
	
	/**
	 * Abre a conexão padrão com o banco de dados
	 *
	 * @return void
	 */
	public function open(){
		if(!$this->connection)
		{
			if(!$this->config){			
				$host = Config::MSSQL_HOST;
				$name = Config::MSSQL_NAME;
				$user = Config::MSSQL_USER;
				$pass = Config::MSSQL_PASS;					
			}
			else{
				$host = $this->config['host'];
				$name = $this->config['name'];
				$user = $this->config['user'];
				$pass = $this->config['pass'];			
			}
			
			if(!function_exists('mssql_connect')){
				die('A função mssql_connect não foi encontrada. Habilite o módulo do microsoft sql server para php!');
				return false;
			}
			
			$this->connection = mssql_connect($host, $user, $pass);
			if(!$this->connection){
				die('Não foi possível obter conexão com a base de dados!');
			}
			else{
				mssql_select_db($name, $this->connection);
				return true;
			}			
		}
		else{
			return true;
		}
	}
	
	/**
	 * Fecha a conexão padrão com o banco de dados
	 *
	 * @return boolean
	 */
	public function close(){
		return mssql_close($this->connection);
	}
	
	/**
	 * Realiza uma consulta na conexão padrão do banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return com.atitudeweb.database.Result
	 */
	public function query($sql){
		
		if(!Config::MSSQL_UTF8)
			$sql = utf8_decode($sql);
		
		$resource = mssql_query($sql, $this->connection);
		if(!$resource)
			return false;
		return new ResultMssql($resource);
	}
	
	/**
	 * Executa uma instrução no banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return boolean
	 */
	public function exec($sql){
		
		if(!Config::MSSQL_UTF8)
			$sql = utf8_decode($sql);
		
		return mssql_query($sql, $this->connection);
	}
	
	public function lastMessage(){
		return mssql_get_last_message();
	}
}

/**
 * Result - Manipula resultados de uma consulta do banco de dados
 * @package com.atitudeweb.database
 * @author Thiago Segato Antunes
 * @version 1.0
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

class ResultMssql implements IResult {

	private $resource;

	/**
	 * Construtor
	 *
	 * @param resource $resource
	 */
	public function ResultMssql($resource){
		$this->resource = $resource;
	}

	/**
	 * Processa o próximo item do resource do banco de dados
	 * foreach($row as $resource->fetch()){ }
	 *
	 * @return array
	 */
	public function fetch(){
		$arr = mssql_fetch_assoc($this->resource);
		if($arr && !Config::MSSQL_UTF8){
			foreach($arr as $key=>$value){
				$arr[$key] = utf8_encode($value);
			}
		}
		return $arr;
	}

	/**
	 * Cria um array com todos os resultados da consulta
	 * $registros = $resource->fetchAll()
	 *
	 * @return array
	 */
	public function fetchAll(){
		$result = array();
		while($result[] = $this->fetch());
		array_pop($result);
		return $result;
	}

	/**
	 * Retorna o número de linhas retornadas pela consulta
	 *
	 * @return int
	 */
	public function rowCount(){
		return @mssql_num_rows($this->resource);
	}

	/**
	 * Retorna o número de linhas afetadas
	 *
	 * @return int
	 */
	public function rowAffected(){
		return @mssql_rows_affected($this->resource);
	}
}
?>