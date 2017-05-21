<?php 
if($_SERVER["HTTPS"] != "on") {
	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	exit();	
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<title>RadarDoBem</title>
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/font-awesome.min.css" rel="stylesheet">
		<link href="../css/main.css" rel="stylesheet">
		<link href="../css/lightbox.css" rel="stylesheet">  
		<style type="text/css">
		html { height: 100% }
		body { height: 100%; margin: 0; padding: 0 }
		#map-canvas { height: 100% }		
		#preloader {
			left: 0;
			top: 0;
			bottom: -30px;
			right: 0;
			background: #fafafa;
			text-align: center;			
		}		
		#preloader > i {
			font-size: 48px;
			height: 48px;
			line-height: 48px;
			color: #00aeef;
			position: absolute;
			left: 50%;
			margin-left: -24px;
			top: 50%;
			margin-top: -24px;
		}
		#loader {
			display:none;
		}
		#loader > i {
			font-size: 30px;
			height: 30px;
			line-height: 30px;
			color: #00aeef;			
			margin-left: 10px;
			margin-top: 5px;
		}
		#infoRequest {
			text-align:center;
			font-size: 14px;
			margin-bottom: 15px;
			display:none;
		}
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
		.fa-margin {
			line-height: 20px;
		}
		.fa-margin i {
			margin-right:8px;
		}
		.fa-margin1 i {
			margin-right:5px;
		}
		.modal {
			overflow-y: scroll;
		}
		
		</style>		
	</head>
	<body>
	
		
		<input id="pac-input" class="form-control control" type="text" placeholder="Local ou Cidade" style="font-family: 'Lato', sans-serif !important;">		
		<div id="loader"><i class="fa fa-sun-o fa-spin"></i></div>	
		<div id="infoRequest" style="font-family: 'Lato', sans-serif !important;"></div>
		<div id="map-canvas">		
			<div id="preloader"><i class="fa fa-sun-o fa-spin"></i></div>
		</div>
		
		
		<!-- Modal Info -->
		<div id="modalInfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" style="font-weight:bold;">Local</h4>
					</div>
					<div class="modal-body">
					
						<div class="row">		
							<div class="col-md-7">											
								<div class="modal-descr" style="white-space:pre-wrap;"></div>
								<br>
								<div style="margin-top:10px">Endereço:</div>
								<span class="modal-endereco"></span>
								<br>
								<div style="margin-top:10px">Modalidades:</div>
								<b><span class="modal-modalidades"></span></b>
								<br>
								<div style="margin-top:10px">Contato:</div>
								<span class="modal-contato"></span>
								<br>
								<div style="color:#0099AE; font-size:12px; margin-top:10px;"> 
									<span class="fa-margin1"><i class="fa fa-share-alt"></i> <span class="modal-view">3</span> Visualizações</span>, &nbsp;&nbsp;&nbsp; 
									<span class="fa-margin1"><i class="fa fa-user"></i> Criado por <span class="modal-user">Endure</span></span>, &nbsp;&nbsp;&nbsp; 
									<span class="fa-margin1"><i class="fa fa-clock-o"></i> <span class="modal-date-create">February 11,2014</span></a></span>, &nbsp;&nbsp;&nbsp; 
									<span class="fa-margin1"><i class="fa fa-edit"></i> <span class="modal-date-edit">February 12,2014</span></a></span>
								</div>
							</div>
							<div class="col-md-5">
								<br>
								<div id="carousel-container">
									<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
										<ol class="carousel-indicators visible-xs" style="margin-top:7px;">
											<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
											<li data-target="#carousel-example-generic" data-slide-to="1"></li>
											<li data-target="#carousel-example-generic" data-slide-to="2"></li>
											<li data-target="#carousel-example-generic" data-slide-to="3"></li>
										</ol>
										<div class="visible-xs" style="margin-top:35px;"></div>
										<div class="carousel-inner">
											<div class="item active"></div>
										</div>
										<a class="left carousel-control hidden-xs" href="#carousel-example-generic" data-slide="prev">
											<span class="glyphicon glyphicon-chevron-left"></span>
										</a>
										<a class="right carousel-control hidden-xs" href="#carousel-example-generic" data-slide="next">
											<span class="glyphicon glyphicon-chevron-right"></span>
										</a>
									</div><!--/#carousel-example-generic-->
								</div>
							</div>						
						</div>
						<br>						
						<!--<div class="response-area">
							<h2 class="bold" style="font-size:18px; padding-bottom:17px;">
								&nbsp;
								<div class="pull-left" style="padding-top:7px; padding-left:5px;">Comentários</div>
								<button type="button" class="form-control btn-submit pull-right" onclick="openCommentModal(1)" style="width:175px; margin-top:0px; font-size:12px; padding:8px; height:35px;"><i class="fa fa-comment-o" aria-hidden="true"></i> Comentar</button>
							</h2>
							<ul class="media-list">
								<li class="media">
									<div class="post-comment" style="padding-left:0px; padding-top:20px;">
										<a class="pull-left text-center" href="#" style="margin-right:20px; font-size:12px;">
											<img class="media-object" src="../images/avatar/2.png" width="85" style="margin-right:0px;">
											Administrador
										</a>
										<div class="media-body" style="padding-bottom:5px;">
											<p style="margin-top:0px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliq Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.</p>
											<ul class="nav navbar-nav post-nav">
												<li style="font-size:12px;"><a href="#"><i class="fa fa-clock-o"></i>February 11,2014</a></li>												
											</ul>
										</div>
									</div>                                           
								</li>
								<li class="media">
									<div class="post-comment" style="padding-left:0px; padding-top:20px;">
										<a class="pull-left text-center" href="#" style="margin-right:20px; font-size:12px;">
											<img class="media-object" src="../images/avatar/2.png" width="85" style="margin-right:0px;">
											Administrador
										</a>
										<div class="media-body" style="padding-bottom:5px;">
											<p style="margin-top:0px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliq Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.</p>
											<ul class="nav navbar-nav post-nav">
												<li style="font-size:12px;"><a href="#"><i class="fa fa-clock-o"></i>February 11,2014</a></li>												
											</ul>
										</div>
									</div>                                           
								</li>
								<li class="media">
									<div class="post-comment" style="padding-left:0px; padding-top:20px;">
										<a class="pull-left text-center" href="#" style="margin-right:20px; font-size:12px;">
											<img class="media-object" src="../images/avatar/1.png" width="85" style="margin-right:0px;">
											Administrador
										</a>
										<div class="media-body" style="padding-bottom:5px;">
											<p style="margin-top:0px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliq Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.</p>
											<ul class="nav navbar-nav post-nav">
												<li style="font-size:12px;"><a href="#"><i class="fa fa-clock-o"></i>February 11,2014</a></li>
												<li style="font-size:12px;"><a href="#"><i class="fa fa-edit"></i>February 12,2014</a></li>
											</ul>
										</div>
									</div>                                           
								</li>
							</ul>							
						</div>
						<div class="text-center" style="margin-bottom:5px;">
							<a href="#" class="fa-margin"><i class="fa fa-angle-down"></i>Mais Comentários</a>
						</div>-->
						
					
					</div>
					<div class="modal-footer">
						
						<button type="button" class="btn btn-default pull-left" style="color:#3c763d;" onclick="myNavFunc();"><i class="fa fa-map-marker"></i> Rota GPS</button>						
						<button type="button" class="btn btn-default pull-left" onclick="$('#modalGestor').modal();"><i class="fa fa-home"></i> Sou Gestor desta Instituição</button>
						<button type="button" class="btn btn-default pull-left" style="color:#C03035;" onclick="$('#modalDenuncia').modal();"><i class="fa fa-exclamation-circle"></i> Denunciar</button>
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fechar</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Modal Comment
		<div id="modalComment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Comentar</h4>
					</div>
					<form>
					<div class="modal-body">					
						<div class="row">		
							<div class="col-md-12">
								<textarea type="text" name="comment_descricao" class="form-control" required="required" placeholder="Descreva o comentário"></textarea>
							</div>
						</div>				
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Enviar</button>
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					</div>
					</form>
				</div>
			</div>
		</div> -->
		
		<!-- Modal Denuncia -->
		<div id="modalDenuncia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-ms">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Denúncia</h4>
					</div>
					<form>
					<div class="modal-body">
						<div class="alert alert-warning fade in">
                            <p>Descreva corretamente o problema. Vamos ajudar o radar com o bem!</p>
                        </div>
						<p><b>Denúncia - Instituição <span class="modal-id-local"></span> - CEP</b></p>
						<p>Iremos analizar e entrar em contato o mais breve possível.</p>
						Email:<br>
						<a href="mailto:radardobemoficial@gmail.com">radardobemoficial@gmail.com</a>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fechar</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- Modal Gestor -->
		<div id="modalGestor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Sou Gestor</h4>
					</div>
					<div class="modal-body">					
						<div class="row">		
							<div class="col-md-12">	
								<div class="alert alert-success fade in">
									<h4>Olá Gestor!</h4>
									<p>Caso seja administrador desta instituição, e se deseja administrá-la, ou remove-la, nos envie um email com o assunto:</p>									
								</div>
								<p><b>Gestor - Instituição <span class="modal-id-local"></span> - CEP</b></p>
								<p>Iremos analizar e entrar em contato o mais breve possível.</p>
								Email:<br>
								<a href="mailto:radardobemoficial@gmail.com">radardobemoficial@gmail.com</a>
							</div>							
						</div>						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fechar</button>
					</div>					
				</div>
			</div>
		</div>
		
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAft-hW3eomVeaGVytuEsTY4LIjFaqgU9s&libraries=places"></script>		
		<script type="text/javascript" src="../js/jquery.js"></script>		
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/lightbox.min.js"></script>
		<script type="text/javascript" src="appmaps.js"></script>
		
	</body>
</html>