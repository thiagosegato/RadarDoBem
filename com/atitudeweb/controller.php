<?php
/**
 * Controller - Controla os redirecionamentos, requisições e cabeçalho da página
 * @package com.atitudeweb
 * @version 1.7
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

class Controller
{
	public static $postRedir = array();
	public static $head = array();

	/**
	 * Redireciona a página no ato da chamada
	 * 
	 * @param string $page
	 * @return void
	 */
	public static function redirect($page){
		if(self::$postRedir){
			$data = ob_get_contents();
			ob_end_clean();
			$form = '<html><body><form name="formredir" action="'.$page.'" method="post">';
			foreach(self::$postRedir as $key=>$value){
				$form .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
			}
			$form .= '</form>
			<script type="text/javascript">
			document.forms.formredir.submit();
			</script></body></html>';
			echo $form;
			exit;
		}
		else{
			header('Location: '.$page);
			exit;
		}		
	}
	
	/**
	 * Adiciona informações para o próximo redirecionamento
	 * Pode ser utilizada juntamente com a função redirect
	 * 
	 * @param string $title
	 * @param string $text
	 * @param string $type
	 * @param array $vars
	 * @return void
	 */
	public static function setInfo($title, $text, $type='ok', $vars=null){
		self::$postRedir['title'] = $title;
		self::$postRedir['text'] = $text;
		self::$postRedir['type'] = $type;
		self::$postRedir['info'] = 1;
		if(is_array($vars)){
			foreach($vars as $key=>$value){
				self::$postRedir[$key] = $value;
			}	
		}		
	}
	
	/**
	 * Adiciona um arquivo css ou javascript no cabeçalho da página
	 * 
	 * @param string $name
	 * @param string $type
	 * @return void
	 */
	public static function addHead($name, $type='js'){
		if($type == 'js'){
			self::$head[] = '<script type="text/javascript" src="assets/js/'.$name.'.js"></script>';
		}			 
		else if($type == 'css'){
			self::$head[] = '<link type="text/css" href="assets/css/'.$name.'.css" rel="stylesheet" />';
		}
	}
	
	/**
	 * Retorna a string da requisição corrente de acordo com as páginas de configuração
	 * encontradas na classe Config, config.php
	 * 
	 * @return string
	 */
	public static function getLocation($pages){		
		$str = '';				
		if(isset($_GET['page'])){
			$parts = explode("/", $_GET['page']);
			$links = "";
			for($i=0; $i<count($parts); $i++){
				if($pages[$parts[$i]]['label']){				
					$links .= $parts[$i].'/';				
					$str .= '<h2 style="margin-top:10px;">'.$pages[$parts[$i]]['label'].'</h2>';				
				}
			}
		}
		else{
			$str .= '<h2>Novo Pedido</h2>';	
		}
		return $str;	
	}	
}
?>