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
$linha_tabelas = siscontrat($id_ped);
$pedido = siscontrat($id_ped);


$codPed = $id_ped;
$objeto = $linha_tabelas["Objeto"];
$ValorGlobal = dinheiroParaBr($linha_tabelas["ValorGlobal"]);
$ValorPorExtenso = valorPorExtenso($linha_tabelas["ValorGlobal"]); 
$periodo = $linha_tabelas["Periodo"];
$duracao = $linha_tabelas["Duracao"];
$dataAtual = date("d/m/Y");
$NumeroProcesso = $linha_tabelas["NumeroProcesso"];
$assinatura = $linha_tabelas["Assinatura"];
$cargo = $linha_tabelas["Cargo"];
$qtdApresentacoes = $pedido["qtdApresentacoes"];
$qtdApresentacoesPorExtenso = qtdApresentacoesPorExtenso ($pedido["qtdApresentacoes"]);
$local = $pedido['Local'];
$processoMae = $pedido['processoMae'];


$pj = siscontratDocs($pedido['IdProponente'],2);
$pjRazaoSocial = $pj["Nome"];
$pjCNPJ = $pj['CNPJ'];

$setor = $linha_tabelas["Setor"];

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
  "<p><strong>INTERESSADO:</strong> "."$pjRazaoSocial"."</span></p>".
  "<p><strong>ASSUNTO:</strong> "."$objeto"."  </p>".
  "<p>&nbsp;</p>".
  "<p><strong>SMC/CAF/SCO</strong></p>".
  "<p><strong>Senhor Supervisor</strong></p>".
  "<p>&nbsp;</p>".
  "<p><b>Objeto:</b> $objeto</p>".
  "<p><b>Data/período:</b> $periodo</p>".
  "<p><b>Duração:</b> $duracao</p>".
  "<p><b>Local:</b> $local</p>".
  "<p><b>Valor:</b> R$ $ValorGlobal ($ValorPorExtenso )</p>".
    "<p>&nbsp;</p>".
  "<p>Diante do exposto, autorizo a reserva de recursos proveniente da nota de reserva inclusa no processo $processoMae - (Pessoa Jurídica) para a presente contratação.</p>".
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