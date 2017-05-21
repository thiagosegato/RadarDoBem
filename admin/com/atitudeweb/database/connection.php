<?php
/**
 * Connection - Conexão e execução de instruções no banco de dados
 * @package com.atitudeweb.database
 * @version 1.0
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 *
 * Exemplo:
 * $config = array(	'host' => 'localhost',
 *					'port' => 5432,
 *					'name' => 'teste',
 *					'user' => 'postgres',
 *					'pass' => '123456');
 * Connection::open('postgres', $config, 'local');
 */

final class Connection
{
	private static $identifier;
	private	static $default = Config::DEFAULT_CONNECTION;
	
	/**
	 * Abre a conexão padrão com o banco de dados através de um driver
	 * Caso os parâmetros não informados serão consideradas as configurações padrões
	 *
	 * @param string $driver - Driver de conexão
	 * @param string $config - Parâmetros de conexão
	 * @param string $id - Identificador da conexão
	 * @return boolean
	 */
	public static function open($driver=null, $config=null, $id=null){		
		
		if(!$driver)
			$driver = self::$default;		
			
		if(!$id)
			$id = $driver;
		
		if(!@self::$identifier[$id]){
			$con = null;
			if(!Loader::import('com.atitudeweb.database.driver.'.$driver)){
				echo 'Não foi possível encontrar o driver de conexão.';
				return false;
			}
			$class = 'Driver'.ucfirst($driver);			
			$con = new $class($config);
			if($con->open()){
				self::$identifier[$id] = $con;				
			}
			else{
				return false;	
			}							
			return true;
		}
		else{
			return true;			
		}
	}
	
	/**
	 * Define uma conexão padrão
	 *
	 * @param string $value - Driver de conexão padrão
	 * @return void
	 */
	public static function setDefault($value){
		self::$default = $value;
	}
	
	/**
	 * Retorna um driver de conexão
	 * Caso não informado o identificador será retornado o padrão
	 *
	 * @param string $id - Identificador da conexão
	 * @return com.atitudeweb.database.IDriver
	 */
	public static function get($id=null){
		
		if(!$id)
			$id = self::$default;
		
		if(!@self::$identifier[$id]){
			return false;
		}
		else{
			return self::$identifier[$id];
		}
	}
	
	/**
	 * Fecha uma conexão com o banco de dados
	 * Caso não informado o identificador será fechado o padrão
	 *
	 * @param string $id - Identificador da conexão
	 * @return boolean
	 */
	public static function close($id=null){
		
		if(!$id)
			$id = self::$default;
		
		if(@self::$identifier[$id]){
			return self::$identifier[$id]->close();
		}
		else{
			return false;
		}
	}
	
	/**
	 * Realiza uma consulta na conexão do banco de dados
	 * Caso não informado o identificador será considerado o padrão
	 *
	 * @param string $sql - Instrução SQL
	 * @param string $id - Identificador da conexão
	 * @return com.atitudeweb.database.IResult
	 */
	public static function query($sql, $id=null){
		if(!$id)
			$id = self::$default;
		
		if(@self::$identifier[$id]){
			return self::$identifier[$id]->query($sql);
		}
		else{
			return false;
		}		
	}
	
	/**
	 * Executa uma instrução no banco de dados
	 * Caso não informado o identificador será considerado o padrão
	 *
	 * @param string $sql - Instrução SQL
	 * @param string $id - Identificador da conexão
	 * @return boolean
	 */
	public static function exec($sql, $id=null){
		
		if(!$id)
			$id = self::$default;
		
		if(@self::$identifier[$id]){
			return self::$identifier[$id]->exec($sql);
		}
		else{
			return false;
		}		
	}
		
}
?>