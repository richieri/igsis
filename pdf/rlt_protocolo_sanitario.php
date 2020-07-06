<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
//require_once("../include/lib/merge_pdf/fpdf_merge.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

session_start();

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../visual/img/logo_smc.jpg',20,10);
        $this->SetFont('Arial','B',11);
        $this->Cell(90);// Move to the right
        $this->Cell(30,6,utf8_decode('PREFEITURA DE SÃO PAULO'),0,1,'C');
        $this->Cell(90);
        $this->Cell(30,6,utf8_decode('SECRETARIA MUNICIPAL DE CULTURA'),0,1,'C');
        $this->Cell(90);
        $this->Cell(30,6,utf8_decode('COORDENADORIA DE PROGRAMAÇÃO CULTURAL'),0,1,'C');
        $this->Cell(90);
        $this->SetFont('Arial','',8);
        $this->Cell(30,6,utf8_decode('Rua Líbero Badaró, 340/346 - 7º andar - Centro - São Paulo - CEP: 01008-950'),0,1,'C');
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
$pdf->SetXY( $x , 50 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,utf8_decode('Termo de Ciência e Compromisso'),0,1,'C');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', $f);
$pdf->MultiCell(170,$l,utf8_decode("Eu {$rep01['Nome']}, portador do CPF número, {$rep01['CPF']}, RG número {$rep01['RG']}, representante legal da {$pj['Nome']}, CNPJ {$pj['CNPJ']}, diante do cenário pandêmico causado pelo novo Coronavírus, em virtude da contratação do artista {$ex['Nome']}  junto à edição do Museu de Arte de Rua 2020 (MAR 2020), me comprometo, junto com toda a equipe envolvida durante a produção e execução do projeto, a cumprir todos os protocolos de segurança e sanitários (anexado ao termo) descritos em ''Diretrizes Transversais''."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', $f);
$pdf->Cell(170,$l,utf8_decode('As diretrizes supracitadas referem-se a:'),0,1,'L');

$pdf->SetX(30);
$pdf->SetFont('Arial','', $f);
$pdf->Cell(170,$l,utf8_decode('1. Distanciamento social;'),0,1,'L');

$pdf->SetX(30);
$pdf->SetFont('Arial','', $f);
$pdf->Cell(170,$l,utf8_decode('2. Higiene pessoal;'),0,1,'L');

$pdf->SetX(30);
$pdf->SetFont('Arial','', $f);
$pdf->Cell(170,$l,utf8_decode('3. Limpeza e higienização de ambientes, equipamentos e materiais;'),0,1,'L');

$pdf->SetX(30);
$pdf->SetFont('Arial','', $f);
$pdf->Cell(170,$l,utf8_decode('4. Comunicação;'),0,1,'L');

$pdf->SetX(30);
$pdf->SetFont('Arial','', $f);
$pdf->Cell(170,$l,utf8_decode('5. Monitoramento das condições de saúde.'),0,1,'L');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', $f);
$pdf->MultiCell(170,$l,utf8_decode("Outrossim, dou ciência das necessidades de emissão de Atestados de Responsabilidade Técnica (ART) para estruturas, bem como uso de Equipamentos de Proteção Individual (EPI) de segurança para trabalhos em altura."));

$pdf->Ln(20);

$pdf->SetX($x);
$pdf->SetFont('Arial','', $f);
$pdf->Cell(170,$l,utf8_decode(strftime('São Paulo, %d de %B de %Y.', strtotime('today'))),0,1,'L');

$pdf->Ln(20);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(120,$l,utf8_decode("Responsável Legal: {$rep01['Nome']}"),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(120,$l,utf8_decode("CPF: {$rep01['CPF']}"),0,1,'L');

$wImg = 210;

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/1.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/2.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/3.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/4.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/5.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/6.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/7.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/8.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/9.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/10.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/11.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/12.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/13.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/14.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/15.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/16.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/17.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/18.jpg',0,0,$wImg);

$pdf->addPage();
$pdf->image('img/protocolo_saneamento/19.jpg',0,0,$wImg);


$pdf->Output();