<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/pdf_html.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS
$conexao = bancoMysqli();

session_start();

class PDF extends PDF_HTML
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

   //INSERIR ARQUIVOS
   function ChapterBody($file)
   {
       // Read text file
       $txt = file_get_contents($file);
       // Arial 10
       $this->SetFont('Arial','',8);
       // Output justified text
       $this->MultiCell(0,4,$txt);
       // Line break
       $this->Ln();
   }

   function PrintChapter($file)
   {
       $this->ChapterBody($file);
   }
}


//CONSULTA
$id_ped=$_GET['id'];
$idPenalidade = $_GET['penal'];
dataProposta($id_ped);
gravaPenalidade($id_ped,$idPenalidade);
$penal = recuperaDados("sis_penalidades",$idPenalidade,"idPenalidades");
$txtPenalidade = $penal['txt'];
$ano=date('Y');

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],1);

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

$Nome = $pessoa["Nome"];
$NomeArtistico = $pessoa["NomeArtistico"];
$EstadoCivil = $pessoa["EstadoCivil"];
$Nacionalidade = $pessoa["Nacionalidade"];
$DataNascimento = exibirDataBr($pessoa["DataNascimento"]);
$RG = $pessoa["RG"];
$CPF = $pessoa["CPF"];
$CCM = $pessoa["CCM"];
$OMB = $pessoa["OMB"];
$DRT = $pessoa["DRT"];
$cbo = $pessoa["cbo"];
$Funcao = $pessoa["Funcao"];
$Endereco = $pessoa["Endereco"];
$Telefones = $pessoa["Telefones"];
$Email = $pessoa["Email"];
$INSS = $pessoa["INSS"];

$sqlParcelas = "SELECT * FROM `igsis_parcelas` WHERE idPedido = '$id_ped'";
$queryParcelas = $conexao->query($sqlParcelas);
$tempoGlobal = 0;

while ($linha = $queryParcelas->fetch_array()) {
   $tempoGlobal += $linha['horas'];
}


// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=6; //DEFINE A ALTURA DA LINHA
$pdf->SetXY( $x , 35 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,5,'(A)',0,0,'L');
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(170,5,'CONTRATADO',0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','I', 9);
   $pdf->Cell(10,10,utf8_decode('(Quando se tratar de grupo, o líder do grupo)'),0,0,'L');

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,'Nome:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(168,$l,utf8_decode($Nome));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(28,$l,utf8_decode('Nome Artístico:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(152,$l,utf8_decode($NomeArtistico));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(7,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(30,$l,utf8_decode($RG),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(40,$l,utf8_decode($CPF),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('DRT:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(30,$l,utf8_decode($DRT),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,utf8_decode('OMB:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(20,$l,utf8_decode($OMB),0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(36,$l,utf8_decode('Data de Nascimento:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   if($DataNascimento == "31/12/1969"){
      $pdf->Cell(25,$l, " " ,0,0,'L');
   }else {
      $pdf->Cell(25,$l,utf8_decode($DataNascimento),0,0,'L');
   }
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(28,$l,utf8_decode('Nacionalidade:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(35,$l,utf8_decode($Nacionalidade),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CCM:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($CCM),0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(20,$l,utf8_decode('Endereço:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(160,$l,utf8_decode($Endereco));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(17,$l,utf8_decode('Telefone:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(87,$l,utf8_decode($Telefones),0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(13,$l,utf8_decode('E-mail:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(53,$l,utf8_decode($Email),0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(64,$l,utf8_decode('Inscrição no INSS ou nº PIS / PASEP:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(50,$l,utf8_decode($INSS),0,1,'L');

   $pdf->SetX($x);
   $pdf->Cell(180,5,'','B',1,'C');

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,10,'(B)',0,0,'L');
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(160,10,'PROPOSTA',0,0,'C');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,10,$ano."-".$id_ped,0,1,'R');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(15,$l,'Objeto:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $convenio = utf8_decode("CONVÊNIO FEDERAL N° 849979/2017 cujo o objeto é a Contratação artística de oficinas de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da Secretaria Municipal de Cultura.");
   $pdf->MultiCell(165,$l,utf8_decode($Objeto)." - ".$convenio);

$pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Data / Período:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(153,$l,utf8_decode($Periodo));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(45,$l,utf8_decode('Tempo global da oficina:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(20,$l,$tempoGlobal." horas",0,0,'L');
   if($CargaHoraria != '')
   {
      $pdf->SetFont('Arial','B', 10);
      $pdf->Cell(27,$l,utf8_decode('Carga Horária:'),0,0,'L');
      $pdf->SetFont('Arial','', 10);
      $pdf->Cell(50,$l,utf8_decode($CargaHoraria),0,0,'L');
   }

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,'Local:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(168,$l,utf8_decode($Local));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,'Valor:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(168,$l,utf8_decode("R$ $ValorGlobal"."  "."($ValorPorExtenso )"));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(40,5,'Forma de Pagamento:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(140,5,utf8_decode($FormaPagamento));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(25,5,'Justificativa:',0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(180,5,utf8_decode($Justificativa));


//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,262);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,utf8_decode($Nome),'T',1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"RG: ".$RG,0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"CPF: ".$CPF,0,0,'L');


//	QUEBRA DE PÁGINA
$pdf->AddPage('','');

$pdf->SetXY( $x , 30 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,$l,'(C)',0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(160,$l,'',0,1,'C');

   $pdf->SetX($x);
   $pdf->Cell(10,$l,'',0,0,'L');

//   $pdf->PrintChapter('txt/proposta_observacao_padrao.txt');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(160,$l,utf8_decode('EDITAL DE CREDENCIAMENTO Nº 02/2018 - SMC/GAB'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,5,utf8_decode("CONVÊNIO FEDERAL N° 849979/2017, cujo o objeto é a Contratação artística de oficinas de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da Secretaria Municipal de Cultura."), 0, 'C');

   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,5,'Declaro que:',0,0,'L');

//   $pdf->SetX($x);
//   $pdf->SetFont('Arial','', 9);
//   $pdf->MultiCell(0,4,utf8_decode($txtPenalidade),0,'J');

   $pdf->Ln(20);

   /*Texto Declaração*/
   $declaracao = [
     'bullet' => chr(149),
     'margin' => ' ',
     'indent' => 0,
     'spacer' => 0,
     'text' => [
         utf8_decode('Conheço e aceito incondicionalmente as regras do Edital n. 02/2018 - SMC/GAB de Credenciamento;'),
         utf8_decode('Em caso de seleção, responsabilizo-me pelo cumprimento da agenda acordada entre o equipamento municipal e o Oficineiro, no tocante ao local, data e horário, para a realização da Oficina. Em acordo com o previsto no convênio federal n° 849979/2017'),
         utf8_decode('Não sou servidor público municipal.'),
         utf8_decode('Estou ciente de que a contratação não gera vínculo trabalhista entre a Municipalidade e o Contratado.'),
         utf8_decode('Estou ciente da aplicação de penalidades conforme item 11 do Edital de Credenciamento nº 02/2018 SMC/GAB')
     ]
   ];

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCellBltArray(160, 5, $declaracao);

   $pdf->SetXY($x, 240);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(180,$l,"Data: _________ / _________ / "."$ano".".",0,0,'L');


//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,262);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,utf8_decode($Nome),'T',1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"RG: ".$RG,0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"CPF: ".$CPF,0,0,'L');



//	QUEBRA DE PÁGINA
$pdf->AddPage('','');
$pdf->SetXY( $x , 37 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA



$l=5; //DEFINE A ALTURA DA LINHA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(170,5,utf8_decode('CRONOGRAMA'),0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(170,7,utf8_decode($Objeto)." - ".$convenio);

   $pdf->Ln();

   $pdf->SetFont('Arial','', 12);

	$ocor = listaOcorrenciasContrato($id);

	for($i = 0; $i < $ocor['numero']; $i++)
   {
   	$tipo = $ocor[$i]['tipo'];
   	$dia = $ocor[$i]['data'];
   	$hour = $ocor[$i]['hora'];
   	$lugar = $ocor[$i]['espaco'];

      $pdf->SetX($x);
      $pdf->SetFont('Arial','B', 10);
      $pdf->Cell(12,$l,utf8_decode('Tipo:'),0,0,'L');
      $pdf->SetFont('Arial','', 10);
      $pdf->MultiCell(158,$l,utf8_decode($tipo));

      $pdf->SetX($x);
      $pdf->SetFont('Arial','B', 10);
      $pdf->Cell(22,$l,utf8_decode('Data/Período:'),0,0,'L');
      $pdf->SetFont('Arial','', 10);
      $pdf->MultiCell(148,$l,utf8_decode($dia));

      $pdf->SetX($x);
      $pdf->SetFont('Arial','B', 10);
      $pdf->Cell(15,$l,utf8_decode('Horário:'),0,0,'L');
      $pdf->SetFont('Arial','', 10);
      $pdf->MultiCell(155,$l,utf8_decode($hour));

      $pdf->SetX($x);
      $pdf->SetFont('Arial','B', 10);
      $pdf->Cell(12,$l,utf8_decode('Local:'),0,0,'L');
      $pdf->SetFont('Arial','', 10);
      $pdf->MultiCell(158,$l,utf8_decode($lugar));

      $pdf->Ln();
	}

//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,262);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,utf8_decode($Nome),'T',1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"RG: ".$RG,0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"CPF: ".$CPF,0,0,'L');


$pdf->Output();
?>
