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
$tipoPessoa = $_GET['tipoPessoa'];

$pedido = siscontrat($id_ped);
$infosPessoa = siscontratDocs($pedido['IdProponente'],$tipoPessoa);
$nomeUsuario = recuperaDados('ig_usuario',$idUsuario,'idUsuario')['nomeCompleto'];

dataPagamento($id_ped);

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$NumeroProcesso = $pedido["NumeroProcesso"];
$local = $pedido['local'];


$ano=date('Y');

$codPed = "";

$dataAtual = date('d/m/Y');

// Representante01





if ($tipoPessoa == 2) {
    $rep01 = siscontratDocs($infosPessoa['Representante01'],3);
    $rep01Nome = $rep01["Nome"];

    $razaoSocial = $infosPessoa['Nome'];

    $textoDoc = "<p align='justify'>DECLARO para os devidos fins, que a empresa $razaoSocial, CNPJ ". $infosPessoa['CNPJ'] .", sediada na ". $infosPessoa['Endereco'].", 
    está ciente e de acordo que o pagamento dos serviços a serem prestados, referente a $Objeto, no $Periodo, no $local, 
    ficará condicionado à apresentação do documento, abaixo listado, regularizado: </p>
    <br>
    <p align='justify'>$dataAtual</p>
    <br>
    <strong align='justify'>__________________________________________________________________ </strong>
    <p align='justify'>$razaoSocial</p>
    <br>
    <p align='justify'>$rep01Nome</p>
    <p align='justify'>CPF ".$rep01['CPF']."</p>";


} else {
    $nomePF = $infosPessoa['Nome'];

    $textoDoc = "<p align='justify'>DECLARO para os devidos fins, que eu $nomePF, CPF ". $infosPessoa['CPF'] .", sediada na ". $infosPessoa['Endereco'].", 
    estou ciente e de acordo que o pagamento dos serviços a serem prestados, referente a $Objeto, no $Periodo, no $local, 
    ficará condicionado à apresentação do documento, abaixo listado, regularizado: </p>
    <br>
    <p align='justify'>$dataAtual</p>
    <br>
    <strong align='justify'>__________________________________________________________________ </strong>
    <p align='justify'>$nomePF</p>
    <p align='justify'>CPF ".$infosPessoa['CPF']."</p>";

}



// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - Condicionamento.doc");

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>

<p align="center">DECLARAÇÃO</p>
<br>
<?=$textoDoc?>
<p>&nbsp;</p>
</body>
</html>