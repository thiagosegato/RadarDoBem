<?php
defined('EXEC') or die();
function readMenu($menuxml){
	global $pages;
	foreach ($menuxml->li as $li){
		$href = @$li->a['href'];
		$parts = explode('=', $href);
		unset($parts[0]);
		$parts = implode('=', $parts);
		$parts = explode('/', $parts);
		$menuitem = $parts[count($parts) - 1];
		if($menuitem)
			$pages[$menuitem] = array(	'label' => (string) $li->a, 'link' => (string) $href );
			
		if(@$li->ul){
			readMenu($li->ul);
		}
	}
}	
?>
<div class="container">
	<div class="row" style="min-height:75px;">
		<div class="col-md-12">
			<!--<a href="index.php"><img src="assets/logo_cleo.png" class="pull-left" width="55" style="margin:10px 10px 0 0;"/></a>-->
			<div style="font-size:32px; text-shadow: 2px 2px 5px #ccc; color:#333; font-weight:bold; margin-top:10px;" class="pull-left"><?php echo Config::SYSTEM_ADMIN; ?></div>
			<div style="font-size:16px; margin-top:14px; text-align:left;" class="pull-right">
				<div><span class="glyphicon glyphicon-user"></span> &nbsp;&nbsp; <?php echo $user['nm_usuario']; ?></div>				
				<div id="boxCartPedidos"></div>
			</div>
		</div>		
	</div>
</div>

	<div class="navbar navbar-default navbar-static-top">
		<div class="container">
		<div class="row">
			<button class="navbar-toggle pull-left" style="margin-left: 10px;" data-toggle = "collapse" data-target = ".navHeaderCollapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="collapse navbar-collapse navHeaderCollapse">			
			<?php 
			ob_start();
			require('includes/menu.php');
			$menuhtml = ob_get_contents();
			ob_end_clean();	
			$menuxml = simplexml_load_string($menuhtml, 'SimpleXMLElement', LIBXML_NOCDATA);	
			$pages = array();
			readMenu($menuxml);	
			echo $menuhtml;
			?>
		        <ul class="nav navbar-nav navbar-right">
					<li><a href="?logout=1" class="btn-default" style="background-color:#f8f8f8;"><span class="glyphicon glyphicon-off"></span> <span class="hidden-sm hidden-md hidden-lg">Sair</span></a></li>
		        </ul>
			</div>
			</div>
		</div>		
	</div>

	<div class="breadcrumb breadcrumb-pedidos">
		<div  class="container">	
			<?php echo @Controller::getLocation($pages); ?>							
		</div>
	</div>