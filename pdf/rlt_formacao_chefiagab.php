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
    "<p><strong>SMC - CONTABILIDADE</strong></p>".
    "<p><strong>Sr.(a) Contador(a)</strong></p>".
    "<p>&nbsp;</p>".
    "<p>Autorizo o presente para providências quanto ao pagamento, uma vez que os serviços foram realizados e confirmados a contento conforme o documento <strong>link SEI (ateste do fiscal)</strong>.</p>"
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