<?php
defined('EXEC') or die();

if(!$auth->isAdmin()){
	Util::info(Config::AUTH_MESSAGE);
	return true;
}

//Importando a class do organograma
Controller::addHead('primitives.min');
Controller::addHead('primitives.latest', 'css');

//Importando classes
Loader::import('com.atitudeweb.TipoTransacao');

$queryGrupos = query('select ci_grupo, nm_grupo, ds_descricao from tb_grupo order by nm_grupo');

function findDescr($num){
	if($num == 0){
		return 'Nenhum encontrado';
	}
	elseif($num == 1){
		return '1 encontrado';
	}
	else{
		return $num.' encontrados';
	}
}

$indexCor = -1;
$cores = array('#4b0082', '#C57F7F', '#32cd32', '#ffa500', '#E64848', '#808000', '#008b8b', '#B800E6');

function getCor(){
	global $indexCor, $cores;
	return $cores[$indexCor];
}
function nextCor(){
	global $indexCor, $cores;
	if(count($cores) == ($indexCor + 1)){
		$indexCor = 0;
	}
	else{	
		$indexCor++;
	}	
	return $cores[$indexCor];
}
?>

	<div class="row bgGrey">
		<img src="assets/grupos.png"/>
		<span class="actiontitle">Grupos <?php echo Config::SYSTEM; ?></span>
		<span class="actionview"> - Organograma</span>
		<button id="btVoltar" onclick="window.location='?page=acesso/grupo';" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-circle-arrow-left"></span> Voltar</button>
	</div>
	
	<div id="centerpanel" style="overflow: hidden; padding: 0px; margin: 0 auto; border: 0px; width:100%; height:500px;"></div>

<script type="text/javascript">
<?php if($queryGrupos->rowCount() < 1){ ?>
var data = null;
<?php } else{ 
echo '
var data = { description: "'.findDescr($queryGrupos->rowCount()).'",
	templateName: "rootTemplate",
	title: "Grupos",
	items: [ ';
	while($row = $queryGrupos->fetch()){
		echo '{ description: "'.$row['ds_descricao'].'",
		itemTitleColor: "'.nextCor().'",
		templateName: "groupTemplate",
		title: "'.$row['nm_grupo'].'",
		items: [ ';
		$queryTran = query('select tt.ci_transacao, tt.tp_tipo, tt.nm_transacao, tt.ds_descricao from tb_grupo_transacao tgt 
		inner join tb_transacao tt on(tgt.cd_transacao=tt.ci_transacao)
		where tgt.cd_grupo = '.$row['ci_grupo'].' order by tt.nm_transacao');
		while($row1 = $queryTran->fetch()){
			echo '{ description: "'.TipoTransacao::$tipos[$row1['tp_tipo']].': '.$row1['ds_descricao'].'",
					itemTitleColor: "'.getCor().'",
					templateName: "transactionTemplate",
					title: "'.$row1['nm_transacao'].'",';
			if($row1['tp_tipo'] == TipoTransacao::FUNCIONALIDADE){
				echo 'add:0,
					edit:0,
					del:0
				},';			
			}
			else{	
				$rowTran = query('select fl_inserir, fl_alterar, fl_deletar from tb_grupo_transacao where cd_grupo = '.$row['ci_grupo'].' and cd_transacao = '.$row1['ci_transacao'])->fetch();			
				echo 'add:'.($rowTran['fl_inserir'] == 'S' ? 1 : 0).',
					edit:'.($rowTran['fl_alterar'] == 'S' ? 1 : 0).',
					del:'.($rowTran['fl_deletar'] == 'S' ? 1 : 0).'
				},';
			}
		}
		
		$rowNumProf = query('select count(tu.ci_usuario) as count
		from tb_usuario_grupo tug 
		inner join tb_usuario tu on(tug.cd_usuario=tu.ci_usuario)
		where tug.cd_grupo = '.$row['ci_grupo'])->fetch();
		if($rowNumProf['count'] > 0){		
			echo '{ description: "'.findDescr($rowNumProf['count']).'",
					itemTitleColor: "'.getCor().'",
					templateName: "rootTemplate",
					title: "Usuários",
					items: [ ';
			
			/*$queryProfissoes = query('select tpro.ci_profissao, tpro.nm_profissao, count(tu.ci_usuario)
			from tb_usuario_grupo tug 
			inner join tb_usuario tu on(tug.cd_usuario=tu.ci_usuario)
			where tug.cd_grupo = '.$row['ci_grupo'].'			  
			group by 1,2
			order by 2');
			while($row1 = $queryProfissoes->fetch()){
				echo '{ description: "'.findDescr($row1['count']).'",
						itemTitleColor: "'.getCor().'",
						templateName: "rootTemplate",
						title: "'.$row1['nm_profissao'].'",
						items: [ ';*/
				
				$queryUsers = query('select tu.ci_usuario, tu.nm_usuario, tu.ds_email, tu.nm_login, tu.fl_ativo, tu.tp_nivelacesso
				from tb_usuario_grupo tug 
				inner join tb_usuario tu on(tug.cd_usuario=tu.ci_usuario)
				where tug.cd_grupo = '.$row['ci_grupo']);
				while($row2 = $queryUsers->fetch()){
					echo '{ cpf: "'.$row2['nm_login'].'",
							localidade: "'.($row2['fl_ativo'] == 't' ? 'ATIVADO' : 'DESATIVADO').'",
							description: "'.$row2['ds_email'].'",
							itemTitleColor: "'.getCor().'",
							templateName: "userTemplate",';
					if($row2['tp_nivelacesso'] == NivelAcesso::ADMINISTRADOR){
						echo 'groupTitleColor: "Green",
							groupTitle: "Administrador",';
					}
					echo 'title: "'.$row2['nm_usuario'].'"
					},';
				}
				//echo ']}, ';
			//}					
			echo ']}';
		}		
		echo '] }, ';		
	}	
echo ']};';
}
?>

var orgDiagram = null;
var treeItems = {};
var contextidcounter = 0;
var currentHighlightDataTreeItem = null;
var currentCursorDataTreeItem = null;
$(function () {
	$('#conteudo').css({ width: "95%" });
	$(window).resize(function(){
		$('#centerpanel').css({ height: ($(this).height() - 250) + "px" });
		$("#centerpanel").orgDiagram("update");
	});
	$.ajaxSetup({
		cache: false
	});
	orgDiagram = $("#centerpanel").orgDiagram(GetOrgDiagramConfig());
	var rootItem = getTreeItem(data);
	$("#centerpanel").orgDiagram("option", {
		rootItem: rootItem,
		cursorItem: rootItem
	});
	$('#centerpanel').css({ height: ($(window).height() - 250) + "px" });
	$("#centerpanel").orgDiagram("update");
});
function GetOrgDiagramConfig() {
	var templates = [getRootTemplate(), getGroupTemplate(), getTransactionTemplate(), getUserTemplate()];
	return {
		pageFitMode: 3,
		orientationType: 0,
		horizontalAlignment: 0,
		connectorType: 2,
		minimalVisibility: 2,
		hasSelectorCheckbox: 2,
		selectionPathMode: 1,
		leavesPlacementType: 2,
		templates: templates,
		onItemRender: onTemplateRender,
		itemTitleFirstFontColor: primitives.common.Colors.White,
		itemTitleSecondFontColor: primitives.common.Colors.White				
	};
}
function getRootTemplate() {
	var result = new primitives.orgdiagram.TemplateConfig();
	result.name = "rootTemplate";
	result.itemSize = new primitives.common.Size(120, 50);
	result.minimizedItemSize = new primitives.common.Size(3, 3);
	result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);
	var itemTemplate = jQuery(
	  '<div class="bp-item bp-corner-all bt-item-frame">'
		+ '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 115px; height: 20px;">'
			+ '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 100px; height: 18px;">'
			+ '</div>'
		+ '</div>'
		+ '<div name="description" class="bp-item" style="top: 28px; left: 10px; width: 110px; height: 18px; font-size: 10px;"></div>'
	+ '</div>'
	).css({
		width: result.itemSize.width + "px",
		height: result.itemSize.height + "px"
	});
	result.itemTemplate = itemTemplate.wrap('<div>').parent().html();
	return result;
}		
function getGroupTemplate() {
	var result = new primitives.orgdiagram.TemplateConfig();
	result.name = "groupTemplate";
	result.itemSize = new primitives.common.Size(270, 90);
	result.minimizedItemSize = new primitives.common.Size(3, 3);
	result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);
	var itemTemplate = jQuery(
	  '<div class="bp-item bp-corner-all bt-item-frame">'
		+ '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 264px; height: 20px;">'
			+ '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 258px; height: 18px;">'
			+ '</div>'
		+ '</div>'
		+ '<div name="description" class="bp-item" style="top: 28px; left: 10px; width: 258px; height: 56px; font-size: 10px;"></div>'
	+ '</div>'
	).css({
		width: result.itemSize.width + "px",
		height: result.itemSize.height + "px"
	});
	result.itemTemplate = itemTemplate.wrap('<div>').parent().html();
	return result;
}		
function getTransactionTemplate() {
	var result = new primitives.orgdiagram.TemplateConfig();
	result.name = "transactionTemplate";
	result.itemSize = new primitives.common.Size(270, 110);
	result.minimizedItemSize = new primitives.common.Size(3, 3);
	result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);
	var itemTemplate = jQuery(
	  '<div class="bp-item bp-corner-all bt-item-frame">'
		+ '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 264px; height: 20px;">'
			+ '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 258px; height: 18px;">'
			+ '</div>'
		+ '</div>'
		+ '<div name="description" class="bp-item" style="top: 28px; left: 10px; width: 258px; height: 55px; font-size: 10px;"></div>'
		+ '<div class="bp-item" style="top:80px; left:200px; width:200px; height:30px;"><img name="add" src="assets/icon_add.png"/><img name="del" src="assets/icon_del.png"/><img name="edit" src="assets/icon_edit.png"/></div>'
	+ '</div>'
	).css({
		width: result.itemSize.width + "px",
		height: result.itemSize.height + "px"
	});
	result.itemTemplate = itemTemplate.wrap('<div>').parent().html();
	return result;
}
function getUserTemplate() {
	var result = new primitives.orgdiagram.TemplateConfig();
	result.name = "userTemplate";
	result.itemSize = new primitives.common.Size(225, 100);
	result.minimizedItemSize = new primitives.common.Size(3, 3);
	result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);
	var itemTemplate = jQuery(
	  '<div class="bp-item bp-corner-all bt-item-frame">'
		+ '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 220px; height: 20px;">'
			+ '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 215px; height: 18px;">'
			+ '</div>'
		+ '</div>'
		+ '<div class="bp-item" style="top: 26px; left: 5px; width: 220px; height: 18px; font-size: 12px;">Login:</div><div name="cpf" class="bp-item" style="top: 26px; left: 50px; width: 220px; height: 18px; font-size: 12px;"></div>'
		+ '<div class="bp-item" style="top: 44px; left: 5px; width: 220px; height: 18px; font-size: 12px;">Email:</div><div name="description" class="bp-item" style="top: 44px; left: 50px; width: 200px; height: 33px; font-size: 10px;"></div>'
		+ '<div class="bp-item" style="top: 62px; left: 5px; width: 220x; height: 18px; font-size: 12px;">Status:</div><div name="localidade" class="bp-item" style="top: 62px; left: 50px; width: 220x; height: 18px; font-size: 12px;"></div>'
	+ '</div>'
	).css({
		width: result.itemSize.width + "px",
		height: result.itemSize.height + "px"
	});
	result.itemTemplate = itemTemplate.wrap('<div>').parent().html();
	return result;
}

function onTemplateRender(event, data) {
	var itemConfig = data.context;
	data.element.find("[name=titleBackground]").css({ "background": itemConfig.itemTitleColor });
	var fields = ["title", "description", "cpf", "localidade"];
	for (var index = 0; index < fields.length; index += 1) {
		var field = fields[index];
		data.element.find("[name=" + field + "]").text(itemConfig[field]);
	}		
	switch (data.templateName) {
		case "transactionTemplate":
			if(itemConfig.add) 
				data.element.find("[name=add]").show();
			else
				data.element.find("[name=add]").hide();
				
			if(itemConfig.edit) 
				data.element.find("[name=edit]").show();
			else
				data.element.find("[name=edit]").hide();
				
			if(itemConfig.del) 
				data.element.find("[name=del]").show();
			else
				data.element.find("[name=del]").hide();
		break;
	}
}		
var fields = ["title", "description", "cpf", "localidade", "add", "edit", "del", "itemTitleColor", "groupTitle", "groupTitleColor", "templateName"];
function getTreeItem(sourceItem) {
	var result = new primitives.orgdiagram.ItemConfig();
	result.label = sourceItem.title;
	for (var index = 0; index < fields.length; index += 1) {
		var field = fields[index];
		if (sourceItem[field] != null) {
			result[field] = sourceItem[field];
		}
	}
	if (sourceItem.items != null) {
		for (var index = 0; index < sourceItem.items.length; index += 1) {
			result.items.push(getTreeItem(sourceItem.items[index]));
		}
	}
	return result;
}
</script>