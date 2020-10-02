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

if ($pedido['TipoPessoa'] == 2){
    $pedido_pessoa = siscontratDocs($pedido['IdProponente'],2);
    $proponente = $pedido_pessoa["Nome"];
    $cnpj = $pedido_pessoa["CNPJ"];
} else {
    $pedido_pessoa = siscontratDocs($pedido['IdProponente'],1);
    $proponente = $pedido_pessoa["Nome"];
    $cnpj = $pedido_pessoa["CPF"];
}
if ($pedido['parcelas'] == 1 ){ //integral
    $texto_parcela = "
        <p style='text-align:justify'>( X ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia _____/____/____, dentro do prazo previsto.<br>O prazo contratual é de data/período {$pedido['Periodo']} .</p>
        <p style='text-align:justify'>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados parcialmente, nos termos previstos no instrumento contratual (ou documento equivalente), do prazo contratual do dia ___/___/__ até o dia ___/___/___.</p>  
    ";
} else{ //parcelado ou outros
    $texto_parcela = "
        <p style='text-align:justify'>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia _____/____/____, dentro do prazo previsto.<br>O prazo contratual é do dia ___/___/__ até o dia ___/___/___.</p>
        <p style='text-align:justify'>( X ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados parcialmente, nos termos previstos no instrumento contratual (ou documento equivalente), do prazo contratual do dia ___/___/__ até o dia ___/___/___.</p>      
    ";
}
$regiao = valorPorRegiao($id_ped);
$norte = dinheiroParaBr($regiao['norte']);
$sul =  dinheiroParaBr($regiao['sul']);
$leste =  dinheiroParaBr($regiao['leste']);
$oeste =  dinheiroParaBr($regiao['oeste']);
$centro =  dinheiroParaBr($regiao['centro']);

$valores= "";
$texto_regiao = "";

if($norte != "0,00"){
    $valores = "a região norte no valor de R$ ".$norte." (".valorPorExtenso($regiao['norte'])." )";
}

if($sul != "0,00"){
    $valores .= ", a região sul no valor de R$ ".$sul." (".valorPorExtenso($regiao['sul'])." )";
}

if($leste != "0,00"){
    $valores .= ", a região leste no valor de R$ ".$leste." (".valorPorExtenso($regiao['leste'])." )";
}

if($oeste != "0,00"){
    $valores .= ", a região oeste no valor de R$ ".$oeste." (".valorPorExtenso($regiao['oeste'])." )";
}

if($centro != "0,00"){
    $valores .= ", a região centro no valor de R$ ".$centro." (".valorPorExtenso($regiao['centro'])." )";
}

if($norte != "0,00" || $sul != "0,00" || $leste != "0,00" || $oeste != "0,00" || $centro != "0,00"){
    $texto_regiao = "<p style='text-align:justify'>Em atendimento ao item referente a regionalização e georreferenciamento das despesas municipais com a implantação do detalhamento da ação, informo que a despesa aqui tratada se refere(m) ".$valores.".</p>";
}

$dataAtual = date("d/m/Y");
$ano=date('Y');
?>
 
 
<html>
<head> 
<meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">

<style>

.texto{
 	width: 900px;
 	border: solid;
 	padding: 20px;
 	font-size: 13px;
 	font-family: Arial, Helvetica, sans-serif;
	text-align:justify;
}
</style>
<script src="include/dist/ZeroClipboard.min.js"></script>
</head>

 <body>

  
<?php

$sei =
    "<p><b>Processo:</b> {$pedido["NumeroProcesso"]}<br>".
    "<b>Interessado:</b> $proponente<br>".
    "<b>Evento:</b> {$pedido["Objeto"]}<br>".
    "<b>Local:</b> {$pedido['Local']}</p>".
    "<p>&nbsp;</p>".
    "<p style='text-align:center'><b>Recebimento da Documentação</b></p>".
    "<p>Atesto:</p>".
    "<p style='text-align:justify'>( ) o recebimento em ___/___/____ de toda a documentação [INSERIR NÚMERO SEI DA NOTA FISCAL E ARQUIVOS CONSOLIDADOS] prevista na Portaria SF no 170/2020.</p>".
    "<p style='text-align:justify'>( ) o recebimento em __/__ /____ da documentação [INSERIR NÚMERO SEI DA NOTA FISCAL E ARQUIVOS CONSOLIDADOS] prevista na Portaria SF no 170/2020, ressalvado (s) [RELACIONAR OS DOCUMENTOS IRREGULARES].</p>".
    "<p style='text-align:justify'>&nbsp;</p>".
    "<p style='text-align:center'><b>Recebimento de material e/ou serviços</b></p>".
    "<p style='text-align:justify'></p>".
    $texto_parcela.
    "<p style='text-align:justify'>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL] foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia _____/____/____, com atraso de ____dias.<br>O prazo contratual é do dia ___/___/___ até o dia ___/___/___.</p>".
    "<p style='text-align:justify'>&nbsp;</p>".
    "<p style='text-align:center'><b>INFORMAÇÕES COMPLEMENTARES</b></p>".
    "<p style='text-align:left'><b>Nota e Anexo de Empenho:</b> {$pedido["NotaEmpenho"]}<br>".
    "<b>Certidões Fiscais:</b> <br>".
    "<b>FACC:</b> </p>".
    "<p style='text-align:left'>Dados do servidor(a) que está confirmando ou não a realização dos serviços:<br>".
    "<b>FISCAL:</b> {$pedido['Fiscal']} - <b>RF:</b> {$pedido['RfFiscal']}<br>".
    "<b>SUPLENTE:</b> {$pedido['Suplente']} - <b>RF:</b> {$pedido['RfSuplente']}</p>".
    $texto_regiao.
    "<p style='text-align:justify'>&nbsp;</p>".
    "<p style='text-align:justify'>À SMC/CAF/SCO</p>".
    "<p style='text-align:justify'>Encaminho para prosseguimento.</p>".
    "<p style='text-align:justify'></p>";
?>

<div align="center">
 <div id="texto" class="texto"><?php echo $sei; ?></div>
</div> 

 <p>&nbsp;</p>
 
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