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


if($data_inicio != "")
{
	if($data_final == "")
	{
		$mensagem = "É preciso informar a data final do filtro";	
	}
	else
	{
		if($data_inicio > $data_final)
		{
			$mensagem = "A data final do filtro deve ser maior que a data inicio";		
			$data_inicio = date('Y-m-d');
			$data_final = date('Y-m-d', strtotime("+30 days",strtotime($data_inicio)));
			$mensagem .= "Filtro aplicado: eventos de ".exibirDataBr($data_inicio)." a ".exibirDataBr($data_final);	
		}
		else
		{
			/*$data_inicio = exibirDataMysql($_POST['inicio']);
			$data_final = exibirDataMysql($_POST['final']);*/
			$mensagem = "Filtro aplicado: eventos entre ".$data_inicio." e ".$data_final;
		}
	}
}
else
{
	$mensagem = "É preciso informar a data de início do filtro!";
}	
/*if(isset($idLocal) AND trim($idLocal))
{
	$idLocal = trim($_POST['local']);
	$local = " AND idLocal = '$idLocal' ";	
}
else
{
	$local = "";	
}*/
if(isset($idInstituicao) AND trim($idInstituicao))
{
	/*$idInstituicao = $_POST['instituicao'];*/
	$instituicao = " AND idInstituicao = '$idInstituicao' ";	
}
else
{
	$instituicao = "";	
}

$con = bancoMysqli();
$sql_busca = 
  "SELECT 
    idEvento 
  FROM 
    igsis_agenda 
  WHERE data >= '$data_inicio' 
  AND   data <= '$data_final'
  AND   idInstituicao = '$idInstituicao'
  AND   idLocal = $idLocal
  ORDER BY data, hora"; 
  
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
	$tipo = recuperaDados("ig_tipo_evento",$busca['idTipo'],"idTipoEvento");
	$servico = geraServico($busca['idEvento']);
	$faixa = recuperaDados("ig_etaria", $evento['faixaEtaria'],"idIdade"); 

	if($busca['idTipo'] != $tipo_evento)
	{ 
		?>
		<h2>
			<br />
			<?php 
			echo $tipo['tipoEvento'];
			echo $tipo_evento .= $tipo['idTipoEvento'];	
		} 
		?>
	</h2>
	<h3>[ <?php echo $evento['nomeEvento'] ?> ]</h3>
	<p><?php echo $evento['releaseCom'] ?></p>
	<?php 
	if($busca['idTipo'] == 1){
		listaFilmesCom($busca['idEvento']);	
		
	}
	?>	
	<?php 
	if($busca['subEvento'] == 1){
		echo "<h3>Sub-eventos</h3>";
		resumoSubEventos($busca['idEvento']);	
		
	}
	else
	{
		echo "";	
	}
	?>	



	<p><?php
	if($tipo_evento != 1)
	{
		echo $servico; 
	}
	?></p>


	<?php 
}
?>
</body>
</html>



