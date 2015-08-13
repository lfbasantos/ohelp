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
	echo " 	<header class=\"major\">";
	echo "   <h3> Login de Usuários </h3>";
	echo "   <img src=\"imgs/logo2.png\" />";
	echo " 	</header>";

	// Conexão com o banco de dados 
	require "comum.php"; 

	// Inicia sessões 
	session_start(); 
		// Inicializa Debug
		$_SESSION["eDebug"] = 0;
		$vDebug = $_SESSION["eDebug"];
		if ( $vDebug == 1 ) {
			echo "[DEBUG IS ON]<br>";
		}

	
   // Identifica Login Facebook e Faz o Processamento para viabilizar Cadastro e Login
   
   // Identifica se existe sessão facebook
   
   if ($_SESSION['FBID']) {
   	
		// Sessão no Facebook existe. 
		// Atribuindo ID Facebook como Nome de Login
		$vLogin = $_SESSION['FBEMAIL'];
		
		//Verificando se já existe o Cadastro.    	
		$vConsulta = "SELECT  id_usuario, id_perfil FROM usuarios where login = '{$vLogin}'"; 
		$vRetorno = $mysqli->query($vConsulta) or die("ERR01 - Erro no banco de dados!"); 
		$vLinha = $vRetorno->fetch_assoc();
		$rPerfil = $vLinha["id_perfil"];
		$rIdUsuario = $vLinha["id_usuario"];
		
		If ($rPerfil > 0) {
			
			// Login Já existe. Gera as Variáveis e Encerra Login
			$_SESSION["eUser"] = $rIdUsuario; 
			$_SESSION["ePerfil"] = $rPerfil;
			$_SESSION["eNome"] = $_SESSION['FBFULLNAME'];
			$_SESSION["eLogin"] = $_SESSION['FBEMAIL'];
			header("Location: main.php"); 
			exit; 
			
		} else {
			
			// Sessão Facebook está ativa, mas Login não existe.
			// Isso indica que é um usuário novo. registrando na base:
			//Dados validados, realiza insert na base e disponibiliza Link para página Inicial.
			// No caso dos usuários facebook, será considerado login o Email Facebook.
			$vInsere="INSERT INTO usuarios(nome, sobrenome, login, nickname, email, senha, id_perfil, dt_ins, dt_atu) 
			VALUES ('{$_SESSION['FULLNAME']}','N/A','{$_SESSION['EMAIL']}','{$_SESSION['FULLNAME']}','{$_SESSION['EMAIL']}','null',4,now(),now())";
			
			$vRetorno = $mysqli->query($vInsere) or die("ERR03 - Erro no banco de dados!"); 
				if ($vRetorno===TRUE) {
					// Login Já existe. Gera as Variáveis e Encerra Login
					$_SESSION["eUser"] = $rIdUsuario; 
					$_SESSION["ePerfil"] = $rPerfil;
					$_SESSION["eNome"] = $_SESSION['FULLNAME'];
					$_SESSION["eLogin"] = $_SESSION['EMAIL'];
					header("Location: main.php"); 
					exit; 
				} else {

					echo "<p>Erro ao realizar o registro, entre em contato com contato@lftecnologia.com</p>";					
					echo " 	<ul class=\"actions\">";
					echo " 		<li><a href=\"index.php\" class=\"button\">Início</a></li>";
					echo " 	</ul>";		
 
				}
			}

   }	
	
	// Não houve Login Facebook, continua com os dados enviados pelo formulário.
	// Recupera o login 
	$vLogin = isset($_POST["eLogin"]) ? addslashes(trim($_POST["eLogin"])) : FALSE; 

	// Recupera a senha criptografando em MD5 
	$vSenha = isset($_POST["ePass"]) ? md5(trim($_POST["ePass"])) : FALSE; 

	// Usuário não forneceu a senha ou o login 
	if(!$vLogin || !$vSenha) 
	{ 

		echo "<p>Você deve digitar sua senha e login!</p>";					
		echo " 	<ul class=\"actions\">";
		echo " 		<li><a href=\"index.php\" class=\"button\">Voltar</a></li>";
		echo " 	</ul>";
							 
		if ( $vDebug == 1 ) {
			echo "[eLogin: " . $eLogin . "]<br>";
			echo "[ePass: " . $ePass . "]<br>";
			echo "[vLogin: " . $vLogin . "]<br>";
			echo "[vSenha: " . $vSenha . "]<br>";
		}
	exit; 
	} 

	/*
	* Executa a consulta no banco de dados. 
	*/ 
	$vConsulta = "SELECT id_usuario, nome, login, senha, id_perfil FROM usuarios where login = '{$vLogin}'"; 
	$vRetorno = $mysqli->query($vConsulta) or die("ERR01 - Erro no banco de dados!"); 
	$vLinha = $vRetorno->fetch_assoc();
	$rCodigo = $vLinha["id_usuario"];
	$rLogin = $vLinha["login"];
	$rSenha = $vLinha["senha"];
	$rPerfil = $vLinha["id_perfil"];
	$rNome = $vLinha["nome"];
	
	// Call Debug
	if ( $vDebug == 1 ) {
			echo "[rLogin: " . $rLogin . "]<br>";
			echo "[rCodigo: " . $rCodigo . "]<br>";
			echo "[rSenha: " . $rSenha . "]<br>";
			echo "[rPerfil: " . $rPerfil . "]<br>";
			echo "[rNome: " . $rNome . "]<br>";
		}

	if(!strcmp($rSenha, $vSenha)) 
	{ 
		$_SESSION["eUser"] = $rCodigo; 
		$_SESSION["ePerfil"] = $rPerfil;
		$_SESSION["eNome"] = $rNome;
		$_SESSION["eLogin"] = $rLogin;
		
		//	echo "Perfil: [{$rPerfil}]";
		
			// Redireciona Gestor
			if ($rPerfil == 1 or $rPerfil == 2 or $rPerfil == 3 ) {
				header("Location: pmanager.php"); 
				exit; 
			}
		
			// Redireciona usuário comum
			if ($rPerfil == 4) {
				header("Location: puser.php"); 
				exit; 
			}
			
	} 
	// Senha inválida 
	else 
	{ 

		echo "<p>Senha inválida!</p>";					
		echo " 	<ul class=\"actions\">";
		echo " 		<li><a href=\"index.php\" class=\"button\">Voltar</a></li>";
		echo " 	</ul>";


	exit; 
	} 

		// Encerra Layout
		echo " </section>";
?>

</body>
</html>