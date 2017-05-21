<?php
/**
 * Autenticacao - Controla a autenticação e recuperação de senha de usuários no padrão
 * @package net.kontrolu
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */
 
//Carregando classes
Loader::import('com.atitudeweb.nivelacesso');

class Autenticacao implements IAuthenticate {

	/**
	 * Devolve o login que está na sessão
	 * 
	 * @return array
	 */
	public function getLogin(){
		return Session::get('user');
	}
		
	/**
	 * Realiza a autenticação interna do sistema 
	 * 
	 * @return string
	 */
	public function authenticate(){
		
		//Ajustando variáveis do formulário
		$return = '';
		$user = strtoupper(addslashes($_POST['user']));
		$pass = addslashes($_POST['pass']);
		
		
		//Relaliza a verificação no banco e valida a autenticação
		$sql = "select ci_usuario,
			nm_usuario,
			tp_nivelacesso,
			fl_atualizousenha,
			fl_ativo,
			DATE_FORMAT(dt_acesso, '%d/%m/%Y às %H:%i:%s') as dt_acesso
		from tb_usuario
		where nm_login = '$user'
		  and nm_senha = md5('$pass')";		
		$query = @query($sql);	
		
		if($query->rowCount() == 1){		
			//Ajustando as variáveis que serão gravadas na sessão ($user, $grupos e $transacoes)
			$user = $query->fetch();
			$atualizousenha = $user['fl_atualizousenha'];
			unset($user['fl_atualizousenha']); //Excluindo a chave fl_atualizousenha para não ser gravada na sessão
			$user['isadmin'] = ($user['tp_nivelacesso'] == NivelAcesso::ADMINISTRADOR ? true : false);
			$user['ismaster'] = ($user['tp_nivelacesso'] == NivelAcesso::MASTER ? true : false);
			$user['dt_acesso'] = ($user['dt_acesso'] == '' ? '---' : $user['dt_acesso']);
			$grupos = '';
			$transacoes = array();
			
			//Verifica se o usuário está ativo ou não
			if($user['fl_ativo'] == '0'){
				$return = Util::alert('Por favor, entre em contato com o administrador do sistema!', false);
			}
			else{			
				//Seleciona uma lista de todos os grupos do usuário
				$queryGrupos = query('select tg.nm_grupo
				from tb_usuario_grupo tug
				inner join tb_grupo tg on(tug.cd_grupo=tg.ci_grupo)
				where tug.cd_usuario = '.$user['ci_usuario']);
				
				//Se este usuário não estiver vinculado em nenhum grupo e não for ADMINISTRADOR ou MASTER a verificação será finalizada aqui
				if($queryGrupos->rowCount() < 1 && !$user['isadmin'] && !$user['ismaster']){
					$return = Util::alert('Você não tem permissão para acessar este sistema!', false);
				}
				else{
					//Caso o usuário seja um membro ADMINISTRADOR ou MASTER, terá acesso a todas as transações do módulo. Sendo que
					//somente o usuário do tipo MASTER poderá criar novas transações, logo este tipo de usuário se destinará para o desenvolvedor.
					//Poderá ocorrer casos especiais, onde, terá de ser testado se o usuário tem vínculo 
					//em um grupo específico. Isso acontece, por exemplo, em um "encaminhamento de estagiários", 
					//que somente poderá ser efetuado se o usuário estiver vinculado a um grupo específico, chamado "orientador-aprovado". Considerando assim,
					//o grupo como uma label de transação. Torna-se necessária então que se mantenha na sessão
					//todos os grupos do usuário na sessão exceto o ADMINISTRADOR e MASTER.
					while($row = $queryGrupos->fetch()){
						$grupos .= $row['nm_grupo'].',';					
					}
					$grupos = substr($grupos, 0, -1);
				
					//Seleciona o privilégio detalhado de todas as transações deste usuário, neste módulo.
					$queryTransacoes = query('select tt.nm_label, tgt.fl_inserir, tgt.fl_alterar, tgt.fl_deletar
					from tb_usuario_grupo tug
					inner join tb_grupo_transacao tgt on(tug.cd_grupo=tgt.cd_grupo)
					inner join tb_transacao tt on(tgt.cd_transacao=tt.ci_transacao)
					where tug.cd_usuario = '.$user['ci_usuario']);
					while($row = $queryTransacoes->fetch()){							
						if(array_key_exists($row['nm_label'], $transacoes)){
							$transacoes[$row['nm_label']] = $this->atualizaPriv($transacoes[$row['nm_label']], $row['fl_inserir'], "S", $row['fl_alterar'], $row['fl_deletar']);
						}	
						else{
							$transacoes[$row['nm_label']] = $this->priv($row['fl_inserir'], "S", $row['fl_alterar'], $row['fl_deletar']);
						}
					}
					
					//Atualizando o acesso atual 
					execute('update tb_usuario set dt_acesso=now() where ci_usuario = '.$user['ci_usuario']);
					
					//Persistindo autenticação na sessão
					Session::save('user', $user);
					Session::save('grupos', $grupos);
					Session::save('transacoes', $transacoes);	

					//Redirencionando para alteração da senha caso seja o primeiro acesso
					if($atualizousenha == '0'){
						Controller::setInfo('Altere sua senha', 'Para maior segurança!', 'info');	
						Controller::redirect('?page=acesso/alterar-senha');	
					}
					else{
						Controller::redirect('http://'.$_POST['url']);	
					}					
				}
			}			
		}
		else{
			$return = Util::alert('Usuário ou Senha incorretos!', false);	
		}
		return $return;
	}
	
	//Verifica se um privilégio, número de uma somatória, tem permissão de acesso para as operações:
	//I - Incluir	- 1
	//P - Pesquisar	- 2
	//A - Alterar	- 4
	//D - Deletar	- 8
	//Exemplos:
	//temPriv(15, "D") retornará true, tem acesso para exclusão
	//temPriv(7, "D") retornará false, pois só tem acesso para criação pesquisa e alteração 
	public function temPriv($priv, $operacao){		
		if($priv >= 8){ //Deletar?
			$priv -= 8;
			if($operacao == 'D')
				return true;
		}			
		if($priv >= 4){ //Alterar?
			$priv -= 4;
			if($operacao == 'A')
				return true;
		}		
		if($priv >= 2){ //Pesquisar?
			$priv -= 2;
			if($operacao == 'P')
				return true;
		}		
		if($priv >= 1){ //Incluir?
			$priv -= 1;
			if($operacao == 'I')
				return true;
		}		
		return false;
	}
		
	//Atualiza o privilégio da transação antiga para a nova caso seja "S" para permitir.
	//Esta função foi criada porque uma transação pode pertencer a vários grupos, 
	//sendo necessário atualizar a transação já incluida em session("Transacoes")
	//Obs: utilizado em login.asp
	public function atualizaPriv($privOld, $I, $P, $A, $D){

		//se o privilégio for igual a 15 quer dizer que o usuário poderá 
		//criar, ler, alterar e excluir, não sendo necessário atualizar
		if($privOld == 15)
			return 15;
			
		//se os parâmetros c-r-u-d forem todos "S" também não será necessário
		//atualizar o privilégio, pois o usuário já poderá realizar todos
		if($I == "S" && $P == "S" && $A == "S" && $D == "S")
			return 15;
		
		//restaurando os privilégios já exsistentes na transação
		$incluir_ = "N";	//1
		$pesquisar_ = "N";	//2
		$alterar_ = "N";	//4
		$deletar_ = "N";	//8
	
		//verifica a permissão de excluir
		if($privOld >= 8){
			$privOld = $privOld - 8;
			$deletar_ = "S";
		}		
	
		//verifica a permissão de alterar
		if($privOld >= 4){
			$privOld = $privOld - 4;
			$alterar_ = "S";
		}
	
		//verifica a permissão de ler
		if($privOld >= 2){
			$privOld = $privOld - 2;
			$pesquisar_ = "S";
		}
	
		//verifica a permissão de excluir
		if($privOld >= 1){
			$privOld = $privOld - 1;
			$incluir_ = "S";
		}
	
		//atualizando os privilégios da transação antiga para a nova
		if($I == "S") $incluir_ = "S";
		if($P == "S") $pesquisar_ = "S";
		if($A == "S") $alterar_ = "S";
		if($D == "S") $deletar_ = "S";
		
		return $this->priv($incluir_, $pesquisar_, $alterar_, $deletar_);		
	}	
	
	//Retorna o número de uma somatória através da string "S" ou "N" vinda do banco, 
	//caso haja dúvida, pesquisar sobre questões somatórias
	//I - Incluir	- 1
	//P - Pesquisar	- 2
	//A - Alterar	- 4
	//D - Deletar	- 8
	//Obs: utilizado em login.asp
	public function priv($I, $P, $A, $D){
		//iniciando com nenhum privilégio para a transação
		$priv = 0;	
		if($I == "S") $priv += 1;	//incluir 	= 1	
		if($P == "S") $priv += 2;	//pesquisar = 2	
		if($A == "S") $priv += 4;	//alterar 	= 4
		if($D == "S") $priv += 8;	//deletar 	= 8	
		return $priv;
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
	 * Testa se um usuário é administrador
	 * 
	 * @return boolean
	 */
	public function isAdmin(){
		$user = $this->getLogin();
		if($user['isadmin'] || $user['ismaster'])
			return true;
		else
			return false;	
	}
	
	/**
	 * Testa se um usuário é master
	 * 
	 * @return boolean
	 */
	public function isMaster(){
		$user = $this->getLogin();
		if($user['ismaster'])
			return true;
		else
			return false;	
	}
	
	/**
	 * Informa se o usuário corrente tem permissão para funcionalidade, desconsidera
	 * o fato de ser administrador
	 * 
	 * @return boolean
	 */
	public function isOn($transaction){
		$transacoes = Session::get('transacoes');
		$result = false;
		if(array_key_exists($transaction, $transacoes)){
			$result = $this->temPriv($transacoes[$transaction], 'P');
		}
		return $result;
	}
	
	/**
	 * Informa se o usuário corrente tem a permissão de criar
	 * 
	 * @return boolean
	 */
	public function isCreate($transaction){
		$transacoes = Session::get('transacoes');
		$result = false;
		if($this->isAdmin()){
			$result = true;
		}
		else{
			if(array_key_exists($transaction, $transacoes)){
				$result = $this->temPriv($transacoes[$transaction], 'I');
			}
		}
		return $result;
	}
	
	/**
	 * Informa se o usuário corrente tem a permissão de ler
	 * 
	 * @return boolean
	 */
	public function isRead($transaction){
		$transacoes = Session::get('transacoes');
		$result = false;
		if($this->isAdmin()){
			$result = true;
		}
		else{
			if(array_key_exists($transaction, $transacoes)){
				$result = $this->temPriv($transacoes[$transaction], 'P');
			}
		}
		return $result;
	}
	
	/**
	 * Informa se o usuário corrente tem a permissão de alterar
	 * 
	 * @return boolean
	 */
	public function isUpdate($transaction){
		$transacoes = Session::get('transacoes');
		$result = false;
		if($this->isAdmin()){
			$result = true;
		}
		else{
			if(array_key_exists($transaction, $transacoes)){
				$result = $this->temPriv($transacoes[$transaction], 'A');
			}
		}
		return $result;
	}
	
	/**
	 * Informa se o usuário corrente tem a permissão de excluir
	 * 
	 * @return boolean
	 */
	public function isDelete($transaction){
		$transacoes = Session::get('transacoes');
		$result = false;
		if($this->isAdmin()){
			$result = true;
		}
		else{
			if(array_key_exists($transaction, $transacoes)){
				$result = $this->temPriv($transacoes[$transaction], 'D');
			}
		}
		return $result;
	}
	
	/**
	 * Testa o formulário de recuperação de senha
	 * 
	 * @return boolean
	 */
	public function passRecovery(){		
		//Compilando variáveis do formulário
		$email = strtolower(addslashes($_POST['ds_email'])); 
		
		//Adquirindo o registro do usuário através do email
		$sql = "select ci_usuario, nm_login
		from tb_usuario 
		where ds_email = '$email'";	
		$query = query($sql);
		if($query->rowCount() < 1){
			Util::notice('Aviso', 'Email não encontrado!', 'alert');
		}
		else{
			$row = $query->fetch();
			$ci_usuario = $row['ci_usuario'];
			$login = $row['nm_login'];
			
			//Gerando senha aleatória
			$senha = $this->senhaAleatoria();
			
			//Caso a confirmação dos dados esteja correta o registro será alterado e será enviado por email
			//uma nova senha, caso contrário uma mensagem de erro será mostrada
			$update = "update tb_usuario set nm_senha = md5('$senha'), fl_atualizousenha = false where ci_usuario = $ci_usuario";
			if(execute($update)){
				$this->emailSenhaRecupera($login, $senha, $email);
				Util::notice('Aviso', 'Um <b>email</b> foi enviado com um novo acesso!');
				return true;
			}
			else{
				Util::notice('Aviso', 'Não foi possível realizar esta operação.', 'error');
			}			
		}
		return false;
	}

	/**
	 * Gera uma senha aleatória
	 * 
	 * @return string
	 */
	public function senhaAleatoria(){
		$senha = '';
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		//$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$caracteres = $lmin.$num;
		$len = strlen($caracteres);		
		for($i=0;$i<6;$i++){
			$rand = mt_rand(1, $len);
			$senha .= $caracteres[$rand - 1];
		}
		return $senha;
	}
	
	/**
	 * Gera email de boas vindas para um novo usuário
	 * 
	 * @param string $usuario
	 * @param string $senha
	 * @param string $email
	 * @return void
	 */
	public function emailSenhaNovoLojista($usuario, $senha, $email){
		$body = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
			<title>".Config::SYSTEM."</title>
		 </head>
		 <body topmargin='0'>
			<table width='550'  border='0' align='center' cellpadding='0' cellspacing='0' bordercolor='#E3E1BA' bgcolor='#FFFFFF'>
				<tr>
					<td><div align='justify'>
					<table width='100%'  border='0' cellpadding='10' cellspacing='0'>
				<tr>
					<td><p><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Bem vindo ao Sistema<br>
					<br>
					Para concluir o cadastro, acesse o sistema (<a href=\"".Config::SITE."\"/>".Config::SITE."</a>) utilizando o usu&aacute;rio e senha abaixo:<br>
					</font></p>
					<p><font color='#018C93' size='2' face='Verdana, Arial, Helvetica, sans-serif'>
					<strong><span class='style1'>Usu&aacute;rio= ".$usuario."<br>
					Senha= ".$senha."</span></strong></font></p>
					<p><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Observa&ccedil;&atilde;o</strong>: <br>
					Ap&oacute;s o primeiro acesso, o sistema solicitar&aacute; automaticamente a troca da senha. A senha &eacute; de uso pessoal e intrasfer&iacute;vel e n&atilde;o deve ser compartilhada com estranhos. Lembre-se que todas as a&ccedil;&otilde;es operacionalizadas em sua conta s&atilde;o de sua inteira responsabilidade.
					</font></p></td>
					</tr>
					</table>
					</div></td>
				</tr>
			</table>
		</body>
		</html>";
		Util::mail('Cadastro '.Config::SYSTEM, Config::EMAIL, Config::SYSTEM, $body, $email);		
	}
	
	/**
	 * Gera email de boas vindas para um novo usuário
	 * 
	 * @param string $usuario
	 * @param string $senha
	 * @param string $email
	 * @return void
	 */
	public function emailSenhaNovoUsuario($usuario, $senha, $email){
		$body = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
			<title>".Config::SYSTEM_ADMIN."</title>
		 </head>
		 <body topmargin='0'>
			<table width='550'  border='0' align='center' cellpadding='0' cellspacing='0' bordercolor='#E3E1BA' bgcolor='#FFFFFF'>
				<tr>
					<td><div align='justify'>
					<table width='100%'  border='0' cellpadding='10' cellspacing='0'>
				<tr>
					<td><p><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Bem vindo ao Sistema<br>
					<br>
					Para concluir o cadastro, acesse o sistema (<a href=\"".Config::SITE_ADMIN."\"/>".Config::SITE_ADMIN."</a>) utilizando o usu&aacute;rio e senha abaixo:<br>
					</font></p>
					<p><font color='#018C93' size='2' face='Verdana, Arial, Helvetica, sans-serif'>
					<strong><span class='style1'>Usu&aacute;rio= ".$usuario."<br>
					Senha= ".$senha."</span></strong></font></p>
					<p><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Observa&ccedil;&atilde;o</strong>: <br>
					Ap&oacute;s o primeiro acesso, o sistema solicitar&aacute; automaticamente a troca da senha. A senha &eacute; de uso pessoal e intrasfer&iacute;vel e n&atilde;o deve ser compartilhada com estranhos. Lembre-se que todas as a&ccedil;&otilde;es operacionalizadas em sua conta s&atilde;o de sua inteira responsabilidade.
					</font></p></td>
					</tr>
					</table>
					</div></td>
				</tr>
			</table>
		</body>
		</html>";
		Util::mail('Cadastro '.Config::SYSTEM_ADMIN, Config::EMAIL, Config::SYSTEM_ADMIN, $body, $email);		
	}
	
	/**
	 * Gera email de recuperação de senha
	 * 
	 * @param string $usuario
	 * @param string $senha
	 * @param string $email
	 * @return void
	 */
	public function emailSenhaRecupera($usuario, $senha, $email){
		$body = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
			<title>".Config::SITE_ADMIN."</title>
		 </head>
		 <body topmargin='0'>
			<table width='500'  border='0' align='center' cellpadding='0' cellspacing='0' bordercolor='#E3E1BA' bgcolor='#FFFFFF'>
				<tr>
					<td><div align='justify'>
					<table width='100%'  border='0' cellpadding='10' cellspacing='0'>
				<tr>
					<td><p><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Recupera&ccedil;&atilde;o de acesso ao sistema ".Config::SITE_ADMIN."!<br>
					<br>
					Caro Usu&aacute;rio,<br>
					Segue seu usu&aacute;rio e sua nova senha de acesso ao sistema:<br>
					</font></p>
					<p><font color='#018C93' size='2' face='Verdana, Arial, Helvetica, sans-serif'>
					<strong><span class='style1'>Usu&aacute;rio= ".$usuario." <br>
					Senha= ".$senha."</span></strong></font></p>
					<p><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Observa&ccedil;&atilde;o</strong>: <br>
					O sistema solicitar&aacute; automaticamente a troca da senha. Essa senha &eacute; de sua inteira responsabilidade, n&atilde;o devendo ser compartilhada com ningu&eacute;m. Lembre-se de que o sistema grava o nome do usu&aacute;rio que realizou qualquer opera&ccedil;&atilde;o. Caso sua senha seja compartilhada, tudo que for realizado utilizando sua senha ser&aacute; tamb&eacute;m de sua responsabilidade.<br>
					</font></p></td>
					</tr>
					</table>
					</div></td>
				</tr>
			</table>
		</body>
		</html>";
		Util::mail(utf8_decode('Recuperação Acesso ').Config::SYSTEM_ADMIN, Config::EMAIL, Config::SYSTEM_ADMIN, $body, $email);
	}
	
}