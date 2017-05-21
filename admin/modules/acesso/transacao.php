<?php
defined('EXEC') or die();

if(!$auth->isMaster()){
	Util::info(Config::AUTH_MESSAGE);
	return true;
}

//Importando classes
Loader::import('com.atitudeweb.SQL');
Loader::import('com.atitudeweb.TipoTransacao');

//Exclusão de vários ou um registro
if(isset($_POST['checkdel'])){	
	if($auth->isMaster()){
		if(@SQL::remove('tb_transacao', 'ci_transacao', $_POST['checkdel'])){		
			Util::notice('Transações', 'Excluídas com sucesso!');				
		}
		else{
			Util::notice('Transações', 'Erro ao excluir. A transação já pode estar sendo utilizada por um grupo!', 'error');
		}		
	}
	else{
		Util::info(Config::AUTH_MESSAGE);
	}
}

//Alteração ou inclusão de um registro
if(isset($_GET['db']) && isset($_GET['form'])){	
	
	$ci_transacao = $_GET['form'];
	$nm_transacao = addslashes($_POST['nm_transacao']);
	$nm_label = addslashes($_POST['nm_label']);	
	$tp_tipo = $_POST['tp_tipo'];	
	$ds_descricao = addslashes($_POST['ds_descricao']);
	
	if($_GET['form'] == 0){ //cadastro	
		$sql = "INSERT INTO tb_transacao(nm_transacao, nm_label, tp_tipo, ds_descricao)
		VALUES ('$nm_transacao', '$nm_label', '$tp_tipo', '$ds_descricao');";
	}	
	elseif($_GET['form'] > 0){ //alteração
		$sql = "UPDATE tb_transacao SET nm_transacao='$nm_transacao', nm_label='$nm_label', tp_tipo='$tp_tipo', ds_descricao='$ds_descricao' WHERE ci_transacao = $ci_transacao;"; 
	}
	
	if(execute($sql)){
		Controller::setInfo('Transações', 'Salva com sucesso!');	
		Controller::redirect(Util::setLink(array('form=null', 'db=null')));	
	}
	else{
		Util::notice('Transações', 'Ocorreu um erro!', 'error');	
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
		$rowEdit = query("select * from tb_transacao where ci_transacao = ".$_GET['form'])->fetch();		
	}
}
else{ //Consulta no banco para listagem dos registros
	$where = '';
	if(@$_POST['search1']){
		$term = addslashes($_POST['search1']);
		$where .= "and nm_transacao ilike '%{$term}%' ";			
	}
	if(@$_POST['search2']){
		$term = addslashes($_POST['search2']);
		$where .= "and nm_label ilike '%{$term}%' ";			
	}
	
	$sql = "select *
	from tb_transacao where 1=1 $where
	order by 2
	limit {$limitPagina} offset ".(($p - 1) * $limitPagina);
	$query = query($sql);
	$sqlNum = "select count(*) as num from tb_transacao
	where 1=1 $where";
	$rowNum = query($sqlNum)->fetch();
	$registros = $rowNum['num'];	
	$paginacao = Util::pagination($registros, 2);	
}

?>

	<div class="row bgGrey">
		<img src="assets/transacoes.png"/>
		<span class="actiontitle">Transações</span>
		<span class="actionview"> - <?php echo (!isset($_GET['form']) ? 'Pesquisa' : (@$_GET['form'] > 0 ? 'Edição' : 'Cadastro')); ?></span>
		<?php if(!isset($_GET['form'])){ ?>
			<button type="button" id="btAdd" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Novo</button>   
		<?php } else{ ?>		
			<button id="btVoltar" onclick="window.location='?page=acesso/transacao';" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-circle-arrow-left"></span> Voltar</button>
		<?php } ?>		
	</div>
	
	<!-- FORMULÁRIO DE PESQUISA -->
	<?php if(!isset($_GET['form'])){ ?>	
	
		<form action="<?php echo Util::setLink(array('p=null')); ?>" method="post">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-9">
						<label>Transação:</label><input type="text" class="form-control" id="search1" name="search1" value="<?php echo @$_POST['search1']; ?>">
					</div>					
				</div>
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-9">
						<label>LABEL:</label><input type="text" class="form-control" id="search2" name="search2" value="<?php echo @$_POST['search2']; ?>">
					</div>
					<div class="col-lg-3">
						<br/>
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
							<td><input id="btCheckAll" type="checkbox"></td>
							<th>ID</th>
							<th>Transação</th>
							<th>LABEL</th>
							<th>Tipo</th>
							<th>Descrição</th>
							<td></td>							
						</tr>
					</thead>
					<tbody>
						<?php 
						while($row = $query->fetch()){
						?>
						<tr>
							<td><input type="checkbox" name="checkdel[]" value="<?php echo $row['ci_transacao']; ?>" class="btCheck"></td>
							<td><?php echo $row['ci_transacao']; ?></td>
							<td><?php echo $row['nm_transacao']; ?></td>
							<td><?php echo $row['nm_label']; ?></td>
							<td><?php echo $row['tp_tipo'].' - '.TipoTransacao::$tipos[$row['tp_tipo']]; ?></td>
							<td><?php echo $row['ds_descricao']; ?></td>
							<td class="text-center">
								<a href="javascript:void(0);" onclick="window.location='<?php echo Util::setLink(array('form='.$row['ci_transacao'])); ?>';">
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
				<div id="modalDel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content clearfix">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
						<p><br>Tem certeza que deseja excluir os registros selecionados?</p>
					  </div>
					  <div class="modal-body pull-right">      
						<button type="button" class="btn btn-small btn-default" data-dismiss="modal">Cancelar</button>
						<input type="submit" class="btn btn-primary" value="OK"/>
					  </div>
					</div>
				  </div>
				</div>			
			</form>
			<div class="row">
				<div class="col-md-1">
					<button class="btn btn-danger" data-toggle="modal" data-target="#modalDel" title="Excluir selecionados" style="float:left;"><span class="glyphicon glyphicon-remove-sign"></span> Excluir</button>
				</div>
			</div>
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
							Transação: *
							<input type="text" id="nm_transacao" name="nm_transacao" value="<?php echo @$rowEdit['nm_transacao']; ?>" maxlength="50" class="form-control"/></td>
						</div>
						</div>
						<div class="row">
						<div class="col-md-12">
							LABEL: *
							<input type="text" id="nm_label" name="nm_label" value="<?php echo @$rowEdit['nm_label']; ?>" maxlength="100" class="form-control"/></td>
						</div>
						</div>
						<div class="row">
						<div class="col-md-12">
							Tipo: *
							<select id="tp_tipo" name="tp_tipo" class="form-control">
							<?php
								foreach(TipoTransacao::$tipos as $key=>$value){
									if(@$rowEdit['tp_tipo'] == $key)
										echo '<option value="'.$key.'" selected="selected">'.$key.' - '.$value.'</option>';
									else
										echo '<option value="'.$key.'">'.$key.' - '.$value.'</option>';
								}
							?>
							</select>
						</div>
						</div>
						<div class="row">
						<div class="col-md-12">
							Descrição: *
							<input type="text" id="ds_descricao" name="ds_descricao" value="<?php echo @$rowEdit['ds_descricao']; ?>" maxlength="100" class="form-control"/></td>
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
$(function(){
	$("#nm_transacao").keyup(function(){
		$(this).val($(this).val().toUpperCase());
	    var varString = $(this).val();
	    var stringAcentos = ('àâêôûãõáéíóúçüÀÂÊÔÛÃÕÁÉÍÓÚÇÜ');
	    var stringSemAcento = ('aaeouaoaeioucuAAEOUAOAEIOUCU');
	    
	    var i = new Number();
	    var j = new Number();
	    var cString = new String();
	    var varRes = '';
	    
	    for (i = 0; i < varString.length; i++) {
	        cString = varString.substring(i, i + 1);
	        for (j = 0; j < stringAcentos.length; j++) {
	            if (stringAcentos.substring(j, j + 1) == cString){
	                cString = stringSemAcento.substring(j, j + 1);
	            }
	        }
	        varRes += cString;        
	    }
	    varRes = varRes.replace( /\s/g, '_');
	    $("#nm_label").val(varRes.toLowerCase());
	});
});
function test(){	
	var valid = true;
	var nm_transacao = $("#nm_transacao").val();
	var nm_label = $("#nm_label").val();

	if (nm_transacao == '') {
		updateTips('O Campo Transacão está vázio');
		valid = false;
	}else if(nm_label == ''){
		updateTips('O Campo Label está vázio');
		valid = false;
	}else{
		$("#formInsertEdit").find("input").each(function(index){
			$(this).removeClass("ui-state-error");						
		});

		valid = valid && checkLength('nm_transacao', 'Transação', 2);
		valid = valid && checkLength('nm_label', 'Label', 2);
	}
	return valid;	
}	
</script>