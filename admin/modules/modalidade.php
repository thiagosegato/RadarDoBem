<?php
defined('EXEC') or die();

if(!$auth->isMaster()){
	Util::info(Config::AUTH_MESSAGE);
	return true;
}

//Alteração ou inclusão de um registro
if(isset($_GET['db']) && isset($_GET['form'])){	
	
	$ci_modalidade = $_GET['form'];
	$nm_modalidade = addslashes($_POST['nm_modalidade']);
	$ds_modalidade = addslashes($_POST['ds_modalidade']);	
		
	if($_GET['form'] == 0){ //cadastro	
		$sql = "INSERT INTO tb_modalidade(nm_modalidade, ds_modalidade)
		VALUES ('$nm_modalidade', '$ds_modalidade');";
	}	
	elseif($_GET['form'] > 0){ //alteração
		$sql = "UPDATE tb_modalidade SET nm_modalidade='$nm_modalidade', ds_modalidade='$ds_modalidade' WHERE ci_modalidade = $ci_modalidade;"; 
	}
	
	if(execute($sql)){
		Controller::setInfo('Modalidade', 'Salva com sucesso!');	
		Controller::redirect(Util::setLink(array('form=null', 'db=null')));	
	}
	else{
		Util::notice('Modalidades', 'Ocorreu um erro!', 'error');	
	}		
}

if(isset($_GET['form'])){ //Formulário para adição ou alteração de registro
	if($_GET['form'] == 0){
		if(!$auth->isMaster()){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}		
	}
	else{
		if(!$auth->isMaster()){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}
		$rowEdit = query("select * from tb_modalidade where ci_modalidade = ".$_GET['form'])->fetch();		
	}
}
else{ //Consulta no banco para listagem dos registros
	$where = '';
	if(@$_POST['search1']){
		$term = addslashes($_POST['search1']);
		$where .= "and nm_modalidade like '%{$term}%' ";			
	}
		
	$sql = "select *
	from tb_modalidade where 1=1 $where
	order by 2
	limit {$limitPagina} offset ".(($p - 1) * $limitPagina);
	$query = query($sql);
	$sqlNum = "select count(*) as num from tb_modalidade
	where 1=1 $where";
	$rowNum = query($sqlNum)->fetch();
	$registros = $rowNum['num'];	
	$paginacao = Util::pagination($registros, 1);	
}

?>

	<div class="row bgGrey">
		<img src="assets/modalidades.png"/>
		<span class="actiontitle">Modalidades</span>
		<span class="actionview"> - <?php echo (!isset($_GET['form']) ? 'Pesquisa' : (@$_GET['form'] > 0 ? 'Edição' : 'Cadastro')); ?></span>
		<?php if(!isset($_GET['form'])){ ?>
			<button type="button" id="btAdd" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Novo</button>   
		<?php } else{ ?>		
			<button id="btVoltar" onclick="window.location='?page=modalidade';" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-circle-arrow-left"></span> Voltar</button>
		<?php } ?>		
	</div>
	
	<!-- FORMULÁRIO DE PESQUISA -->
	<?php if(!isset($_GET['form'])){ ?>	
	
		<form action="<?php echo Util::setLink(array('p=null')); ?>" method="post">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-9">
						<label>Modalidade:</label><input type="text" class="form-control" id="search1" name="search1" value="<?php echo @$_POST['search1']; ?>">
					</div>					
					<div class="col-lg-3">
						<div style="margin-top:25px;"></div>
						<button type="submit" name="search" value="1" class="btn btn-info"><span class="glyphicon glyphicon-zoom-in"></span> Pesquisar</button>
					</div>
				</div>				
			</div>
		</form>
	
		<br>
		
		<div class="row">
			<form id="formSearch" method="post">
				<div class="table-responsive btMarginRight">
				<table class="table table-hover table-bordered">
					<thead>
						<tr class="btn-info">
							<th>ID</th>
							<th>Nome</th>
							<th>Descrição</th>
							<td></td>							
						</tr>
					</thead>
					<tbody>
						<?php 
						while($row = $query->fetch()){
						?>
						<tr>
							<td><?php echo $row['ci_modalidade']; ?></td>
							<td><?php echo $row['nm_modalidade']; ?></td>
							<td><?php echo $row['ds_modalidade']; ?></td>
							<td class="text-center">
								<a href="javascript:void(0);" onclick="window.location='<?php echo Util::setLink(array('form='.$row['ci_modalidade'])); ?>';">
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
	
	<?php } else{ ?>
	
		<!-- FORMULÁRIO DE CADASTRO -->
		<form action="<?php echo Util::setLink(array('db=1')) ?>" method="post" id="formInsertEdit" onsubmit="return test();">		
			
			<div class="row"><div id="validateBox" class="col-md-8 col-md-offset-2"></div></div>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<fieldset class="" style="">
						<div class="row">
						<div class="col-md-12">
							Modalidade: *
							<input type="text" id="nm_modalidade" name="nm_modalidade" value="<?php echo @$rowEdit['nm_modalidade']; ?>" maxlength="50" class="form-control"/></td>
						</div>
						</div>
						<div class="row">
						<div class="col-md-12">
							Descrição: *
							<textarea id="ds_modalidade" name="ds_modalidade" class="form-control"><?php echo @$rowEdit['ds_modalidade']; ?></textarea>							
						</div>
						</div>						
					</fieldset>
				</div>
			</div>
			
			<br>
			
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center">
					<button id="btInsertEdit" type="submit" class="btn btn-success text-center">Salvar</button>
					<img class="loader" src="assets/loading.gif"/>
				</div>
			</div>
			
			<br>
			
		</form>
	
	<?php } ?>
	
<script type="text/javascript">
function test(){	
	var valid = true;
	var nm_modalidade = $("#nm_modalidade").val();
	var ds_modalidade = $("#ds_modalidade").val();

	if (nm_modalidade == '') {
		updateTips('O Campo Modalidade está vázio');
		valid = false;
	}else if(ds_modalidade == ''){
		updateTips('O Campo Descrição está vázio');
		valid = false;
	}else{
		$("#formInsertEdit").find("input,textarea").each(function(index){
			$(this).removeClass("ui-state-error");						
		});

		valid = valid && checkLength('nm_modalidade', 'Modalidade', 2);
		valid = valid && checkLength('ds_modalidade', 'Descrição', 2);
	}
	return valid;	
}	
</script>