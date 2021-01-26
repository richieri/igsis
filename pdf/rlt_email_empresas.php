<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS
$conexao = bancoMysqli();

//CONSULTA
$id_ped=$_GET['id'];
$modeloEmail = $_GET['modelo'];
$idUsuario = $_GET['idUsuario'];

switch ($modeloEmail) {
    case 'empresas':
        $item4 = "Declaração do Simples Nacional (para ser assinada pelo(a) representante legal, somente em caso de Empresa optante pelo Simples Nacional).";
        break;
    case 'cooperativas':
        $item4 = "Modelos de declarações comprobatórias quanto a isenção ou imunidade de impostos (para ser preenchida e assinada pelo(a) representante legal).";
        break;
    case 'associacoes':
        $item4 = "Declaração de Instituições de Caráter Filantrópico, Recreativo, Cultural, Científico e às Associações Civis (para ser preenchida e assinada pelo(a) representante legal).";
        break;
    case 'minuta':
        $item4 = "Declaração do Simples Nacional (para ser assinada pelo(a) representante legal, somente em caso de Empresa optante pelo Simples Nacional);";
        break;
}

$pedido = siscontrat($id_ped);
$pj = siscontratDocs($pedido['IdProponente'],2);
$rep01 = siscontratDocs($pj['Representante01'],3);
$usuario = $conexao->query("SELECT nomeCompleto,email FROM ig_usuario WHERE idUsuario = '$idUsuario'")->fetch_assoc();

switch ($idUsuario) {
    case "274":
        $email = "mariasoniapagamentos@gmail.com";
        break;
    case "275":
        $email = "myviapagamentos@gmail.com";
        break;
    case "393":
        $email = "caiogpagamentos@gmail.com";
        break;
    case "424":
        $email = "silviarpagamentos@gmail.com";
        break;
    case "445":
        $email = "andrearpagamentos@gmail.com";
        break;
    case "569":
        $email = "documentoscastro32@gmail.com";
        break;
    case "844":
        $email = "mantovanidocumentos@gmail.com";
        break;
    case "993":
        $email = "ivanilsonpagamentos@gmail.com";
        break;
    case "1121":
        $email = "caiobaudocumentos@gmail.com";
        break;
    case "1123":
        $email = "manfrinicontratos@gmail.com";
        break;
    case "1126":
        $email = "cortezmacontratos@gmail.com";
        break;
    case "1135":
        $email = "eltonbpagamentos@gmail.com";
        break;
    case "1256":
        $email = "yankacontratos@gmail.com";
        break;
    case "1295":
        $email = "menezesadriana2011@gmail.com";
        break;
    case "1320":
        $email = "gabrielalemoscontratos@gmail.com";
        break;
    case "1333":
        $email = "dcerescontratos@gmail.com";
        break;
    case "1390":
        $email = "pedrohcontratos@gmail.com";
        break;
    case "1429":
        $email = "coracontratos@gmail.com";
        break;
    case "1440":
        $email = "laiscontratos@gmail.com";
        break;
    case "1455":
        $email = "teodorocontratos@gmail.com";
        break;
    case "1461":
        $email = "pagamentosjornada2020@gmail.com";
        break;
    case "1462":
        $email = "alinegrisacontratos@gmail.com";
        break;
    case "1463":
        $email = "lucasperozzicontratos@gmail.com";
        break;
    case "1465":
        $email = "adrianocontratos@gmail.com";
        break;
    case "1466":
        $email = "pricontratos@gmail.com";
        break;
    case "1467":
        $email = "leticiamartinscontratos@gmail.com";
        break;
    default:
        $email = "smc.pagamentosartisticos@gmail.com";
}

dataPagamento($id_ped);

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$NumeroProcesso = $pedido["NumeroProcesso"];
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

<p style="text-align:justify">Prezado(a) Senhor(a) <?= $rep01Nome?>,</p>
<p>&nbsp;</p>
<p style="text-align:justify">Tendo em vista a apresentação <?= $Objeto?>, na data/período de <?= $Periodo?>, encaminho em anexo, para fins de pagamento, os itens abaixo relacionados:</p>
<p style="text-align:justify">a) Recibo da nota de empenho (para ser assinado pelo(a) representante legal da Empresa);</p>
<p style="text-align:justify">b) Pedido de pagamento (para ser assinado pelo(a) representante legal);</p>
<p style="text-align:justify">c) Instruções para Emissão da Nota Fiscal Eletrônica;</p>
<p style="text-align:justify">d) <?=$item4?></p>
<?php if ($modeloEmail == 'minuta'): ?>
<p style="text-align:justify">e) Termo de Contrato nº  (para ser assinado pelo(a) representante legal e uma testemunha) Rubrica nas primeiras folhas e assinatura na última folha.</p>
<?php endif; ?>
<p style="text-align:justify">Informo que a documentação acima citada deverá ser devolvida digitalizada em PDF, juntamente com as certidões fiscais de Pessoa Jurídica ( CTM, CADIN, CND, CNDT, CCM e FGTS ) atualizadas,<strong> somente através do e-mail <?= $email ?> , em até 48 horas, impreterivelmente. </strong> </p>
<p style="text-align:justify">Para fins de arquivo da empresa, segue também o Anexo e a Nota de Empenho da referida contratação.</p>
<p>&nbsp;</p>
<p style="text-align:justify">Atenciosamente,</p>
<p>&nbsp;</p>
<p><?=$usuario['nomeCompleto']?><br>
SMC / Pagamentos Artísticos</p>
<!--<p>Tel: (11) 3397-0191</p>-->
<p>&nbsp;</p>
</body>
</html>