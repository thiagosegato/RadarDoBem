<?php
/**
 * Interface para criar uma autenticação com o sistema
 * @package com.atitudeweb
 * @version 1.0
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

interface IAuthenticate {

	/**
	 * Devolve o login que está na sessão
	 * 
	 * @return array
	 */
	public function getLogin();
	
	/**
	 * Realiza a autenticação interna do sistema 
	 * 
	 * @return string
	 */
	public function authenticate();
	
	/**
	 * Realiza o logout do sistema
	 * 
	 * @return void
	 */
	public function logout();
	
	/**
	 * Processa a recuperação da senha do usuário
	 * 
	 * @return boolean
	 */
	public function passRecovery();
	
	/**
	 * Informa se o usuário corrente tem a permissão de criar
	 * 
	 * @return boolean
	 */
	public function isCreate($transaction);
	
	/**
	 * Informa se o usuário corrente tem a permissão de ler
	 * 
	 * @return boolean
	 */
	public function isRead($transaction);
	
	/**
	 * Informa se o usuário corrente tem a permissão de alterar
	 * 
	 * @return boolean
	 */
	public function isUpdate($transaction);
	
	/**
	 * Informa se o usuário corrente tem a permissão de excluir
	 * 
	 * @return boolean
	 */
	public function isDelete($transaction);	
}
?>