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
$pj = siscontratDocs($pedido['IdProponente'],2);
$rep01 = siscontratDocs($pj['Representante01'],3);
$pj = siscontratDocs($pedido['IdProponente'],2);


dataPagamento($id_ped);

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$NumeroProcesso = $pedido["NumeroProcesso"];


$ano=date('Y');

$codPed = "";

// Representante01

$rep01Nome = $rep01["Nome"];


// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - EmailKitPagamento.doc"); 

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>

<p align="justify">Prezado(a) Senhor(a) <?php echo $rep01Nome?>,</p>
<p align="justify">Tendo em vista a apresentação <?php echo $Objeto?>, no período de <?php echo $Periodo?>, encaminho em anexo,  para fins de pagamento, os itens abaixo relacionados:</p>
<p align="justify">a) Recibo da nota de empenho (deverá ser devidamente assinado pelo(a) representante legal da Empresa);</p>
<p align="justify">b) Pedido de pagamento (deverá ser devidamente copiado em papel timbrado da Empresa, carimbado e assinado pelo(a) representante legal);</p>
<p align="justify">c) Dados para emissão da Nota Fiscal eletrônica;</p>
<p align="justify">d) Declaração do Simples Nacional (deverá ser devidamente copiada em papel timbrado da Empresa, carimbada e assinada pelo(a) representante legal, somente em caso de Empresa optante pelo Simples Nacional).</p>
<p align="justify">Cabe ressaltar que, caso seja optante do Simples Nacional, o referido documento de arrecadação atualizado (Guia do DAS – referente ao mês (DATA) – com vencimento em (DATA) deverá ser encaminhado juntamente com o comprovante bancário de pagamento.</p>
<p align="justify">Informo que a documentação acima citada deverá ser devolvida digitalizada, <strong>somente através do e-mail smc.pagamentosartisticos@gmail.com, em até 48 horas, impreterivelmente.</strong></p>
<p>&nbsp;</p>
<p align="justify">Atenciosamente,</p>
<p>SMC / Pagamentos Artísticos</p>
<p>Tel: (11) 3397-0191</p>
<p>&nbsp;</p>
</body>
</html>