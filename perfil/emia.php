<?php
//include para EMIA

if(isset($_GET['p']))
{
	$p = $_GET['p'];	
}
else
{
	$p = "index";
}
include "../funcoes/funcoesSiscontrat.php";	
include "../funcoes/funcoesEmia.php";	
include "m_emia/".$p.".php";
?>