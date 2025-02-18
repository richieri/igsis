<?php
   @ini_set('display_errors', '1');
	error_reporting(E_ALL); 

//require '../include/';
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");
      require_once("../funcoes/funcoesFormacao.php");

//CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli();

//CONSULTA 
$id_ped=$_GET['id'];
$ano=date('Y');

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],1);

if ($pedido['TipoPessoa'] == 2) {
    echo "<script>window.location = 'rlt_pedido_contratacao_pj.php?id=".$id_ped."';</script>";
}


$setor = $pedido["Setor"];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$Duracao = $pedido["Duracao"];
$CargaHoraria = $pedido["CargaHoraria"];
$Local = $pedido["Local"];
$ValorGlobal = dinheiroParaBr($pedido["ValorGlobal"]);
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]);
$FormaPagamento = $pedido["FormaPagamento"];
$processoMae = $pedido["processoMae"];
$Justificativa = $pedido["Justificativa"];
$Fiscal = $pedido["Fiscal"];
$rfFiscal = $pedido["RfFiscal"];
$Suplente = $pedido["Suplente"];
$rfSuplente = $pedido["RfSuplente"];
$dataAtual = exibirDataBr($pedido["DataCadastro"]);
$qtdApresentacoes = $pedido["qtdApresentacoes"];
$NumeroProcesso = $pedido["NumeroProcesso"];
$integrantes = $pedido["integrantes"];

$verba = recuperaVerba($pedido['Verba']);
$vocativo = $verba["vocativo"];

$Nome = $pessoa["Nome"];
$NomeArtistico = $pessoa["NomeArtistico"];
$EstadoCivil = $pessoa["EstadoCivil"];
$Nacionalidade = $pessoa["Nacionalidade"];
$RG = $pessoa["RG"];
$CPF = $pessoa["CPF"];
$CCM = $pessoa["CCM"];
$OMB = $pessoa["OMB"];
$DRT = $pessoa["DRT"];
$Funcao = $pessoa["Funcao"];
$Endereco = $pessoa["Endereco"];
$Telefones = $pessoa["Telefones"];
$Email = $pessoa["Email"];
$INSS = $pessoa["INSS"];
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

if($processoMae != NULL){
    $frase = "<p><strong>Processo SEI de reserva global:</strong> "."$processoMae"."</p>";
}
else{
    $frase = NULL;
}


$sei = 
"<p align='center'><strong>PEDIDO DE  CONTRATAÇÃO DE PESSOA FÍSICA</strong></p>".
"<p>&nbsp;</p>".
"<p><strong>Sr(a) ".$vocativo."</strong></p>".
"<p>Solicitamos a contratação a seguir:</p>".
"<p>&nbsp;</p>".
"<p><strong>Pedido de Contratação nº:</strong> "."$ano-$id_ped"."</p>".
"<p><strong>Processo SEI nº:</strong> "."$NumeroProcesso"."</p>".
$frase.
"<p><strong>Setor  solicitante:</strong> "."$setor"."</p>".
"<p>&nbsp;</p>".
"<p><strong>Nome:</strong> "."$Nome"." <br />".
  "<strong>CPF:</strong> "."$CPF"."<br />".
   "<strong>Telefone:</strong> "."$Telefones"."<br />".
   "<strong>E-mail:</strong> "."$Email"."</p>".
"<p>&nbsp;</p>".
"<p><strong>Objeto:</strong> "."$Objeto"."</p>".
"<p><strong>Data / Período:</strong> "."$Periodo".", totalizando "."$qtdApresentacoes"." apresentações conforme proposta/cronograma.</p>".
"<p><strong>Tempo Aproximado de Duração do Espetáculo:</strong> "."$Duracao"."</p>".
"<p><strong>Carga Horária:</strong> "."$CargaHoraria"."</p>".
"<p align='justify'><strong>Local:</strong> "."$Local"."</p>".
"<p><strong>Valor: </strong> R$ "."$ValorGlobal"."  ("."$ValorPorExtenso". " )</p>".
"<p align='justify'><strong>Forma de Pagamento:</strong> "."$FormaPagamento "."</p>".
"<p align='justify'><strong>Justificativa: </strong> "."$Justificativa"."</p>".
"<p align='justify'>Nos termos do art. 6º do decreto 54.873/2014, fica designado como fiscal desta contratação artística o(a) servidor(a) "."$Fiscal".", RF "."$rfFiscal"." e, como substituto, "."$Suplente".", RF "."$rfSuplente".". Diante do exposto, solicitamos autorização para prosseguimento do presente."."</p>";


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