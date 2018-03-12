<?php 
	session_start();
	   @ini_set('display_errors', '1');
	error_reporting(E_ALL); 	
   
   // INSTALAÇÃO DA CLASSE NA PASTA FPDF.
	require_once("../include/lib/fpdf/fpdf.php");
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli(); 
   



//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];

$ano=date('Y');
$dataAtual = date("d/m/Y");

$pedido = siscontrat($id_ped);

$pj = siscontratDocs($pedido['IdProponente'],2);
$ex = siscontratDocs($pedido['IdExecutante'],1);
$rep01 = siscontratDocs($pj['Representante01'],3);
$rep02 = siscontratDocs($pj['Representante02'],3);

$setor = $pedido["Setor"];

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$Duracao = $pedido["Duracao"];
$CargaHoraria = $pedido["CargaHoraria"];
$Local = $pedido["Local"];
$ValorGlobal = dinheiroParaBr($pedido["ValorGlobal"]);
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]);
$FormaPagamento = $pedido["FormaPagamento"];
$Justificativa = $pedido["Justificativa"];
$Fiscal = $pedido["Fiscal"];
$rfFiscal = $pedido["RfFiscal"];
$Suplente = $pedido["Suplente"];
$rfSuplente = $pedido["RfSuplente"];
$NumeroProcesso = $pedido["NumeroProcesso"];
$notaempenho = $pedido["NotaEmpenho"];
$data_entrega_empenho = exibirDataBr($pedido['EntregaNE']);
$data_emissao_empenho = exibirDataBr($pedido['EmissaoNE']);

$grupo = grupos($id_ped);
$integrantes = $grupo["texto"];

//PessoaJuridica

$pjRazaoSocial = $pj["Nome"];
$pjNomeArtistico = $pj["NomeArtistico"];
$pjEstadoCivil = $pj["EstadoCivil"];
$pjNacionalidade = $pj["Nacionalidade"];
$pjRG = $pj["RG"];
$pjCPF = $pj["CPF"];
$pjCCM = $pj["CCM"];
$pjOMB = $pj["OMB"];
$pjDRT = $pj["DRT"];
$pjFuncao = $pj["Funcao"];
$pjEndereco = $pj["Endereco"];
$pjTelefones = $pj["Telefones"];
$pjEmail = $pj["Email"];
$pjINSS = $pj["INSS"];
$pjCNPJ = $pj['CNPJ'];

$codPed = "";

// Executante

$exNome = $ex["Nome"];
$exNomeArtistico = $ex["NomeArtistico"];
$exEstadoCivil = $ex["EstadoCivil"];
$exNacionalidade = $ex["Nacionalidade"];
$exRG = $ex["RG"];
$exCPF = $ex["CPF"];
$exCCM = $ex["CCM"];
$exOMB = $ex["OMB"];
$exDRT = $ex["DRT"];
$exFuncao = $ex["Funcao"];
$exEndereco = $ex["Endereco"];
$exTelefones = $ex["Telefones"];
$exEmail = $ex["Email"];
$exINSS = $ex["INSS"];

// Representante01

$rep01Nome = $rep01["Nome"];
$rep01NomeArtistico = $rep01["NomeArtistico"];
$rep01EstadoCivil = $rep01["EstadoCivil"];
$rep01Nacionalidade = $rep01["Nacionalidade"];
$rep01RG = $rep01["RG"];
$rep01CPF = $rep01["CPF"];
$rep01CCM = $rep01["CCM"];
$rep01OMB = $rep01["OMB"];
$rep01DRT = $rep01["DRT"];
$rep01Funcao = $rep01["Funcao"];
$rep01Endereco = $rep01["Endereco"];
$rep01Telefones = $rep01["Telefones"];
$rep01Email = $rep01["Email"];
$rep01INSS = $rep01["INSS"];


// Representante02

$rep02Nome = $rep02["Nome"];
$rep02NomeArtistico = $rep02["NomeArtistico"];
$rep02EstadoCivil = $rep02["EstadoCivil"];
$rep02Nacionalidade = $rep02["Nacionalidade"];
$rep02RG = $rep02["RG"];
$rep02CPF = $rep02["CPF"];
$rep02CCM = $rep02["CCM"];
$rep02OMB = $rep02["OMB"];
$rep02DRT = $rep02["DRT"];
$rep02Funcao = $rep02["Funcao"];
$rep02Endereco = $rep02["Endereco"];
$rep02Telefones = $rep02["Telefones"];
$rep02Email = $rep02["Email"];
$rep02INSS = $rep02["INSS"];

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=minuta_80k.doc");

//contando parcelas
$con = bancoMysqli();
$sql_parcela = "SELECT * FROM igsis_parcelas WHERE idPedido = $id_ped AND valor != 0";
$query_parcela = mysqli_query($con, $sql_parcela);

$n_parcela = mysqli_num_rows($query_parcela);

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<style type='text/css'>
.style_01 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
</style>

<p><strong>PREFEITURA DO MUNICÍPIO DE SÃO PAULO</strong></p>
<p><strong>SECRETARIA MUNICIPAL DE CULTURA</strong></p>
<p><strong>TERMO DE CONTRATO Nº _______________________</strong></p>
<p><strong>Processo nº <?php echo strtoupper($NumeroProcesso); ?></strong></p>
<p><strong>TERMO DE CONTRATO DE PRESTAÇÃO DE SERVIÇOS FORMALIZADO ENTRE A SECRETARIA MUNICIPAL CULTURA E <?php echo strtoupper($pjRazaoSocial); ?>, COM FUNDAMENTO NO ARTIGO 25, INCISO III, DA LEI FEDERAL Nº 8666/93 E ALTERAÇÕES POSTERIORES, ARTIGO 1º DA LEI MUNICIPAL Nº 13.278/02 E ARTIGOS 16 E 17 DO DECRETO MUNICIPAL Nº 44.279/03.</strong></p>
<p> </p>
<p> A PREFEITURA DO MUNICÍPIO DE SÃO PAULO doravante denominada simplesmente PREFEITURA, através da SECRETARIA MUNICIPAL DE CULTURA, neste ato representada pela Chefe de Gabinete, Giovanna de Moura Rocha Lima, e <?php echo strtoupper($pjRazaoSocial); ?>, CNPJ <?php echo $pjCNPJ; ?>, com endereço <?php echo strtoupper($pjEndereco); ?> , neste ato representada por <?php echo strtoupper($rep01Nome); ?>, RG n° <?php echo strtoupper($rep01RG); ?> , CPF Nº <?php echo $rep01CPF; ?>, doravante denominada CONTRATADA, com fundamento no artigo 25, inciso III da Lei Federal nº 8.666/93 e conforme consta do processo administrativo em referência, tem justo e acordado o que segue:</p>
<p> </p>
<h3>CLÁUSULA PRIMEIRA - DO OBJETO</h3>
<p> </p>
<p>Contratação dos serviços profissionais de natureza artística de <?php echo strtoupper($Objeto); ?>, através de <?php echo strtoupper($exNome); ?> e demais integrantes mencionados na Declaração de Exclusividade, por intermédio da empresa <?php echo strtoupper($pjRazaoSocial); ?>, CNPJ: <?php echo ($pjCNPJ); ?>, representada legalmente por <?php echo strtoupper($rep01Nome); ?>, CPF: <?php echo ($rep01CPF); ?>, para realização do <?php echo ($Objeto); ?> no <?php echo ($Local); ?>, no período <?php echo ($Periodo); ?>, conforme proposta e cronograma.</p>
<p> &nbsp; </p>
<h3>CLÁUSULA SEGUNDA – DAS CONDIÇÕES GERAIS</h3>
<p> </p>
<p>2.1 O presente contrato é regido pelas leis e normas vigentes, especialmente pela Lei Federal nº. 8.666/93, artigo 1º. da Lei Municipal nº. 13.278/02 nos termos dos artigos 16 e 17 do Decreto nº. 44.279/03, inclusive quanto às hipóteses de rescisão.</p>
2.2 A CONTRATANTE se exime de todo e quaisquer ônus e obrigações assumidas pela CONTRATADA
<p>em decorrência de eventual contratação de terceiros.</p>
<p>2.3 A CONTRATANTE fica inteiramente responsável por garantir as condições indispensáveis à consecução dos trabalhos por parte da CONTRATADA no local e horários estipulados.</p>
<p>&nbsp;</p>
<h3>CLÁUSULA TERCEIRA – DO PREÇO E CONDIÇÕES DE PAGAMENTO</h3>
<p> </p>
<p>3.1 Pelos serviços prestados, a CONTRATANTE pagará à CONTRATADA o total de R$ <?php echo ($ValorGlobal); ?>, a serem pagos em <?php echo ($n_parcela); ?> parcelas, após a confirmação da execução dos serviços pela unidade requisitante.</p>
<p>3.2  As despesas relativas ao presente Contrato estão garantidas pela dotação n° 25.10 13.392.3001.6.354 3.3.90.39.00.00. </p>
<p>3.3 Não haverá reajuste do valor contratual.</p>
<p>3.4 No caso de atraso no pagamento por culpa exclusiva da CONTRATANTE haverá, a pedido da CONTRATADA, compensação financeira, nos termos da Portaria SF nº. 05, publicada em 07 de janeiro de 2012. </p>
<p>&nbsp;</p>
<h3>CLÁUSULA QUARTA – DA RESCISÃO E PENALIDADES</h3>
<p> </p>
<p>4.1  A CONTRATADA incorrerá em multa de:</p>
<p>4.1.1. 10% (dez por cento) no caso de infração de cláusula contratual, desobediência às determinações da fiscalização ou se desrespeitar munícipes ou funcionários municipais;</p>
<p>4.1.2 10% (dez por cento) no caso de inexecução parcial do contrato;</p>
<p>4.1.3 30% (trinta por cento) no caso de inexecução total do contrato;</p>
<p>4.1.4 10% (dez por cento) a cada 30 (trinta) minutos de atraso no início do evento sobre o valor total do ajuste. Ultrapassado esse tempo, e independentemente da aplicação da penalidade, fica a critério da SMC autorizar a realização do evento, visando evitar prejuízos à grade de programação. Não sendo autorizada a realização do evento, será considerada inexecução parcial ou total do ajuste conforme o caso, com aplicação da multa prevista por inexecução, acumulada da multa de 20% (vinte por cento) do valor do contrato por rescisão contratual por culpa do contratado. </p>
<p>4.1.5. 10% (dez por cento) sobre o valor do contrato, em função da falta de regularidade fiscal da Contratada, bem como, pela verificação de que a Contratada possui pendências junto ao Cadastro Informativo Municipal – CADIN MUNICIPAL.</p>
<p>4.2 O valor da multa será calculado sobre o valor total do contrato.</p>
<p>4.3. A multa será descontada do pagamento devido ou será inscrita como divida ativa, sujeita à cobrança judicial.</p>
<p>4.4 As multas são independentes entre si, podendo ser aplicadas conjuntamente.</p>
<p>4.5. Além da pena de multa poderá a contratada ser apenada com suspensão temporária de contratar e licitar com a Municipalidade, de acordo com a legislação aplicável.</p>
<p>4.6. O contrato será rescindido nos casos previstos em lei.</p>
<p>&nbsp;</p>
<h3>CLÁUSULA QUINTA – DAS DISPOSIÇÕES FINAIS</h3>
<p>5.1 Nos termos do art. 6º do Decreto Municipal nº 54.873/2014, designo como fiscal desta contratação artística o(a) servidor(a) <?php echo strtoupper($Fiscal); ?> , RF: <?php echo strtoupper($rfFiscal); ?> e, como substituto,  <?php echo strtoupper($Suplente); ?>, RF: <?php echo strtoupper($rfSuplente); ?>.</p>
<p>5.2 O Foro da Fazenda Pública desta Capital será o competente para todo e qualquer procedimento oriundo deste contrato, com renúncia de qualquer outro, por mais especial e privilegiado que seja.</p>
<p>E, para constar, o presente Termo foi digitado em três vias, de igual teor, o qual lido e achado conforme vai assinado pelas partes, com as testemunhas abaixo a tudo presentes.</p>

<p>&nbsp;</p>

<p align='center'>São Paulo, _________ de ________________________________ de 2017.</p>

<p>&nbsp;</p>
<p>&nbsp;</p>

<p align='center'><strong>____________________________________<br/>
GIOVANNA DE MOURA ROCHA LIMA<br/>
Chefe de Gabinete<br/>
Secretria Municipal de Cultura
</strong></p>

<p>&nbsp;</p>

<p align='center'><strong>________________________________<br/>
<?php echo $pjRazaoSocial; ?><br/>
CPF: 
</p></strong>

<p>&nbsp;</p>

<p align='center'><strong>TESTEMUNHAS:</strong></p>
<p>&nbsp;</p>
<p>&nbsp;</p>


<p align='center'>_______________________________ &nbsp; &nbsp; &nbsp; _______________________________ </p>

<p>&nbsp;</p>

</body>
</html>
