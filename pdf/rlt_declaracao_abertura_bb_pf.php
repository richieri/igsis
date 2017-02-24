<?php 
   
   // INSTALAÇÃO DA CLASSE NA PASTA FPDF.
	require_once("../include/lib/fpdf/fpdf.php");
	
   //require '../include/';
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli();
   
class PDF extends FPDF
{

}



//CONSULTA 
$id_ped=$_GET['id'];

$id_ped=$_GET['id'];

$ano=date('Y');

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],1);


$Objeto = $pedido["Objeto"];

$Nome = $pessoa["Nome"];
$RG = $pessoa["RG"];
$CPF = $pessoa["CPF"];
$CCM = $pessoa["CCM"];
$Endereco = $pessoa["Endereco"];
$Telefones = $pessoa["Telefones"];
$Email = $pessoa["Email"];
$INSS = $pessoa["INSS"];
$DataNascimento = exibirDataBr($pessoa["DataNascimento"]);



$ano=date('Y');


// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

   
$x=20;
$l=8; //DEFINE A ALTURA DA LINHA   
   
   $pdf->SetXY( $x , 30 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode('DECLARAÇÃO DE ABERTURA DE CONTA CORRENTE NO BANCO DO BRASIL'),0,1,'C');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 11);
   $pdf->MultiCell(167,$l,utf8_decode("Eu, ".$Nome.", RG ".$RG.", CPF ".$CPF.", declaro para os devidos fins que estou providenciando a abertura de conta corrente de pessoa física no Banco do Brasil."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 11);
   $pdf->MultiCell(167,$l,utf8_decode("Estou ciente que o pagamento referente a/o ".$Objeto." só será realizado através de crédito em conta corrente no Banco do Brasil, após a apresentação da Ficha de Atualização do Cadastro de Credores (FACC), devidamente assinada e preenchida com os dados da conta corrente no Banco do Brasil, conforme decreto 51197/10."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 11);
   $pdf->MultiCell(167,$l,utf8_decode("Obs.: A conta NÃO pode ser aberta na modalidade conta fácil."));
   
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
   $pdf->Cell(165,$l,utf8_decode($Nome),'T',1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 11);
   $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 11);
   $pdf->Cell(50,$l,utf8_decode($RG),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 11);
   $pdf->Cell(11,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 11);
   $pdf->Cell(50,$l,utf8_decode($CPF),0,1,'L');


$pdf->Output();
?>