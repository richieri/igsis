<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");
require_once("../funcoes/funcoesFormacao.php");

//CONEXÃO COM BANCO DE DADOS 
$con = bancoMysqli();

//CONSULTA 
$id = $_GET['id'];
$pedido = siscontrat($id);

$formacao = $con->query("SELECT idPrograma FROM sis_formacao WHERE idPedidoContratacao = '$id'")->fetch_assoc();
$programa = $con->query("SELECT * FROM sis_formacao_programa WHERE Id_Programa = '{$formacao['idPrograma']}'")->fetch_assoc();
$dotacao = $con->query("SELECT * FROM sis_verba WHERE Id_Verba = '{$programa['verba']}'")->fetch_assoc();

$objeto = $pedido["Objeto"];
$periodo = $pedido['Periodo'].", conforme proposta e cronograma (link SEI).";
$local = $pedido["Local"];
$carga = $pedido["CargaHoraria"];
$ValorGlobal = $pedido["ValorGlobal"];
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]);

$linha_tabelas_pessoa = siscontratDocs($pedido['IdProponente'], 1);
$nome = $linha_tabelas_pessoa["Nome"];
$cpf = $linha_tabelas_pessoa["CPF"];
?>

<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">

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
    "<p align='justify'>I - À vista dos elementos constantes do presente, em especial da seleção realizada conforme Edital de chamamento para credenciamento de  Artistas-Educadores e Coordenadores Artístico-Pedagógicos do {$programa['Programa']} - {$programa['edital']}, para atuar nos equipamentos públicos da Secretaria Municipal de Cultura e nos CEUS (Centros Educacionais Unificados)  da Secretaria Municipal de Educação na edição de 2020, publicado no DOC de  24/10/2019  (link SEI), no uso da competência a mim delegada pela Portaria nº 17/2018 - SMC/G , AUTORIZO com fundamento no artigo 25 “caput”, da Lei Federal nº 8.666/93, a contratação nas condições abaixo estipuladas, observada a legislação vigente e demais cautelas legais:</p>" .
    "<p><strong>Contratado:</strong> " . "$nome" . ", CPF (" . "$cpf" . ")</p>" .
    "<p><strong>Objeto:</strong> " . "$objeto" . "</p>" .
    "<p><strong>Data / Período:</strong> " . "$periodo" . "</p>" .
    "<p><strong>Locais:</strong> " . $local . "</p>" .
    "<p><strong>Carga Horária:</strong> " . $carga . "</p>" .
    "<p><strong>Valor:</strong> R$ " . dinheiroParaBr($ValorGlobal) . "  " . "($ValorPorExtenso )" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> Os valores devidos ao contratado serão apurados mensalmente de acordo com as horas efetivamente trabalhadas e pagos a partir do 1° dia útil do mês subseqüente ao trabalhado, desde que comprovada a execução dos serviços através da entrega à Supervisão de Formação Artística e Cultural dos documentos modelos preenchidos corretamente, sem rasuras, além da entrega do Relatório de Horas Trabalhadas atestadas pelo equipamento vinculado e, apenas para os artistas educadores/orientadores, as Listas de Presença de cada turma, nos termos do item 13.1 do Edital.</p>" .
    "<p><strong>Dotação Orçamentária:</strong> {$dotacao['DetalhamentoAcao']}</p>" .
    "<p align='justify'>II - Nos termos do art. 6º do Decreto nº 54.873/2014, designo a servidora Natalia Silva Cunha, RF 842.773.9,  como fiscal do contrato, e  Ilton T. Hanashiro Yogi, RF n.º 800.116.2, como suplente.</p>" .
    "<p align='justify'>III - Publique-se e encaminhe-se ao setor competente para providências cabíveis.</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'><b>Chefe de Gabinete<br/>S.M.C</b></p>" .
    "<p>&nbsp;</p>"

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