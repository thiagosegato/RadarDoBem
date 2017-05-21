<?php
defined('EXEC') or die();
//Dinamica de alteração de senha do usuário
if(@$_POST['edit']){
	$user = $auth->getLogin();
	$senhaAtual = $_POST['nm_senha_atual'];
	$novaSenha = $_POST['nm_nova_senha'];
	$repetirSenha = $_POST['nm_repetir_senha'];

	//Consulta utilizada na verificação do usuario logado.
	$sql_usuario = "select nm_login, nm_senha from tb_usuario where ci_usuario = {$user['ci_usuario']}";
	$rs = query($sql_usuario)->fetch();
	
	//Verificação da senha atual.
	if(md5($senhaAtual) == $rs['nm_senha']){
		//Verificação da senha repetida e update da nova senha.
		if($novaSenha == $repetirSenha){
			$update = "update tb_usuario set nm_senha = md5('{$novaSenha}'), fl_atualizousenha = true 
						where ci_usuario = {$user['ci_usuario']}";
			if(@execute($update)){
				Util::info('Senha atualizada com sucesso!');
				Controller::setInfo('Senha', 'Senha atualizada com sucesso!');
				Controller::redirect('index.php');				
			}
			else{
				Util::info('Houve um erro ao alterar a senha. Por favor tente mais tarde!');
			}			
		}
		else{
			Util::info('Verifique se a nova senha foi repetida corretamente!');
		}
		
	}else{
		Util::info('Senha atual inválida!');		
	}
}


?>
<div class="container">
	<div class="row"><div id="validateBox" class="col-md-8 col-md-offset-2"></div></div>
	<form method="post" onsubmit="return test();" role="form">
		<div class="row">
			<div class="col-lg-4 col-lg-offset-4">
				<label>Usuário:</label><input type="text" class="form-control" id="nm_usuario" name="nm_usuario" value="<?php echo $user['nm_usuario']; ?>" disabled="disabled"/>
			</div>
			<div class="col-lg-3 col-lg-offset-4">
				<label>Senha Atual: *</label><input type="password" class="form-control" id="nm_usuario" name="nm_senha_atual" placeholder="Digite a senha atual" maxlength="20"/>
			</div>
			<div class="col-lg-3 col-lg-offset-4">
				<label>Nova Senha: *</label><input type="password" class="form-control" id="nm_nova_senha" name="nm_nova_senha" placeholder="Digite a senha nova" maxlength="20"/>
			</div>
			<div class="col-lg-3 col-lg-offset-4">
				<label>Repetir Senha: *</label><input type="password" class="form-control" id="nm_repetir_senha" name="nm_repetir_senha" placeholder="Repita a nova senha" maxlength="20"/>
			</div>
			<div class="col-lg-3 col-lg-offset-4">
				<br>
				<button type="submit" class="btn btn-success">Alterar</button>
				<input type="hidden" name="edit" value="1"/>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	function test(){	
	var valid = true;
	var nm_usuario = $("#nm_usuario").val();
	var nm_senha_atual = $("#nm_senha_atual").val();
	var nm_nova_senha = $("#nm_nova_senha").val();
	var nm_repetir_senha = $("#nm_repetir_senha").val();

	if (nm_usuario == '') {
		updateTips('O Campo Usuario está vázio.');
		valid = false;
	}else if(nm_senha_atual == ''){
		updateTips('O Campo Senha Atual está vázio.');
		valid = false;
	}else if(nm_nova_senha == ''){
		updateTips('O Campo Nova Senha está vázio.');
		valid = false;
	}else if(nm_repetir_senha == ''){
		updateTips('O Campo Repitir Senha está vázio.');
		valid = false;
	}else{
		$("#formInsertEdit").find("input").each(function(index){
			$(this).removeClass("ui-state-error");						
		});

		valid = valid && checkLength('nm_usuario', 'Usuario', 2);
		valid = valid && checkLength('nm_senha_atual', 'Senha Atual', 2);
		valid = valid && checkLength('nm_nova_senha', 'Nova Senha', 2);
		valid = valid && checkLength('nm_repetir_senha', 'Repetir Senha', 2);
	}
	return valid;
	
}	
</script>