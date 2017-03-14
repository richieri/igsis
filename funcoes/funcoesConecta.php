<?php
	// Conexão de Banco MySQLi
	// Cria conexao ao banco. Substitui o include "conecta_mysql.php" .
	function bancoMysqli()
	{
		$servidor = '200.237.5.34';
		$usuario = 'root';
		$senha = 'lic54eca';
		$banco = 'igsisbeta';
		$con = mysqli_connect($servidor,$usuario,$senha,$banco); 
		mysqli_set_charset($con,"utf8");
		return $con;
	}
	// Cria conexao ao banco de CEPs.
	function bancoMysqliCep()
	{
		$servidor = '200.237.5.34';
		$usuario = 'root';
		$senha = 'lic54eca';
		$banco = 'cep';
		$con = mysqli_connect($servidor,$usuario,$senha,$banco); 
		mysqli_set_charset($con,"utf8");
		return $con;
	}
?>