<?php
/**
 * Interfaçe para criar um driver de banco de dados
 * @package com.atitudeweb.database
 * @version 1.0
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */
interface IDriver {	

	/**
	 * Abre a conexão padrão com o banco de dados
	 *
	 * @return void
	 */
	public function open();	
	
	/**
	 * Fecha a conexão padrão com o banco de dados
	 *
	 * @return boolean
	 */
	public function close();
	
	/**
	 * Realiza uma consulta na conexão padrão do banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return com.atitudeweb.database.Result
	 */
	public function query($sql);
	
	/**
	 * Executa uma instrução no banco de dados
	 *
	 * @param string $sql - Instrução SQL
	 * @return boolean
	 */
	public function exec($sql);	
}
?>