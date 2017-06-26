<?php
defined('EXEC') or die();	
?>
<ul class="nav navbar-nav">
	<?php echo ($auth->isRead('instituicao') ? '<li><a href="?page=instituicao">Instituições</a></li>' : '' ); ?>
	
	<?php if($auth->isMaster()){ ?>
		<li><a href="?page=modalidade">Modalidades</a></li>
		<li><a href="?page=municipios">Municípios</a></li>
	<?php } ?>
	
	<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Opções <b class="caret"></b></a>
			<ul class="dropdown-menu">
					<li><a href="?page=acesso/alterar-senha">Alterar Senha</a></li>
					<?php echo ($auth->isRead('usuario') ? '<li><a href="?page=acesso/usuario">Usuários</a></li>' : '' ); ?>
					<?php echo ($auth->isAdmin() ? '<li><a href="?page=acesso/grupo">Grupos</a></li>' : '' ); ?>
					<?php echo ($auth->isMaster() ? '<li><a href="?page=acesso/transacao">Transações</a></li>' : '' ); ?>
			</ul>
	</li>
</ul>