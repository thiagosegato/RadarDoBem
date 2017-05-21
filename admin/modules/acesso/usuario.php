<?php
defined('EXEC') or die();
$transacao = 'usuario';
$ufDefault = 'CE';

if(!$auth->isRead($transacao) && !$auth->isAdmin()){
	Util::info(Config::AUTH_MESSAGE);
	return true;
}

//Validando formulário de gerar uma nova senha e reenviar boas vindas
if(@$_POST['gerarSenha'] && @$_GET['form'] > 0){
	if(!$auth->isAdmin()){
		Util::info(Config::AUTH_MESSAGE);
		return true;
	}
	
	$ci_usuario = $_GET['form'];	
	$queryGeraSenha = query("select nm_login, ds_email from tb_usuario where ci_usuario = $ci_usuario")->fetch();	
	$nm_login = $queryGeraSenha['nm_login'];
	$senha = $auth->senhaAleatoria();
	$ds_email = $queryGeraSenha['ds_email'];
	$sql = "UPDATE tb_usuario
			   SET nm_senha=md5('$senha'), fl_atualizousenha=false
			 WHERE ci_usuario = $ci_usuario; ";
	if(execute($sql)){
		$auth->emailSenhaNovoUsuario($nm_login, $senha, $ds_email);
		Controller::setInfo('Usuários', 'Nova senha gerada e reenvio de email com sucesso!');
		Controller::redirect(Util::setLink(array('form=null', 'db=null')));
	}	
}

//Alteração ou inclusão de um registro
if(isset($_GET['db']) && isset($_GET['form'])){
	
	$nm_usuario = addslashes($_POST['nm_usuario']);
	$nm_login = addslashes(strtoupper($_POST['nm_login']));
	$ds_email = addslashes(strtolower($_POST['ds_email']));
	$fl_sexo = $_POST['fl_sexo'];
	$grupos = @$_POST['cd_grupo_select'];
	$fl_ativo = $_POST['fl_ativo'];
	$tp_nivelacesso = $_POST['tp_nivelacesso'];
	
	//Gerando senha aleatória
	$senha = $auth->senhaAleatoria();
	
	//Validando para que não haja cpfs, emails ou logins duplicados
	$queryTestLogin = query("select ci_usuario from tb_usuario where nm_login = '$nm_login' and ci_usuario != ".$_GET['form']);
	$queryTestEmail = query("select ci_usuario from tb_usuario where ds_email = '$ds_email' and ci_usuario != ".$_GET['form']);
	if($queryTestLogin->rowCount() > 0){
		$rowEdit = $_POST;
		$rowEditPro = $_POST;
		Util::alert('Já existe um usuário com este Login: '.$nm_login.' !');		
	}
	elseif($queryTestEmail->rowCount() > 0){
		$rowEdit = $_POST;
		$rowEditPro = $_POST;
		Util::alert('Já existe um usuário com este Email: '.$ds_email.' !');		
	}
	else{
		execute('BEGIN');
		
		if($_GET['form'] == 0){ //cadastro
			
			$ci_usuario = 'LAST_INSERT_ID()';
			$sql = "INSERT INTO tb_usuario(nm_usuario, nm_login, nm_senha, ds_email, fl_sexo, fl_ativo, tp_nivelacesso)
			VALUES ('$nm_usuario', '$nm_login', md5('$senha'), '$ds_email', '$fl_sexo', $fl_ativo, $tp_nivelacesso); ";
			execute($sql);			
			
		}	
		elseif($_GET['form'] > 0){ //alteração
			
			$ci_usuario = $_GET['form'];
			$sql = "UPDATE tb_usuario
			   SET nm_usuario='$nm_usuario', nm_login='$nm_login', 
				   ds_email='$ds_email', fl_sexo='$fl_sexo', fl_ativo=$fl_ativo, tp_nivelacesso=$tp_nivelacesso
			 WHERE ci_usuario = $ci_usuario; ";
			execute($sql);
			 
		}
		
		//Removendo grupos e adicionando novamente
		execute('delete from tb_usuario_grupo where cd_usuario = '.$ci_usuario);
		$sqlGrupos = '';
		for ($i=0;$i<count($grupos);$i++){ 
			execute('insert into tb_usuario_grupo (cd_usuario, cd_grupo) values('.$ci_usuario.', '.$grupos[$i].'); ');				
		}
		
		if(execute('COMMIT')){
			if($_GET['form'] == 0){
				$auth->emailSenhaNovoUsuario($nm_login, $senha, $ds_email);
				Controller::setInfo('Um email foi enviado para este usuário', 'Salvo com sucesso!');
			}
			else{
				Controller::setInfo('Usuários', 'Salvo com sucesso!');
			}
			Controller::redirect(Util::setLink(array('form=null', 'db=null', 'nm_login='.$nm_login)));	
		}
		else{
			Util::notice('Usuários', 'Ocorreu um erro!', 'error');	
		}
	}		
}

if(isset($_GET['form'])){ //Formulário para adição ou alteração de registro
	if($_GET['form'] == 0){
		if(!$auth->isCreate($transacao) && !$auth->isAdmin()){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}
		$queryGruposDisponiveis = query('select ci_grupo, nm_grupo, ds_descricao from tb_grupo order by nm_grupo asc');
		$queryGruposUtilizados = null;
	}
	else{
		if(!$auth->isUpdate($transacao) && !$auth->isAdmin()){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}
		if(@!$rowEdit){
			$rowEdit = query("select * from tb_usuario where ci_usuario = ".$_GET['form'])->fetch();
		}
		else{
			foreach($rowEdit as $key=>$value){
				$rowEdit[$key] = addslashes($value);
			}
		}		
		
		$queryGruposDisponiveis = query('select ci_grupo, nm_grupo, ds_descricao from tb_grupo where ci_grupo not in(select cd_grupo from tb_usuario_grupo where cd_usuario = '.$_GET['form'].') order by nm_grupo asc');
		$queryGruposUtilizados = query('select tg.ci_grupo, tg.nm_grupo, tg.ds_descricao from tb_usuario_grupo tug inner join tb_grupo tg on(tug.cd_grupo=tg.ci_grupo) where tug.cd_usuario = '.$_GET['form']);
	}
	
	//Deixando pré-selecinada a opção masculino
	if(@!$rowEdit['fl_sexo'])
		$rowEdit['fl_sexo'] = 1;
}
else{ //Consulta no banco para listagem dos registros

	//Verificando a pesquisa rápida por login
	if(@$_GET['nm_login'] && !isset($_POST['search2']))
		$_POST['search2'] = $_GET['nm_login'];

	$where = '';
	if(@$_POST['search1']){
		$term = addslashes($_POST['search1']);
		$where .= "and tu.nm_usuario like '%{$term}%' ";
		$search = true;
	}
	if(@$_POST['search2']){
		$term = addslashes($_POST['search2']);
		$where .= "and tu.nm_login like '%{$term}%' ";
		$search = true;
	}
	if(@$_POST['search3']){
		$term = addslashes($_POST['search3']);
		$where .= "and tu.ds_email like '%{$term}%' ";
		$search = true;
	}
	if(@$_POST['search4']){
		$term = addslashes($_POST['search4']);
		$where .= "and tu.fl_ativo = {$term} ";
		$search = true;
	}
	
	$nivelWhere = '1';
	if($auth->isAdmin())
		$nivelWhere .= ', 2';
	if($auth->isMaster())
		$nivelWhere .= ', 3';
		
	$sql = "select 	tu.ci_usuario,
		tu.nm_usuario,
		tu.nm_login,
		tu.ds_email,		
		tu.fl_ativo,
		tu.tp_nivelacesso
	from tb_usuario tu
	where tu.tp_nivelacesso in($nivelWhere) $where
	order by 2
	limit ".(($p - 1) * $limitPagina).",".$limitPagina;
	$query = query($sql);
	$sqlNum = "select count(*) as num 
	from tb_usuario tu
	where 1=1 $where";
	$rowNum = query($sqlNum)->fetch();
	$registros = $rowNum['num'];	
	$paginacao = Util::pagination($registros, 6);
}
?>

	<div class="row bgGrey">
		<img src="assets/usuarios.png"/>
		<span class="actiontitle">Usuários</span>
		<span class="actionview"> - <?php echo (!isset($_GET['form']) ? 'Pesquisa' : (@$_GET['form'] > 0 ? 'Edição' : 'Cadastro')); ?></span>		
		<?php if(!isset($_GET['form'])){ ?>
			<button type="button" id="btAdd" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Novo</button>   
		<?php } else{ ?>					
			<button id="btVoltar" onclick="window.location='?page=acesso/usuario';" class="btn btn-info btn-sm pull-right btn-space"><span class="glyphicon glyphicon-circle-arrow-left"></span> Voltar</button>
		<?php } ?>
		<?php if($auth->isAdmin() && @$_GET['form'] > 0){ ?>
			<button type="button" data-toggle="modal" data-target="#modalGerarSenha" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-export"></span> Gerar nova senha e reenviar boas vindas</button>
		<?php } ?>
	</div>

	<!-- FORMULÁRIO DE PESQUISA -->
	<?php if(!isset($_GET['form'])){ ?>	
		
		<?php if(@$_POST['search'] && (!@$_POST['search1'] && !@$_POST['search2'] && !@$_POST['search3'] && !@$_POST['search4'])) { ?>
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="alert alert-warning">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
						<p>Preencha um dos campos abaixo para pesquisa!</p>
					</div>
				</div>
			</div>
		<?php } ?>
	
	
		<form action="<?php echo Util::setLink(array('p=null')); ?>" method="post">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-8">
						<label>Nome Completo:</label>
						<input type="text" class="form-control" id="search1" name="search1" value="<?php echo @$_POST['search1']; ?>">
					</div>
					<div class="col-lg-4">
						<label>Login:</label>
						<input type="text" class="form-control" id="search2" name="search2" value="<?php echo @$_POST['search2']; ?>">											
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-8">					
						<label>Email:</label>
						<input type="tel" id="search3" name="search3" value="<?php echo @$rowEdit['search3']; ?>" class="form-control"/>
					</div>
					<div class="col-lg-4">
						<label>Status:</label>
						<select id="search4" name="search4" class="form-control">
							<option value="0">...</option>
							<option value="true" <?php echo (@$_POST['search4'] == 'true' ? 'selected="selected"' : ''); ?> style="color:green; font-weight:bold;">ATIVADO</option>
							<option value="false" <?php echo (@$_POST['search4'] == 'false' ? 'selected="selected"' : ''); ?> style="color:red; font-weight:bold;">DESATIVADO</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-12">
						<div class="marginBottom"></div>
						<button type="submit" name="search" value="1" class="btn btn-info"><span class="glyphicon glyphicon-zoom-in"></span> Pesquisar</button>
						<img class="loader" src="assets/loading.gif"/>
					</div>					
				</div>
			</div>
		</form>
	
		<br>
		
		<?php if(!isset($_GET['form'])){ ?>
		
			<div class="row">
				<form id="formSearch" method="post">
					<div class="table-responsive btMarginRight">		
						<table class="table table-hover table-bordered">
							<thead>
								<tr class="btn-info">
									<th>Usuário</th>
									<th>Login</th>
									<th>Email</th>
									<th>Acesso</th>
									<th>Status</th>
									<th></th>						
								</tr>
							</thead>
							<tbody>
								<?php 
								$fl_ativo[0] = '<font color="red"><b>DESATIVADO</b></font>';
								$fl_ativo[1] = '<font color="green"><b>ATIVADO</b></font>';								
								while($row = $query->fetch()){
								?>
								<tr>
									<td><?php echo $row['nm_usuario']; ?></td>
									<td><?php echo $row['nm_login']; ?></td>
									<td><?php echo $row['ds_email']; ?></td>
									<td><?php echo NivelAcesso::$niveis[$row['tp_nivelacesso']]; ?></td>
									<td><?php echo $fl_ativo[$row['fl_ativo']] ?></td>
									<td class="text-center">
										<a href="javascript:void(0);" onclick="window.location='<?php echo Util::setLink(array('form='.$row['ci_usuario'])); ?>';">
											<span class="glyphicon glyphicon-edit"></span>
										</a>
									</td>
								</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>						
				</form>
				<?php echo $paginacao; ?>
			</div>
			
		<?php } ?>		
		
	<?php } else{ ?>
	
		<!-- FORMULÁRIO DE CADASTRO -->
		<form action="<?php echo Util::setLink(array('db=1')) ?>" method="post" id="formInsertEdit" onsubmit="return test();">
		
			<div class="row"><div id="validateBox" class="col-md-8 col-md-offset-2"></div></div>
			
			<div class="row">
				<div class="col-md-6 col-md-offset-3">					
					<legend>DADOS GERAIS</legend>		
					<div class="row">
					<div class="col-md-12">
						Nome Completo: *
						<input type="text" id="nm_usuario" name="nm_usuario" value="<?php echo @$rowEdit['nm_usuario']; ?>" maxlength="150" class="form-control"/>
					</div>
					</div>
					<div class="row">
					<div class="col-md-12">
						Email: *
						<input type="text" id="ds_email" name="ds_email" value="<?php echo @$rowEdit['ds_email']; ?>" maxlength="100" class="form-control"/>
					</div>
					</div>
					<div class="row">
					<div class="col-md-5">
						Login: *
						<input type="text" id="nm_login" name="nm_login" value="<?php echo @$rowEdit['nm_login']; ?>" maxlength="50" class="form-control"/>							
					</div>
					<div class="col-md-5 col-md-offset-2">
						Nível de Acesso: *
						<select id="tp_nivelacesso" name="tp_nivelacesso" class="form-control">
						<?php
							$niveis = array();
							$niveis[1] = NivelAcesso::$niveis[1];
							if($auth->isAdmin())	
								$niveis[2] = NivelAcesso::$niveis[2];						
							if($auth->isMaster())
								$niveis[3] = NivelAcesso::$niveis[3];						
						
							foreach($niveis as $key=>$value){						
								if(@$rowEdit['tp_nivelacesso'] == $key)
									echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
								else						
									echo '<option value="'.$key.'">'.$value.'</option>';
							}
						?>
						</select>							
					</div>
					</div>
					<div class="row">
					<div class="col-md-5">
						Sexo: *<br>
						<label><input type="radio" name="fl_sexo" value="1" <?php echo (@$rowEdit['fl_sexo'] == 1 ? 'checked="checked"' : ''); ?>/> Masculino</label>&nbsp;
						<label><input type="radio" name="fl_sexo" value="2" <?php echo (@$rowEdit['fl_sexo'] == 2 ? 'checked="checked"' : ''); ?>/> Feminino</label>
					</div>
					<div class="col-md-5 col-md-offset-2">
						Status: 
						<select id="fl_ativo" name="fl_ativo" class="form-control">
							<option value="true" <?php echo (@$rowEdit['fl_ativo'] == 1 ? 'selected="selected"' : ''); ?> style="color:green; font-weight:bold;">ATIVADO</option>
							<option value="false" <?php echo (@$rowEdit['fl_ativo'] == 0 ? 'selected="selected"' : ''); ?> style="color:red; font-weight:bold;">DESATIVADO</option>									
						</select>
					</div>
					</div>
					
				</div>
			</div>
			
			<br/>
			
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<legend>GRUPOS</legend>					
					<div id="cd_grupo" class="row">
						<div class="col-md-6">
							Disponíveis:
							<select size="10" style="font-family:courier; padding:0px;" class="form-control">
								<?php
								if(@$queryGruposDisponiveis){
									while($row = $queryGruposDisponiveis->fetch()){
										echo '<option value="'.$row['ci_grupo'].'" title="'.$row['ds_descricao'].'">'.$row['nm_grupo'].'</option>';
									}
								}
								?>							
							</select>
						</div>
						<div class="col-md-1 text-center controls" style="margin-top:20px;"></div>
						<div class="col-md-5">
							Selecionados:
							<select id="cd_grupo_select" name="cd_grupo_select[]" size="10" style="font-family:courier; padding:0px" class="form-control">						
								<?php
								if(@$queryGruposUtilizados){
									while($row = $queryGruposUtilizados->fetch()){
										echo '<option value="'.$row['ci_grupo'].'" title="'.$row['ds_descricao'].'">'.$row['nm_grupo'].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			
			<br>
			
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center">
					<button id="btInsertEdit" type="submit" class="btn btn-success text-center">Salvar</button>
					<img class="loader" src="assets/loading.gif"/>
				</div>
			</div>				
			
		</form>		
	
	<?php } ?>	

	<?php if($auth->isAdmin() && @$_GET['form'] > 0){ ?>
	<div id="modalGerarSenha" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content clearfix">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
			<p><br>Tem certeza que deseja realizar esta operação?</p>
			<p>Este processo não poderá ser revertido!</p><br>
		  </div>
		  <div class="modal-body pull-right">      
			<form method="post">
				<input type="hidden" name="gerarSenha" value="1"/>
				<input type="submit" class="btn btn-primary" value="OK"/>
				<button type="button" class="btn btn-small btn-default" data-dismiss="modal">Cancelar</button>
			</form>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>	
	
<!-- Modal Vínculos -->
<div id="modalVinculos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>				
				<iframe id="modalVinculosFrame" width="100%" height="500" style="border:none;"></iframe>				
			</div>
		</div>
	</div>
</div>
<!-- Fim Modal Vínculos -->
	
<script type="text/javascript">
$(function(){
    $("#cd_grupo").pickList();	
});
function test(){
	var valid = true;
	$("#formInsertEdit").find("input,select").each(function(index){
		$(this).removeClass("ui-state-error");						
	});	
	valid = checkLength('nm_usuario', 'Nome Completo', 3) && valid;
	valid = checkLength('nm_login', 'Login', 3) && valid;
	if(!checkMail($('#ds_email').val())){
		valid = false;
		$('#ds_email').addClass("ui-state-error").focus();			
		updateTips('Email inválido.');
	}
	return valid;
}
</script>