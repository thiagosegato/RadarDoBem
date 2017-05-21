<?php
/**
 * Classe de configuração do sistema
 * @package default
 * @version 1.1
 */
 
final class Config{
	
	//Dados do Sistema
	const SYSTEM 		= 'Radar do Bem';
	const SYSTEM_ADMIN	= 'Colaboração RadarDoBem';
	const SITE 			= 'http://www.radardobem.com.br/';
	const SITE_ADMIN	= 'http://www.radardobem.com.br/admin/';
	const EMAIL 		= 'suporte@radardobem.com.br';
	const SESSION_NAME 	= 'RadarDoBem';
	
	//Banco de Dados	
	const DEFAULT_CONNECTION = 'mysql';	
	const MYSQL_HOST = 'localhost';
	const MYSQL_PORT = 3306;  
	const MYSQL_NAME = 'gluecatc_radardobem';
	const MYSQL_USER = 'gluecatc_radardo';
	const MYSQL_PASS = 'radar2018';		
	
	//Configuração do envio de email autenticado ou não
	const MAIL_AUTH 	= 'mail'; // mail = Utiliza o daemon sendmail linux | smtp = Utiliza comunicação socket no protocolo smtp
	const MAIL_HOST 	= '';
	const MAIL_USER 	= '';
	const MAIL_PASS 	= '';
	const MAIL_PORT 	= 587;
		
	//Classe de Autenticação
	const AUTH_CLASS 		= 'com.atitudeweb.Autenticacao'; 
	const AUTH_MESSAGE 		= 'É necessário ter permissão para executar esta operação!';
	
	//Upload Imagens
	const TEMP_FOLDER_IMG = 'up_image_00';
	
}
?>