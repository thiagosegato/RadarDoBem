<?php
/**
 * Radar do Bem
 * @version 1.0
 * @copyright gluecatcode 
*/

defined('EXEC') or die();
$currentPage = (@$_GET['page'] ? $_GET['page'] : 'index');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Radar do Bem">
    <meta name="author" content="Radar do Bem">
    <title>Radar do Bem</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet"> 
    <link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">

    <!--[if lt IE 9]>
	    <script src="js/html5shiv.js"></script>
	    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="images/ico/favicon.ico">
	<link rel="apple-touch-icon" sizes="60x60" href="images/ico/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/ico/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="images/ico/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/ico/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="images/ico/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="images/ico/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="images/ico/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="images/ico/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="images/ico/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/ico/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="images/ico/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/ico/favicon-16x16.png">
	<meta name="msapplication-TileImage" content="images/ico/ms-icon-144x144.png">	
	
	
</head><!--/head-->

<body>
	<header id="header">      
        <div class="container">
            <div class="row">
                <div class="col-sm-12 overflow">
                   <div class="social-icons pull-right">
                        <ul class="nav nav-pills">
							<li><a href="https://play.google.com/store/apps/details?id=com.gluecatcode.radardobem" target="_blank"><i class="fa fa-android"></i></a></li>
                            <li><a href="https://www.facebook.com/Radar-do-Bem-Oficial-Rastreando-Solidariedade-1301174043302348/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <!--<li><a href=""><i class="fa fa-twitter"></i></a></li>-->
                            <li><a href="https://plus.google.com/118179223238720226258" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div> 
                </div>
             </div>
        </div>
        <div class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="index.php" style="margin-top:-95px;">
                    	<img src="images/logo.png" width="250" alt="logo">
                    </a>
                    
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li <?php echo ($currentPage == 'index' ? 'class="active"' : ''); ?>><a href="index.php">Home</a></li>                        
                        <li <?php echo ($currentPage == 'about' ? 'class="active"' : ''); ?>><a href="index.php?page=about">Sobre Nós</a></li>
						<!--<li><a href="admin/index.php">Seja um Colaborador &nbsp;&nbsp;<i class="fa fa-angle-right"></i></a></li>-->
                    </ul>
                </div>                
            </div>
        </div>
    </header>
    <!--/#header-->
