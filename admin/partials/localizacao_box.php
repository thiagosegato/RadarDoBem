<?php
if(!@$_POST['cd_estado'])
	exit;

require_once('../includes/frameworkajax.php');
$ufDefault = $_POST['cd_estado'];
$queryMunicipios = query("select * from tb_municipio where sg_estado = '$ufDefault' order by 2 asc");
echo '<select id="ci_municipio" name="ci_municipio" class="form-control">';							
while($row = $queryMunicipios->fetch()){
	echo '<option value="'.$row['ci_municipio'].'">'.$row['nm_municipio'].'</option>';
}
echo '</select>';
?>