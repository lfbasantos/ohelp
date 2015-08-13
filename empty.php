<!DOCTYPE HTML>
<html>
	<head>
		<title>Odonto Help</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
		

	</head>
	<body id="top">

	<!-- INICIO PHP -->
	<?php
		// Inicializa Sessão
		session_start(); 
		
		if ($_SESSION["eLogout"]==1 || !isset($_SESSION["eDebug"])) {
		
			// Destruindo sessão
			session_unset(); 
			session_destroy(); 
			
			// envia código status "301 movido permanentemente" primeiro 
			header("HTTP/1.1 301 Moved Permanently"); 

			// Redireciona o navegador para http://www.meusite.com.br/paginanova 
			header("location: http://www.lftecnologia.com/apps/dev/odontohelp/index.php"); 

			exit(); 		
		}	
	?>
	<!-- FIM PHP -->
	
		<!-- Header -->
			<header id="header">
				<div class="content">
					<h1><a href="#">OdontoHelp</a></h1>
					<p>Escolha uma Opção<br /></p>
					<ul class="actions">
						<li><a href="#one" class="button icon fa-chevron-down scrolly">Perfil</a></li>
						<li><a href="#two" class="button icon fa-chevron-down scrolly">Mensagens</a></li>
						<li><a href="#three" class="button icon fa-chevron-down scrolly">Créditos</a></li>
						<li><a href="#four" class="button icon fa-chevron-down scrolly">Logout</a></li>
					</ul>
					
				</div>
			</header>
			
		<!-- One -->
			<section id="one" class="wrapper style2 special">
				<header class="major">
					<h2></h2>
				</header>				
				<ul class="actions">
					<li><a href="#three" class="button">Meus Créditos</a></li>
					<li><a href="#" class="button">Voltar</a></li>
				</ul>
			</section>

		<!-- Two -->
			<section id="two" class="wrapper style2 special">
				<header class="major">
					<h2></h2>
				</header>				
				<ul class="actions">
					<li><a href="#" class="button">Voltar</a></li>
				</ul>
			</section>
			<br><br>

		<!-- Three -->
			<section id="three" class="wrapper style2 special">
				<header class="major">
					<h2></h2>
				</header>				
				<ul class="actions">
					<li><a href="#" class="button">Voltar</a></li>
				</ul>
			</section>
		
		<!-- Four -->
			<section id="four" class="wrapper style2 special">
				<header class="major">
					<h2></h2>
					<p></p>
				</header>
				<ul class="actions">
				<li><a href="#" class="button">Voltar</a></li>
				</ul>
			</section>

		<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
					<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
				</ul>
				<p class="copyright">&copy; <a href="http://lftecnologia.com">LF Tecnologia</a></p>
			</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>

