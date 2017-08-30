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

  
 ?>
 
 <?
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
  "<p><strong><u><center>ATESTADO DE CONFIRMAÇÃO DE SERVIÇOS</strong></p></u></center>".
  "<p>&nbsp;</p>".
  "<p>Informamos que os serviços prestados por: ".$nome."</p>".
  "<p><strong>PROCESSO: </strong> ".$NumeroProcesso." </p>".
  "<p><strong>EVENTO: </strong> ".$objeto." </p>".
  "<P><strong>PERÍODO: </strong>".$periodoParcela."</p>".
  "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS</p>".
  "<p>( X ) FORAM REALIZADOS A CONTENTO</p>".
  "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS A CONTENTO, PELO SEGUINTE MOTIVO:</p>".
  "<p>&nbsp;</p>".
  "<p><strong>DADOS DO SERVIDOR (A) QUE ESTÁ CONFIRMANDO OU NÃO A REALIZAÇÃO DOS SERVIÇOS:</strong></p>".
  "<p><strong>FISCAL:</strong> ".$fiscal."</p>".
  "<p><strong>RF:</strong> ".$rfFiscal."</p>".
  "<p><strong>SUPLENTE:</strong> ".$suplente."</p>".
  "<p><strong>RF:</strong> ".$rfSuplente."</p>".
  "<p>&nbsp;</p>".
  "<p>Atesto que os serviços prestados discriminados no documento: link SEI, foram executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no(s) dia(s): ".$dataFinal.", dentro do prazo previsto.</p>".
  "<p>O prazo contratual é do dia ".$periodoParcela.". <p>".
  "<p>&nbsp;</p>".
  "<p>À área gestora de liquidação e pagamento encaminho para prosseguimento.</p>" 

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