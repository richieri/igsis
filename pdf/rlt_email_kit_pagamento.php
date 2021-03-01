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
$pj = siscontratDocs($pedido['IdProponente'],2);
$rep01 = siscontratDocs($pj['Representante01'],3);
$pj = siscontratDocs($pedido['IdProponente'],2);
$usuario = $conexao->query("SELECT nomeCompleto, email FROM ig_usuario WHERE idUsuario = '$idUsuario'")->fetch_assoc();

switch ($idUsuario){
    case "1389":
        $email = "andersonpagamentosartisticos@gmail.com";
        break;
    case "1125":
        $email = "tomcontratos@gmail.com";
        break;
    case "1393":
        $email = "brunamotacontratos@gmail.com";
        break;
    case "1392":
        $email = "danielbarbosacontratos@gmail.com";
        break;
    case "1391":
        $email = "marianaoliveiracontratos@gmail.com";
        break;
    case "1429":
        $email = "coracontratos@gmail.com";
        break;
    case "1296":
        $email = "fernandagcontratos@gmail.com";
        break;
    case "1333":
        $email = "dcerescontratos@gmail.com";
        break;
    case "1516":
        $email = "eloizapagamentos@gmail.com";
        break;
    default:
        $email = "smc.pagamentosartisticos@gmail.com";
}


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
<p align="justify">Tendo em vista a apresentação <?php echo $Objeto?>, na data/período de <?php echo $Periodo?>, encaminho em anexo, para fins de pagamento, os itens abaixo relacionados:</p>
<p align="justify">a) Recibo da nota de empenho (para ser assinado pelo(a) representante legal da Empresa);</p>
<p align="justify">b) Pedido de pagamento (para ser assinado pelo(a) representante legal);</p>
<p align="justify">c) Instruções para Emissão da Nota Fiscal Eletrônica;</p>
<p align="justify">d) Declaração do Simples Nacional (para ser assinada pelo(a) representante legal, somente em caso de Empresa optante pelo Simples Nacional).</p>
<p align="justify">Informo que a documentação acima citada deverá ser devolvida digitalizada em PDF, juntamente com as certidões fiscais de Pessoa Física ( CTM, CADIN, CND, CNDT, CCM e FGTS ) atualizadas,<strong> somente através do e-mail <?= $usuario['email'] ?>, em até 48 horas, impreterivelmente.</strong></p>
<p align="justify">Para fins de arquivo da empresa, segue também o Anexo e a Nota de Empenho da referida contratação.</p>
<p>&nbsp;</p>
<p align="justify">Atenciosamente,</p>

<p><?= $usuario['nomeCompleto'] ?></p>
<p>SMC / Pagamentos Artísticos</p>
<p>Tel: (11) 3397-0191</p>
<p>&nbsp;</p>
</body>
</html>