<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS
$conexao = bancoMysqli();

//CONSULTA
$id_ped=$_GET['id'];
$idUsuario = $_GET['idUsuario'];
$modeloEmail = $_GET['modelo'];

switch ($modeloEmail) {
    case 'empresas':
        $item4 = "Declaração do Simples Nacional (assinada pelo(a) representante legal, somente em caso de Empresa optante pelo Simples Nacional).";
        break;
    case 'cooperativas':
        $item4 = "Documento comprobatório quanto a isenção ou imunidade de impostos.";
        break;
    case 'associacoes':
        $item4 = "Declaração de Associação sem fins lucrativos.";
        break;
}

$pedido = siscontrat($id_ped);
$pj = siscontratDocs($pedido['IdProponente'],2);
$rep01 = siscontratDocs($pj['Representante01'],3);
$pj = siscontratDocs($pedido['IdProponente'],2);
$nomeUsuario = recuperaDados('ig_usuario',$idUsuario,'idUsuario')['nomeCompleto'];

dataPagamento($id_ped);

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$NumeroProcesso = $pedido["NumeroProcesso"];


$ano=date('Y');

$codPed = "";

$dataAtual = date('d/m/Y');

// Representante01

$rep01Nome = $rep01["Nome"];


// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - Email". ucfirst($modeloEmail) .".doc");

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>

<p align="justify">Prezado(a) Senhor(a) <?= $rep01Nome?>,</p>
<p align="justify">Tendo em vista a apresentação <?= $Objeto?>, no período de <?= $Periodo?>, DE ACORDO COM PROGRAMAÇÃO DO EVENTO NO PERÍODO DA VIRADA CULTURAL., encaminho em anexo, para fins de pagamento, os itens abaixo relacionados:</p>
<p align="justify">1) Recibo da nota de empenho (assinado pelo(a) representante legal da Empresa);</p>
<p align="justify">2) Pedido de pagamento (assinado pelo(a) representante legal);</p>
<p align="justify">3) Nota Fiscal eletrônica;</p>
<p align="justify">4) <?=$item4?></p>
<p align="justify">Para fins de arquivamento da empresa, segue o Anexo e a Nota de Empenho da referida contratação.  </p>
<p align="justify">Informo que a documentação acima citada deverá ser devolvida digitalizada, <strong>somente através do e-mail smc.pagamentosartisticos@gmail.com, em até 48 horas, impreterivelmente.</strong></p>
<p>&nbsp;</p>
<p align="justify">Atenciosamente,</p>
<p><?=$nomeUsuario?></p>
<p>SMC / Pagamentos Artísticos</p>
<p>Tel: (11) 3397-0191</p>
<p>&nbsp;</p>
</body>
</html>