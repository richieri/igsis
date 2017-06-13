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
}



//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];

$ano=date('Y');

$pedido = siscontrat($id_ped);

$pj = siscontratDocs($pedido['IdProponente'],2);
$ex = siscontratDocs($pedido['IdExecutante'],1);
$rep01 = siscontratDocs($pj['Representante01'],3);
$rep02 = siscontratDocs($pj['Representante02'],3);

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
$Suplente = $pedido["Suplente"];

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


// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

   
$x=20;
$l=8; //DEFINE A ALTURA DA LINHA   
   
   $pdf->SetXY( $x , 30 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode('DECLARAÇÃO DE CONDIÇÕES PARA PAGAMENTO'),0,1,'C');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   
   if ($rep02Nome != '')
   {
	   $pdf->SetX($x);
	   $pdf->SetFont('Arial','', 11);
	   $pdf->MultiCell(167,$l,utf8_decode("Eu, ".$rep01Nome.", RG ".$rep01RG.", CPF ".$rep01CPF.", e eu ".$rep02Nome.", RG ".$rep02RG.", CPF ".$rep02CPF.", representantes da empresa ".$pjRazaoSocial.", inscrita no CNPJ ".$pjCNPJ.", declaramos para os devidos fins que a empresa não possui conta no Banco do Brasil."));
   }
   else
   {
	   $pdf->SetX($x);
	   $pdf->SetFont('Arial','', 11);
	   $pdf->MultiCell(167,$l,utf8_decode("Eu, ".$rep01Nome.", RG ".$rep01RG.", CPF ".$rep01CPF.", representante da empresa ".$pjRazaoSocial.", inscrita no CNPJ ".$pjCNPJ.", declaro para os devidos fins que a empresa não possui conta no Banco do Brasil."));
   }
      
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 11);
   $pdf->MultiCell(167,$l,utf8_decode("Por se tratar de uma contratação de natureza eventual e não continuada e o cachê não exceder R$ 5.000,00 (cinco mil reais), solicito que o pagamento seja efetuado na conta informada na FACC, através de recursos 500, conforme art. 3º da portaria SF 255/15."));
      
   $pdf->Ln();
   $pdf->Ln();
    
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 11);
   $pdf->Cell(180,$l,utf8_decode("São Paulo, _________ de ________________________________ de "."$ano"."."),0,0,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 11);
   $pdf->Cell(165,$l,utf8_decode($rep01Nome),'T',1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 11);
   $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 11);
   $pdf->Cell(50,$l,utf8_decode($rep01RG),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 11);
   $pdf->Cell(11,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 11);
   $pdf->Cell(50,$l,utf8_decode($rep01CPF),0,1,'L');
   
   if ($rep02Nome != '')
   {
	   $pdf->Ln();
	   $pdf->Ln();
	   $pdf->Ln();
	   $pdf->Ln();
	   $pdf->Ln();

	   $pdf->SetX($x);
	   $pdf->SetFont('Arial','B', 11);
	   $pdf->Cell(165,$l,utf8_decode($rep02Nome),'T',1,'L');
	   
	   $pdf->SetX($x);
	   $pdf->SetFont('Arial','B', 11);
	   $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
	   $pdf->SetFont('Arial','', 11);
	   $pdf->Cell(50,$l,utf8_decode($rep02RG),0,1,'L');
	   
	   $pdf->SetX($x);
	   $pdf->SetFont('Arial','B', 11);
	   $pdf->Cell(11,$l,utf8_decode('CPF:'),0,0,'L');
	   $pdf->SetFont('Arial','', 11);
	   $pdf->Cell(50,$l,utf8_decode($rep02CPF),0,1,'L');
   }
   
$pdf->Output();
?>