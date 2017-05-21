<?php
/**
 * Níveis de acesso de usuários
 * @package com.atitudeweb
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */
final class NivelAcesso {	
	const COMUM			= 1; 
	const ADMINISTRADOR = 2;
	const MASTER 		= 3; //Nível de super usuário
		
	public static $niveis = array(	1 => 'COMUM',
									2 => 'ADMINISTRADOR',
									3 => 'MASTER');
} 
 ?>