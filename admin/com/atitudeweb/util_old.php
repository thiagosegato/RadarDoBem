<?php
/**
 * Util - Métodos utilitários para agizar o desenvolvimento
 * @package com.atitudeweb
 * @version 1.1
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

final class Util{
	
	/**
	 * O método setLink facilita sua vida relacionada a links,
	 * adicionando ou substituindo variáveis no link atual.
	 * 
	 * Protótipo:
	 * string setLink ( [array $newVars] [,bool $newLink] )
	 * 
	 * Exemplos no link atual: http://www.atitudeweb.com/index.php?page=home
	 * 
	 * print setLink(array('page=produtos', 'id=555')); //http://www.atitudeweb.com/index.php?page=produtos&id=555	  
	 * print setLink(array('id=777'), true); //http://www.atitudeweb.com/index.php?id=777	  
	 * print setLink(array('id=null')); //http://www.atitudeweb.com/index.php?teste=123&algo=321
	 * print setLink(); //retorna o link atual
	 * 
	 * @param string $newVars - Variáveis novas ou já existentes a serem enviadas
	 * @param boolean $newLink - Informa se é um novo link ou não para exclusão de variáveis já existentes
	 * @return string
	 */	
    public static function setLink($newVars = null, $newLink = false){
		$result = '';
		$boxNewVars = array();
		$replaceValues = array();
		$numNewVars = count($newVars);

		// guarda de forma correta todos os valores declarados em $newVars
        // na variável $boxNewVars
        for($i = 0; $i < $numNewVars; $i++){
			$partsV = explode('=', $newVars[$i]); // split parts of var
	        $boxNewVars[$i] = array('name' => $partsV[0], 'value' => $partsV[1]);
		}

        if(!$newLink){ // se o link não for novo
			foreach($_GET as $key=>$value){
				$replaceValues[$key] = $value;
				for($i = 0; $i < $numNewVars; $i++){
					// se a chave setada no parametro $newVars for igual
                    // a uma chave existente será alterado o valor
                    if($key == @$boxNewVars[$i]['name']){
                        $replaceValues[$key] = $boxNewVars[$i]['value'];
						$boxNewVars = self::array_remove_by_key($boxNewVars, $i);
						break;
					}
				}

                // se a variável existente estiver como nula será excluída
                // da reconstrução
                if($replaceValues[$key] == 'null')
                    continue;                    

				$result .= $key.'='.$replaceValues[$key].'&';
			}
		}
		
		if(count($boxNewVars) > 0){ // add new parts
			foreach($boxNewVars as $key=>$value){
				if($value['value'] == 'null')
                    continue;                    

                $result .= $value['name'].'='.$value['value'].'&';
			}
		}

		$result = $_SERVER['PHP_SELF'].'?'.$result;
		$result = substr($result, 0, -1);
		return $result;
	}
	
	public static function setURL($url, $newVars = null){
		$result = '';
		$boxNewVars = array();
		$replaceValues = array();
		$numNewVars = count($newVars);

		// guarda de forma correta todos os valores declarados em $newVars
        // na variável $boxNewVars
        for($i = 0; $i < $numNewVars; $i++){
			$partsV = explode('=', $newVars[$i]); // split parts of var
	        $boxNewVars[$i] = array('name' => $partsV[0], 'value' => $partsV[1]);
		}

        /*if(!$newLink){ // se o link não for novo
			foreach($_GET as $key=>$value){
				$replaceValues[$key] = $value;
				for($i = 0; $i < $numNewVars; $i++){
					// se a chave setada no parametro $newVars for igual
                    // a uma chave existente será alterado o valor
                    if($key == @$boxNewVars[$i]['name']){
                        $replaceValues[$key] = $boxNewVars[$i]['value'];
						$boxNewVars = self::array_remove_by_key($boxNewVars, $i);
						break;
					}
				}

                // se a variável existente estiver como nula será excluída
                // da reconstrução
                if($replaceValues[$key] == 'null')
                    continue;                    

				$result .= $key.'='.$replaceValues[$key].'&';
			}
		}*/
		
		if(count($boxNewVars) > 0){ // add new parts
			foreach($boxNewVars as $key=>$value){
				if($value['value'] == 'null')
                    continue;                    

                $result .= $value['name'].'='.$value['value'].'&';
			}
		}

		$result = $url.'?'.$result;
		$result = substr($result, 0, -1);
		return $result;
	}
	
	/**
	 * Remove uma chave do array e o retorna
	 * 
	 * @param array $arg1 - Array principal
	 * @return array
	 */
	public static function array_remove_by_key(){
		$args  = func_get_args();
		return array_diff_key($args[0],array_flip(array_slice($args,1)));
	}
	
	public function array_remove_by_value($array, $value)
	{
		return array_values(array_diff($array, array($value)));
	}
	
	/**
	 * Envia um email autenticado para um ou vários recipientes
	 * 
	 * @param string $subject - assunto
	 * @param string $from - email de envio
	 * @param string $fromname - nome do email de envio
	 * @param string $body - corpo da mensagem
	 * @param array $recipients - email para envio, pode ser array ou string
	 * @return boolean
	 */	
	public static function mail($subject, $from, $fromName, $body, $recipients, $attachment=null){	
		
		//Importando a biblioteca de email
		Loader::import('com.worxware.phpmailer');		
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		
		//Verificando o tipo de envio
		if(Config::MAIL_AUTH == 'smtp'){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
		}
		else{
			$mail->IsMail();
		}
				
		//$mail->SMTPDebug = 1;
		$mail->Host      	= Config::MAIL_HOST;
		$mail->Username 	= Config::MAIL_USER;
		$mail->Password 	= Config::MAIL_PASS;
		$mail->Port 		= Config::MAIL_PORT;
		$mail->Subject  	= $subject;
		$mail->From 		= $from;
		$mail->FromName 	= $fromName;	
		if(is_array($recipients)){
			for($i=0;$i<count($recipients);$i++){
				$mail->AddAddress($recipients[$i]);
			}
		}
		else{
			$mail->AddAddress($recipients);
		}		
		
		$mail->Body = $body;
		$mail->AltBody = $mail->Body;
		
		if($attachment != null)
			$mail->AddAttachment($attachment['path'], $attachment['name'], $attachment['encode'], $attachment['type']);
		
		return $mail->Send();
	}
	
	/**
	 * Mostra um aviso na tela por javascript
	 * 
	 * @param string $title - título do aviso
	 * @param string $text - texto do aviso
	 * @param string $type - (ok|info|alert|error)
	 * @return void
	 */	
	public static function notice($title, $text, $type='ok'){
		echo "<script type=\"text/javascript\">
		$(function(){
			$.gritter.add({
				title: '$title',
				text: '$text',
				image: 'assets/css/gritter/growl_".$type.".png',				
			});	
		});
		</script>";
	}
	
	/**
	 * Mostra uma informação de alerta impressa na tela
	 *
	 * @param string $msg - Mensagem do aviso
	 * @param boolean $print - Imprime na tela ou retorna
	 * @return void
	 */
	public static function alert($msg, $print=true){
		$info = '<div class="alert alert-warning">
			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
			<p>'.$msg.'</p></div>';
		if($print)
			echo $info;
		else
			return $info;
	}

	/**
	 * Mostra uma informação impressa na tela
	 *
	 * @param string $msg - Mensagem do aviso
	 * @param boolean $print - Imprime na tela ou retorna
	 * @return void
	 */
	public static function info($msg, $print=true){
		$info = '<div class="alert alert-info">
			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
			<p>'.$msg.'</p></div>';
		if($print)
			echo $info;
		else
			return $info;	
	}
	
	public static function error($msg, $print=true){
		$info = '<div class="alert alert-danger">
			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
			<p>'.$msg.'</p></div>';
		if($print)
			echo $info;
		else
			return $info;
	}
	
	/**
	 * Gera um relatório ireport
	 * 
	 * @param string $reportName - Nome do arquivo jasper
	 * @param string $relName - Nome do download em pdf
	 * @param array $params - Parametros do relatório
	 * @param boolean $popup - Abrir em popup ou fazer download do relatório
	 * @return string
	 * @author Evandir Rodrigo
	 */
	public static function ireport($reportName, $relName, $params=array(), $popup=false, $type='PDF', $listArray=null){		
		
		//Verificando $listArray
		if($listArray != null){
			$obj = self::object_to_array($listArray);
			Session::save('ireport_temp', $obj);
		}
		
		//Importando a biblioteca do IReport e PHP
		Loader::import('reports.PHPIReport');
		PHPIreport::show($reportName, $relName, $params, $popup, $type);				
	}
	
	public static function object_to_array($data){
		if (is_array($data) || is_object($data))
		{
			$result = array();
			foreach ($data as $key => $value)
			{
				$result[$key] = self::object_to_array($value);
			}
			return $result;
		}
		return $data;
	}
	
	
	/**
	 * Gera paginação simples semelhante a do google
	 * 
	 * @param int $registros - Total de registros da consulta
	 * @param int $formSearch - Total de itens do formulário de pesquisa para que a paginação possa persisti-los
	 * @param int $limitPagina - Registros por página
	 * @param int $faixa - Faixa de páginas que irão ser mostradas paginação google
	 * @param array $dif - A diferença que a faixa de páginas irão começar a caminhar google
	 * @return string
	 */
	public static function pagination($registros, $formSearch=1, $limitPagina=10, $faixa=4, $dif=5){
		$p = (@$_GET['p'] ? $_GET['p'] : 1);		
		$paginas = ceil($registros / $limitPagina);
		$dif = $p - ($faixa / 2);
		$min = 1;
		$max = ($paginas > $faixa ? $faixa : $paginas);
		if($dif > 0 && ($paginas > $faixa)){
			$min += $dif;
			$min = ($min > ($paginas - $faixa) ? ($paginas - $faixa) : $min);
			$max += $dif;
			$max = ($max > $paginas ? $paginas : $max);
		}
		
		if($p == 1)
			$back = 1;
		else
			$back = $p - 1;
			
		if($p == $paginas)
			$next = $paginas;
		else
			$next = $p + 1;	
			
		if($registros > 1){
			$descrRows = $registros." registros encontrados";
		}
		else{
			$descrRows = $registros." registro encontrado";
		}

		$str = '';		
		if($registros > 0){		
			$str .= '
			<form id="formPaginacao" name="formPaginacao" method="post">
			<div class="pagination_container text-center">
				<ul class="pagination">
					<li><a href="javascript:void(0);" onclick="setPaginacao(\''.Util::setLink(array('p='.$back)).'\')">&laquo;</a></li>';
					
					for($i=$min;$i<=$max;$i++){
						if($i == $p)
							$str .= '<li class="active"><a href="javascript:void(0);" onclick="setPaginacao(\''.Util::setLink(array('p='.$i)).'\')">'.$i.'</a></li>';
						else
							$str .= '<li><a href="javascript:void(0);" onclick="setPaginacao(\''.Util::setLink(array('p='.$i)).'\')">'.$i.'</a></li>';
					}
					$str .= '<li><a href="javascript:void(0);" onclick="setPaginacao(\''.Util::setLink(array('p='.$next)).'\')">&raquo;</a></li>
				</ul>
			</div>';
			for($i=1;$i<=$formSearch;$i++){
				$str .= '<input type="hidden" name="search'.$i.'" value="'.@$_POST['search'.$i].'"/>';
			}
			$str .= '</form>';
		}
		if($registros != 0){
			$str .= '<div align="center" style="margin-top:8px;"><i>'.$descrRows.'</i></div>';
		}
		return $str;		
	}
	
	public static function paginationAjaxSimple($p, $registros, $limitPagina=6, $faixa=6, $dif=5){
		//$p = (@$_GET['p'] ? $_GET['p'] : 1);		
		$paginas = ceil($registros / $limitPagina);
		$dif = $p - ($faixa / 2);
		$min = 1;
		$max = ($paginas > $faixa ? $faixa : $paginas);
		if($dif > 0 && ($paginas > $faixa)){
			$min += $dif;
			$min = ($min > ($paginas - $faixa) ? ($paginas - $faixa) : $min);
			$max += $dif;
			$max = ($max > $paginas ? $paginas : $max);
		}
		//echo 'p: '.$p.'<br>registros: '.$registros.'<br>paginas: '.$paginas.'<br>dif: '.$dif; 
		
		//exit;
		
		if($p == 1)
			$back = 1;
		else
			$back = $p - 1;
			
		if($p == $paginas)
			$next = $paginas;
		else
			$next = $p + 1;	
			
		if($registros > 1){
			$descrRows = $registros." registros encontrados";
		}
		else{
			$descrRows = $registros." registro encontrado";
		}

		$str = '';		
		if($registros > 0){		
			$str .= '
			<div class="pagination_container text-center">
				<ul class="pagination">
					<li><a href="javascript:void(0);" onclick="setPaginacaoAjaxSimple('.($back-1).')">&laquo;</a></li>';
					
					for($i=$min;$i<=$max;$i++){
						if($i == $p)
							$str .= '<li class="active"><a href="javascript:void(0);" onclick="setPaginacaoAjaxSimple('.($i-1).')">'.$i.'</a></li>';
						else
							$str .= '<li><a href="javascript:void(0);" onclick="setPaginacaoAjaxSimple('.($i-1).')">'.$i.'</a></li>';
					}
					$str .= '<li><a href="javascript:void(0);" onclick="setPaginacaoAjaxSimple('.($next-1).')">&raquo;</a></li>
				</ul>
			</div>';			
		}
		if($registros != 0){
			$str .= '<div align="center"><i>'.$descrRows.'</i></div>';
		}
		return $str;
		
		
		/*$p = (@$_GET['p'] ? $_GET['p'] : 1);		
		$paginas = ceil($registros / $limitPagina);
		$dif = $p - ($faixa / 2);
		$min = 1;
		$max = ($paginas > $faixa ? $faixa : $paginas);
		if($dif > 0){
			$min += $dif;
			$min = ($min > ($paginas - $faixa) ? ($paginas - $faixa) : $min);
			$max += $dif;
			$max = ($max > $paginas ? $paginas : $max);
		}
		if($p == 1)
			$back = 1;
		else
			$back = $p - 1;
			
		if($p == $paginas)
			$next = $paginas;
		else
			$next = $p + 1;	
			
		if($registros > 1){
			$descrRows = $registros." registros encontrados";
		}
		elseif($registros == 0){
			$descrRows = 'Nenhum registro encontrado';
		}
		else{
			$descrRows = $registros." registro encontrado";
		}

		$str = '';		
		if($registros > 0){
			$str = '<table border="0" cellpadding="1" cellspacing="0" style="margin:0 auto; padding-top:4px;">
			<tr>
				<td><div onclick="setPaginacaoAjaxSimple(\''.$dialog.'\', \''.$partial.'\', \'p='.$back.'\')" class="buttonPag back ui-state-default ui-corner-all" style="padding-left:4px;">
					<span class="ui-icon ui-icon ui-icon-carat-1-w"></span></div>
				</td>';
			for($i=$min;$i<=$max;$i++){
				if($i == $p)
					$str .= '<td><div onclick="setPaginacaoAjaxSimple(\''.$dialog.'\', \''.$partial.'\', \'p='.$i.'\')" class="buttonPag ui-state-default ui-corner-all"><u>'.$i.'</u></div></td>';
				else
					$str .= '<td><div onclick="setPaginacaoAjaxSimple(\''.$dialog.'\', \''.$partial.'\', \'p='.$i.'\')" class="buttonPag ui-state-default ui-corner-all">'.$i.'</div></td>';
			}
			$str .= '<td><div onclick="setPaginacaoAjaxSimple(\''.$dialog.'\', \''.$partial.'\', \'p='.$next.'\')" class="buttonPag ui-state-default ui-corner-all" style="padding-left:4px;">
					<span class="ui-icon ui-icon-carat-1-e"></span></div>
				</td>
				</tr>
			</table>
			<div align="right" style="margin-top:4px;">Página '.$p.' de '.$paginas.'</div>';
		}
		$str .= '<div align="center" style="margin-top:8px;"><i>'.$descrRows.'</i></div>';
		return $str;*/
	}
	
	
	/**
	 * Gera paginação simples para ajax semelhante a do google
	 * 
	 * @param int $registros - Total de registros da consulta
	 * @param int $formSearch - Total de itens do formulário de pesquisa para que a paginação possa persisti-los
	 * @param int $limitPagina - Registros por página
	 * @param int $faixa - Faixa de páginas que irão ser mostradas paginação google
	 * @param array $dif - A diferença que a faixa de páginas irão começar a caminhar google
	 * @return string
	 */
	public static function paginationAjax($registros, $dialog, $partial, $limitPagina=10, $faixa=10, $dif=5){
		$p = (@$_GET['p'] ? $_GET['p'] : 1);		
		$paginas = ceil($registros / $limitPagina);
		$dif = $p - ($faixa / 2);
		$min = 1;
		$max = ($paginas > $faixa ? $faixa : $paginas);
		if($dif > 0){
			$min += $dif;
			$min = ($min > ($paginas - $faixa) ? ($paginas - $faixa) : $min);
			$max += $dif;
			$max = ($max > $paginas ? $paginas : $max);
		}
		if($p == 1)
			$back = 1;
		else
			$back = $p - 1;
			
		if($p == $paginas)
			$next = $paginas;
		else
			$next = $p + 1;	
			
		if($registros > 1){
			$descrRows = $registros." registros encontrados";
		}
		elseif($registros == 0){
			$descrRows = 'Nenhum registro encontrado';
		}
		else{
			$descrRows = $registros." registro encontrado";
		}

		$str = '';		
		if($registros > 0){
			$str = '<form method="post">
			<table border="0" cellpadding="1" cellspacing="0" align="center" style="padding-top:4px;">
				<tr>			
					<td><div onclick="setPaginacaoAjax(\''.$dialog.'\', \''.$partial.'\', \'p='.$back.'\')" class="buttonPag back ui-state-default ui-corner-all" style="padding-left:4px;">
						<span class="ui-icon ui-icon ui-icon-carat-1-w"></span></div>
					</td>';
			for($i=$min;$i<=$max;$i++){
				if($i == $p)
					$str .= '<td><div onclick="setPaginacaoAjax(\''.$dialog.'\', \''.$partial.'\', \'p='.$i.'\')" class="buttonPag ui-state-default ui-corner-all"><u>'.$i.'</u></div></td>';
				else
					$str .= '<td><div onclick="setPaginacaoAjax(\''.$dialog.'\', \''.$partial.'\', \'p='.$i.'\')" class="buttonPag ui-state-default ui-corner-all">'.$i.'</div></td>';
			}
			$str .= '<td><div onclick="setPaginacaoAjax(\''.$dialog.'\', \''.$partial.'\', \'p='.$next.'\')" class="buttonPag ui-state-default ui-corner-all" style="padding-left:4px;">
						<span class="ui-icon ui-icon-carat-1-e"></span></div>
					</td>
				</tr>
			</table>				
			<div align="right" style="margin-top:4px;">Página '.$p.' de '.$paginas.'</div>';
		}		
		$str .= '<div align="center" style="margin-top:8px;"><i>'.$descrRows.'</i></div>';
		return $str;
	}

}
?>