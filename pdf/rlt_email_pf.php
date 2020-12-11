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
$pedido = siscontrat($id_ped);
$pf = siscontratDocs($pedido['IdProponente'],1);
$usuario = $conexao->query("SELECT nomeCompleto, email FROM ig_usuario WHERE idUsuario = '$idUsuario'")->fetch_assoc();

switch ($idUsuario){
    case "1121":
        $email = "caiobaudocumentos@gmail.com";
        break;
    case "1256":
        $email = "yankacontratos@gmail.com";
        break;
    case "1429":
        $email = "coracontratos@gmail.com";
        break;
    case "1440":
        $email = "laiscontratos@gmail.com";
        break;
    case "1462":
        $email = "alinegrisacontratos@gmail.com";
        break;
    case "1461":
        $email = "pagamentosjornada2020@gmail.com";
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

// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - Email.doc");

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>

<p style="text-align:justify">Prezado(a) Senhor(a) <?= $pf['Nome'] ?>,</p>
<p>&nbsp;</p>
<p style="text-align:justify">Tendo em vista a apresentação <?= $Objeto?>, na data/período de <?= $Periodo?>, encaminho em anexo, para fins de pagamento, os itens abaixo relacionados:</p>
<p style="text-align:justify">a) Recibo da nota de empenho (para ser assinado pelo(a) contratado(a));</p>
<p style="text-align:justify">b) Pedido de pagamento (para ser assinado pelo(a) contratado(a));</p>
<p style="text-align:justify">c) Recibo de pagamento (para ser assinado pelo(a) contratado(a));</p>
<p>&nbsp;</p>
<p style="text-align:justify">Informo que a documentação acima citada deverá ser devolvida digitalizada em PDF, juntamente com as certidões fiscais de Pessoa Física ( CTM, CADIN, CND, CNDT e CCM ) atualizadas, <strong>somente através do e-mail <?= $email ?>, em até 48 horas, impreterivelmente.</strong></p>
<p>&nbsp;</p>
<p style="text-align:justify">Para fins de arquivo, segue também o Anexo e a Nota de Empenho da referida contratação.</p>
<p>&nbsp;</p>
<p style="text-align:justify">Atenciosamente,</p>
<p>&nbsp;</p>
<p><?= $usuario['nomeCompleto'] ?><br>
SMC / Pagamentos Artísticos</p>
<!--<p>Tel: (11) 3397-0191</p>-->
<p>&nbsp;</p>
</body>
</html>