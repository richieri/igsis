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


$codPed = $id_ped;
$objeto = $pedido["Objeto"];
$local = $pedido["Local"];
$justificativa = $pedido["Justificativa"];
$notaempenho = $pedido["NotaEmpenho"];
$NumeroProcesso = $pedido["NumeroProcesso"];

$pedido_pessoa = siscontratDocs($pedido['IdProponente'],1);
$nome = $pedido_pessoa["Nome"];
$cpf = $pedido_pessoa["CPF"];
$INSS = $pedido_pessoa["INSS"];


$dataAtual = date("d/m/Y");
$ano=date('Y');

$regiao = valorPorRegiao($id_ped);
$norte = dinheiroParaBr($regiao['norte']);
$sul =  dinheiroParaBr($regiao['sul']);
$leste =  dinheiroParaBr($regiao['leste']);
$oeste =  dinheiroParaBr($regiao['oeste']);
$centro =  dinheiroParaBr($regiao['centro']);

$valores= "";
$texto = "";

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
    $texto = "<p>&nbsp;</p><p>Em atendimento ao item referente a regionalização e georreferenciamento das despesas municipais com a implantação do detalhamento da ação, informo que a despesa aqui tratada se refere(m) ".$valores.".</p>";
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
  "<p><strong>Interessado:</strong> ".$nome."</p>".
  "<p><strong>Do evento:</strong> ".$objeto."</p>".
  "<p>&nbsp;</p>".
  "<p>Atesto o recebimento em <strong>DATA</strong>, de toda a documentação: recibo  <strong>LINK RECIBO PAGAMENTO</strong> e arquivos consolidados, previstos na Portaria SF 08/16.</p>".
  "<p>&nbsp;</p>".
  "<p>&nbsp;</p>".
  "<p><strong>SMC - CONTABILIDADE</strong></p>".
  "<p><strong>Sr.(a) Contador(a)</strong></p>".
  "<p>&nbsp;</p>". 
  "<p>Encaminho o presente para providências quanto ao pagamento, uma vez que os serviços foram realizados e confirmados a contento conforme documento <strong>LINK DA SOLICITAÇÃO</strong>.</p>".
  $texto.
  "<p>&nbsp;</p>". 
  "<p>&nbsp;</p>".
  "<p>INFORMAÇÕES COMPLEMENTARES</p>".
  "<hr>".  
  "<p><strong>Nota e Anexo de Empenho:</strong>$notaempenho</p>".
  "<p><strong>Kit de Pagamento Assinado:</strong></p>".
  "<p><strong>Certidões Fiscais:</strong></p>".
  "<p><strong>FACC:</strong> </p>".
  /*  "<p><strong>Anexo de Nota de Empenho:</strong> </p>".
    "<p><strong>Publicação em DOC:</strong> </p>".
    "<p><strong>Recibo de Nota de Empenho:</strong> </p>".
    "<p><strong>Pedido de Pagamento:</strong> </p>".
    "<p><strong>Recibo de pagamento:</strong> </p>".
    "<p><strong>NIT/PIS/PASEP:</strong> ".$INSS."</p>".
    "<p><strong>CND:</strong> </p>".
    "<p><strong>FDC-CCM:</strong> </p>".
    "<p><strong>CTM:</strong> </p>".
    "<p><strong>Declaração ISS:</strong> </p>".
    "<p><strong>CADIN:</strong> </p>".*/
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