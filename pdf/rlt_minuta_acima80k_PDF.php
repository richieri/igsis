<?php 
	session_start();
   
   // INSTALAÇÃO DA CLASSE NA PASTA FPDF.
   require_once("../include/lib/fpdf/fpdf.php");
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli(); 
   

class PDF extends FPDF
{
// Page header
function Header()
{
	$inst = recuperaDados("ig_instituicao",$_SESSION['idInstituicao'],"idInstituicao");	$logo = "img/".$inst['logo']; // Logo
    $this->Image($logo,20,20,50);
    // Move to the right
    $this->Cell(80);
    $this->Image('../visual/img/logo_smc.jpg',170,10);
    // Line break
    $this->Ln(20);
}

}


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

//contando parcelas
$con = bancoMysqli();
$sql_parcela = "SELECT * FROM igsis_parcelas WHERE idPedido = $id_ped AND valor != 0";
$query_parcela = mysqli_query($con, $sql_parcela);

$n_parcela = mysqli_num_rows($query_parcela);

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

   
$x=20;
$l=6; //DEFINE A ALTURA DA LINHA   
   
   $pdf->SetXY( $x , 20 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("PREFEITURA DO MUNICÍPIO DE SÃO PAULO"),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("SECRETARIA MUNICIPAL DE CULTURA"),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("TERMO DE CONTRATO Nº _________________________"),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("PROCESSO Nº ".$NumeroProcesso),0,1,'L');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(80,$l,"",0,0,'L');
   $pdf->MultiCell(100,$l,utf8_decode("TERMO DE CONTRATO DE PRESTAÇÃO DE SERVIÇOS FORMALIZADO ENTRE A SECRETARIA MUNICIPAL CULTURA E ".$pjRazaoSocial.", COM FUNDAMENTO NO ARTIGO 25, INCISO III, DA LEI FEDERAL Nº 8666/93 E ALTERAÇÕES POSTERIORES, ARTIGO 1º DA LEI MUNICIPAL Nº 13.278/02 E ARTIGOS 16 E 17 DO DECRETO MUNICIPAL Nº 44.279/03."));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("                       A PREFEITURA DO MUNICÍPIO DE SÃO PAULO doravante denominada simplesmente PREFEITURA, através da SECRETARIA MUNICIPAL DE CULTURA, neste ato representada pela Chefe de Gabinete, Giovanna de Moura Rocha Lima, e ".$pjRazaoSocial.", CNPJ  ".$pjCNPJ.", com endereço ".$pjEndereco.", neste ato representada por ".$rep01Nome.", RG n° ".$rep01RG.", CPF Nº ".$rep01CPF.", doravante denominada CONTRATADA, com fundamento no artigo 25, inciso III da Lei Federal nº 8.666/93 e conforme consta do processo administrativo em referência, tem justo e acordado o que segue:"));
   
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("CLÁUSULA PRIMEIRA - DO OBJETO"),0,1,'L');

   $pdf->Ln();
     
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Contratação dos serviços profissionais de natureza artística de ".$Objeto.", através de ".$exNome." e demais integrantes mencionados na Declaração de Exclusividade, por intermédio da empresa ".$pjRazaoSocial.", CNPJ: ".$pjCNPJ.", representada legalmente por ".$rep01Nome.", CPF: ".$rep01CPF.", para realização do ".$Objeto." no ".$Local.", no período ".$Periodo.", conforme proposta e cronograma."));
   
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("CLÁUSULA SEGUNDA - DAS CONDIÇÕES GERAIS"),0,1,'L');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("2.1. O presente contrato é regido pelas leis e normas vigentes, especialmente pela Lei Federal nº. 8.666/93, artigo 1º. da Lei Municipal nº. 13.278/02 nos termos dos artigos 16 e 17 do Decreto nº. 44.279/03, inclusive quanto às hipóteses de rescisão."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("2.2. A CONTRATANTE se exime de todo e quaisquer ônus e obrigações assumidas pela CONTRATADA em decorrência de eventual contratação de terceiros."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("2.3. A CONTRATANTE fica inteiramente responsável por garantir as condições indispensáveis à consecução dos trabalhos por parte da CONTRATADA no local e horários estipulados."));
   
//	QUEBRA DE PÁGINA
$pdf->AddPage('','');
$pdf->SetXY( $x , 30 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("CLÁUSULA TERCEIRA - DO PREÇO E CONDIÇÕES DE PAGAMENTO"),0,1,'L');
   
   $pdf->Ln();
   
   // ARRUMAR A QUANTIDADE DE PARCELAS
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("3.1. Pelos serviços prestados, a CONTRATANTE pagará à CONTRATADA o total de R$ ".$ValorGlobal.", a serem pagos em ".$n_parcela." parcelas, após a confirmação da execução dos serviços pela unidade requisitante."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("3.2. As despesas relativas ao presente Contrato estão garantidas pela dotação n° 25.10.13.392.3001.6.354 3.3.90.39.00.00."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("3.3. Não haverá reajuste do valor contratual."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("3.4. No caso de atraso no pagamento por culpa exclusiva da CONTRATANTE haverá, a pedido da CONTRATADA, compensação financeira, nos termos da Portaria SF nº. 05, publicada em 07 de janeiro de 2012."));

   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("CLÁUSULA QUARTA - DA RESCISÃO E PENALIDADES"),0,1,'L');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.1. A CONTRATADA incorrerá em multa de:"));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.1.1. 10% (dez por cento) no caso de infração de cláusula contratual, desobediência às determinações da fiscalização ou se desrespeitar munícipes ou funcionários municipais;"));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.1.2. 10% (dez por cento) no caso de inexecução parcial do contrato;"));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.1.3. 30% (trinta por cento) no caso de inexecução total do contrato;"));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.1.4. 10% (dez por cento) a cada 30 (trinta) minutos de atraso no início do evento sobre o valor total do ajuste. Ultrapassado esse tempo, e independentemente da aplicação da penalidade, fica a critério da SMC autorizar a realização do evento, visando evitar prejuízos à grade de programação. Não sendo autorizada a realização do evento, será considerada inexecução parcial ou total do ajuste conforme o caso, com aplicação da multa prevista por inexecução, acumulada da multa de 20% (vinte por cento) do valor do contrato por rescisão contratual por culpa do contratado."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.1.5. 10% (dez por cento) sobre o valor do contrato, em função da falta de regularidade fiscal da Contratada, bem como, pela verificação de que a Contratada possui pendências junto ao Cadastro Informativo Municipal – CADIN MUNICIPAL."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.2. O valor da multa será calculado sobre o valor total do contrato."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.3. A multa será descontada do pagamento devido ou será inscrita como divida ativa, sujeita à cobrança judicial."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.4. As multas são independentes entre si, podendo ser aplicadas conjuntamente."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.5. Além da pena de multa poderá a contratada ser apenada com suspensão temporária de contratar e licitar com a Municipalidade, de acordo com a legislação aplicável."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.6. O contrato será rescindido nos casos previstos em lei."));
   
   $pdf->Ln();
   $pdf->Ln();
	
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode("CLÁUSULA QUINTA - DAS DISPOSIÇÕES FINAIS"),0,1,'L');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("5.1. Nos termos do art. 6 do Decreto nº. 54.873/2014, designo como fiscal desta contratação artística o(a) servidor(a) ".$Fiscal.", RF ".$rfFiscal." e como substituto ".$Suplente.", RF ".$rfSuplente."."));

//	QUEBRA DE PÁGINA
$pdf->AddPage('','');
$pdf->SetXY( $x , 40 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("5.2. O Foro da Fazenda Pública desta Capital será o competente para todo e qualquer procedimento oriundo deste contrato, com renúncia de qualquer outro, por mais especial e privilegiado que seja.
E, para constar, o presente Termo foi digitado em três vias, de igual teor, o qual lido e achado conforme vai assinado pelas partes, com as testemunhas abaixo a tudo presentes."));
      
   $pdf->Ln();
   $pdf->Ln();
    
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(180,$l,utf8_decode("São Paulo, _________ de ________________________________ de "."$ano"."."),0,0,'C');
 
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(40,$l,'',0,0,'C');
   $pdf->Cell(100,$l,utf8_decode('GIOVANNA DE MOURA ROCHA LIMA'),'T',0,'C');
   $pdf->Cell(40,$l,'',0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Chefe de Gabinete'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Secretria Municipal de Cultura'),0,0,'C');

   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();    
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(40,$l,'',0,0,'C');
   $pdf->Cell(100,$l,utf8_decode($pjRazaoSocial),'T',0,'C');
   $pdf->Cell(40,$l,'',0,1,'C');
      
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode($rep01Nome),0,0,'C');
    
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,"CPF: ".$rep01CPF,0,0,'C');
      
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("TESTEMUNHAS:"),0,1,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(80,$l,utf8_decode(''),'T',0,'L');
   $pdf->Cell(20,$l,utf8_decode(''),'',0,'L');
   $pdf->Cell(80,$l,utf8_decode(''),'T',0,'R');


$pdf->Output();
?>