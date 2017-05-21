<?php
defined('EXEC') or die();

if(!$auth->isAdmin()){
	Util::info(Config::AUTH_MESSAGE);
	return true;
}

//Importando a class javascript datatable
Controller::addHead('jquery.datatables.min');

//Importando classes
Loader::import('com.atitudeweb.SQL');
Loader::import('com.atitudeweb.TipoTransacao');

//Exclusão de vários ou um registro
if(isset($_POST['checkdel'])){	
	if($auth->isAdmin()){	
		execute("BEGIN;");
		execute("delete from tb_grupo_transacao where cd_grupo in(".implode(',', $_POST['checkdel']).");");
		execute("delete from tb_grupo where ci_grupo in(".implode(',', $_POST['checkdel']).");");
		if(execute("COMMIT;")){
			Util::notice('Grupos', 'Excluídos com sucesso!');			
		}
		else{
			Util::notice('Grupos', 'Erro ao excluir. O grupo já pode estar sendo utilizado por um usuário!', 'error');			
		}
	}
	else{
		Util::info(Config::AUTH_MESSAGE);
	}
}

//Alteração ou inclusão de um registro
if(isset($_GET['db']) && isset($_GET['form'])){
	
	$ci_grupo = $_GET['form'];
	$nm_grupo = addslashes($_POST['nm_grupo']);
	$ds_descricao = addslashes($_POST['ds_descricao']);
	$transacao = @$_POST['transacao'];
	
	execute("BEGIN;");
	
	if($_GET['form'] == 0){ //cadastro				
		execute("INSERT INTO tb_grupo(nm_grupo, ds_descricao) VALUES ('$nm_grupo', '$ds_descricao');");
		if($transacao){
			for($i=0;$i<count($transacao);$i++){
				$ci_transacao = $transacao[$i];
				$fl_inserir = (@$_POST['insert_'.$ci_transacao] ? "S" : "N");
				$fl_alterar = (@$_POST['update_'.$ci_transacao] ? "S" : "N");
				$fl_deletar = (@$_POST['delete_'.$ci_transacao] ? "S" : "N");
				execute("INSERT INTO tb_grupo_transacao(cd_grupo, cd_transacao, fl_inserir, fl_alterar, fl_deletar)
				VALUES (LAST_INSERT_ID(), $ci_transacao, '$fl_inserir', '$fl_alterar', '$fl_deletar'); ");			
			}
		}		
	}	
	elseif($_GET['form'] > 0){ //alteração	
		execute("UPDATE tb_grupo
				SET nm_grupo='$nm_grupo', ds_descricao='$ds_descricao'
				WHERE ci_grupo = $ci_grupo;");
		execute("DELETE FROM tb_grupo_transacao
				WHERE cd_grupo = $ci_grupo;");		
		if($transacao){
			for($i=0;$i<count($transacao);$i++){
				$ci_transacao = $transacao[$i];
				$fl_inserir = (@$_POST['insert_'.$ci_transacao] ? "S" : "N");
				$fl_alterar = (@$_POST['update_'.$ci_transacao] ? "S" : "N");
				$fl_deletar = (@$_POST['delete_'.$ci_transacao] ? "S" : "N");
				execute("INSERT INTO tb_grupo_transacao(cd_grupo, cd_transacao, fl_inserir, fl_alterar, fl_deletar)
				VALUES ($ci_grupo, $ci_transacao, '$fl_inserir', '$fl_alterar', '$fl_deletar'); ");			
			}
		}
	}
		
	if(execute("COMMIT;")){		
		Controller::setInfo('Grupo', 'Salvo com sucesso!');	
		Controller::redirect(Util::setLink(array('form=null', 'db=null')));	
	}
	else{
		Util::notice('Grupo', 'Ocorreu um erro!', 'error');	
	}	
}

if(isset($_GET['form'])){ //Formulário para adição ou alteração de registro
	if($_GET['form'] == 0){
		if(!$auth->isAdmin()){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}	
		
		//Selecionando todas as transacões possíveis do módulo
		$sqlPossiveis = "select ci_transacao, nm_transacao, ds_descricao, tp_tipo
		from tb_transacao
		order by nm_transacao";
		$queryPossiveis = query($sqlPossiveis);				
	}
	else{
		if(!$auth->isAdmin()){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}
		
		//Selecionando todas as transacões possíveis do módulo exceto as já selecionadas
		$sqlPossiveis = "select tt.ci_transacao, tt.nm_transacao, tt.ds_descricao, tt.tp_tipo
		from tb_transacao tt
		where tt.ci_transacao not in(select cd_transacao from tb_grupo_transacao where cd_grupo = ".$_GET['form'].")
		order by tt.nm_transacao";
		$queryPossiveis = query($sqlPossiveis);
		
		//Selecionando as transações já selecionadas
		$sqlSelecionadas = 'select tt.ci_transacao, tt.nm_transacao, tt.ds_descricao, tt.tp_tipo, tgt.fl_inserir, tgt.fl_alterar, tgt.fl_deletar
		from tb_grupo_transacao tgt
		inner join tb_transacao tt on(tgt.cd_transacao=tt.ci_transacao)
		where tgt.cd_grupo = '.$_GET['form'].'
		order by tt.nm_transacao asc';
		$querySelecionadas = query($sqlSelecionadas);
		
		$rowEdit = query('select * from tb_grupo where ci_grupo = '.$_GET['form'])->fetch();
	}
}
else{ //Consulta no banco para listagem dos registros
	
	$where = '';
	if(@$_POST['search1']){
		$term = addslashes($_POST['search1']);
		$where .=  "and nm_grupo ilike '%{$term}%' ";
	}
	if(@$_POST['search2']){
		$term = addslashes($_POST['search2']);
		$where .=  "and ds_descricao = {$term} ";
	}
	
	$sql = "select * from tb_grupo
	where 1=1 $where
	order by nm_grupo asc
	limit {$limitPagina} offset ".(($p - 1) * $limitPagina);
	$query = query($sql);
	$sqlNum = "select count(*) as num from tb_grupo where 1=1 $where";
	$rowNum = query($sqlNum)->fetch();
	$registros = $rowNum['num'];	
	$paginacao = Util::pagination($registros, 2);	
}

?>
<style>
tr.odd td.sorting_1 { background-color: #E2F2DE; }
tr.even td.sorting_1 { background-color: #F3F9F2; }
table.display { color:#111111; cursor:pointer; font-family: Verdana,Geneva,sans-serif; }
table.display td { padding: 3px; height: 25px; font-size:12px; }
table.display th { height: 25px; }
tr.odd { background-color: #E2F2DE; }
.dataTables_info { padding: 5px 0 0 0; }
.paginate_disabled_next, .paginate_enabled_next { margin-left: 10px; padding-right: 23px; }
.paginate_disabled_previous, .paginate_enabled_previous, .paginate_disabled_next, .paginate_enabled_next { color: #111111 !important; cursor: pointer; float: left; height: 19px; }
.dataTables_paginate { float: right; text-align: right; }
.paginate_disabled_previous, .paginate_disabled_next { color: #666666 !important; }
.paginate_enabled_next { background: url("assets/forward_enabled.png") no-repeat scroll right top transparent; }
.paginate_enabled_next:hover { background: url("assets/forward_enabled_hover.png") no-repeat scroll right top transparent; }
.paginate_disabled_next { background: url("assets/forward_disabled.png") no-repeat scroll right top transparent; }
.paginate_enabled_previous { background: url("assets/back_enabled.png") no-repeat scroll left top transparent; }
.paginate_enabled_previous:hover { background: url("assets/back_enabled_hover.png") no-repeat scroll left top transparent; }
.paginate_disabled_previous { background: url("assets/back_disabled.png") no-repeat scroll left top transparent; }
.paginate_disabled_previous, .paginate_enabled_previous { padding: 2px 0 0 20px; }
.paginate_disabled_next, .paginate_enabled_next { padding: 2px 20px 0 0; }
.sorting_asc { background: url("assets/sort_asc.png") no-repeat scroll right center transparent; }
.sorting_desc { background: url("assets/sort_desc.png") no-repeat scroll right center transparent; }
#box_possiveis, #box_selecionadas{ background-color: #F8FCF7; border: 1px solid #CDDED3; margin: 5px; padding: 0 5px 5px 5px; border-radius:4px; height: 455px; cursor:default; }
#box_possiveis h1, #box_selecionadas h1{ color:#037D44; font-size:18px; }
#box_possiveis input[type='checkbox'], #box_selecionadas input[type='checkbox']{ width:20px; height:20px; }
.drag_1{ border:2px dashed #327E04 !important; }
.drag_2{ background-color:#EDF7EA !important; }
.tabledrag { width:350px; cursor:pointer; font-family: Verdana,Geneva,sans-serif; }
.tabledrag td { height:19px; padding: 3px; }
</style>

	<div class="row bgGrey">
		<img src="assets/grupos.png"/>
		<span class="actiontitle">Grupos</span>
		<span class="actionview"> - <?php echo (!isset($_GET['form']) ? 'Pesquisa' : (@$_GET['form'] > 0 ? 'Edição' : 'Cadastro')); ?></span>
		<?php if(!isset($_GET['form'])){ ?>
			<button id="btAdd" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Novo</button>
			<button class="btn btn-info btn-sm pull-right" onclick="window.location='?page=acesso/grupo/organograma';" style="margin-right:5px"><span class="glyphicon glyphicon-th"></span> Organograma</button>
		<?php } else{ ?>		
			<button id="btVoltar" onclick="window.location='?page=acesso/grupo';" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-circle-arrow-left"></span> Voltar</button>
		<?php } ?>		
	</div>
	
	<!-- FORMULÁRIO DE PESQUISA -->
	<?php if(!isset($_GET['form'])){ ?>	
	
		<form action="<?php echo Util::setLink(array('p=null')); ?>" method="post">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-9">
						<label>Grupo:</label><input type="text" class="form-control" id="search1" name="search1" value="<?php echo @$_POST['search1']; ?>">
					</div>					
				</div>
				<div class="col-lg-6 col-lg-offset-3">
					<div class="col-lg-9">
						<label>Descrição:</label><input type="text" class="form-control" id="search2" name="search2" value="<?php echo @$_POST['search2']; ?>">
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
							<th>Grupo</th>
							<th>Descrição</th>
							<td></td>							
						</tr>
					</thead>
					<tbody>
						<?php 
						while($row = $query->fetch()){
						?>
						<tr>
							<td><input type="checkbox" name="checkdel[]" value="<?php echo $row['ci_grupo']; ?>" class="btCheck"></td>
							<td><?php echo $row['ci_grupo']; ?></td>
							<td><?php echo $row['nm_grupo']; ?></td>
							<td><?php echo $row['ds_descricao']; ?></td>
							<td class="text-center">
								<a href="javascript:void(0);" onclick="window.location='<?php echo Util::setLink(array('form='.$row['ci_grupo'])); ?>';">
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
					<div class="row">
					<div class="col-md-12">
						Grupo: *
						<input type="text" id="nm_grupo" name="nm_grupo" value="<?php echo @$rowEdit['nm_grupo']; ?>" maxlength="50" class="form-control"/></td>
					</div>
					</div>
					<div class="row">
					<div class="col-md-12">
						Descrição: *
						<input type="text" id="ds_descricao" name="ds_descricao" value="<?php echo @$rowEdit['ds_descricao']; ?>" maxlength="100" class="form-control"/></td>
					</div>
					</div>
				</div>
			</div>
			
			<legend>TRANSAÇÕES</legend>	
			<div class="row">
				<div class="col-md-6">
					<div id="box_possiveis">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td><h1>Possíveis:</h1></td>								
							</tr>
						</table>
						<table id="table_possiveis" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
							<thead>
								<tr>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while($row = $queryPossiveis->fetch()){
									echo '<tr idtransacao="'.$row['ci_transacao'].'" tptipo="'.$row['tp_tipo'].'" title="'.TipoTransacao::$tipos[$row['tp_tipo']].': '.$row['ds_descricao'].'"><td>'.$row['nm_transacao'].'</td></tr>';
								}
								?>
							</tbody>	
						</table>
					</div>
				</div>
				<div class="col-md-6">
					<div id="box_selecionadas">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td><h1>Pertencentes ao grupo:</h1></td>								
							</tr>
						</table>								
						<table id="table_selecionadas" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
							<thead>
								<tr>
									<th>&nbsp;</th>									
									<th style="width:30px;" align="center"><span class="glyphicon glyphicon-plus-sign" style="color:green;"></span></th>
									<th style="width:30px;" align="center"><span class="glyphicon glyphicon-pencil" style="color:blue;"></span></th>
									<th style="width:30px;" align="center"><span class="glyphicon glyphicon-remove" style="color:red;"></span></th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(@$querySelecionadas){
									while($row = $querySelecionadas->fetch()){
										echo '<tr idtransacao="'.$row['ci_transacao'].'" tptipo="'.$row['tp_tipo'].'" title="'.TipoTransacao::$tipos[$row['tp_tipo']].': '.$row['ds_descricao'].'">
											<td>'.$row['nm_transacao'].'</td>
											<td><input type="hidden" name="transacao[]" value="'.$row['ci_transacao'].'" />';												
										if($row['tp_tipo'] == TipoTransacao::CADASTRO){
											echo '<input type="checkbox" name="insert_'.$row['ci_transacao'].'" value="1" '.($row['fl_inserir'] == 'S' ? 'checked="checked"' : '').'/></td>
												<td><input type="checkbox" name="update_'.$row['ci_transacao'].'" value="1" '.($row['fl_alterar'] == 'S' ? 'checked="checked"' : '').'/></td>
												<td><input type="checkbox" name="delete_'.$row['ci_transacao'].'" value="1" '.($row['fl_deletar'] == 'S' ? 'checked="checked"' : '').'/></td>';
										}
										elseif($row['tp_tipo'] == TipoTransacao::SOMENTE_INCLUSAO){
											echo '<input type="checkbox" name="insert_'.$row['ci_transacao'].'" value="1" '.($row['fl_inserir'] == 'S' ? 'checked="checked"' : '').'/></td>
												<td></td>
												<td></td>';												
										}
										elseif($row['tp_tipo'] == TipoTransacao::SOMENTE_ALTERACAO){
											echo '</td>
												<td><input type="checkbox" name="update_'.$row['ci_transacao'].'" value="1" '.($row['fl_alterar'] == 'S' ? 'checked="checked"' : '').'/></td>
												<td></td>';												
										}
										elseif($row['tp_tipo'] == TipoTransacao::SOMENTE_EXCLUSAO){
											echo '</td>
												<td></td>
												<td><input type="checkbox" name="delete_'.$row['ci_transacao'].'" value="1" '.($row['fl_deletar'] == 'S' ? 'checked="checked"' : '').'/></td>';												
										}
										else{
											echo '</td>
												<td></td>
												<td></td>';												
										}
										echo '</tr>';
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div id="boxRowsHidden" style="display:none;"></div>				
				
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
var table_possiveis;
var table_selecionadas;
var objConfigTable = { 	"bLengthChange": false,
						"oLanguage": {
							"sInfo": "Mostrando _START_ até _END_<br> _TOTAL_ registros",
							"sInfoFiltered": " - filtrando de _MAX_ registros",
							"sSearch": "",
							"oPaginate": {
								"sNext": "Próximo",
								"sPrevious": "Anterior"
							},
							"sZeroRecords": "NENHUM REGISTRO ENCONTRADO...",
							"sInfoEmpty": ""
						}
					};	

function test(){	
	var valid = true;
	$("#formInsertEdit").find("input,select").each(function(index){
		$(this).removeClass("ui-state-error");						
	});	
	valid = checkLength('nm_grupo', 'Grupo', 2) && valid;
	if($('#fl_nivel_acesso').val() == 0){
		$('#fl_nivel_acesso').addClass("ui-state-error").focus();
		valid = false;	
		updateTips('Selecione corretamente um Nível de Acesso.');
	}	
	
	var inputs = $(table_selecionadas.fnGetHiddenTrNodes()).find('input');
	$("#boxRowsHidden").append(inputs);
	return valid;	
}
function dragRowTable(tableID, addExtraClass){	
	$("#" + tableID + " tbody tr").not(".ui-draggable").each(function(){
		if($(this).children().hasClass("dataTables_empty"))
			return;
						
		$(this).addClass(addExtraClass);								
		$(this).draggable({
			helper: function(){
				var index = $("#" + tableID).dataTable().fnGetPosition(this);
				var temp = $(this).clone();
				temp.find("td:gt(0)").remove();
				return $("<table class='tabledrag' rowindex='" + index + "'></table>").append(temp);
			},
			appendTo: "body",
			revert: "invalid",
			opacity: 0.9
		});
	});
}
$(function() {	
	objConfigTable['fnDrawCallback'] = function(oSettings){ dragRowTable('table_possiveis', 'transacao_add'); };
	table_possiveis = $('#table_possiveis').dataTable(objConfigTable);	
	objConfigTable['aoColumnDefs'] = [ {"bSortable":false, "aTargets":[1, 2, 3] } ];		
	objConfigTable['fnDrawCallback'] = function(oSettings){ dragRowTable('table_selecionadas', 'transacao_added'); };
	table_selecionadas = $('#table_selecionadas').dataTable(objConfigTable);			
	$("#box_selecionadas").droppable({
		activeClass: "drag_1",
		hoverClass: "drag_2",
		accept: ".transacao_add",
		drop: function(event, ui){		
			var index = ui.helper.attr("rowindex");
			var idtransacao = ui.draggable.attr("idtransacao");
			var tptipo = ui.draggable.attr("tptipo");
			var title = ui.draggable.attr("title");
			
			if(tptipo == 1)			
				var a = table_selecionadas.fnAddData([ui.draggable.text().trim(),"<td><input type='hidden' name='transacao[]' value='" + idtransacao + "' /><input type='checkbox' name='insert_" + idtransacao + "' value='1' /></td>","<td><input type='checkbox' name='update_" + idtransacao + "' value='1' /></td>","<td><input type='checkbox' name='delete_" + idtransacao + "' value='1' /></td>"]);
			else if(tptipo == 2)
				var a = table_selecionadas.fnAddData([ui.draggable.text().trim(),"<td><input type='hidden' name='transacao[]' value='" + idtransacao + "' /><input type='checkbox' name='insert_" + idtransacao + "' value='1' /></td>","<td></td>","<td></td>"]);
			else if(tptipo == 3)
				var a = table_selecionadas.fnAddData([ui.draggable.text().trim(),"<td><input type='hidden' name='transacao[]' value='" + idtransacao + "' /></td>","<td><input type='checkbox' name='update_" + idtransacao + "' value='1' /></td>","<td></td>"]);
			else if(tptipo == 4)
				var a = table_selecionadas.fnAddData([ui.draggable.text().trim(),"<td><input type='hidden' name='transacao[]' value='" + idtransacao + "' /></td>","<td></td>","<td><input type='checkbox' name='delete_" + idtransacao + "' value='1' /></td>"]);
			else
				var a = table_selecionadas.fnAddData([ui.draggable.text().trim(),"<td><input type='hidden' name='transacao[]' value='" + idtransacao + "' /></td>","<td></td>","<td></td>"]);
			
			table_possiveis.fnDeleteRow(index);			
			var nTr = table_selecionadas.fnSettings().aoData[ a[0] ].nTr;
			$(nTr).attr("idtransacao", idtransacao);
			$(nTr).attr("tptipo", tptipo);
			$(nTr).attr("title", title);
		}
	});
	$("#box_possiveis").droppable({
		activeClass: "drag_1",
		hoverClass: "drag_2",
		accept: ".transacao_added",
		drop: function(event, ui){				
			var index = ui.helper.attr("rowindex");
			var idtransacao = ui.draggable.attr("idtransacao");	
			var tptipo = ui.draggable.attr("tptipo");
			var title = ui.draggable.attr("title");
			var a = table_possiveis.fnAddData([ui.draggable.text().trim()]);	
			table_selecionadas.fnDeleteRow(index);				
			var nTr = table_possiveis.fnSettings().aoData[ a[0] ].nTr;
			$(nTr).attr("idtransacao", idtransacao);
			$(nTr).attr("tptipo", tptipo);
			$(nTr).attr("title", title);
		}
	});
	$("#nm_grupo").keyup(function(){
		$(this).val($(this).val().toUpperCase());	
	});
});
</script>