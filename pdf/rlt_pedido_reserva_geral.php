<?php
@ini_set('display_errors', '1');
error_reporting(E_ALL);

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS 
$conexao = bancoMysqli();

//CONSULTA 
$id_ped = $_GET['id'];
dataReserva($id_ped);
$pedido = siscontrat($id_ped);

$objeto = $pedido["Objeto"];
$ValorGlobal = dinheiroParaBr($pedido["ValorGlobal"]);
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]);
$periodo = $pedido["Periodo"];
$qtdApresentacoes = $pedido["qtdApresentacoes"];
$qtdApresentacoesPorExtenso = qtdApresentacoesPorExtenso($pedido["qtdApresentacoes"]);
$idVerba = $pedido['Verba'];
$verba = recuperaDados("sis_verba", $idVerba, "Id_Verba");


if ($pedido['tipoPessoa'] == 1) {
    $pedido_pessoa = siscontratDocs($pedido['IdProponente'], 1);
    $proponente = $pedido_pessoa["Nome"];
    $tipoPessoa = "Pessoa Física";
} else {
    $pedido_pessoa = siscontratDocs($pedido['IdProponente'], 2);
    $proponente = $pedido_pessoa["Nome"];
    $tipoPessoa = "Pessoa Jurídica";
}

?>

<html lang="pt">
<head>
    <title>IGSIS - Pedido de Reserva</title>
    <meta http-equiv="Content-Type" content="text/html. charset=Windows-1252">
    <style>
        .texto {
            width: 900px;
            border: solid;
            padding: 20px;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: justify;
        }
    </style>
    <script src="include/dist/ZeroClipboard.min.js"></script>
</head>
<body>
<?php
$sei =
    "<p>&nbsp;</p>" .
    "<p><strong>INTERESSADO:</strong> $proponente</span></p>" .
    "<p><strong>ASSUNTO:</strong> $objeto</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>SMC/CAF/SCO</strong></p>" .
    "<p><strong>Senhor Supervisor</strong></p>" .
    "<p>&nbsp;</p>" .
    "<p>O presente processo trata da contratação de $objeto, no valor de R$ $ValorGlobal ( $ValorPorExtenso ), concernente a $qtdApresentacoes ( $qtdApresentacoesPorExtenso ) apresentações, no período de $periodo.</p>" .
    "<p>Assim, solicito a reserva de recursos que deverá onerar a ação {$verba['DetalhamentoAcao']}  ($tipoPessoa) da U.O. 25.10 - Fonte 00.</p>" .
    "<p>&nbsp;</p>" .
    "<p>Após, enviar para SMC/AJ para prosseguimento.</p>" .
    "<p>&nbsp;</p>" .
    "<p>Chefe de Gabinete</p>"
?>

<div align="center">
    <div id="texto" class="texto"><?php echo $sei; ?></div>
</div>

<p>&nbsp;</p>

<div align="center">
    <button id="botao-copiar" data-clipboard-target="texto"><img src="img/copy-icon.jpg"> CLIQUE AQUI PARA COPIAR O
        TEXTO
    </button>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
        <button>CLIQUE AQUI PARA ACESSAR O <img src="img/sei.jpg"></button>
    </a>
</div>

<script>
    var client = new ZeroClipboard();
    client.clip(document.getElementById("botao-copiar"));
    client.on("aftercopy", function () {
        alert("Copiado com sucesso!");
    });
</script>

</body>
</html>