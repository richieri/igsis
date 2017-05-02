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


// Page footer
/*
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
*/

//INSERIR ARQUIVOS

function ChapterBody($file)
{
    // Read text file
    $txt = file_get_contents($file);
    // Arial 10
    $this->SetFont('Arial','',10);
    // Output justified text
    $this->MultiCell(0,5,$txt);
    // Line break
    $this->Ln();
}

function PrintChapter($file)
{
    $this->ChapterBody($file);
}

}


//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];
$idPenalidade = $_GET['penal'];
dataProposta($id_ped);
gravaPenalidade($id_ped,$idPenalidade);
$penal = recuperaDados("sis_penalidades",$idPenalidade,"idPenalidades");
$txtPenalidade = $penal['txt'];
$ano=date('Y');

$pedido = siscontrat($id_ped);

$idPedido = $id_ped;

gravaPenalidade($idPedido, $idPenalidade);

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

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

   
$x=20;
$l=7; //DEFINE A ALTURA DA LINHA   

	//Executante
   
   $pdf->SetXY( $x , 37 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,5,'(A)',0,0,'L');
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(170,5,'CONTRATADO',0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','I', 10);
   $pdf->Cell(10,10,utf8_decode('(Quando se tratar de grupo, o líder do grupo)'),0,0,'L');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,'Nome:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(168,$l,utf8_decode($exNome));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(28,$l,utf8_decode('Nome Artístico:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(152,$l,utf8_decode($exNomeArtistico));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(23,$l,utf8_decode('Estado Civil:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(65,$l,utf8_decode($exEstadoCivil),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(28,$l,utf8_decode('Nacionalidade:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(17,$l,utf8_decode($exNacionalidade),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(7,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(50,$l,utf8_decode($exRG),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(53,$l,utf8_decode($exCPF),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CCM:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($exCCM),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,utf8_decode('OMB:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($exOMB),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('DRT:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($exDRT),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(15,$l,utf8_decode('Função:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($exFuncao),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(20,$l,utf8_decode('Endereço:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(160,$l,utf8_decode($exEndereco));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(17,$l,utf8_decode('Telefone:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(88,$l,utf8_decode($exTelefones),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(13,$l,utf8_decode('E-mail:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(52,$l,utf8_decode($exEmail),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(64,$l,utf8_decode('Inscrição no INSS ou nº PIS / PASEP:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($exINSS),0,1,'L');
   
  
   
   $pdf->SetX($x);
   $pdf->Cell(180,5,'','B',1,'C');
   
   $pdf->Ln();
    
   
	// Proposta   
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
   $pdf->MultiCell(165,$l,utf8_decode($Objeto));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Data / Período:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(153,$l,utf8_decode("Conforme programação anexa."));
      
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(15,$l,'Local:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(165,$l,utf8_decode($Local));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(15,$l,'Valor:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(168,$l,utf8_decode("R$ $ValorGlobal"."  "."($ValorPorExtenso )"));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(40,$l,'Forma de Pagamento:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(140,5,utf8_decode("O pagamento se dará em até 45 dias úteis, após a data de realização do evento, mediante a entrega dentro do prazo solicitado de toda documentação correta relativa ao pagamento."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(25,$l,'Justificativa:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(155,5,utf8_decode("Promovida desde 2005 pela Prefeitura de São Paulo, por meio da Secretaria Municipal de Cultura, a Virada Cultural tornou-se ao longo de sua existência um dos maiores eventos culturais oferecidos aos cidadãos paulistanos e aos turistas que para cá convergem por ocasião da realização deste evento. Tradicionalmente o evento oferece, todos os anos, 24 horas de programação contínua integrando os diversos equipamentos da SMC, bem como a ocupação de espaços públicos das diferentes regiões da cidade de São Paulo. O objeto deste processo é a contratação dos artistas para realizar a atração durante este evento que em 2017 ocorrerá ao longo dos dias 20 e 21 de maio A Prefeitura de São Paulo, através de uma política cultural diversificada, proporciona assim, a todos os munícipes e visitantes, o acesso gratuito ao que há de melhor na produção cultural atual existente no País."));


//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,261);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(85,5,utf8_decode($rep01Nome),'T',0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,5,utf8_decode($rep02Nome),'T',0,'L');
   }  
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(85,5,"RG: ".$rep01RG,0,0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,5,"RG: ".$rep02RG,0,0,'L');
   }  
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(85,5,"CPF: ".$rep01CPF,0,0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,5,"CPF: ".$rep02CPF,0,0,'L');
   }
   

//	QUEBRA DE PÁGINA
$pdf->AddPage('','');

$pdf->SetXY( $x , 20 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$l=4; //DEFINE A ALTURA DA LINHA  

	//Pessoa Jurídica

$pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,5,'(C)',0,0,'L');
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(170,5,utf8_decode('PESSOA JURÍDICA'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','I', 9);
   $pdf->Cell(10,10,utf8_decode('(empresário exclusivo SE FOR O CASO)'),0,0,'L');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(33,$l,'Nome da empresa:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(155,$l,utf8_decode($pjRazaoSocial));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CCM:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(65,$l,utf8_decode($pjCCM),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,utf8_decode('CNPJ:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(65,$l,utf8_decode($pjCNPJ),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(20,$l,utf8_decode('Endereço:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(160,$l,utf8_decode($pjEndereco));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(17,$l,utf8_decode('Telefone:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(85,$l,utf8_decode($pjTelefones),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(13,$l,utf8_decode('E-mail:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(60,$l,utf8_decode($pjEmail),0,1,'L');
   
   //Representante01
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(28,$l,'Representante:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(155,$l,utf8_decode($rep01Nome));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(23,$l,utf8_decode('Estado Civil:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(67,$l,utf8_decode($rep01EstadoCivil),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Nacionalidade:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(16,$l,utf8_decode($rep01Nacionalidade),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(82,$l,utf8_decode($rep01RG),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(48,$l,utf8_decode($rep01CPF),0,1,'L');
   
   //$pdf->Ln();

	// Representante02
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(28,7,'Representante:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(155,7,utf8_decode($rep02Nome));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(23,$l,utf8_decode('Estado Civil:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(67,$l,utf8_decode($rep02EstadoCivil),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Nacionalidade:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(16,$l,utf8_decode($rep02Nacionalidade),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(82,$l,utf8_decode($rep02RG),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(48,$l,utf8_decode($rep02CPF),0,1,'L');   
   
   $pdf->SetX($x);
   $pdf->Cell(180,5,'','B',1,'C');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,5,'(D)',0,0,'L');
   $pdf->SetFont('Arial','B', 8);
   $pdf->Cell(170,5,utf8_decode('OBSERVAÇÕES'),0,1,'C');
   
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 8);
   $pdf->MultiCell(0,$l,utf8_decode("A/o proponente tem ciência da obrigatoriedade de fazer menção dos créditos da PREFEITURA DA CIDADE DE SÃO PAULO, SECRETARIA MUNICIPAL DE CULTURA, em toda divulgação, escrita ou falada, realizada sobre o espetáculo programado, sob pena de cancelamento sumário do evento.
No caso de pagamento do cachê por reversão de bilheteria, fica o valor dos ingressos sujeito ao atendimento no disposto nas Leis Municipais nº 10.973/91, regulamentada pelo Decreto Municipal nº 30.730/91; Leis Municipais 11.113/91, 11.357/93 e 12.975/2000 e Portaria 66/SMC/2007; Lei Estadual nº 7.844/92, regulamentada pelo Decreto Estadual nº 35.606/92; Lei Estadual 10.858/2001, com as alterações da Lei Estadual nº 14.729/2012 e Lei Federal nº 12.933/2013.
Nos casos de comercialização de qualquer produto artístico-cultural, a proponente assume inteira responsabilidade fiscal e tributária quanto à sua comercialização, isentando a Municipalidade de quaisquer ônus ou encargos, nos termos da O.I. nº 01/2002 – SMC-G.
No caso de espetáculo musical, declara assumir quaisquer ônus decorrentes da fiscalização e autuação da Ordem dos Músicos do Brasil – OMB."),0,'J');
   
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 8);
   $pdf->Cell(180,5,utf8_decode('DECLARAÇÕES'),0,1,'C');
   

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 8);
   $pdf->MultiCell(0,$l,utf8_decode($txtPenalidade),0,'J');

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 8);
   $pdf->Cell(180,$l,"Data: _________ / _________ / "."$ano".".",0,0,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   
   
//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,264);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(85,4,utf8_decode($rep01Nome),'T',0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,4,utf8_decode($rep02Nome),'T',0,'L');
   }  
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(85,4,"RG: ".$rep01RG,0,0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,4,"RG: ".$rep02RG,0,0,'L');
   }  
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(85,4,"CPF: ".$rep01CPF,0,0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,4,"CPF: ".$rep02CPF,0,0,'L');
   }
   
   
   
//	QUEBRA DE PÁGINA
$pdf->AddPage('','');

$pdf->SetXY( $x , 37 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$l=5; //DEFINE A ALTURA DA LINHA 

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(170,5,utf8_decode('CRONOGRAMA'),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 12);
   $pdf->MultiCell(170,$l,utf8_decode($Objeto));
   
   $pdf->Ln();	 

	$ocor = listaOcorrenciasContrato($id);

	for($i = 0; $i < $ocor['numero']; $i++){
	
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
   $pdf->Cell(22,$l,utf8_decode('Data/Perído:'),0,0,'L');
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
   $pdf->SetXY($x,261);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(85,5,utf8_decode($rep01Nome),'T',0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,5,utf8_decode($rep02Nome),'T',0,'L');
   }  
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(85,5,"RG: ".$rep01RG,0,0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,5,"RG: ".$rep02RG,0,0,'L');
   }  
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(85,5,"CPF: ".$rep01CPF,0,0,'L');
   if ($rep02Nome != '')
   {
	   $pdf->Cell(85,5,"CPF: ".$rep02CPF,0,0,'L');
   }  
   

//for($i=1;$i<=20;$i++)
   // $pdf->Cell(0,10,'Printing line number '.$i,0,1);
$pdf->Output();


?>