<?php

    require_once 'funcoesConecta.php';

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: *');
	header('Content-Type: application/json');

	$conn = bancoMysqliProponente();

	if(isset($_GET['programa_id'])){
		$id = $_GET['programa_id'];

		$sql = "SELECT id, funcao FROM formacao_funcoes WHERE tipo_formacao_id = '$id' AND publicado = '1' order by funcao";

		$res = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

		$funcoes =  json_encode($res);

		print_r($funcoes);

	}
