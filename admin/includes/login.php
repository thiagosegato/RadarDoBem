<?php
defined('EXEC') or die();

//Verificando a recuperação do acesso
if (@$_POST['ds_email']) {
	$auth->passRecovery();
}

//Autenticando o usuário e o redirecionando
if(@$_POST['user'] && @$_POST['pass']){	
	$test = $auth->authenticate();
}

?>
		<div class="col-sm-12">
			
			<div style="margin-top:15px;"></div>
			
			<div style="margin-top:40px;" class="visible-lg-block"></div>
			
			<div style="background:url('assets/banner.jpg') no-repeat; max-width:1700px; margin:0 auto;">				
				<div class="container">
					
					<div class="row">
					
						<div class="col-md-8 hidden-xs hidden-sm">
						
							
							<div style="margin-top:420px;" class="hidden-xs hidden-sm hidden-lg"></div>
							<div style="margin-top:450px;" class="hidden-xs hidden-sm hidden-md"></div>
						
								
								<table><tr><td><span style="font-size:42px; text-shadow: 2px 2px 5px #aaa; font-weight:bold;"><?php echo Config::SYSTEM_ADMIN; ?></span><br>
								<span style="font-size:16px;">Gerenciamento de Instituições</span></td></tr></table>
							
						</div>
					
						<div class="col-sm-12 col-md-4">
					
							<div class="text-center" style="background:url('assets/login_background.jpg') no-repeat; width:323px; min-height:590px; padding:0 20px 0 20px; margin:0 auto;">
						
								<div style="padding-top:60px;"></div>
								
								<img src="assets/logo.png" style="max-width:120px;"/>
								
								<?php if(@$test){ echo '<div style="margin-top:30px;"></div>'.$test.'<div style="margin-top:30px;"></div>'; } else { echo '<div style="margin-top:60px;"></div>'; } ?>
								
								<form method="post">						
									<div class="input-group">
										<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
										<input type="text" name="user" id="user" class="form-control" style="height:46px;" placeholder="Digite seu usuário" required="required">
									</div>
									
									<div style="margin-top:20px;"></div>
									
									<div class="input-group">
										<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
										<input type="password" name="pass" id="pass" class="form-control" style="height:46px;" placeholder="Digite sua senha" required="required">
									</div>
									
									<div style="margin-top:40px;"></div>
									<input type="hidden" name="url" value="<?php echo $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>"/>
									<button class="btn btn-primary" style="width:220px; height:50px;"><b>ENTRAR</b></button>								
									
									<div style="margin-top:20px;"></div>
									
									<a href="javascript:void(0);" data-toggle="modal" data-target="#modalEsqueceu" style="color: white; text-decoration: underline;">Esqueceu o usuário ou senha?</a>
									
								</form>						
								
							</div>
					
						</div>
					
					</div>
					
				</div>			
			</div>
			
			<div style="margin-top:40px;"></div>
			
			
			
		
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<img src="assets/gcc.jpg" width="60" height="60">
					<span style="color:#a49d9d;">Copyright © 2017 <a href="http://gluecatcode.com/" target="_blank" style="color:#444444;">Glue Cat Code</a></span>						
				</div>
			</div>
		</div>
		
		<br>
	
<div class="modal fade" id="modalEsqueceu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Recuperar Senha</h4>
      </div>
	  <form method="post">
		  <div class="modal-body">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
				<p>Olá! Para recuperar seu usuário ou senha informe o email de cadastro:</p>
				<input id="ds_email" name="ds_email" type="email" class="form-control" placeholder="Digite seu email" required="required"/>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<input type="submit" class="btn btn-primary" value="Recuperar"/>
		  </div>
	  </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){	
	$("#user").focus();
});
</script>
