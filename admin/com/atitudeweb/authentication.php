<?php
/**
 * Authenticate - Controla toda a autenticação do framework
 * @package com.atitudeweb
 * @version 1.1
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

final class Authentication implements IAuthenticate {
	
	/**
	 * Devolve o login que está na sessão
	 * 
	 * @return array
	 */
	public function getLogin(){
		return Session::get('login');
	}
		
	/**
	 * Realiza a autenticação interna do sistema 
	 * 
	 * @return string
	 */
	public function authenticate(){
		//Compilando variáveis do formulário
		$return = '';
		$user = strtolower(addslashes($_POST['user']));
		$pass = md5($_POST['pass']);
		
		
		echo $_POST['user'];
		echo $_POST['pass'];
		exit;
		
		//Relaliza a verificação no banco e valida a autenticação
		$sql = "select tu.ci_usuario, 
				tu.cd_grupo, 
				tu.nm_usuario, 
				tu.fl_ativo,
				to_char(tu.dt_ultimoacesso, 'dd/mm/yyyy HH24:MI:SS') as dt_ultimoacesso
		from tb_usuario tu 				
		where nm_login = '$user' 
		  and nm_senha = '$pass'";
		$query = Connection::query($sql);			
		if($query->rowCount() > 0){
			$user = $query->fetch();
			if($user['fl_ativo'] != 't'){
				$return = Util::alert('Entre em contato com o administrador do sistema!', false);
			}
			else{				
				Session::save('login', $user);
				$sql = 'update tb_usuario set dt_ultimoacesso=now() where ci_usuario = '.$user['ci_usuario'];
				execute($sql);
				Controller::redirect($_POST['url']);
			}
		}
		else{
			$return = Util::alert('Usuário ou senha inválidos!', false);				
		}
		
		return $return;
	}
	
	/**
	 * Realiza o logout do sistema
	 * 
	 * @return void
	 */
	public function logout(){		
		Session::close();		
	}

	/**
	 * Processa a recuperação da senha do usuário
	 * 
	 * @return boolean
	 */
	public function passRecovery(){		
		$ds_email = addslashes($_POST['ds_email']);
		$sql = "select ci_usuario, nm_login, nm_senha from tb_usuario where ds_email = '$ds_email'";
		$query = query($sql);
		if($query->rowCount() > 0){		
			//Gerando senha aleatória
			$lmin = 'abcdefghijklmnopqrstuvwxyz';
			$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$num = '1234567890';
			$caracteres = $lmin.$lmai.$num;
			$len = strlen($caracteres);
			$senha = '';
			for($i=0;$i<6;$i++){
				$rand = mt_rand(1, $len);
				$senha .= $caracteres[$rand - 1];
			}
		
			$user = $query->fetch();
			$this->emailSenha($user['nm_login'], $senha, $ds_email);			
			$sql = "update tb_usuario set nm_senha = md5('$senha') where ci_usuario = ".$user['ci_usuario'];
			execute($sql);			
			Util::notice('Aviso', 'Um <b>email</b> foi enviado!');			
		}
		else{
			Util::notice('Aviso', 'Entre em contato com o administrador do sistema!', 'info');
		}
	}	
	
	/**
	 * Informa se o usuário corrente tem a permissão de criar
	 * 
	 * @return boolean
	 */
	public function isCreate($transaction){
		return true;
	}
	
	/**
	 * Informa se o usuário corrente tem a permissão de ler
	 * 
	 * @return boolean
	 */
	public function isRead($transaction){
		return true;
	}
	
	/**
	 * Informa se o usuário corrente tem a permissão de alterar
	 * 
	 * @return boolean
	 */
	public function isUpdate($transaction){
		return true;
	}
	
	/**
	 * Informa se o usuário corrente tem a permissão de excluir
	 * 
	 * @return boolean
	 */
	public function isDelete($transaction){
		return true;
	}
	
	/**
	 * Realiza a rerificação de uma operação para uma transação
	 * 
	 * @param string $transacao
	 * @param string $crud
	 * @return boolean
	 */
	public function verificaAcesso($transacao, $crud){
		$retorno = false;
		$user = Session::get('login');
		$grupo = $user['cd_grupo'];
		
		$sql = "select {$crud} from tb_grupo_transacao where cd_grupo={$grupo} and cd_transacao={$transacao}";
		$row = query($sql)->fetch();
		if(@$row[$crud] == 't') $retorno = true;
		return $retorno;
	}

	/**
	 * Gera email de recuperação de senha
	 * 
	 * @param string $usuario
	 * @param string $senha
	 * @param string $email
	 * @return void
	 */
	public function emailSenha($usuario, $senha, $email){
		$body = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
			<title>".Config::SYSTEM."</title>
		 </head>		
			<table width='500'  border='1' align='center' cellpadding='0' cellspacing='0' bordercolor='#fdfdfd' bgcolor='#FFFFFF'>
				<tr>
					<td><div align='justify'>
		 	 	    <table width='100%'  border='0' cellpadding='10' cellspacing='0'>
				<tr>
		 	 	    <td><p><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Segue logo abaixo o usu&aacute;rio e senha:<br>
		 	 	    </font></p>
					<p><strong>Usu&aacute;rio= ".$usuario." <br>
		 	 	    Senha= ".$senha."</strong></p>
					<br><br>
					Em caso de d&uacute;vidas, entre em contato com o administrador do sistema.</font></p></td>
					</tr>
					</table>
					</div></td>
				</tr>
			</table>
		</body>
		</html>";
		Util::mail(Config::SYSTEM, 'framework@atitudeweb.com', 'Sistema', $body, $email);
	}
}
?>