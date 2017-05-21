<?php
defined('EXEC') or die();

if(!$auth->isRead('instituicao')){
	Util::info(Config::AUTH_MESSAGE);
	return true;
}

//Remove Fotos
if(@$_GET['removeFoto']){
	$ci_foto = $_GET['removeFoto'];
	
	$sql = "select * from tb_foto where ci_foto = $ci_foto";
	$foto = query($sql)->fetch();
	$pathRemove = $foto['ds_path'].$ci_foto.'.jpg';
	$pathRemoveThumb = $foto['ds_path'].$ci_foto.'_thumb.jpg';
	$sql = "delete from tb_foto where ci_foto = $ci_foto";
	execute($sql);	
	@unlink($pathRemove);
	@unlink($pathRemoveThumb);	
}

//Fotos
if(@$_POST['upload']){
	
	$cd_local = $_GET['form'];
	$cd_usuario = $user['ci_usuario'];
	
	$sql = "insert into tb_foto (cd_local, cd_usuario, ds_path, ds_hash) values($cd_local, $cd_usuario, '', '')";
	if(execute($sql)) {
		
		$filename = lastInsertId();		
		Loader::import('com.atitudeweb.UploadImage');
		$up = new UploadImage($filename);
		$uploadInfo = $up->upload('foto');
		if($up->check()) {
			$ds_path = $uploadInfo['path'];
			$ds_hash = $uploadInfo['hash'];
			$sql = "update tb_foto set ds_path = '$ds_path', ds_hash = '$ds_hash' where ci_foto = $filename";
			execute($sql);
		}
		else{
			execute("delete from tb_foto where ci_foto = $filename");
			foreach($uploadInfo['error'] as $key=>$error){
				Util::notice('Instituição', $error, 'error');
			}
		}
	}
	else{
		Util::notice('Instituição', 'Erro ao cadastrar foto!', 'error');
	}
}

//Modalidades
if(@$_POST['modalidades']){
	
	$cd_local = $_GET['form'];
	$mod = $_POST['mod'];	
	
	$sql = "delete from tb_local_modalidade where cd_local = $cd_local";
	if(execute($sql)){
		
		execute("BEGIN;");		
		for($i=0;$i<count($mod);$i++){
			$cd_modalidade = $mod[$i];
			$sql = "insert into tb_local_modalidade (cd_local, cd_modalidade) values($cd_local, $cd_modalidade);";
			execute($sql);
		}		
		if(execute("COMMIT;")){
			Util::notice('Instituição', 'Modalidades vinculadas com sucesso!');			
		}
		else{
			Util::notice('Instituição', 'Erro ao vincular as modalidades!', 'error');			
		}
		
	}
	else{
		Util::notice('Instituição', 'Ocorreu um erro ao manipular as modalidades!', 'error');	
	}	
	
}

//Alteração ou inclusão de um registro
if(isset($_GET['db']) && isset($_GET['form'])){	
	
	$ci_local = $_GET['form'];
	$cd_usuario_owner_edit = $user['ci_usuario'];
	$nm_local = addslashes($_POST['nm_local']);
	$ds_local = addslashes($_POST['ds_local']);	
	$ds_contato = addslashes($_POST['ds_contato']);
	$nm_rua = addslashes($_POST['nm_rua']);
	$nr_rua_numero = addslashes($_POST['nr_rua_numero']);
	$nm_bairro = addslashes($_POST['nm_bairro']);
	$nr_cep = addslashes($_POST['nr_cep']);
	$nr_lat = addslashes($_POST['nr_lat']);
	$nr_lng = addslashes($_POST['nr_lng']);
	$fl_ativo = addslashes($_POST['fl_ativo']);
		
	if($_GET['form'] == 0){ //cadastro	
		$sql = "INSERT INTO tb_local(nm_local, ds_local, ds_contato, nm_rua, nr_rua_numero, nm_bairro, nr_cep, nr_lat, nr_lng, cd_usuario_owner, fl_ativo)
		VALUES ('$nm_local', '$ds_local', '$ds_contato', '$nm_rua', '$nr_rua_numero', '$nm_bairro', '$nr_cep', '$nr_lat', '$nr_lng', $cd_usuario_owner_edit, $fl_ativo);";
	}	
	elseif($_GET['form'] > 0){ //alteração
		$sql = "UPDATE tb_local SET nm_local='$nm_local', ds_local='$ds_local', ds_contato='$ds_contato', nm_rua='$nm_rua', nr_rua_numero='$nr_rua_numero', nm_bairro='$nm_bairro', nr_cep='$nr_cep', nr_lat='$nr_lat', nr_lng='$nr_lng', cd_usuario_edit=$cd_usuario_owner_edit, fl_ativo=$fl_ativo, dt_edit=now() WHERE ci_local = $ci_local;"; 
	}
	
	//echo $sql; exit;
	if(execute($sql)){
		Controller::setInfo('Instituição', 'Salva com sucesso!');	
		Controller::redirect(Util::setLink(array('form=null', 'db=null')));	
	}
	else{
		Util::notice('Instituição', 'Ocorreu um erro!', 'error');	
	}		
}

if(isset($_GET['form'])){ //Formulário para adição ou alteração de registro
	if($_GET['form'] == 0){
		if(!$auth->isRead('instituicao')){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}		
	}
	else{
		if(!$auth->isRead('instituicao')){
			Util::info(Config::AUTH_MESSAGE);
			return true;
		}
		$rowEdit = query("select * from tb_local where ci_local = ".$_GET['form'])->fetch();
		$modalidades = query("select * from tb_modalidade order by 2 asc");
		$modalidadesSelect = query("select cd_modalidade from tb_local_modalidade where cd_local = ".$_GET['form'])->fetchAll();		
		$fotos = query("select * from tb_foto where cd_local = ".$_GET['form'])->fetchAll();
	}
}
else{ //Consulta no banco para listagem dos registros
	$where = '';
	if(@$_POST['search1']){
		$term = addslashes($_POST['search1']);
		$where .= "and ci_local = {$term} ";			
	}
	if(@$_POST['search2']){
		$term = addslashes($_POST['search2']);
		$where .= "and nm_local like '%{$term}%' ";			
	}
		
	$sql = "select *
	from tb_local where 1=1 $where
	order by 2
	limit {$limitPagina} offset ".(($p - 1) * $limitPagina);
	$query = query($sql);
	$sqlNum = "select count(*) as num from tb_local
	where 1=1 $where";
	$rowNum = query($sqlNum)->fetch();
	$registros = $rowNum['num'];	
	$paginacao = Util::pagination($registros, 1);	
}

?>

<style type="text/css">
	#map-canvas { width:100%; height: 300px; }		
	#pac-input {
	  background-color: #fff;
	  font-family: Roboto;
	  font-size: 15px;
	  font-weight: 300;
	  margin-top:10px;
	  margin-left: 12px;		  
	  padding: 0 11px 0 13px;
	  text-overflow: ellipsis;
	  width: 300px;
	  display:none;
	}
	#pac-input:focus {
	  border-color: #4d90fe;
	}
	.pac-container {
	  font-family: Roboto;
	}
</style>

	<div class="row bgGrey">
		<img src="assets/instituicoes.png"/>
		<span class="actiontitle">Instituições</span>
		<span class="actionview"> - <?php echo (!isset($_GET['form']) ? 'Pesquisa' : (@$_GET['form'] > 0 ? 'Edição' : 'Cadastro')); ?></span>
		<?php if(!isset($_GET['form'])){ ?>
			<button type="button" id="btAdd" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Novo</button>   
		<?php } else{ ?>		
			<button id="btVoltar" onclick="window.location='?page=instituicao';" class="btn btn-info btn-sm pull-right"><span class="glyphicon glyphicon-circle-arrow-left"></span> Voltar</button>
		<?php } ?>		
	</div>
	
	<!-- FORMULÁRIO DE PESQUISA -->
	<?php if(!isset($_GET['form'])){ ?>	
	
		<form action="<?php echo Util::setLink(array('p=null')); ?>" method="post">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="col-md-2">
						<label>ID:</label><input type="text" class="form-control input-mask-numbers" id="search1" name="search1" value="<?php echo @$_POST['search1']; ?>" maxlength="14">
					</div>
					<div class="col-md-8">
						<label>Instituição:</label><input type="text" class="form-control" id="search2" name="search2" value="<?php echo @$_POST['search2']; ?>">
					</div>					
					<div class="col-md-2">
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
							<th>Instituição</th>
							<th>Endereço</th>
							<th>Contato</th>
							<th>CEP</th>
							<th>Ativo?</th>
							<td></td>							
						</tr>
					</thead>
					<tbody>
						<?php 
						while($row = $query->fetch()){
						?>
						<tr>
							<td><?php echo $row['ci_local']; ?></td>
							<td><?php echo $row['nm_local']; ?></td>
							<td><?php echo $row['nm_rua'].' Nº '.$row['nr_rua_numero']; ?></td>
							<td><?php echo $row['ds_contato']; ?></td>
							<td><?php echo $row['nr_cep']; ?></td>
							<td><?php echo ($row['fl_ativo'] == 1 ? '<font color="green">SIM</font>' : '<font color="red">NÃO</font>'); ?></td>
							<td class="text-center">
								<a href="javascript:void(0);" onclick="window.location='<?php echo Util::setLink(array('form='.$row['ci_local'])); ?>';">
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
				<div class="col-md-6">
					
					<br><h4>Dados Gerais</h4>
					
					<div class="row">
						<div class="col-md-12">
							Instituição: *
							<input type="text" id="nm_local" name="nm_local" value="<?php echo @$rowEdit['nm_local']; ?>" maxlength="100" class="form-control"/>
						</div>
						<div class="col-md-12">
							Descrição: *
							<textarea id="ds_local" name="ds_local" class="form-control"><?php echo @$rowEdit['ds_local']; ?></textarea>							
						</div>
						<div class="col-md-9">
							Contatos: * 
							<input type="text" id="ds_contato" name="ds_contato" value="<?php echo @$rowEdit['ds_contato']; ?>" maxlength="200" class="form-control" placeholder="Contato 1 (85)99988-5566, Contato2 (85)98811-5566"/>							
						</div>						
						<div class="col-md-3">
							Ativo?: * 
							<select id="fl_ativo" name="fl_ativo" class="form-control">
								<option value="true" <?php echo (@$rowEdit['fl_ativo'] == 1 ? 'selected="selected"' : ''); ?>>SIM</option>
								<option value="false" <?php echo (@$rowEdit['fl_ativo'] == 0 ? 'selected="selected"' : ''); ?>>NÃO</option>
							</select>
						</div>						
					</div>
					
					<br><h4>Endereço</h4>				
						
					<div class="row">
						<div class="col-md-12">
							Endereço: *
							<input type="text" id="nm_rua" name="nm_rua" value="<?php echo @$rowEdit['nm_rua']; ?>" maxlength="200" class="form-control"/>
						</div>
						<div class="col-md-8">
							Bairro: 
							<input type="text" id="nm_bairro" name="nm_bairro" value="<?php echo @$rowEdit['nm_bairro']; ?>" maxlength="200" class="form-control"/>
						</div>
						<div class="col-md-4">
							Número: *
							<input type="text" id="nr_rua_numero" name="nr_rua_numero" value="<?php echo @$rowEdit['nr_rua_numero']; ?>" maxlength="10" class="form-control input-mask-numbers"/>
						</div>
						<div class="col-md-4">
							CEP: *
							<input type="tel" id="nr_cep" name="nr_cep" value="<?php echo @$rowEdit['nr_cep']; ?>" maxlength="8" class="form-control input-mask-numbers"/>
						</div>
						<div class="col-md-4">
							Latitude: 
							<input type="text" id="nr_lat" name="nr_lat" value="<?php echo @$rowEdit['nr_lat']; ?>" maxlength="100" class="form-control" readonly/>
						</div>
						<div class="col-md-4">
							Longitude: 
							<input type="text" id="nr_lng" name="nr_lng" value="<?php echo @$rowEdit['nr_lng']; ?>" maxlength="100" class="form-control" readonly/>
						</div>
					</div>
						
					<br><h4>Mapa</h4>
					
					<input id="pac-input" class="form-control control" type="text" placeholder="Local ou Cidade" style="font-family: 'Lato', sans-serif !important;">
					<div id="map-canvas"></div>					
					
					<br><br>
					
					<div class="row">
						<div class="col-md-6 col-md-offset-3 text-center">
							<button id="btInsertEdit" type="submit" class="btn btn-success text-center"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar Instituição, Dados Gerais e Endereço</button>
							<img class="loader" src="assets/loading.gif"/>
						</div>
					</div>
					
				</div>
				
			</form>
			
			
				<div class="col-md-6">
			
			<!-- FORMULÁRIO DE MODALIDADES -->
			<form method="post">
			
					<input type="hidden" name="modalidades" value="1"/>
			
					<br><h4>Modalidades</h4>
					
					<?php
						if($_GET['form'] == 0){ //cadastro	
					?>
										
					<br><div class="alert alert-info">
						<p>É necessário cadastrar a instituição para selecionar as modalidades!</p>
					</div>	
					
					<?php
						} else {
					?>

					<div class="row">
						<?php 
							$count = 0;
							while($mod = $modalidades->fetch()){
								
								$checked = false;
								foreach($modalidadesSelect as $key=>$modSel){
									if($modSel['cd_modalidade'] == $mod['ci_modalidade']){
										$checked = true;
										break;
									}
								}
								
								echo '<div class="col-md-3"><label><input type="checkbox" name="mod[]" value="'.$mod['ci_modalidade'].'" '.($checked ? 'checked="checked"' : '').'/> '.$mod['nm_modalidade'].'</label></div>';
								$count++;
								if($count == 4){
									echo '<div class="clearfix visible-md-block visible-lg-block"></div>';
									$count = 0;
								}
							}
						?>						
					</div>
					<br>					
					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar Modalidades</button>
						</div>
					</div>
					
			</form>			
					
					<?php
						}
					?>
					
					<br><h4>Fotos</h4>
					
					<?php
						if($_GET['form'] == 0){ //cadastro	
					?>
					
					<br><div class="alert alert-info">
						<p>É necessário cadastrar a instituição para enviar as fotos!</p>
					</div>					
					
					<?php
						} else {
					?>
					
					<br>
					
					<?php 
						if(count($fotos) > 0) { 
							echo '<div class="row"><div class="col-md-12 text-center">';
							foreach($fotos as $key=>$foto){
								echo '<a href="'.$foto['ds_path'].$foto['ci_foto'].'.jpg" target="_blank"><img src="'.$foto['ds_path'].$foto['ci_foto'].'_thumb.jpg" width="100" style="margin:10px;"/></a>
								<a href="'.Util::setLink(array('removeFoto='.$foto['ci_foto'])).'" onclick="return confirm(\'Tem certeza que deseja excluir esta foto?\');" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a>';
							}
							echo '</div></div>';
						}
						else {
					?>
					
						<div class="alert alert-warning">
							<p>Nenhuma foto encontrada! Por favor envie no mínimo 3 fotos!</p>
						</div>
					
					<?php
						}
					?>	
						
					<br>
					
			<!-- FORMULÁRIO DE FOTOS -->
			<form method="post" enctype="multipart/form-data">
			
					<input type="hidden" name="upload" value="1"/>
					<div class="row">
						<div class="col-md-9">					
							Selecione a foto (JPG, 6MP, 2816 x 2112):
							<input type="file" name="foto" class="form-control"/>
						</div>
						<div class="col-md-3">
							<button class="btn btn-success" style="margin-top:20px; width:100%;"><span class="glyphicon glyphicon-send"></span> Enviar</button>
						</div>
					</div>
					
			</form>
					
					<?php
						}
					?>
					
				</div>
			</div>
			
			<br>
			
			
			
			<br>
			
		
	
	<?php } ?>

<?php 
	if(isset($_GET['form'])){ //Formulário para adição ou alteração de registro
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAft-hW3eomVeaGVytuEsTY4LIjFaqgU9s&libraries=places"></script>
<script type="text/javascript">
var map,navigatorLAT,navigatorLNG,markerGPS;
function getLocation() {
    
	<?php 
		if($_GET['form'] == 0){ //cadastro
	?>
		navigatorLAT = -3.76999;
		navigatorLNG = -38.52562;
	<?php
		} else {
	?>
		navigatorLAT = <?php echo @$rowEdit['nr_lat']; ?>;
		navigatorLNG = <?php echo @$rowEdit['nr_lng']; ?>;
	<?php
		}
	?>
	initializeMap();
}
function initializeMap() {
	
	console.log("initializeMap...");
	var input = $("#pac-input")[0];
	var mapOptions = {
		center: new google.maps.LatLng(navigatorLAT, navigatorLNG),
		zoom: 13,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
		},
		mapTypeControl: true,
		mapTypeControlOptions: {
			position: google.maps.ControlPosition.RIGHT_BOTTOM
		},
		streetViewControl: true,
		streetViewControlOptions: {
			position: google.maps.ControlPosition.RIGHT_TOP
		}
	};
	map = new google.maps.Map($("#map-canvas")[0], mapOptions);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	
	markerGPS = new google.maps.Marker({
		map:map,
		draggable:false,
		animation: google.maps.Animation.DROP,
		position: new google.maps.LatLng(navigatorLAT, navigatorLNG),		
		icon: 'assets/pin_gps.png'
	});	
	
	var infowindow = new google.maps.InfoWindow();
	var marker = new google.maps.Marker({
		map: map,
		anchorPoint: new google.maps.Point(0, -29)
	});
	
	var autocomplete = new google.maps.places.Autocomplete(input, 
		{ componentRestrictions: {'country': 'br'} } 
	);
	autocomplete.bindTo('bounds', map);
	autocomplete.addListener('place_changed', function() {
		
		markerGPS.setMap(null);
		infowindow.close();
		marker.setVisible(false);
		var place = autocomplete.getPlace();
		if (!place.geometry) {
			window.alert("Autocomplete's returned place contains no geometry");
			return;
		}

		// If the place has a geometry, then present it on a map.
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(15);  // Why 17? Because it looks good.
		}
		marker.setIcon(/** @type {google.maps.Icon} */({
			url: place.icon,
			size: new google.maps.Size(71, 71),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(17, 34),
			scaledSize: new google.maps.Size(35, 35)
		}));
		marker.setPosition(place.geometry.location);
		marker.setVisible(true);

		var address = '';
		if (place.address_components) {
			address = [
				(place.address_components[0] && place.address_components[0].short_name || ''),
				(place.address_components[1] && place.address_components[1].short_name || ''),
				(place.address_components[2] && place.address_components[2].short_name || '')
			].join(' ');
		}

		infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
		infowindow.open(map, marker);
	});

  	$("#pac-input").show();
	
	map.addListener('idle', function(){
		var c = map.getCenter();
		
		$('#nr_lat').val(c.lat());
		$('#nr_lng').val(c.lng());
		
		markerGPS.setMap(map);
		markerGPS.setPosition(new google.maps.LatLng(c.lat(), c.lng()));
		
	});	
	
}

$(function(){
	
	$('#nm_rua').keydown(function(){
		var str = $('#nm_rua').val() + ' ' + $('#nr_rua_numero').val() + ' ' + $('#nr_cep').val();
		$("#pac-input").val(str);
	});
	$('#nm_rua').keyup(function(){
		var str = $('#nm_rua').val() + ' ' + $('#nr_rua_numero').val() + ' ' + $('#nr_cep').val();
		$("#pac-input").val(str);
	});
	$('#nr_rua_numero').keydown(function(){
		var str = $('#nm_rua').val() + ' ' + $('#nr_rua_numero').val() + ' ' + $('#nr_cep').val();
		$("#pac-input").val(str);
	});
	$('#nr_rua_numero').keyup(function(){
		var str = $('#nm_rua').val() + ' ' + $('#nr_rua_numero').val() + ' ' + $('#nr_cep').val();
		$("#pac-input").val(str);
	});
	$('#nr_cep').keydown(function(){
		var str = $('#nm_rua').val() + ' ' + $('#nr_rua_numero').val() + ' ' + $('#nr_cep').val();
		$("#pac-input").val(str);
	});
	$('#nr_cep').keyup(function(){
		var str = $('#nm_rua').val() + ' ' + $('#nr_rua_numero').val() + ' ' + $('#nr_cep').val();
		$("#pac-input").val(str);
	});
	
	
	getLocation();	
	
});

function test(){	
	var valid = true;
	valid = valid && checkLength('nm_local', 'Instituição', 2);
	valid = valid && checkLength('ds_local', 'Descrição', 2);
	valid = valid && checkLength('ds_contato', 'Contatos', 2);
	valid = valid && checkLength('nm_rua', 'Endereço', 2);
	valid = valid && checkLength('nr_rua_numero', 'Número', 2);
	valid = valid && checkLength('nr_cep', 'CEP', 2);
	valid = valid && checkLength('nr_lat', 'Latitude', 2);
	valid = valid && checkLength('nr_lng', 'Longitude', 2);
	return valid;	
}	
</script>
<?php
	}
?>