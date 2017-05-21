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

class DriverPostgres implements IDriver{
	
	private $connection;
	private $config;
	
	public function DriverPostgres($config=null){
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
				$host = Config::POSTGRES_HOST;
				$port = Config::POSTGRES_PORT;
				$name = Config::POSTGRES_NAME;
				$user = Config::POSTGRES_USER;
				$pass = Config::POSTGRES_PASS;
			}
			else{
				$host = $this->config['host'];
				$port = $this->config['port'];
				$name = $this->config['name'];
				$user = $this->config['user'];
				$pass = $this->config['pass'];			
			}
			
			if(!function_exists('pg_connect')){
				die('A função pg_connect não foi encontrada. Habilite o módulo do postgres para php!');
				return false;
			}			
			$this->connection = @pg_connect("host=$host port=$port dbname=$name user=$user password=$pass");
			if(!$this->connection){
				die('Não foi possível obter conexão com a base de dados!');
			}
			else{
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
		return pg_close($this->connection);
	}
	
	/**
	 * Realiza uma consulta na conexão padrão do banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return com.atitudeweb.database.Result
	 */
	public function query($sql){
		return new ResultPostgres(pg_query($this->connection, $sql));
	}
	
	/**
	 * Executa uma instrução no banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return boolean
	 */
	public function exec($sql){
		return pg_query($this->connection, $sql);
	}
	
	/**
	 * Converte um array do postgres para o php
	 * http://www.php.net/manual/en/ref.pgsql.php#89841
	 *
	 * @param resource $resource
	 * @return array
	 */	
	public function arrayParse($text, $limit=false, $offset=1){
		$output = false;
		if(false === $limit){
			$limit = strlen( $text )-1;			
		}
		if( '{}' != $text && @$text ){
			$output = array();
			do
			{
			  if( '{' != $text{$offset} )
			  {
				preg_match( "/(\\{?\"([^\"\\\\]|\\\\.)*\"|[^,{}]+)+([,}]+)/", $text, $match, 0, $offset );
				$offset += strlen( $match[0] );
				$output[] = ( '"' != $match[1]{0} ? $match[1] : stripcslashes( substr( $match[1], 1, -1 ) ) );
				if( '},' == $match[3] ) return $offset;
			  }
			  else  $offset = pg_array_parse( $text, $output[], $limit, $offset+1 );
			}
			while( $limit > $offset );
		}
		return $output;
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

class ResultPostgres implements IResult {

	private $resource;

	/**
	 * Construtor
	 *
	 * @param resource $resource
	 */
	public function ResultPostgres($resource){
		$this->resource = $resource;
	}

	/**
	 * Processa o próximo item do resource do banco de dados
	 * foreach($row as $resource->fetch()){ }
	 *
	 * @return array
	 */
	public function fetch(){
		return @pg_fetch_assoc($this->resource);
	}

	/**
	 * Cria um array com todos os resultados da consulta
	 * $registros = $resource->fetchAll()
	 *
	 * @return array
	 */
	public function fetchAll(){
		$result = array();
		while($result[] = pg_fetch_assoc($this->resource));
		array_pop($result);
		return $result;
	}

	/**
	 * Retorna o número de linhas retornadas pela consulta
	 *
	 * @return int
	 */
	public function rowCount(){
		return @pg_num_rows($this->resource);
	}

	/**
	 * Retorna o número de linhas afetadas
	 *
	 * @return int
	 */
	public function rowAffected(){
		return @pg_affected_rows($this->resource);
	}
}
?>