<?php
/**
 * Interfaçe para criar um classe de resultado query
 * @package com.atitudeweb.database
 * @version 1.0
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */
interface IResult {	

	/**
	 * Processa o próximo item do resource do banco de dados
	 * foreach($row as $resource->fetch()){ }
	 *
	 * @return array
	 */
	public function fetch();
	
	/**
	 * Cria um array com todos os resultados da consulta
	 * $registros = $resource->fetchAll()
	 *
	 * @return array
	 */
	public function fetchAll();
	
	/**
	 * Retorna o número de linhas retornadas pela consulta
	 *
	 * @return int
	 */
	public function rowCount();
	
	/**
	 * Retorna o número de linhas afetadas
	 *
	 * @return int
	 */
	public function rowAffected();	
}
?>