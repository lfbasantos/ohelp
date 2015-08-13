<!DOCTYPE html>
<html lang="pt-BR">
		<meta charset="UTF-8">
<head>
<style>

table, th, td {
    border: 2px solid black;
	border-collapse: collapse;
	text-align:center;
}

</style>
</head>
<body style="background-color:white;font-family:verdana;text-align:left">

<h1>
	<table style="border: hidden;">
		<tr>
			<td><img src="imgs/logo2.png" alt="Odonto Help"></td>
		</tr>
	</table>
</h1>


<?php
	// Inicializa Sessão
	session_start(); 
	
	// Variável de debug. 0 Desligado / 1 Ligado
	$vDebug = $_SESSION["eDebug"];
	
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
		
	// Lista a Página Principal conforme o Perfil

	if ($vIdPerfil == 4)
	{ 
		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[PERFIL 4 - USER]<br>";
		}
		
		header("Location: puser.php"); 
		exit; 
	}
	
	if ($vIdPerfil == 5)
	{ 
		// Call Debug
		if ( $vDebug == 1 ) {
			echo "[PERFIL 3 - GESTOR]<br>";
		}
		
		header("Location: puser.php"); 
		exit; 
	}
	

?>

</body>
</html>