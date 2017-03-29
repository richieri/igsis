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
$ano=date('Y');
$pedido = siscontrat($id_ped);

$pj = siscontratDocs($pedido['IdProponente'],2);
$ex = siscontratDocs($pedido['IdExecutante'],1);
$rep01 = siscontratDocs($pj['Representante01'],3);
$rep02 = siscontratDocs($pj['Representante02'],3);
$codPed = $id_ped;
$objeto = $pedido["Objeto"];
$ValorGlobal = $pedido["ValorGlobal"];
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]); 
$dataAtual = date("d/m/Y");
$NumeroProcesso = $pedido["NumeroProcesso"];
$assinatura = $pedido["Assinatura"];
$cargo = $pedido["Cargo"];
$qtdApresentacoes = $pedido["qtdApresentacoes"];
$qtdApresentacoesPorExtenso = qtdApresentacoesPorExtenso ($pedido["qtdApresentacoes"]);
$Periodo = $pedido["Periodo"];

//PessoaJuridica

$pjRazaoSocial = $pj["Nome"];
$pjCNPJ = $pj['CNPJ'];

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
  "<p><strong>SMC/DPH</strong></p>".
  "<p>&nbsp;</p>".
  "<p>O presente processo trata da contratação de "."$objeto".", no valor de R$ "."$ValorGlobal"."("."$ValorPorExtenso"."), conforme solicitação LINK, foram anexados os documentos necessários e incluído o parecer técnico LINK, ratificando o caráter artístico e o valor proposto para o cachê referente a "."$qtdApresentacoes"." ("."$qtdApresentacoesPorExtenso".") apresentações, no período de "."$Periodo".". Diante disso, encaminho para reserva de recursos.</p>".
  "<p>Supervisão de Contratação Artística</p>".
  "<p>&nbsp;</p>".
  "<p>_________________________________________________________________________________________________________________________________</p>".
 "<p>&nbsp;</p>".
  "<p><strong>SMC/CAF/SCO</strong></p>".
  "<p><strong>Senhor Supervisor</strong></p>".
  "<p>&nbsp;</p>".
  "<p>Autorizo a reserva de recursos no valor de R$ ".$ValorGlobal."  (".$ValorPorExtenso." ) na Atividade 6354 – Programação de Atividades Culturais da U.O. 25.50 (Pessoa Jurídica) visando possibilitar a contratação acima mencionada.</p>".
  "<p>Departamento do Patrimônio Histórico</p>".
  "<p>&nbsp;</p>".
  "<p>Após, encaminhar para SMC / Assessoria Jurídica para prosseguimento. </p>".
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