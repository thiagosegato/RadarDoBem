<?php
/**
 * SQL - Manipulação de SQL para um CRUD SIMPLES
 * @package com.atitudeweb
 * @version 1.1
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

final class SQL{
	
	/**
	 * Persiste um objeto no banco de dados
	 *  
	 * @param string $table
	 * @param array $fields
	 * @param array $fields2
	 * @return boolean
	 */
	public static function save($table, $fields=null, $fields2=null){		
		$columns = '';
		$values = '';
		if(!$fields){
			$fields = $_POST;
		}		
		foreach($fields as $key=>$value){
			$columns .= $key.', ';
			$values .= "'".addslashes(@$value)."', ";
		}		
		if($fields2){
			foreach($fields2 as $key=>$value){
				$columns .= $key.', ';
				$values .= "'".addslashes($value)."', ";				
			}
		}
		$columns = substr($columns, 0, -2);
		$values = substr($values, 0, -2);		
		$sql = "insert into {$table} ({$columns}) values ($values);";
		return Connection::exec($sql);		
	}
	
	/**
	 * Atualiza um objeto no banco de dados
	 *
	 * @param string $table
	 * @param array $where
	 * @param array $fields	 
	 * @return boolean
	 */
	public static function update($table, $where, $fields=null){
		$sql = "update {$table} set ";
		if(!$fields){
			$fields = $_POST;
		}
		foreach($fields as $key=>$value){
			$sql .= $key."='".addslashes($value)."', ";	
		}
		$sql = substr($sql, 0, -2);
		$sql .= ' where ';
		foreach($where as $key=>$value){
			$sql .= $key.'='.$value.' and ';
		}		
		$sql = substr($sql, 0, -5);
		return Connection::exec($sql);		
	}
	
	/**
	 * Retorna o número de registros de uma entidade
	 * 
	 * @param string $table
	 * @param string $where
	 * @return int
	 */
	public static function getNumTable($table, $where=null){
		$sql = "select count(*) as num from $table";
		if($where)
			$sql .= ' where '.$where;
		$query = Connection::query($sql);
		$row = $query->fetch();
		return $row['num'];
	}
	
	/**
	 * Adquire os registros de uma entidade
	 * 
	 * @param string $table
	 * @param array $where
	 * @param string $otherWhere
	 * @param boolean $fetch
	 * @param int $start
	 * @param int $offset
	 * @param string $order
	 * @return multitype:|Ambigous <com.atitudeweb.database.IResult, array>
	 */
	public static function getRows($table, $where=null, $otherWhere=null, $fetch=false, $start=0, $offset=0, $order=null){
		$sql = "select * from {$table}";		
		if($where){
			$sql .= ' where ';
			foreach($where as $key=>$value){
				$sql .= $key.'='.$value.' and ';
			}		
			$sql = substr($sql, 0, -5);
		}		
		if($otherWhere){
			if(!$where){
				$sql .= ' where '; 
			}	
			else{
				$sql .= ' and ';
			}
			$sql .= $otherWhere;
		}
		
		if($order)
			$sql .= ' order by '.$order;
		
		if($offset > 0)
			$sql .= ' limit '.$offset.' offset '.$start;
		
		if($fetch){
			$query = Connection::query($sql);
			return $query->fetch();	
		}
		else{
			return Connection::query($sql);
		}
	}
	
	/**
	 * Remove um objeto
	 * 
	 * @param string $table
	 * @param string $tablepk
	 * @param array $ids
	 * @return boolean
	 */
	public static function remove($table, $tablepk, $ids){
		if(count($ids) > 0){		
			$sql = "delete from {$table} where {$tablepk} in(";
			for($i=0; $i<count($ids); $i++){
				$sql .= $ids[$i].', ';
			}
			$sql = substr($sql, 0, -2);
			$sql .= ');';
			return Connection::exec($sql);
		}
		else{
			return false;
		}
	}
	
}
?>