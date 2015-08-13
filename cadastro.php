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

	//Inicializ Layout
	echo " <section id=\"one\" class=\"wrapper style2 special\">";
	
	// Conexão com o banco de dados 
	require "comum.php"; 
	
	// Inicializa Debug.
	$vDebug=0;
	
	// Inicializa variáveis locais do formulário.
	$vCadPrimeiroNome = $vCadUltimoNome = $vCadLogin = $vCadEmail = $vCadSenha1 = $vCadSenha2 = $vCadNick = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Valida Senha
		$vValidaSenha1 = isset($_POST["cadSenha1"]) ? md5(trim($_POST["cadSenha1"])) : ""; 	
		$vValidaSenha2 = isset($_POST["cadSenha2"]) ? md5(trim($_POST["cadSenha2"])) : ""; 
		// Processa valores do formulario
		$vCadPrimeiroNome = fValidaInput($_POST["cadPrimeiroNome"]);
		$vCadUltimoNome = fValidaInput($_POST["cadUltimoNome"]);
		$vCadLogin = fValidaInput($_POST["cadLogin"]);
		$vCadEmail = fValidaInput($_POST["cadEmail"]);
		$vCadNick = fValidaInput($_POST["cadNick"]);
		$vCadSenha1 = fValidaInput($vValidaSenha1);
		$vCadSenha2 = fValidaInput($vValidaSenha2);
	}
	
	if ($vDebug==1) {
		echo "[vCadPrimeiroNome]{$vCadPrimeiroNome}";
		echo "[vCadUltimoNome]{$vCadUltimoNome}";
		echo "[vCadLogin]{$vCadLogin}";
		echo "[vCadEmail]{$vCadEmail}";
		echo "[vCadSenha1]{$vCadSenha1}";
		echo "[vCadSenha2]{$vCadSenha2}<br>";
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
		// Valida se o nome de usuário já existe
		if ($vCadLogin <> "") {
			$vConsulta = "SELECT count(*) cnt FROM usuarios where login = '{$vCadLogin}'"; 
			$vRetorno = $mysqli->query($vConsulta) or die("ERR01 - Erro no banco de dados!"); 
			$vLinha = $vRetorno->fetch_assoc();
			$rCnt = $vLinha["cnt"];	
			if ($rCnt > 0) {
				echo "Nome de Usuário já existe!<br>"; 
				$vFlagExit = 1;
			}
		} else {
				echo "Login não pode estar em branco!<br>"; 
				$vFlagExit = 1;
		}
	
		// Valida se o e-mail já existe
		if ($vCadEmail <> "") {
			$vConsulta = "SELECT count(*) cnt FROM usuarios where email = '{$vCadEmail}'"; 
			$vRetorno = $mysqli->query($vConsulta) or die("ERR02 - Erro no banco de dados!"); 
			$vLinha = $vRetorno->fetch_assoc();
			$rCnt = $vLinha["cnt"];	
			if ($rCnt > 0) {
				echo "Email já existe!<br>"; 
				$vFlagExit = 1;
			}
		} else {
				echo "Email não pode estar em branco!<br>"; 
				$vFlagExit = 1;
		}
	
		// Valida se a Senha digita confere.

		if ($vCadSenha1 == "" or $vCadSenha2 == "") {
				echo "Senhas não podem estar em branco!<br>"; 
				$vFlagExit = 1;
		} else {
			if ($vCadSenha1 <> $vCadSenha2) {
				echo "Senhas não conferem!<br>"; 
				$vFlagExit = 1;
			}
		}
		
		if ($vFlagExit > 0) {
			echo "Clique no botão Voltar do Browser e tente novamente.<br>"; 
			exit;
		} else {
		//Dados validados, realiza insert na base e disponibiliza Link para página Inicial.
		$vInsere="INSERT INTO usuarios(nome, sobrenome, login, nickname, email, senha, id_perfil, dt_ins, dt_atu) 
		VALUES ('{$vCadPrimeiroNome}','{$vCadUltimoNome}','{$vCadLogin}','{$vCadNick}','{$vCadEmail}','{$vCadSenha1}',5,now(),now())";
		
		$vRetorno = $mysqli->query($vInsere) or die("ERR03 - Erro no banco de dados!"); 
			if ($vRetorno===TRUE) {
	
				echo " 	<h3>Registro Realizado!</h3>";		
				echo " 	<ul class=\"actions\">";
				echo " 		<li><a href=\"index.php\" class=\"button\">Login</a></li>";
				echo " 	</ul>";				
				exit;
			} else {

				echo " 	<h3>Erro ao realizar o registro, entre em contato com suporte.</h3>";		
				echo " 	<ul class=\"actions\">";
				echo " 		<li><a href=\"http://www.lftecnologia.com/Contatos\" class=\"button\">Suporte</a></li>";
				echo " 	</ul>";	
				exit;
			}
				
		}
				// Encerra Layout
				echo " </section>";
	}

	?>
	<!-- FIM PHP -->
				
		<!-- One -->
			<section id="two" class="wrapper style2 special">
				<header class="major">
					<img src="imgs/logo2.png" alt="" />
				</header>						
					<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<div class="row uniform">

								<div class="6u 12u$(xsmall)">
									<input type="text" name="cadPrimeiroNome" placeholder="Nome" />
								</div>

								<div class="6u$ 12u$(xsmall)">
									<input type="text" name="cadUltimoNome" placeholder="Sobrenome" />
								</div>
								
								<div class="6u 12u$(xsmall)">
									<input type="email" name="cadEmail" placeholder="Email" />
								</div>

								<div class="6u$ 12u$(xsmall)">
									<input type="text" name="cadLogin" placeholder="Login" />
								</div>

								<div class="6u 12u$(xsmall)">
									<input type="text" name="cadNick"  placeholder="Apelido" />
								</div>

								<div class="6u 12u$(xsmall)">
									<input type="password" name="cadSenha1" placeholder="Senha" />
								</div>

								<div class="6u 12u$(xsmall)">
									<input type="password" name="cadSenha2" placeholder="Confirme a Senha" />
								</div>
								
								
								<div class="6u 12u$(small)">
									<input type="checkbox" id="demo-copy" name="demo-copy">
									<label for="demo-copy">Concordo com os Termos</label>
								</div>

								<div class="12u$">
									<ul class="actions">
										<li><input type="submit" value="&nbsp Ok! &nbsp" class="special" /></li>
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


