<?php
/**
 * Radar do Bem
 * @version 1.0
 * @copyright gluecatcode 
*/

define('EXEC', 1);
require_once('framework.php');

if(!@$_POST['id'])
	die();

$id = $_POST['id'];




//Local
$sql = "
select 	tl.nm_local, 
		tl.ds_local, 
        tl.ds_contato, 
        concat(tl.nm_rua, ' Nº ', tl.nr_rua_numero, ' - ', tl.nr_cep, ' ', tl.nm_bairro) as endereco, 
		tl.nr_view, 
        tu.nm_usuario,
        date_format(tl.dt_create, '%d/%m/%Y') as date_create,
        date_format(tl.dt_edit, '%d/%m/%Y') as date_edit 
from tb_local as tl
inner join tb_usuario as tu on(tl.cd_usuario_owner=tu.ci_usuario)
where tl.ci_local = $id
  and tl.fl_ativo = true
";
$query = query($sql);
$local = $query->fetch();

//Modalidades
$sql = "select tm.nm_modalidade 
from tb_local_modalidade tlm
inner join tb_modalidade tm on(tlm.cd_modalidade=tm.ci_modalidade)
where tlm.cd_local = $id
order by 1 asc";
$query = query($sql);
$modalidades = '';
while($row = $query->fetch()){
	$modalidades .= $row['nm_modalidade'] .', ';
}
$modalidades = substr($modalidades, 0, -2);

//Fotos
$sql = "select ds_hash from tb_foto where cd_local = $id";
$query = query($sql);
$fotos = $query->fetchAll();

$sql = "update tb_local set nr_view = nr_view + 1 where ci_local = $id";
execute($sql);

$return = array(	'local'			=> $local,
					'modalidades' 	=> $modalidades,
					'fotos'			=> $fotos
);

echo json_encode($return);
?>