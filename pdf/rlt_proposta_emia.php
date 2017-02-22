<?php 
   //Exibe erros PHP
@ini_set('display_errors', '1');
error_reporting(E_ALL); 
   
   // INSTALAÇÃO DA CLASSE NA PASTA FPDF.
   require_once("../include/lib/fpdf/fpdf.php");
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli(); 
   
// logo da instituição 
session_start();


//var_dump($_SESSION);
  
class PDF extends FPDF
{
// Page header
function Header()
{
	$inst = recuperaDados("ig_instituicao",$_SESSION['idInstituicao'],"idInstituicao");
	$logo = "../visual/img/".$inst['logo']; 
    // Logo
    $this->Image($logo,20,20,50);
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

/* variáveis novas a criar 
$formacao = pdfFormacao($id_ped);
$cargo = $formacao['Cargo'];
$programa = $formacao['Programa'];
$descricaoPrograma = $formacao['descricaoPrograma'];
$edital = $formacao['edital'];
$linguagem = $formacao['linguagem'];
*/

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

   
$x=20;
$l=7; //DEFINE A ALTURA DA LINHA   
   
   $pdf->SetXY( $x , 40 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

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
   $pdf->MultiCell(168,$l,utf8_decode($Nome));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(28,$l,utf8_decode('Nome Artístico:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(152,$l,utf8_decode($NomeArtistico));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(23,$l,utf8_decode('Estado Civil:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(35,$l,utf8_decode($EstadoCivil),0,0,'L');
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
   $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(50,$l,utf8_decode($RG),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(53,$l,utf8_decode($CPF),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(12,$l,utf8_decode('OMB:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($OMB),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('DRT:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(40,$l,utf8_decode($DRT),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(15,$l,utf8_decode('C.B.O.:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(30,$l,utf8_decode($cbo),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(15,$l,utf8_decode('Função:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($Funcao),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(20,$l,utf8_decode('Endereço:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(160,$l,utf8_decode($Endereco));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(17,$l,utf8_decode('Telefone:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(87,$l,utf8_decode($Telefones),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(13,$l,utf8_decode('E-mail:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(53,$l,utf8_decode($Email),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(64,$l,utf8_decode('Inscrição no INSS ou nº PIS / PASEP:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(50,$l,utf8_decode($INSS),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(36,$l,utf8_decode('Data de Nascimento:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($DataNascimento),0,1,'L');
   
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
   $pdf->MultiCell(165,$l,utf8_decode($Objeto));
  
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Data / Período:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(153,$l,utf8_decode($Periodo));
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Horário:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(153,$l,utf8_decode("em dias e horários a serem determinados pela Direção da escola podendo ser distribuídos de 2ª feira a sábado, com carga horária mensal distribuídas conforme cronograma."));
      
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
   $pdf->Cell(25,5,'Justificativa:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(155,5,utf8_decode($Justificativa));


//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,262);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,$l,utf8_decode($Nome),'T',1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,$l,"RG: ".$RG,0,0,'L');
   

//	QUEBRA DE PÁGINA
$pdf->AddPage('','');

$pdf->SetXY( $x , 27 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,5,'(C)',0,0,'L');
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(170,5,utf8_decode('CONDIÇÕES GERAIS'),0,1,'C');
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,5,utf8_decode("I - DEVOLUÇÃO: Este documento deverá ser devolvido à Unidade de Origem.
II - DOCUMENTAÇÃO: A xérox da documentação (legível) deverá iniciar o processo constando:
1 - Proposta do artista.
2 - Programa do Evento.
3 - Autorização do SBAT (conforme o caso)
4 - Ficha Técnica atualizada.
5 - Autorização dos componentes do grupo.
6 - RG e CIC do(s) artista(s) ou líder do grupo observada a Ordem Interna nº 10/97 (DOM de 22/8/97).
7 - D.R.T. ou O.M.B. ou Diploma do(s) artista(s).
8 - Currículo(s) do(s) artista(s).
9 - Documento comprobatório da exclusividade do eventual empresário.
10- Contrato Social e alterações posteriores, devidamente registrados na Junta Comercial ou no Cartório de Registro Civil de Pessoas Jurídicas.
11- C.G.C. e C.C.M. da empresa.
12- C.N.D.
13- R.G. e C.I.C. do Representante Legal da empresa.
14- Material de Imprensa, que demonstre consagração pelo público e pela crítica.
15- Parecer conclusivo sobre a natureza artística e razoabilidade do cachê.
III - PUBLICAÇÕES: Constará na publicação o número do processo, autorização para contratação, nome do contratado, serviço e período."),0,'J');
      
   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(0,5,utf8_decode($txtPenalidade),0,'J');
   
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode('DA RESCISÃO CONTRATUAL'),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,5,utf8_decode("1 O contrato poderá ser rescindido pela CONTRATANTE a qualquer tempo.
2 O contrato poderá ser rescindido por qualquer das partes, sem aplicação de penalidades, mediante a notificação à outra, por escrito, com 30 (trinta) dias de antecedência.
3 A inexecução total ou parcial do contrato enseja a sua rescisão, com as consequências contratuais e as previstas em Lei ou regulamento."),0,'J');

   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode('DECLARAÇÕES'),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,5,utf8_decode("Declaro que não tenho débitos perante as Fazendas Públicas, em especial perante a Prefeitura do Município de São Paulo. Declaro, também, estar ciente da penalidade prevista no item IV do Campo ( D ). Todas as informações precedentes são formadas sob as penas da Lei."),0,'J');

   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(180,$l,"Data: _________ / _________ / "."$ano".".",0,0,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   
   
//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,262);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,$l,utf8_decode($Nome),'T',1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,$l,"RG: ".$RG,0,0,'L');

   
      
   
//	QUEBRA DE PÁGINA
$pdf->AddPage('','');
$pdf->SetXY( $x , 37 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA



$l=5; //DEFINE A ALTURA DA LINHA 

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,"CRONOGRAMA",0,1,'C');
   
   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,5,utf8_decode("O(a) contratado(a) executará ".$RG." para a faixa etária ".$RG.", no período ".$Periodo." com carga horária e em dias e horários a serem deterinados pela direção da Escola, distribuída na forma abaixo descrita."),0,'J');
      
   $pdf->Ln();
   /*
   //inicio cronograma
   $con = bancoMysqli();
   $sql_parcelas = "SELECT * FROM igsis_parcelas WHERE idPedido = '$id_ped' ORDER BY vigencia_inicio ASC";
   $query = mysqli_query($con,$sql_parcelas);
   while($parcela = mysqli_fetch_array($query))
   {
		if($parcela['valor'] > 0)
		{
			$inicio = exibirDataBr($parcela['vigencia_inicio']);
			$fim = exibirDataBr($parcela['vigencia_final']);
			$horas = $parcela['horas'];
		
			$pdf->SetX($x);
			$pdf->SetFont('Arial','', 10);
			$pdf->MultiCell(180,$l,utf8_decode("De $inicio a $fim - $horas horas"));
		}
	}
	//fim cronograma
	*/
	//inicio cronograma
   $con = bancoMysqli();
   $sql_parcelas = "SELECT * FROM igsis_parcelas WHERE idPedido = '$id_ped' ORDER BY vigencia_inicio ASC";
   $query = mysqli_query($con,$sql_parcelas);
   while($parcela = mysqli_fetch_array($query))
   {
		if($parcela['valor'] > 0)
		{
			$inicio = exibirDataBr($parcela['vigencia_inicio']);
			$fim = exibirDataBr($parcela['vigencia_final']);
			$horas = $parcela['horas'];
		
			$pdf->SetX($x);
			$pdf->SetFont('Arial','', 10);
			$pdf->MultiCell(180,$l,utf8_decode("De $inicio a $fim - $horas horas"));
		}
	}
	//fim cronograma
   
   $pdf->Ln();
    
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(180,$l,utf8_decode("São Paulo, ______ de ____________________ de "."$ano").".",0,0,'L');
   


//RODAPÉ PERSONALIZADO
   $pdf->SetXY($x,262);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,$l,utf8_decode($Nome),'T',1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,$l,"RG: ".$RG,0,0,'L');
   

//for($i=1;$i<=20;$i++)
   // $pdf->Cell(0,10,'Printing line number '.$i,0,1);

$pdf->Output();


?>