<?php 

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");


//CONEXÃO COM BANCO DE DADOS 
$conexao = bancoMysqli();


//CONSULTA 
$id_ped=$_GET['id'];

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],1);

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$Duracao = $pedido["Duracao"];
$CargaHoraria = $pedido["CargaHoraria"];
$Local = $pedido["Local"];
$ValorGlobal = dinheiroParaBr($pedido["ValorGlobal"]);
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]);
$FormaPagamento = $pedido["FormaPagamento"];
$Justificativa = $pedido["Justificativa"];
$Fiscal = $pedido["Fiscal"];
$Suplente = $pedido["Suplente"];
$NumeroProcesso = $pedido["NumeroProcesso"];


$Nome = $pessoa["Nome"];
$NomeArtistico = $pessoa["NomeArtistico"];
$EstadoCivil = $pessoa["EstadoCivil"];
$Nacionalidade = $pessoa["Nacionalidade"];
$DataNascimento = exibirDataBr($pessoa["DataNascimento"]);
$RG = $pessoa["RG"];
$CPF = $pessoa["CPF"];
$CCM = $pessoa["CCM"];
$OMB = $pessoa["OMB"];
$DRT = $pessoa["DRT"];
$cbo = $pessoa["cbo"];
$Funcao = $pessoa["Funcao"];
$Endereco = $pessoa["Endereco"];
$Telefones = $pessoa["Telefones"];
$Email = $pessoa["Email"];
$INSS = $pessoa["INSS"];

$ano=date('Y');


// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - Parcela $id_parcela.doc");

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>

<p align="center"><strong>RECIBO DE PAGAMENTO</strong></p>
<p>&nbsp;</p>
<p align="justify">Recebi da Prefeitura de São Paulo - Secretaria Municipal de Cultura a importância de R$ <?php echo $ValorGlobal ?> (<?php echo $ValorPorExtenso?>  ) referente à serviços prestados por meio do Processo Administrativo <?php echo $NumeroProcesso ?>.</p>
<p align="justify"><strong>Nome:</strong> <?php echo $Nome?></p>
<p><strong>Estado Civil:</strong> <?php echo $EstadoCivil?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Nacionalidade:</strong> <?php echo $Nacionalidade?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CCM:</strong> <?php echo $CCM?></p> 
<p><strong>RG:</strong> <?php echo $RG?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CPF:</strong> <?php echo $CPF?></p>
<p><strong>Endereço:</strong> <?php echo $Endereco?></p>
<p><strong>Telefone:</strong> <?php echo $Telefones?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>E-mail:</strong> <?php echo $Email?></p> 
<p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> <?php echo $INSS?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Data de Nascimento:</strong> <?php echo $DataNascimento?></p>   
<p align="justify"><strong>Serviço Prestado:</strong> <?php echo $Objeto?></p>
<p align="justify"><strong>Data / Período:</strong> <?php echo $Periodo?></p>
<p align="justify"><strong>Duração:</strong> <?php echo $Duracao?>utos</p>
<p align="justify"><strong>Local:</strong> <?php echo $Local?></p>
<p>&nbsp;</p>
<p align="justify">São Paulo, _______ de ________________________ de <?php echo $ano?>.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" border="0">
<p>________________________</p>
<p><?php echo $Nome?><br/>
   RG: <?php echo $RG ?> <br/>
   CPF: <?php echo $CPF ?></p>
<p align="justify"><strong>OBSERVAÇÃO:</strong> A validade deste recibo fica condicionada ao efetivo por ordem de pagamento ou depósito na conta corrente no Banco do Brasil, indicada pelo contratado, ou na falta deste, ao recebimento no Departamento do Tesouro da Secretaria das Finanças e Desenvolvimento Econômico, situado à Rua Pedro Américo, 32.</p>

</body>
</html>