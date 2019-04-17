<?php
	//include para contratos
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = "index";
	}
	include "../funcoes/funcoesSiscontrat.php";	
	include "../funcoes/funcoesFormacao.php";
	include "m_contratos/".$p.".php";

$idUsuario = $_SESSION['idUsuario'];

$con = bancoMysqli();
$sql = $con->query("SELECT usr.idUsuario, pap.idPapelUsuario, pap.contratos FROM ig_usuario AS usr
INNER JOIN ig_papelusuario AS pap ON usr.ig_papelusuario_idPapelUsuario = pap.idPapelUsuario
WHERE idUsuario = '$idUsuario'");
$array = mysqli_fetch_array($sql);

$coluna = $array['contratos'];

if($coluna = 0){
    echo "<script>window.history.back();</script>";
}
?>