<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

class PDF extends FPDF{

}

//CONSULTA
$con = bancoMysqli();
$id_ped=$_GET['id'];

$pj = $con->query("SELECT pj.RazaoSocial, pj.CNPJ, pj.IdRepresentanteLegal1  FROM sis_pessoa_juridica AS pj INNER JOIN igsis_pedido_contratacao AS ipc ON ipc.idPessoa = pj.Id_PessoaJuridica WHERE ipc.tipoPessoa = 2 AND ipc.publicado = 1 AND ipc.idPedidoContratacao = '$id_ped'")->fetch_assoc();
$rep = $con->query("SELECT srl.RepresentanteLegal, srl.RG, srl.CPF FROM sis_representante_legal AS srl WHERE srl.Id_RepresentanteLegal = '{$pj['IdRepresentanteLegal1']}'")->fetch_assoc();

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=7; //DEFINE A ALTURA DA LINHA
$f=12; //DEFINE O TAMANHO DA FONTE

$pdf->SetXY( $x , 45 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(180,5,utf8_decode("DECLARAÇÃO"),0,1,'C');

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', $f);
$pdf->MultiCell(170,$l,utf8_decode("Devido ao contexto de pandemia causada pelo novo Coronavírus - COVID-19 - e o não funcionamento de todos os serviços disponibilizados pela Caixa Econômica Federal durante este período, razão pela qual não foi possível realizar o cadastro de empregador e, portanto, não ser possível a emissão do Certificado de Regularidade do FGTS-CRF, DECLARO, sob as penas da lei, que a empresa {$pj['RazaoSocial']}, CNPJ nº {$pj['CNPJ']}, não possuiu funcionários e se compromete a apresentar referido documento assim que normalizados os trabalhos da Caixa Econômica Federal."));

$pdf->Ln(40);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(165,$l,utf8_decode($rep['RepresentanteLegal']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(9,5,utf8_decode('RG:'),0,0,'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(50,5,utf8_decode($rep['RG']),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(11,5,utf8_decode('CPF:'),0,0,'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(50,5,utf8_decode($rep['CPF']),0,0,'L');

$pdf->Output();