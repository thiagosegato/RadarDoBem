<?php
/**
 * Tipos de transações disponíveis
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */
final class TipoTransacao {	
	const CADASTRO			= 1; 
	const SOMENTE_INCLUSAO 	= 2;
	const SOMENTE_ALTERACAO	= 3;
	const SOMENTE_EXCLUSAO 	= 4;
	const FUNCIONALIDADE 	= 5;
		
	public static $tipos = array(	1 => 'CADASTRO',
									2 => 'SOMENTE_INCLUSAO',
									3 => 'SOMENTE_ALTERACAO',
									4 => 'SOMENTE_EXCLUSAO',
									5 => 'FUNCIONALIDADE');

} 
 ?>