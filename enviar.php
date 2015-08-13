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
		
		// Conexão com o banco de dados 
		require "comum.php"; 
		
		// Variável de debug. 0 Desligado / 1 Ligado
		$vDebug = $_SESSION["eDebug"];
		if ( $vDebug == 1 ) {
			echo "[DEBUG IS ON THE TABLE]<br>";
			}
		
		// Carrega ID do usuário (recebido de processalogin.php) em Variável Local
		$vIdUsuario = $_SESSION["eUser"];
		$vIdPerfil = $_SESSION["ePerfil"];
		$vLogin = $_SESSION["eLogin"];
		$vNome = $_SESSION["eNome"];

		// Inicializa tamanho Mensagem
		$vSize=12000;
		
		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[ID USUÁRIO: " . $vIdUsuario . "]<br>";
			echo "[ID PERFIL: " . $vIdPerfil . "]<br>";
			echo "[LOGIN: " . $vLogin . "]<br>";
			echo "[NOME: " . $vNome . "]<br>";
			}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Processa valores do formulario
			$vPrioridade = fValidaInput($_POST["fpriority"]);
			$vMensagem = fValidaInput($_POST["fmensagem"]);
		}
		

		// Valida e limpa Input de Dados
		function fValidaInput($vDados) {
		  $vDados = trim($vDados);
		  $vDados = stripslashes($vDados);
		  $vDados = htmlspecialchars($vDados);
		  return $vDados;
		}
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$vFlagExit=0;

			if ($vMensagem == "") {
				header("HTTP/1.1 301 Moved Permanently");
				header("location: http://www.lftecnologia.com/apps/dev/odontohelp/puser.php");
				exit(); 		
			} else {
				if (strlen(utf8_decode($vMensagem))>$vSize) {
					echo "A Mensagem digitada possui ". strlen(utf8_decode($vMensagem)) . " caracteres, e não pode ser maior que {$vSize}.<br>"; 
					$vFlagExit = 1;
				}
			}
			
			if ($vFlagExit > 0) {
				echo "Clique no botão Voltar do Browser e tente novamente.<br>"; 
				exit;
			} else {
			
			//Dados validados, realiza insert na base e redireciona para perfil.
			$vInsere="INSERT into perguntas (id_usuario, id_status_mensagem, msg_pergunta, id_prioridade, dt_ins, dt_atu)
			values ({$vIdUsuario}, 2, '{$vMensagem}', {$vPrioridade}, now(), now())";
			echo $vInsere;
			$vRetorno = $mysqli->query($vInsere) or die(mysql_error()); 
				if ($vRetorno===TRUE) {
					echo "Operação realizada com sucesso.<br>"; 
					header("Location: puser.php");
					exit;
				} else {
					echo "Erro ao realizar operação, entre em contato com contato@lftecnologia.com<br>"; 
					exit();
				}
			}

		}			
	?>
	<!-- FIM PHP -->
				
		<!-- One -->
			<section id="one" class="wrapper style2 special">
				<header class="major">
					<img src="imgs/logo2.png" alt="" />
				</header>
				
						<form method="post" action=""<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"">
						<div class="row uniform">
						
							<div class="4u 12u$(small)">
								<input type="radio" value = 1 id="demo-priority-low" name="fpriority" checked>
								<label for="demo-priority-low">Normal</label>
							</div>
							
							<div class="4u 12u$(small)">
								<input type="radio" value = 2 id="demo-priority-normal" name="fpriority">
								<label for="demo-priority-normal">Urgente</label>
							</div>
							
							<div class="4u$ 12u$(small)">
								<input type="radio" value = 3 id="demo-priority-high" name="fpriority">
								<label for="demo-priority-high">Emergência</label>
							</div>
							
							<div class="12u$">
								<textarea name="fmensagem" id="demo-message" placeholder="Descreva sua dúvida, procure ser claro e dar detalhes." rows="6"></textarea>
							</div>
							
							<div class="12u$">
								<ul class="actions">
									<li><input type="submit" value="Enviar Mensagem" class="special" /></li>
									<li><input type="reset" value="Limpar" /></li>
								</ul>
							</div>
						
						</div>
						</form>				
				
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
