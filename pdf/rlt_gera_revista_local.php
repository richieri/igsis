<?php
	
//require '../include/';
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");
   require_once("../funcoes/funcoesComunicacao.php");
   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli();

if(isset($_POST['inicio']) AND $_POST['inicio'] != "")
	{
		if($_POST['final'] == "")
		{
			$mensagem = "É preciso informar a data final do filtro";	
		}
		else
		{
			if(dataTime($_POST['inicio']) > dataTime($_POST['final']))
			{
				$mensagem = "A data final do filtro deve ser maior que a data inicio";		
				$data_inicio = date('Y-m-d');
				$data_final = date('Y-m-d', strtotime("+30 days",strtotime($data_inicio)));
				$mensagem .= "Filtro aplicado: eventos de ".exibirDataBr($data_inicio)." a ".exibirDataBr($data_final);	
			}
			else
			{
				$data_inicio = exibirDataMysql($_POST['inicio']);
				$data_final = exibirDataMysql($_POST['final']);
				$mensagem = "Filtro aplicado: eventos entre ".$_POST['inicio']." e ".$_POST['final'];
			}
		}
	}
	else
	{
		$data_inicio = date('Y-m-d');
		$data_final = date('Y-m-d', strtotime("+0 days",strtotime($data_inicio)));
		$mensagem = "Filtro aplicado: eventos de ".exibirDataBr($data_inicio)." a ".exibirDataBr($data_final);
	}	
	if(isset($_POST['local']) AND trim($_POST['local']))
	{
		$idLocal = trim($_POST['local']);
		$local = " AND idLocal = '$idLocal' ";	
	}
	else
	{
		$local = "";	
	}
	if(isset($_POST['instituicao']) AND trim($_POST['instituicao']))
	{
		$idInstituicao = $_POST['instituicao'];
		$instituicao = " AND idInstituicao = '$idInstituicao' ";	
	}
	else
	{
		$instituicao = "";	
	}
	
//gera a tabela temporária
	$sql_limpa = "TRUNCATE TABLE temp_emcartaz";	
	$query_limpa = mysqli_query($conexao,$sql_limpa);
	if($query_limpa){

$sql_temp = "SELECT DISTINCT idEvento FROM igsis_agenda WHERE data >= '$data_inicio' AND data <= '$data_final' $instituicao $local ORDER BY data, hora"; 
$query_temp = mysqli_query($conexao,$sql_temp);
	while($consulta = mysqli_fetch_array($query_temp)){
		$evento = recuperaDados("ig_evento",$consulta['idEvento'],"idEvento");
		$idEvento = $evento['idEvento'];
		$idTipo = $evento['ig_tipo_evento_idTipoEvento'];
		$idProjeto = $evento['projetoEspecial'];
		$sql_insert = "INSERT INTO `temp_emcartaz` (`idEmcartaz`, `idEvento`, `idTipo`, `idProjeto`, `idInstituicao`, `idLocal`) VALUES (NULL, '$idEvento', '$idTipo', '$idProjeto', '$local', '$instituicao')";
		mysqli_query($conexao,$sql_insert);
	}
}

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
<p><?php echo $sql_temp ?></p>
<?php
	$t = "";
	$p = "";
	$sql_consulta = "SELECT * FROM temp_emcartaz ORDER BY idProjeto, idTipo";
	$query_consulta = mysqli_query($conexao,$sql_consulta);
	while($consulta = mysqli_fetch_array($query_consulta)){
	$evento = recuperaDados("ig_evento",$consulta['idEvento'],"idEvento");
	$tipo = recuperaDados("ig_tipo_evento",$consulta['idTipo'],"idTipoEvento");
	$servico = geraServico($consulta['idEvento']);
	$faixa = recuperaDados("ig_etaria", $evento['faixaEtaria'],"idIdade");
?>
	<?php if($consulta['idTipo'] != $t){ ?>
    <h2>
	<br />
	<?php echo $tipo['tipoEvento'] ?>
    <?php 
	$t = $tipo['idTipoEvento'];	
	} ?>
    </h2>
  	<h3>[ <?php echo $evento['nomeEvento'] ?> ]</h3>
    <p><?php echo $evento['sinopse'] ?></p>
	    <p>Faixa etária: <?php echo $faixa['faixa']; ?></p>
	<?php 
		if($consulta['idTipo'] == 1){
			listaFilmesCom($consulta['idEvento']);	
			
		}
	?>	
	<?php 
		if($consulta['subEvento'] == 1){
			echo "<h3>Sub-eventos</h3>";
			resumoSubEventos($consulta['idEvento']);	
			
		}
	?>	
	
	
	
	<p><?php
	if($t != 1){
	 echo $servico; 
	}
	 ?></p>

	
	
	
	

<?php 
	}
?>
</body>
</html>
