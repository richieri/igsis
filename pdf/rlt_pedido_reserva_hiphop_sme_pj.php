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
dataReserva($id_ped);
$pedido = siscontrat($id_ped);


$codPed = $id_ped;
$objeto = $pedido["Objeto"];
$ValorGlobal = $pedido["ValorGlobal"];
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]); 
$periodo = $pedido["Periodo"];
$local = $pedido['Local'];
$duracao = $pedido["Duracao"];
$dataAtual = date("d/m/Y");
$NumeroProcesso = $pedido["NumeroProcesso"];
$assinatura = $pedido["Assinatura"];
$cargo = $pedido["Cargo"];
$qtdApresentacoes = $pedido["qtdApresentacoes"];
$qtdApresentacoesPorExtenso = qtdApresentacoesPorExtenso ($pedido["qtdApresentacoes"]);

$setor = $pedido["Setor"];

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

  "<p>&nbsp;</p>".
  "<p><strong>SMC/CAF/SCO</strong></p>".
  "<p><strong>Senhor Supervisor</strong></p>".
  "<p>&nbsp;</p>".
  "<p><b>Objeto:</b> ".$objeto."</p>".
  "<p><b>Data / Período:</b> ".$periodo."</p>".
  "<p><b>Duração:</b> ".$duracao."</p>".
  "<p><b>Local:</b> ".$local."</p>".
  "<p><b>Valor:</b> R$ ".dinheiroParaBr($ValorGlobal)." (".$ValorPorExtenso." )</p>".
  "<p>&nbsp;</p>".
  "<p>Diante do exposto, autorizo a realização da reserva de recursos proveniente da nota de reserva (link da Nota de transferência) - (Pessoa Jurídica) para a presente contratação.</p>".
  "<p>&nbsp;</p>".
  "<p>Após, enviar para SMC/AJ para prosseguimento.</p>".
  "<p>&nbsp;</p>".
  "<p>Chefe de Gabinete</p>".  
  "<p>&nbsp;</p>"

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