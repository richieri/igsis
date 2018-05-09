<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");
require_once("../funcoes/funcoesComunicacao.php");

$data_inicio = exibirDataMysql($_GET['inicio']);
$data_final = exibirDataMysql($_GET['final']);
$idLocal = $_GET['local'];
$idInstituicao = $_GET['instituicao'];


$con = bancoMysqli();
$sql_busca = 
  "SELECT idEvento FROM igsis_agenda WHERE data >= '$data_inicio' AND data <= '$data_final' AND idInstituicao = '$idInstituicao' AND idLocal = $idLocal ORDER BY data, hora";   
$query_busca = mysqli_query($con,$sql_busca);


// gera a consulta

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=revista_local.doc");
?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
	<style type='text/css'>
	.style_01 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
</style>

<p><?php echo $sql_busca ?></p>

<?php
$tipo_evento = "";
while($busca = mysqli_fetch_array($query_busca))
{
	$evento = recuperaDados("ig_evento",$busca['idEvento'],"idEvento");
	$tipo = recuperaDados("ig_tipo_evento",$evento['ig_tipo_evento_idTipoEvento'],"idTipoEvento");
	$servico = geraServico($busca['idEvento']);
	$faixa = recuperaDados("ig_etaria", $evento['faixaEtaria'],"idIdade"); 
?>

	<h3>[ <?php echo $evento['nomeEvento'] ?> ]</h3>

	<p><?php echo $evento['releaseCom'] ?></p>

	<?php 
	if($tipo == 1){
		listaFilmesCom($busca['idEvento']);	
		
	}
	?>	
	
	<p><?php
	if($tipo != 1)
	{
		echo $servico; 
	}
	?></p>


	<?php 
}
?>
</body>
</html>



