﻿<?php
	// Funções específicas do módulo Formação
	
	function retornaCargo($id)
	{
		$programa = recuperaDados("sis_emia_cargo",$id,"Id_Cargo");
		return $programa['Cargo'];	
	}
	
	function retornaStatus($id)
	{
		if($id != 0)
		{
			return "Ativo";
		}
		else
		{
			return "Inativo";	
		}	
	}
	
	function retornaPeriodoEmia($idPedido,$parcelas)
	{
		$con = bancoMysqli();
		$sql1 = "SELECT * FROM igsis_parcelas WHERE idPedido = '$idPedido' AND valor > '0' ORDER BY vigencia_inicio ASC LIMIT 0,1";
		$sql2 = "SELECT * FROM igsis_parcelas WHERE idPedido = '$idPedido' AND valor > '0' ORDER BY vigencia_final DESC LIMIT 0,1";
		$query1 = mysqli_query($con,$sql1);
		$query2 = mysqli_query($con,$sql2);
		$data1 = mysqli_fetch_array($query1);
		$data2 = mysqli_fetch_array($query2);
		$periodo = "De ".exibirDataBr($data1['vigencia_inicio'])." a ".exibirDataBr($data2['vigencia_final']);	
		return $periodo;
	}
	function pdfEmia($idPedido)
	{
		$con = bancoMysqli();
		$sql = "SELECT * FROM sis_emia WHERE idPedidoContratacao = '$idPedido'";
		$query = mysqli_query($con,$sql);
		$emia = mysqli_fetch_array($query);
		$cargo = recuperaDados("sis_emia_cargo",$emia['IdCargo'],"Id_Cargo");
		$programa = recuperaDados("sis_emia_programa",$emia['IdPrograma'],"Id_Programa");
		$linguagem = recuperaDados("sis_emia_linguagem",$emia['IdLinguagem'],"Id_Linguagem");
		$x['Cargo'] = $cargo['Cargo'];
		$x['Programa'] = $programa['Programa'];
		$x['descricaoPrograma'] = $programa['descricao'];
		$x['edital'] = $programa['edital'];
		$x['linguagem'] = $linguagem['Linguagem'];
		$x['processoPagamento'] = $emia['NumeroProcessoPagamento'];
		return $x;
	}	
?>