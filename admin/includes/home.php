<?php
defined('EXEC') or die();	
?>
<div class="container">
		<div class="alert alert-info">
			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
			<p><?php 
				echo 'Olá <b>'.$user['nm_usuario'];
				echo '</b>, seu último acesso foi em '.$user['dt_acesso'].'.';
			
				if($auth->isMaster()){
					echo ' <b>*MASTER</b>';				
				}
				elseif($auth->isAdmin()){
					echo ' <b>*ADMINISTRADOR</b>';				
				}
			
			?>
			
			</p>
		</div>
	</div>
	
<div class="container">
	<div class="col-md-5 col-md-offset-3">
		<img src="assets/bemvindo.gif" class="img-block center-block img-responsive" />
	</div>
</div> 