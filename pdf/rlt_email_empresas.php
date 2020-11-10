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

switch ($idUsuario){
    case "1081":
        $email = "casponcontratos@gmail.com";
        break;
    case "1121":
        $email = "caiobaudocumentos@gmail.com";
        break;
    case "1125":
        $email = "tomcontratos@gmail.com";
        break;
    case "1256":
        $email = "yankacontratos@gmail.com";
        break;
    case "1296":
        $email = "fernandagcontratos@gmail.com";
        break;
    case "1333":
        $email = "dcerescontratos@gmail.com";
        break;
    case "1389":
        $email = "andersonpagamentosartisticos@gmail.com";
        break;
    case "1391":
        $email = "marianaoliveiracontratos@gmail.com";
        break;
    case "1392":
        $email = "danielbarbosacontratos@gmail.com";
        break;
    case "1393":
        $email = "brunamotacontratos@gmail.com";
        break;
    case "1429":
        $email = "coracontratos@gmail.com";
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