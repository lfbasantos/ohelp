<!DOCTYPE HTML>
<html>
	<head>
		<title>Odonto Help</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body id="top">
	
	<!-- INICIO PHP-->
	<?php
		// Inicializa Sessão
		session_start(); 
		
		if (isset($_POST["fSair"]) || !isset($_SESSION["eDebug"])) {
		
			// remove all session variables
			session_unset(); 

			// destroy the session 
			session_destroy(); 
			
			// envia código status "301 movido permanentemente" primeiro 
			header("HTTP/1.1 301 Moved Permanently"); 

			// Redireciona o navegador para http://www.meusite.com.br/paginanova 
			header("location: http://www.lftecnologia.com/apps/dev/odontohelp/index.php"); 

			// opcionalmente é possivel dizer ao PHP para sair a partir deste ponto. 
			// você pode precisar fazer isso se você não tem certeza se alguma 
			// outra saída pode ser produzida pelo seu ambiente PHP. 
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
		
		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[ID USUÁRIO: " . $vIdUsuario . "]<br>";
			echo "[ID PERFIL: " . $vIdPerfil . "]<br>";
			echo "[LOGIN: " . $vLogin . "]<br>";
			echo "[NOME: " . $vNome . "]<br>";
			}
		

		
		//
		// Executa a Consulta para obter o caminho da imagem.
		//
			// Consulta
			$vConsulta = "SELECT ds_path_img FROM imagem_perfil where id_usuario = {$vIdUsuario}"; 
			$vRetorno = $mysqli->query($vConsulta) or die("ERR02 - Erro no banco de dados!"); 
			$vLinha = $vRetorno->fetch_assoc();
			$rImgPerfil = $vLinha["ds_path_img"];

			// Carrega Variáveis
			$vImgPerfil = $rImgPerfil;
		
		//
		// Executa a Consulta para obter o caminho da imagem.
		//
			// Consulta
			$vConsulta = "SELECT nickname FROM usuarios where id_usuario = {$vIdUsuario}"; 
			$vRetorno = $mysqli->query($vConsulta) or die("ERR03 - Erro no banco de dados!"); 
			$vLinha = $vRetorno->fetch_assoc();
			$rNick = $vLinha["nickname"];

			// Carrega Variáveis
			$vNick = $rNick;

		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[ID SALA: " . $vIdSala . "]<br>";
			echo "[NOME SALA: " . $vNomeSala . "]<br>";
			}

		///////////////////////////////////////////////////////////////////////////////////////////////
		// Verificações
		//
		// Verifica se não existe imagem para o perfil e carrega dummy
		if ($vImgPerfil == "") {
			$vImgPerfil = "imgs/profiles/dummy.jpg";
		}
		// Verifica se o Nick está vazio, se estiver utiliza o nome de usuário como nick.
		if ($vNick == "") {
			$vNick = $vLogin;
		}
		///////////////////////////////////////////////////////////////////////////////////////////////
		
	?>
	<!-- FIM PHP-->
	
		<!-- Header -->
			<header id="header">
				<div class="content">
					<img src="imgs/logowhite.png" height=150 width=160 alt="" />
					<p>Olá <?php echo "<font style=\"color:white\">{$vNick}</font>"; ?>! Escolha uma Opção</p>
					<ul class="actions">
						<li><a href="#one" class="button icon fa-chevron-down scrolly">Perfil</a></li>
						<li><a href="#two" class="button icon fa-chevron-down scrolly">Mensagens</a></li>
						<li><a href="#three" class="button icon fa-chevron-down scrolly">Créditos</a></li>
					</ul>
					<ul class="actions">
						<h5>
						<li><a href="#four" class="button">Logout</a></li>
						</h5>
					</ul>				
				</div>
			</header>
			
		<!-- One -->
			<section id="one" class="wrapper style2 special">
				<header class="major">
					<h2>Meu Perfil</h2>
					<?php
					// Grid Meu Perfil
					echo "<table>";
						echo "<tr><td>{$vNick}</td></tr>";
						echo "<tr>";
						echo "<td><img src=\"{$vImgPerfil}\" alt=\"Perfil\" height=\"100\" width=\"100\"></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td> &nbsp </td>";
						echo "</tr>";
					echo "</table>";
					?>
				</header>				

				<ul class="actions">
					<li><a href="imagemperfil.php" class="button">Mudar Imagem</a></li>
					<li><a href="editperfil.php" class="button">Mudar Apelido</a></li>
					<li><a href="#" class="button">Voltar</a></li>
				</ul>
			</section>

		<!-- Two -->
			<section id="two" class="wrapper style2 special">
				<header class="major">
					<h3>Mensagens</h3>

					<?php 
					
					// Verifica se existem Mensagens não respondidas.		
					$vConsultaCount = "SELECT count(t1.id_pergunta) cnt FROM perguntas t1 where t1.id_status_mensagem = 2";
					$vRetornoCount = $mysqli->query($vConsultaCount) or die("ERR01- ERRO NO BANCO DE DADOS!");
					$vLinhaCount = $vRetornoCount->fetch_assoc();
					$vCount = $vLinhaCount["cnt"];
					
					
					// Inicializa Variáveis
					$rPrioridade = $rMsgPergunta = $rDtPergunta = $rIdPergunta = "";
							
					// Faz a consulta na base somente se houver algum registro.
					if ($vCount > 0) {
					   
						// Consulta última pergunta realizada que não está respondida
						$vConsulta = "SELECT t1.id_pergunta, t2.desc_prioridade, t1.msg_pergunta, t1.dt_ins, t2.id_prioridade 
						  FROM perguntas t1 inner join prioridade t2 on t1.id_prioridade = t2.id_prioridade 
						  where t1.id_status_mensagem = 2 
						  order by t2.id_prioridade desc, t1.dt_ins desc"; 
						$vRetorno = $mysqli->query($vConsulta) or die(mysql_error()); 
						$vLinha = $vRetorno->fetch_assoc();
						$rPrioridade = $vLinha["desc_prioridade"];
						$rMsgPergunta = $vLinha["msg_pergunta"];
						$rDtPergunta = $vLinha["dt_ins"];
						$rIdPergunta = $vLinha["id_pergunta"];
						
						// Consulta resposta para a pergunta realizada
						$vConsulta = "SELECT msg_resposta FROM respostas where id_pergunta = {$rIdPergunta}"; 
						$vRetorno = $mysqli->query($vConsulta) or die("ERR05 - Erro no banco de dados!"); 
						$vLinha = $vRetorno->fetch_assoc();
						$rMsgResposta = $vLinha["msg_resposta"];
						
						// Tratando variáveis de pergunta e resposta, para caber no Grid
						// Substituindo descrição caso não haja uma resposta.
						if (strlen($rMsgPergunta) > 50) {
								$vMsgPergunta = substr($rMsgPergunta, 0, 50) . "...";
						}	else {
								$vMsgPergunta = $rMsgPergunta;
						}
						
						if ($rMsgResposta) {
							if (strlen($rMsgResposta) > 50) {
								$vMsgResposta = substr($rMsgResposta, 0, 50) . "...";
							}

						} else {
							$vMsgResposta = "Pergunta Não Respondida.";
						}
						
						// Tratando Variáveis de Data para formatar no Grid 'YYYY-MM-DD HH:MI:SS'
						$vData = substr($rDtPergunta, 0, 10);
						$vHora = substr($rDtPergunta, 11, 8);
					
					} 
					
					echo "	<div class=\"table-wrapper\">";
					echo "	<table class=\"alt\">";
	
					echo "	<thead>";
					echo "		<tr>";
					echo "		<th>Mensagem</th>";
					echo "		</tr>";
					echo "	</thead>";
	
					echo "	<tbody style=\"font-size:12px\" >";
					echo "		<tr>";
					echo "			<td>";
					echo "				<table class=\"alt\">";
					echo "				<tr><td><strong>Prioridade</strong><br>{$CSS_AbrirFonte}{$rPrioridade}{$CSS_FecharFonte}</td></tr>";
					echo "				<tr><td><strong>Pergunta</strong><br>{$CSS_AbrirFonte}{$vMsgPergunta}{$CSS_FecharFonte}</td></tr>";
					echo "				<tr><td><strong>Resposta</strong><br>{$CSS_AbrirFonte}{$vMsgResposta}{$CSS_FecharFonte}</td></tr>";
					echo "				<tr><td><strong>Data</strong><br>{$CSS_AbrirFonte}{$rDtPergunta}{$CSS_FecharFonte}</td></tr>";
					echo "				</table>";
					echo "			</td>";
					echo "		</tr>";
					echo "	</tbody>";
	
					echo "	</table>";
					echo "	</div>";
				
					?>

				</header>				
				<ul class="actions">
					<?php
						if ($vCount > 0) {
							echo "<li><a href=\"inbox_manager.php\" class=\"button\">InBox</a></li>";
						}
					?>
					<li><a href="#" class="button">Voltar</a></li>
				</ul>
			</section>
	
		<!-- Three -->
			<section id="three" class="wrapper style2 special">
				<header class="major">
					<h2></h2>
					<?php
					// Grid Meu Perfil
					echo "<table>";
					echo "<tr><td>Meus Créditos</td></tr>";
					echo "<tr>";
					echo "<td> 9999 Créditos";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>";
					// Box de Pagamento ////////////////////////////////////////////////////////////////////////
					echo "<form action=\"https://pagseguro.uol.com.br/checkout/v2/payment.html\" method=\"post\" onsubmit=\"PagSeguroLightbox(this); return false;\">";
					echo "<input type=\"hidden\" name=\"code\" value=\"C8C21D7217179EBBB44C0F880AAEE878\" />";
					echo "<input type=\"image\" src=\"https://p.simg.uol.com.br/out/pagseguro/i/botoes/pagamentos/209x48-comprar-assina.gif\" name=\"submit\" alt=\"Pague com PagSeguro - é rápido, grátis e seguro!\" />";
					echo "</form>";
					echo "<script type=\"text/javascript\" src=\"https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js\"></script>";
					////////////////////////////////////////////////////////////////////////////////////////////
					echo "</td>";
					echo "</tr>";
					echo "</table>";
					echo "<br>";
					?>

				</header>				
				<ul class="actions">
					<li><a href="#" class="button">Voltar</a></li>
				</ul>
			</section>
		
		<!-- Four -->
			<section id="four" class="wrapper style2 special">
				<header class="major">
					<h2>Confirmar Logout</h2>
					<p>Tem certeza de que deseja sair do Sistema?</p>
				</header>
				<ul class="actions">				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
					<input type="hidden" value="1" name="fSair" id="IdSair">
					<li><input type="submit" value=" &nbsp Sair &nbsp " name="submit"></li>
					</form>
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

