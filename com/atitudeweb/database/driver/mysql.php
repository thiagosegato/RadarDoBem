<?php	
/**
 * Driver de conexão com o banco de dados mysql
 * @package com.atitudeweb.database.driver
 * @version 1.3
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */
 
//Carregando interfaces
Loader::import('com.atitudeweb.database.IDriver');
Loader::import('com.atitudeweb.database.IResult');

class DriverMysql implements IDriver{
	
	private $connection;
	private $config;
	
	public function DriverMysql($config=null){
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
				$host = Config::MYSQL_HOST;
				$port = Config::MYSQL_PORT;
				$name = Config::MYSQL_NAME;
				$user = Config::MYSQL_USER;
				$pass = Config::MYSQL_PASS;
			}
			else{
				$host = $this->config['host'];
				$port = $this->config['port'];
				$name = $this->config['name'];
				$user = $this->config['user'];
				$pass = $this->config['pass'];			
			}
			
			if(!function_exists('mysql_connect')){
				die('A função mysql_connect não foi encontrada. Habilite o módulo do mysql para php!');
				return false;
			}			
			$this->connection = @mysql_connect($host.":".$port, $user, $pass);
			if(!$this->connection){
				die('Não foi possível obter conexão com a base de dados!');
			}
			else{
				mysql_select_db($name, $this->connection);	
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
		return mysql_close($this->connection);
	}
	
	/**
	 * Realiza uma consulta na conexão padrão do banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return com.atitudeweb.database.Result
	 */
	public function query($sql){
		return new ResultMysql(mysql_query($sql, $this->connection));
	}
	
	/**
	 * Executa uma instrução no banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return boolean
	 */
	public function exec($sql){
		return mysql_query($sql, $this->connection);
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

class ResultMysql implements IResult {

	private $resource;

	/**
	 * Construtor
	 *
	 * @param resource $resource
	 */
	public function ResultMysql($resource){
		$this->resource = $resource;
	}

	/**
	 * Processa o próximo item do resource do banco de dados
	 * foreach($row as $resource->fetch()){ }
	 *
	 * @return array
	 */
	public function fetch(){
		return @mysql_fetch_assoc($this->resource);
	}

	/**
	 * Cria um array com todos os resultados da consulta
	 * $registros = $resource->fetchAll()
	 *
	 * @return array
	 */
	public function fetchAll(){
		$result = array();
		while($result[] = mysql_fetch_assoc($this->resource));
		array_pop($result);
		return $result;
	}

	/**
	 * Retorna o número de linhas retornadas pela consulta
	 *
	 * @return int
	 */
	public function rowCount(){
		return @mysql_num_rows($this->resource);
	}

	/**
	 * Retorna o número de linhas afetadas
	 *
	 * @return int
	 */
	public function rowAffected(){
		return @mysql_affected_rows($this->resource);
	}
}
?>