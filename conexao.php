<?php

	$user = "root";
	$senha = "usbw";
	$banco = "exercicio_4b";
	$server = "localhost";
	
	$conexao = mysqli_connect($server, $user, $senha, $banco);
	
	mysqli_set_charset($conexao,"utf8");
?>