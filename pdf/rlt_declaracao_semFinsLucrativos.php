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
$pj = siscontratDocs($pedido['IdProponente'],2);
$rep01 = siscontratDocs($pj['Representante01'],3);

dataPagamento($id_ped);

$NumeroProcesso = $pedido["NumeroProcesso"];

$ano=date('Y');

$codPed = "";

$dataAtual = date('d/m/Y');


// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - DeclaracaoSemFinsLucrativos.doc");

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<p align="center"><strong>Anexo III - Instrução Normativa 1.234/2012</strong></p>
<br>
<p align="center">DECLARAÇÃO INSTITUIÇÕES DE CARÁTER FILANTRÓPICO, RECREATIVO, CULTURAL, CIENTÍFICO E ÀS ASSOCIAÇÕES CIVIS</p>
<br>
<p align="justify">À Secretaria Municipal de Cultura</p>
<br>
<p align="justify"><?= $pj['Nome'] ?>, com sede em <?=$pj['Endereco']?>, inscrita no CNPJ sob o nº <?=$pj['CNPJ']?> DECLARA à Secretaria Municipal de Cultura, para fins de não incidência na fonte do IR, da CSLL, da Cofins, e da Contribuição para o PIS/Pasep, a que se refere o art. 64 da Lei nº 9.430, de 27 de dezembro de 1996, que é entidade sem fins lucrativos de caráter ............................................., a que se refere o art 15 da Lei nº 9.532, de 10 de dezembro de 1997.</p>
<br>
<p align="justify">Para esse efeito, a declarante informa que:</p>
<br>
<p align="justify">I - preenche os seguintes requisitos, cumulativamente:</p>
<p align="justify">a) é entidade sem fins lucrativos;</p>
<p align="justify">b) presta serviços para os quais foi instituída e os coloca à disposição do grupo de pessoas a que se destinam;</p>
<p align="justify">c) não remunera, por qualquer forma, seus dirigentes por serviços prestados;</p>
<p align="justify">d) aplica integralmente seus recursos na manutenção e desenvolvimento de seus objetivos sociais;</p>
<p align="justify">f) conserva em boa ordem, pelo prazo de 5 (cinco) anos, contado da data da emissão, os documentos que comprovam a origem de suas receitas e a efetivação de suas despesas, bem como a realização de quaisquer outros atos ou operações que venham a modificar sua situação patrimonial; e,</p>
<p align="justify">g) apresenta anualmente Declaração de Informações Econômico-Fiscais da Pessoa Jurídica (DIPJ), em conformidade com o disposto em ato da Secretaria da Receita Federal do Brasil (RFB);</p>
<br>
<p align="justify">II - o signatário é representante legal desta entidade, assumindo o compromisso de informar à RFB e à unidade pagadora, imediatamente, eventual desenquadramento da presente situação e está ciente de que a falsidade na prestação dessas informações, sem prejuízo do disposto no art. 32 da Lei nº 9.430, de 1996, o sujeitará, com as demais pessoas que para ela concorrem, às penalidades previstas na legislação criminal e tributária, relativas à falsidade ideológica (art. 299 do Decreto-Lei nº 2.848, de 7 de dezembro de 1940 - Código Penal) e ao crime contra a ordem tributária (art. 1º da Lei nº 8.137, de 27 de dezembro de 1990).</p>
<br>
<p align="justify">Local e data.....................................................</p>
<p align="justify">Assinatura do Responsável</p>
</body>
</html>