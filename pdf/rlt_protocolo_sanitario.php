<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

session_start();

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Move to the right
        $this->Cell(80);
        $this->Image('../visual/img/logo_smc.jpg',170,10);
        // Line break
        $this->Ln(20);
    }
}


//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];

$pedido = siscontrat($id_ped);
$pj = siscontratDocs($pedido['IdProponente'],2);
$rep01 = siscontratDocs($pj['Representante01'],3);
$ex = siscontratDocs($pedido['IdExecutante'],1);

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=6; //DEFINE A ALTURA DA LINHA
$f=11; //TAMANHO DA FONTE
$pdf->SetXY( $x , 35 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,utf8_decode('Termo de Ciência e Compromisso'),0,1,'C');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', $f);
$pdf->MultiCell(170,$l,utf8_decode("Eu {$rep01['Nome']}, portador do CPF número, {$rep01['CPF']}, RG número {$rep01['RG']}, representante legal da {$pj['Nome']}, CNPJ {$pj['CNPJ']}, diante do cenário pandêmico causado pelo novo Coronavírus, em virtude da contratação do artista {$ex['Nome']}  junto à edição do Museu de Arte de Rua 2020 (MAR 2020), me comprometo, junto com toda a equipe envolvida durante a produção e execução do projeto, a cumprir todos os protocolos de segurança e sanitários (anexado ao termo) descritos em ''Diretrizes Transversais''."));

$pdf->Output();