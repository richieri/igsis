<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");
require_once("../funcoes/funcoesFormacao.php");

//CONEXÃO COM BANCO DE DADOS
$conexao = bancoMysqli();

//CONSULTA
$id_ped=$_GET['id'];
$pedido = siscontrat($id_ped);
$id_parcela = $_GET['parcela'];


$codPed = $id_ped;
$objeto = $pedido["Objeto"];
$local = $pedido["Local"];

$pedido_pessoa = siscontratDocs($pedido['IdProponente'],4);
$nome = $pedido_pessoa["Nome"];

$parcelamento = retornaParcelaPagamento($id_ped);
$periodoParcela = $parcelamento[$id_parcela]['periodo'];
$horas = $parcelamento[$id_parcela]['horas'];
$dataPagamento = $parcelamento[$id_parcela]['pagamento'];
$NumeroProcesso = $pedido["NumeroProcesso"];
$fiscal = $pedido["Fiscal"];
$suplente = $pedido["Suplente"];
$rfFiscal = $pedido["RfFiscal"];
$rfSuplente = $pedido["RfSuplente"];



$dataAtual = date("d/m/Y");
$ano=date('Y');
$dataFinal = $parcelamento[$id_parcela]['vigencia_final'];

//datas
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$semana = date('w');


//mês

switch ($mes){

    case 1: $mes = "Janeiro"; break;
    case 2: $mes = "Fevereiro"; break;
    case 3: $mes = "Março"; break;
    case 4: $mes = "Abril"; break;
    case 5: $mes = "Maio"; break;
    case 6: $mes = "Junho"; break;
    case 7: $mes = "Julho"; break;
    case 8: $mes = "Agosto"; break;
    case 9: $mes = "Setembro"; break;
    case 10: $mes = "Outubro"; break;
    case 11: $mes = "Novembro"; break;
    case 12: $mes = "Dezembro"; break;

}
?>



<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">

    <style>

        .texto{
            width: 900px;
            border: solid;
            padding: 20px;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align:justify;
        }
    </style>
    <script src="include/dist/ZeroClipboard.min.js"></script>
</head>

<body>


<?php

$sei =
    "<p><strong><u><center>Anexo I da Portaria SF nº 170, de 31 agosto de 2020</strong></p></u></center>".
    "<p><center>Modelo de recebimento da documentação e ateste total/parcial de nota fiscal dentro/fora do prazo</center></p>".
    "<p>&nbsp;</p>".
    "<p><strong>Recebimento da Documentação </strong></p>".
    "<p>&nbsp;</p>".
    "<p>Atesto:</p>".
    "<p>( ) o recebimento em ___/___/____ de toda a documentação [INSERIR NÚMERO SEI DA NOTA FISCAL E ARQUIVOS
CONSOLIDADOS] prevista na Portaria SF nº 170/2020.</p>".
    "<p>( ) o recebimento em __/__ /____ da documentação [INSERIR NÚMERO SEI DA NOTA FISCAL E ARQUIVOS CONSOLIDADOS]
prevista na Portaria SF nº 170/2020, ressalvado (s) [RELACIONAR OS DOCUMENTOS IRREGULARES].</p>".
    "<p>&nbsp;</p>".
    "<p><strong>Recebimento de material e/ou serviços: </strong></p>".
    "<p>&nbsp;</p>".
    "<p>Atesto:</p>".
    "<p>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ]
foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento
equivalente) no dia _____/____/____, dentro do prazo previsto.<br>O prazo contratual é do dia ___/___/__ até o dia ___/___/___.</p>".
    "<p>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ]
foram entregues e/ou executados parcialmente, nos termos previstos no instrumento contratual (ou documento
equivalente), do dia ___/___/___, dentro do prazo previsto.<br>O prazo contratual é do dia ___/___/__ até o dia ___/___/___. </p>".
    "<p>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL]
foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento
equivalente) no dia _____/____/____, com atraso de ____dias.<br>O prazo contratual é do dia ___/___/___ até o dia ___/___/___. </p>".
    "<p>&nbsp;</p>".
    "<p>INFORMAÇÕES COMPLEMENTARES </p>".
    "<p>____________________________________________________________________________________________________________________________________</p>".
    "<p>____________________________________________________________________________________________________________________________________</p>".
    "<p>À área gestora / de liquidação e pagamento. </p>".
    "<p>&nbsp;</p>".
    "<p>Encaminho para prosseguimento </p>".
    "<p>São Paulo/SP, {$dia} de {$mes} de {$ano} </p>"

?>

<div align="center">
    <div id="texto" class="texto"><?php echo $sei; ?></div>
</div>

<div align="center"><button id="botao-copiar" data-clipboard-target="texto"><img src="img/copy-icon.jpg"> CLIQUE AQUI PARA COPIAR O TEXTO</button>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
        <button>CLIQUE AQUI PARA ACESSAR O <img src="img/sei.jpg"></button></a>
</div>

<script>
    var client = new ZeroClipboard();
    client.clip(document.getElementById("botao-copiar"));
    client.on("aftercopy", function(){
        alert("Copiado com sucesso!");
    });
</script>

</body>
</html>