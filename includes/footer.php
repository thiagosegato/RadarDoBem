<?php
/**
 * Radar do Bem
 * @version 1.0
 * @copyright gluecatcode 
*/

Loader::import('com.google.recaptcha');

//Verificando mensagem de suporte
if (@$_POST['suporte_send']) {	
	
	$nome  	= addslashes($_POST['suporte_nome']); 
	$email 	= strtolower(addslashes($_POST['suporte_email'])); 
	$duvida = $_POST['suporte_duvida']; 
	$secret = "6LfDqg4UAAAAABtyCrwSrSWoHh9GPX0siRDTa2sY"; 
	$response = null;
	$reCaptcha = new ReCaptcha($secret);
	
	$body = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
	<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
		<title>".Config::SYSTEM."</title>
	 </head>
	 <body topmargin='0'>
		<table width='500'  border='0' align='center' cellpadding='0' cellspacing='0' bordercolor='#E3E1BA' bgcolor='#FFFFFF'>
			<tr>
				<td><img src='http://www.radardobem.com.br/images/logo.png' width='70'></td>
			</tr>
			<tr>
				<td>
					<table border='0'>
						<tr><td>Nome:</td><td>".$nome."</td></tr>
						<tr><td>Email:</td><td>".$email."</td></tr>
						<tr><td>Dúvida ou Sugestão:</td><td>".$duvida."</td></tr>						
					</table>				
				</td>
			</tr>
		</table>			
	</body>
	</html>";
	
	if(@$_POST["g-recaptcha-response"]){	
	
		$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
		if ($response != null && $response->success) {
			Util::mail(utf8_decode('Suporte Telessaúde'), 'radardobem@radardobem.com.br', 'RadarDoBem', $body, 'radardobemoficial@gmail.com'); //$email
			Controller::setInfo('Dúvida ou Sugestão', 'Enviada com sucesso! Por favor aguarde!');		
		} 
		else {
			Controller::setInfo('Dúvida ou Sugestão', 'Erro ao verificar capcha!', 'error');
		}
	}
	else{
		Controller::setInfo('Dúvida ou Sugestão', 'Erro ao enviar, verifique o formulário!', 'error');
	}
	Controller::redirect();	
}


//secret key radardobem.com.br
//6LePZBIUAAAAAM6wsxR2OI3iNf5ieOuMUQb7F4Ps

defined('EXEC') or die();
?>
	<footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center bottom-separator">
                    <img src="images/home/under.png" class="img-responsive inline" alt="">
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="testimonial bottom">
                        <h2>Testemunhos</h2>
                        <div class="media">
                            <div class="pull-left">
                                <a href="#"><img src="images/home/profile-madre-teresa.jpg" alt=""></a>
                            </div>
                            <div class="media-body">
                                <blockquote>As mãos que ajudam são mais sagradas do que os lábios que rezam.</blockquote>
                                <h3><a href="#">- Madre Teresa de Calcutá</a></h3>
                            </div>
                         </div>
                        <div class="media">
                            <div class="pull-left">
                                <a href="#"><img src="images/home/profile-chico-xavier.jpg" alt=""></a>
                            </div>
                            <div class="media-body">
                                <!--<blockquote>Se todos trabalhassem pelo pão de cada dia, dividindo com os outros as migalhas que lhes sobrassem do pão cotidiano, a paz seria uma realidade e a justiça social se faria sem tantas lutas.</blockquote>-->
								<blockquote>A caridade é o processo de somar alegrias, diminuir males, multiplicar esperanças e dividir a felicidade para que a terra se realize na condição do esperado Reino de Deus.</blockquote>
                                <h3><a href="">- Chico Xavier</a></h3>
                            </div>
                        </div>   
                    </div> 
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="contact-info bottom">
                        <h2>Contato</h2>
                        <address>
                        E-mails: <br>
						<a href="mailto:radardobemoficial@gmail.com">radardobemoficial@gmail.com</a> <br> 
						<a href="mailto:admin@radardobem.com.br">admin@radardobem.com.br</a> <br> 					
						
						</address>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="contact-form bottom">
                        <h2>Dúvidas ou Sugestões</h2>
                        <form id="main-contact-form" name="contact-form" method="post">
							<input type="hidden" name="suporte_send" value="1"/>
                            <div class="form-group">
                                <input type="text" id="suporte_nome" name="suporte_nome" class="form-control" required="required" placeholder="Nome">
                            </div>
                            <div class="form-group">
                                <input type="email" id="suporte_email" name="suporte_email" class="form-control" required="required" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <textarea id="suporte_duvida" name="suporte_duvida" required="required" class="form-control" rows="8" placeholder="Seu texto aqui"></textarea>
                            </div>
							<div class="form-group">
								Clique no item abaixo:
								<div class="g-recaptcha" data-sitekey="6LfDqg4UAAAAABAIsz6CaLhU3GeaVjwWqtgrwqm1"></div>
							</div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-submit" value="Enviar">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="copyright-text text-center">
                        <br>
                        <p>Desenvolvido por <a target="_blank" href="http://www.gluecatcode.com">GlueCatCode</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--/#footer-->

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/wow.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
	<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>	
	
</body>
</html>