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
$justificativa = $pedido["Justificativa"];

$form = recuperaDados("sis_formacao",$id_ped,"idPedidoContratacao");
$coord = recuperaDados("sis_formacao_coordenadoria",$form['Coordenarias'],"idCoordenadoria");

$pedido_pessoa = siscontratDocs($pedido['IdProponente'],1);
$nome = $pedido_pessoa["Nome"];
$cpf = $pedido_pessoa["CPF"];

//$horas = retornaCargaHoraria($id_ped,$id_parcela);

$parcelamento = retornaParcelaPagamento($id_ped);
$periodoParcela = $parcelamento[$id_parcela]['periodo']; 
$horas = $parcelamento[$id_parcela]['horas'];


//datas
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$semana = date('w');
$dataAtual = date("d/m/Y");
$ano=date('Y');

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
  "<p>&nbsp;</p>".
  "<p><strong>Interessado:</strong> ".$nome."</p>".
  "<p><strong>Do evento:</strong> ".$objeto."</p>".
  "<p>&nbsp;</p>".
  "<p>&nbsp;</p>".
  "<p><strong>SMC - CONTABILIDADE</strong></p>".
  "<p><strong>Sr.(a) Contador(a)</strong></p>".
  "<p align='justify'>Encaminho o presente para providências quanto ao pagamento, uma vez que os serviços foram realizados e confirmados a contento conforme documento link SEI.</p>".
  "<p align='justify'>Em virtude do detalhamento da Ação em 2019, informamos que o pagamento  no valor de R$ 4.194,72 (quatro mil, cento e noventa e quatro reais e setenta e dois centavos) foi gasto na zona sul de São Paulo, rua Volkswagen, s/nº, Jabaquara, SP.</p>".
  "<p>&nbsp;</p>".
  "<p>INFORMAÇÕES COMPLEMENTARES</p>".
  "<hr />".
  "<p><strong>Nota de Empenho:</strong></p>".
  "<p><strong>Anexo Nota de Empenho:</strong></p>".
  "<p><strong>Recibo da Nota de Empenho:</strong></p>".
  "<p><strong>Pedido de Pagamento:</strong></p>".
  "<p><strong>Recibo de pagamento:</strong></p>".
  "<p><strong>Relatório de Horas Trabalhadas:</strong></p>".
  "<p><strong>NIT/PIS/PASEP:</strong></p>".
  "<p><strong>Certidões fiscais:</strong></p>".
  "<p>&nbsp;</p>".

  "<p>São Paulo, ".$dia." de ".$mes." de ".$ano.".</p>";

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