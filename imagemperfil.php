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
		
		if (!isset($_SESSION["eDebug"])) {
		
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
		
		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[ID USUÁRIO: " . $vIdUsuario . "]<br>";
			echo "[ID PERFIL: " . $vIdPerfil . "]<br>";
			echo "[LOGIN: " . $vLogin . "]<br>";
			echo "[NOME: " . $vNome . "]<br>";
			}

		// Se houver Submit, Processa o Upload
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			// Verifica se não houve configuração de arquivo, e redireciona para home
			if (!isset($_FILES["formArquivo"])) {
	         header("HTTP/1.1 301 Moved Permanently");
		      header("location: http://www.lftecnologia.com/apps/dev/odontohelp/puser.php");
				exit(); 		
			}	
						
			// Caminho de destino do arquivo
			$vPath="imgs/profiles/";
			
			// Nome de arquivo enviado pelo formulário
			$vFormArq=basename($_FILES["formArquivo"]["name"]);
			
			// Tipo do Arquivo
			$vTipoArq = pathinfo($vFormArq,PATHINFO_EXTENSION);
			
			// Arquivo de Destino
			$vArq=$vPath . $vLogin . "-" . $vIdUsuario . "." . $vTipoArq;
			
			// Verifica se é arquivo de imagem
			if(isset($_POST["submit"])) {
				$vVerifica = getimagesize($_FILES["formArquivo"]["tmp_name"]);
				if($vVerifica !== false) {
					echo "Arquivo de Imagem - " . $vVerifica["mime"] . ".";
					$vFlagOk = 1;
				} else {
					echo "Arquivo inválido";
					$vFlagOk = 0;
				}
			}
			
			// Valida tamanho da imagem
			if ($_FILES["formArquivo"]["size"] > 1000000) {
				echo "Arquivo de imagem muito grande (limite: 1Mb)";
				$vFlagOk = 0;
			}
			
			// Allow certain file formats
			if($vTipoArq != "jpg" && $vTipoArq != "png" && $vTipoArq != "jpeg"
			&& $vTipoArq != "gif" ) {
				echo "Apenas JPG, JPEG, PNG e GIF são permitidos.";
				$vFlagOk = 0;
			}
			
			// Realiza Upload, se não houver erros
			if ($vFlagOk == 0) {
				echo "Não foi possível realizar o upload.";
			} else {
				if (move_uploaded_file($_FILES["formArquivo"]["tmp_name"], $vArq)) {
					echo "Arquivo ". basename( $_FILES["formArquivo"]["name"]). " transferido com sucesso.";
					
					// Se o arquivo foi transferido com sucesso, abre conexão com a base para inserir ou atualizar o Path
					// Consulta para verificar se já existe Path
					$vConsulta = "select count(*) cnt from imagem_perfil where id_usuario = {$vIdUsuario}"; 
					$vRetorno = $mysqli->query($vConsulta) or die("ERR02 - Erro no banco de dados!"); 
					$vLinha = $vRetorno->fetch_assoc();
					$rFlag = $vLinha["cnt"];
					$vFlag = $rFlag;
					
					// Se já existe, atualiza, senão insere.
					If ($vFlag > 0){
						$vConsulta = "update imagem_perfil set ds_path_img = '{$vArq}', dt_atu = now() where id_usuario = {$vIdUsuario}"; 
						$vRetorno = $mysqli->query($vConsulta) or die("ERR02 - Erro no banco de dados!");
						echo "$vConsulta"; 	
					} else {
						$vConsulta = "insert into imagem_perfil (id_usuario, ds_path_img, dt_ins, dt_atu) 
						values ({$vIdUsuario}, '{$vArq}', now(), now())"; 
						$vRetorno = $mysqli->query($vConsulta) or die("ERR02 - Erro no banco de dados!");
						echo "$vConsulta"; 	
					}
									
					// Com a base de dados atualizada, redireciona para página do perfil
					if ($vIdPerfil == 1 or $vIdPerfil == 2 or $vIdPerfil == 3) {
							header("Location: pmanager.php");
						} else {
							header("Location: puser.php");
					}
					
				} else {
					echo "Erro ao realizar o Upload, tente novamente.";
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
					<ul class="actions">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
					<li>Selecione a Imagem:</li>
					<li><input type="file" name="formArquivo" id="IdArquivo"></li>
					<li><input type="submit" value="Carregar" name="submit"></li>
					<li>Apenas imagens, .png, .jpg, etc.</li>
					<li>Máximo 1Mb por arquivo.</li>
					<li>Melhor formatação: 100x100px</li>
					</form>
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
