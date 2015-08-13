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
		
		// Carrega Id da Pergunta Selecionada no Grid em InBox.
			if( isset($_GET{'id'} ) )
			{
				$vIdPergunta = $_GET{'id'};
			}

		
		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[ID USUÁRIO: " . $vIdUsuario . "]<br>";
			echo "[ID PERFIL: " . $vIdPerfil . "]<br>";
			echo "[LOGIN: " . $vLogin . "]<br>";
			echo "[NOME: " . $vNome . "]<br>";
			}


	?>
	<!-- FIM PHP -->
				
		<!-- One -->
			<section id="one" class="wrapper style2 special">
				<header class="major">
					<img src="imgs/logo2.png" alt="" />
				</header>				
				<h3>InBox</h3>
					<?php 
					
					// Consulta Pergunta
						$vConsultaPergunta = "SELECT t1.id_pergunta, t2.desc_prioridade, t1.msg_pergunta, 
					  		t1.dt_ins FROM perguntas t1 inner join prioridade t2
					  		on t1.id_prioridade = t2.id_prioridade where t1.id_usuario = {$vIdUsuario}
					  		and t1.id_pergunta = {$vIdPergunta}";
					   //echo "[QUERY]{$vConsultaPergunta}<br>";
						
						$vRetornoPergunta = $mysqli->query($vConsultaPergunta) or die("ERR01 - ERRO NO BANCO DE DADOS!"); 
						$vLinhaPergunta = $vRetornoPergunta->fetch_assoc();
						$rPrioridade = $vLinhaPergunta["desc_prioridade"];
						$rMsgPergunta = $vLinhaPergunta["msg_pergunta"];
						$rDtPergunta = $vLinhaPergunta["dt_ins"];
						$rIdPergunta = $vLinhaPergunta["id_pergunta"];
						
						
						// Consulta resposta para a pergunta realizada
						$vConsultaResposta = "SELECT msg_resposta FROM respostas where id_pergunta = {$rIdPergunta}";
						//echo "[QUERY]{$vConsultaResposta}<br>"; 
						$vRetornoResposta = $mysqli->query($vConsultaResposta) or die("ERR02 - Erro no banco de dados!"); 
						$vLinhaResposta = $vRetornoResposta->fetch_assoc();
						$rMsgResposta = $vLinhaResposta["msg_resposta"];
						

						
						// Formatação com base na prioridade
						if ($rPrioridade == "NORMAL") {
							$vCor = "green";
						} else if ($rPrioridade == "URGENTE") {
							$vCor = "orange";
						} else {
							$vCor = "red";
						}
						
						
						echo "	<div class=\"table-wrapper\">";
						echo "	<table class=\"alt\">";
						echo "	<thead>";
						echo "		<tr>";
						echo "		<th style=\"color:{$vCor}\">Data da Mensagem: {$rDtPergunta}</th>";
						echo "		</tr>";
						echo "	</thead>";
						echo "	<tbody style=\"font-size:12px\" >";
						echo "		<tr>";
						echo "			<td>";
						echo "				<table class=\"alt\">";
						echo "				<tr><td><strong>Pergunta</strong><br>{$rMsgPergunta}</td></tr>";
						echo "				</table>";
						echo "			</td>";
						echo "		</tr>";
						echo "	</tbody>";
						echo "	</table>";
						echo "	</div>";
					

						// Fecha conexão com o banco de dados.
						$mysqli->close();
					?>
				<ul class="actions">
					<li><a href="puser.php" class="button">Voltar</a></li>
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
