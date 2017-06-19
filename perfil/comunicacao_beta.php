<?php
	//include para comunicação
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = "index";
	}
	include "../funcoes/funcoesComunicacao.php";
	include "m_comunicacao_beta/".$p.".php";
?>