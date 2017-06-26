<?php
defined('EXEC') or die();

if(!$auth->isMaster()){
	Util::info(Config::AUTH_MESSAGE);
	return true;
}

//Alteração ou inclusão de um registro
if(isset($_GET['db']) && isset($_GET['form'])){	
	
	$ci_municipio = $_GET['form'];
	$nm_municipio = addslashes($_POST['nm_municipio']);
	$sg_estado = addslashes($_POST['sg_estado']);	
		
	if($_GET['form'] == 0){ //cadastro	
		$sql = "INSERT INTO tb_municipio(nm_municipio, sg_estado)
		VALUES ('$nm_municipio', '$sg_estado');";
	}	
	elseif($_GET['form'] > 0){ //alteração
		$sql = "UPDATE tb_municipio SET nm_municipio='$nm_municipio', sg_estado='$sg_estado' WHERE ci_municipio = $ci_municipio;"; 
	}
	
	if(execute($sql)){
		Controller::setInfo('Município', 'Salvo com sucesso!');	
		Controller::redirect(Util::setLink(array('form=null', 'db=null')));	
	}
	else{
		Util::notice('Município', 'Ocorreu um erro!', 'error');	
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
		$rowEdit = query("select * from tb_municipio where ci_municipio = ".$_GET['form'])->fetch();				
	}
}
else{ //Consulta no banco para listagem dos registros
	$where = '';
	if(@$_POST['search1']){
		$term = addslashes($_POST['search1']);
		$where .= "and nm_municipio like '%{$term}%' ";			
	}
		
	$sql = "select *
	from tb_municipio where 1=1 $where
	order by sg_estado,nm_municipio
	limit {$limitPagina} offset ".(($p - 1) * $limitPagina);
	$query = query($sql);
	$sqlNum = "select count(*) as num from tb_municipio
	where 1=1 $where";
	$rowNum = query($sqlNum)->fetch();
	$registros = $rowNum['num'];	
	$paginacao = Util::pagination($registros, 1);	
}

?>

	<div class="row bgGrey">
		<img src="assets/municipios.png"/>
		<span class="actiontitle">Municípios</span>
		<span class="actionview"> - <?php echo (!isset($_GET['form']) ? 'Pesquisa' : (@$_GET['form'] > 0 ? 'Edição' : 'Cadastro')); ?></span>
		<?php if(!isset($_GET['form'])){ ?>
			<button type="button" id="btAdd" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Novo</button>   
		<?php } else{ ?>		
			<button id="btVoltar" onclick="window.location='?page=municipios';" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-circle-arrow-left"></span> Voltar</button>
		<?php } ?>		
	</div>
	
	<!-- FORMULÁRIO DE PESQUISA -->
	<?php if(!isset($_GET['form'])){ ?>	
	
		<form action="<?php echo Util::setLink(array('p=null')); ?>" method="post">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-9">
						<label>Município:</label><input type="text" class="form-control" id="search1" name="search1" value="<?php echo @$_POST['search1']; ?>">
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
							<th>Município</th>
							<th>UF</th>
							<td></td>							
						</tr>
					</thead>
					<tbody>
						<?php 
						while($row = $query->fetch()){
						?>
						<tr>
							<td><?php echo $row['ci_municipio']; ?></td>
							<td><?php echo $row['nm_municipio']; ?></td>
							<td><?php echo $row['sg_estado']; ?></td>
							<td class="text-center">
								<a href="javascript:void(0);" onclick="window.location='<?php echo Util::setLink(array('form='.$row['ci_municipio'])); ?>';">
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
							Município: *
							<input type="text" id="nm_municipio" name="nm_municipio" value="<?php echo @$rowEdit['nm_municipio']; ?>" maxlength="50" class="form-control"/>
						</div>
						</div>
						<div class="row">
						<div class="col-md-12">
							UF: *
							<input type="text" id="sg_estado" name="sg_estado" value="<?php echo @$rowEdit['sg_estado']; ?>" maxlength="2" class="form-control"/>
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
	var nm_municipio = $("#nm_municipio").val();
	var sg_estado = $("#sg_estado").val();

	if (nm_municipio == '') {
		updateTips('O Campo Município está vázio');
		valid = false;
	}else if(sg_estado == ''){
		updateTips('O Campo UF está vázio');
		valid = false;
	}else{
		$("#formInsertEdit").find("input,textarea").each(function(index){
			$(this).removeClass("ui-state-error");						
		});

		valid = valid && checkLength('nm_municipio', 'Município', 2);
		valid = valid && checkLength('sg_estado', 'UF', 2);
	}
	return valid;	
}	
</script>