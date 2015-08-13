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

		// Inicializa tamanho Nick
		$vSizeNick=20;

		
		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[ID USUÁRIO: " . $vIdUsuario . "]<br>";
			echo "[ID PERFIL: " . $vIdPerfil . "]<br>";
			echo "[LOGIN: " . $vLogin . "]<br>";
			echo "[NOME: " . $vNome . "]<br>";
			}

		////////////////////////////////////////////////////////////////////////////////		
		// Inicializa Parâmetros de Paginação
		// para mostrar os registros paginados de mensagens
		//
		// Define limite de paginação
			$vLimite = 5; 
		// Verifica se houve recarregamento da página através do PHP_SELF.
			if( isset($_GET{'pagina'} ) )
			{
			   $vPagina = $_GET{'pagina'} + 1;
			   $vOffset = $vLimite * $vPagina ;
			}
			else
			{
			   $vPagina = 0;
			   $vOffset = 0;
			}
			
		// Conta quantos registros existem na tabela de mensagens não respondidas
		$vConsultaCount = "SELECT count(t1.id_pergunta) cnt FROM perguntas t1 where t1.id_status_mensagem = 2";
		$vRetornoCount = $mysqli->query($vConsultaCount) or die("ERR01- ERRO NO BANCO DE DADOS!");
		$vLinhaCount = $vRetornoCount->fetch_assoc();
		$vCount = $vLinhaCount["cnt"];
		//
		// Verifica se chegou no final dos registros da tabela
		$vSobra = $vCount - ($vPagina * $vLimite);
		////////////////////////////////////////////////////////////////////////////////


	?>
	<!-- FIM PHP -->
				
		<!-- One -->
			<section id="one" class="wrapper style2 special">
				<header class="major">
					<img src="imgs/logo2.png" alt="" />
				</header>				
				<h3>InBox</h3>
					<?php 
					
					// Consulta Perguntas
					$vConsultaPergunta = "SELECT t1.id_pergunta, t2.desc_prioridade, t1.msg_pergunta, 
					  t1.dt_ins FROM perguntas t1 inner join prioridade t2
					  on t1.id_prioridade = t2.id_prioridade where t1.id_status_mensagem = 2
					  order by t1.id_prioridade desc, t1.dt_ins desc
					  LIMIT {$vOffset}, {$vLimite}";
					   
					$vRetornoPergunta = $mysqli->query($vConsultaPergunta) or die("ERR02 - ERRO NO BANCO DE DADOS!"); 
					
					while ($vLinhaPergunta = $vRetornoPergunta->fetch_assoc())
					{
						$rPrioridade = $vLinhaPergunta["desc_prioridade"];
						$rMsgPergunta = $vLinhaPergunta["msg_pergunta"];
						$rDtPergunta = $vLinhaPergunta["dt_ins"];
						$rIdPergunta = $vLinhaPergunta["id_pergunta"];
					
						
						// Consulta resposta para a pergunta realizada
						$vConsultaResposta = "SELECT msg_resposta FROM respostas where id_pergunta = {$rIdPergunta}"; 
						$vRetornoResposta = $mysqli->query($vConsultaResposta) or die("ERR03 - Erro no banco de dados!"); 
						$vLinhaResposta = $vRetornoResposta->fetch_assoc();
						$rMsgResposta = $vLinhaResposta["msg_resposta"];
						
						// Tratando variáveis de pergunta e resposta, para caber no Grid
						// Substituindo descrição caso não haja uma resposta.
						$vMsgPergunta = $rMsgPergunta;
						if ($rMsgResposta) {
							$vMsgResposta = $rMsgResposta;
						} else {
							$vMsgResposta = "Pergunta Não Respondida.";
						}
						
						// Tratando Variáveis de Data para formatar no Grid 'YYYY-MM-DD HH:MI:SS'
						$vData = substr($rDtPergunta, 0, 10);
						$vHora = substr($rDtPergunta, 11, 8);
						
						// Tratando variáveis de Pergunta e resposta para caber no Grid.
						if (strlen($vMsgResposta) > 50) {
							$vMsgResposta = substr($vMsgResposta, 0, 50) . "...";
						}
						
						if (strlen($vMsgPergunta) > 50) {
							$vMsgPergunta = substr($vMsgPergunta, 0, 50) . "...";
						}
						
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

						// Link para responder mensagem
						echo "			<tr><td><strong>Pergunta</strong><br><a href=\"responder.php?id={$rIdPergunta}\">{$CSS_AbrirFonte}{$vMsgPergunta}{$CSS_FecharFonte }</a></td></tr>";

						echo "				</table>";
						echo "			</td>";
						echo "		</tr>";
						echo "	</tbody>";
		
						echo "	</table>";
						echo "	</div>";
					}
					
				?>
					
				<ul class="actions">
				<?php
						if( $vPagina > 0 )
						{
						   $vAnterior = $vPagina - 2;
						   echo "<li><a href=\"$_PHP_SELF?pagina=$vAnterior\" class=\"button\">&nbsp<<&nbsp</a></li>";
							   if ($vSobra > $vLimite) {
							   	echo "<li><a href=\"$_PHP_SELF?pagina=$vPagina\" class=\"button\">&nbsp>>&nbsp</a></li>";
								}
						}
						else if( $vPagina == 0 )
						{
							if ($vCount > $vLimite) {
						   	echo "<li><a href=\"$_PHP_SELF?pagina=$vPagina\" class=\"button\">&nbsp>>&nbsp</a></li>";
							}
						}
						else if( $vSobra < $vLimite )
						{
						   $vAnterior = $vPagina - 2;
						   echo "<li><a href=\"$_PHP_SELF?pagina=$Anterior\" class=\"button\">&nbsp<<&nbsp</a></li>";
						}
					
						// Fecha conexão com o banco de dados.
						$mysqli->close();
					?>
					<li><a href="pmanager.php" class="button">Voltar</a></li>
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
