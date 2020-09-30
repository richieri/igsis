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
    $texto = "
        <p style='text-align:justify'>( X ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia _____/____/____, dentro do prazo previsto.<br>O prazo contratual é de data/período {$pedido['Periodo']} .</p>
        <p style='text-align:justify'>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados parcialmente, nos termos previstos no instrumento contratual (ou documento equivalente), do prazo contratual do dia ___/___/__ até o dia ___/___/___.</p>  
    ";
} else{ //parcelado ou outros
    $texto = "
        <p style='text-align:justify'>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia _____/____/____, dentro do prazo previsto.<br>O prazo contratual é do dia ___/___/__ até o dia ___/___/___.</p>
        <p style='text-align:justify'>( X ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL ] foram entregues e/ou executados parcialmente, nos termos previstos no instrumento contratual (ou documento equivalente), do prazo contratual do dia ___/___/__ até o dia ___/___/___.</p>      
    ";
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
    $texto.
    "<p style='text-align:justify'>( ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL] foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia _____/____/____, com atraso de ____dias.<br>O prazo contratual é do dia ___/___/___ até o dia ___/___/___.</p>".
    "<p style='text-align:justify'>&nbsp;</p>".
    "<p style='text-align:center'><b>INFORMAÇÕES COMPLEMENTARES</b></p>".
    "<p style='text-align:justify'>DADOS DO SERVIDOR (A) QUE ESTÁ CONFIRMANDO OU NÃO A REALIZAÇÃO DOS SERVIÇOS:</p>".
    "<p style='text-align:justify'><b>FISCAL:</b> {$pedido['Fiscal']} - <b>RF:</b> {$pedido['RfFiscal']}<br>".
    "<b>SUPLENTE:</b> {$pedido['Suplente']} - <b>RF:</b> {$pedido['RfSuplente']}</p>".
    "<p style='text-align:justify'><b>Nota e Anexo de Empenho:</b> {$pedido["NotaEmpenho"]}<br>".
    "<b>Kit de Pagamento Assinado:</b> <br>".
    "<b>Certidões Fiscais:</b> <br>".
    "<b>FACC:</b> </p>".
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