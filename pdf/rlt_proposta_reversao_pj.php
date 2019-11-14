<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS
$conexao = bancoMysqli();

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
$NumeroProcesso = $pedido["NumeroProcesso"];


//PessoaJuridica
$pjRazaoSocial = $pj["Nome"];
$pjCNPJ = $pj['CNPJ'];
$pjCCM = $pj["CCM"];
$pjEndereco = $pj["Endereco"];
$pjTelefones = $pj["Telefones"];
$pjEmail = $pj["Email"];


// Executante
$exNome = $ex["Nome"];
$exNomeArtistico = $ex["NomeArtistico"];
$exEstadoCivil = $ex["EstadoCivil"];
$exNacionalidade = $ex["Nacionalidade"];
$exDataNascimento = exibirDataBr($ex["DataNascimento"]);
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
$rep01EstadoCivil = $rep01["EstadoCivil"];
$rep01Nacionalidade = $rep01["Nacionalidade"];
$rep01RG = $rep01["RG"];
$rep01CPF = $rep01["CPF"];


// Representante02
$rep02Nome = $rep02["Nome"];
$rep02EstadoCivil = $rep02["EstadoCivil"];
$rep02Nacionalidade = $rep02["Nacionalidade"];
$rep02RG = $rep02["RG"];
$rep02CPF = $rep02["CPF"];


// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=6; //DEFINE A ALTURA DA LINHA
$pdf->SetXY( $x , 25 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA6

//Executante
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
	$pdf->MultiCell(168,$l,utf8_decode($exNome));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(28,$l,utf8_decode('Nome Artístico:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->MultiCell(152,$l,utf8_decode($exNomeArtistico));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(7,$l,utf8_decode('RG:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(30,$l,utf8_decode($exRG),0,0,'L');
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(40,$l,utf8_decode($exCPF),0,0,'L');
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(10,$l,utf8_decode('DRT:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(30,$l,utf8_decode($exDRT),0,0,'L');
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(12,$l,utf8_decode('OMB:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(20,$l,utf8_decode($exOMB),0,1,'L');

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
    $pdf->Cell(36,$l,utf8_decode('Data de Nascimento:'),0,0,'L');
    $pdf->SetFont('Arial','', 10);
    if($exDataNascimento == "31/12/1969"){
      $pdf->Cell(25,$l, " " ,0,0,'L');
    }else {
      $pdf->Cell(25,$l,utf8_decode($exDataNascimento),0,0,'L');
    }
    $pdf->SetFont('Arial','B', 10);
    $pdf->Cell(28,$l,utf8_decode('Nacionalidade:'),0,0,'L');
    $pdf->SetFont('Arial','', 10);
    $pdf->Cell(35,$l,utf8_decode($exNacionalidade),0,0,'L');
    $pdf->SetFont('Arial','B', 10);
    $pdf->Cell(10,$l,utf8_decode('CCM:'),0,0,'L');
    $pdf->SetFont('Arial','', 10);
    $pdf->Cell(45,$l,utf8_decode($exCCM),0,1,'L');

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(20,$l,utf8_decode('Endereço:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->MultiCell(160,$l,utf8_decode($exEndereco));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(17,$l,utf8_decode('Telefone:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(75,$l,utf8_decode($exTelefones),0,1,'L');

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(13,$l,utf8_decode('E-mail:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(65,$l,utf8_decode($exEmail),0,1,'L');

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
    $pdf->Cell(10,10,$NumeroProcesso,0,1,'R');
    $pdf->Cell(186,10,$ano."-".$id_ped,0,1,'R');


	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(15,$l,'Objeto:',0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->MultiCell(165,$l,utf8_decode($Objeto));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(27,$l,utf8_decode('Data / Período:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->MultiCell(153,$l,utf8_decode("$Periodo"." - conforme cronograma."));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(82,$l,utf8_decode('Tempo Aproximado de Duração do Espetáculo:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->MultiCell(98,$l,utf8_decode("$Duracao"."utos"));

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
	$pdf->Cell(40,5,'Forma de Pagamento:',0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->MultiCell(140,5,utf8_decode($FormaPagamento));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(25,$l,'Justificativa:',0,1,'L');

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 9);
	$pdf->MultiCell(180,5,utf8_decode($Justificativa));


//RODAPÉ PERSONALIZADO
	$pdf->SetXY($x,262);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(85,4,utf8_decode($rep01Nome),'T',0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(85,4,utf8_decode($rep02Nome),'T',0,'L');
	}

	$pdf->Ln();

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(85,4,"RG: ".$rep01RG,0,0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(85,4,"RG: ".$rep02RG,0,0,'L');
	}

	$pdf->Ln();

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(1005,4,"CPF: ".$rep01CPF,0,0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(100,4,"CPF: ".$rep02CPF,0,0,'L');
	}


//	QUEBRA DE PÁGINA
$pdf->AddPage('','');
$pdf->SetXY( $x , 25 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA
$l=5; //DEFINE A ALTURA DA LINHA


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
	$pdf->Cell(75,$l,utf8_decode($pjTelefones),0,0,'L');
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(13,$l,utf8_decode('E-mail:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(65,$l,utf8_decode($pjEmail),0,1,'L');

	$pdf->Ln();

//Representante01
	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(28,$l,'Representante:',0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->MultiCell(155,$l,utf8_decode($rep01Nome));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(82,$l,utf8_decode($rep01RG),0,0,'L');
	$pdf->SetFont('Arial','B', 10);
	$pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(48,$l,utf8_decode($rep01CPF),0,1,'L');

// Representante02
	if ($rep02Nome != '')
	{
		$pdf->SetX($x);
		$pdf->SetFont('Arial','B', 10);
		$pdf->Cell(28,7,'Representante:',0,0,'L');
		$pdf->SetFont('Arial','', 10);
		$pdf->MultiCell(155,7,utf8_decode($rep02Nome));

		$pdf->SetX($x);
		$pdf->SetFont('Arial','B', 10);
		$pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
		$pdf->SetFont('Arial','', 10);
		$pdf->Cell(82,$l,utf8_decode($rep02RG),0,0,'L');
		$pdf->SetFont('Arial','B', 10);
		$pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
		$pdf->SetFont('Arial','', 10);
		$pdf->Cell(48,$l,utf8_decode($rep02CPF),0,1,'L');
	}

	$pdf->SetX($x);
	$pdf->Cell(180,5,'','B',1,'C');

	$pdf->Ln();

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 9);
	$pdf->Cell(10,$l,'(D)',0,0,'L');
	$pdf->SetFont('Arial','B', 9);
	$pdf->Cell(160,5,utf8_decode('OBSERVAÇÕES'),0,1,'C');

	$pdf->SetX($x);
	$pdf->PrintChapter('txt/proposta_observacao_padrao.txt');

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 9);
	$pdf->Cell(10,$l,'',0,0,'L');
	$pdf->SetFont('Arial','B', 9);
	$pdf->Cell(160,5,utf8_decode('DECLARAÇÕES'),0,1,'C');

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 8);
	$pdf->MultiCell(0,4,utf8_decode($txtPenalidade),0,'J');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 9);
   $pdf->Cell(160,5,utf8_decode('NOS CASOS DE REVERSÃO DE BILHETERIA'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 8);
   $pdf->MultiCell(180,5,utf8_decode('1) No caso de pagamento do cachê por reversão de bilheteria, fica o valor dos ingressos sujeito ao atendimento no disposto nas Leis Municipais nº 10.973/91, regulamentada pelo Decreto Municipal nº 30.730/91; Leis Municipais 11.113/91; 11.357/93 e 12.975/2000 e Portaria nº 66/SMC/2007; Lei Estadual nº 7844/92, regulamentada pelo Decreto Estadual nº 35.606/92; Lei Estadual nº 10.858/2001, com as alterações da Lei Estadual 14.729/2012 e Lei Federal nº 12.933/2013.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 8);
   $pdf->MultiCell(180,5,utf8_decode('2) O pagamento do cachê corresponderá à reversão integral da renda obtida na bilheteria a/o ontratada/o, deduzidos os impostos e taxas pertinentes.'));

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 8);
	$pdf->MultiCell(180,5,utf8_decode('3) Os ingressos poderão ser vendidos com preços reduzidos, em face de promoções realizadas pela produção do evento.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 9);
   $pdf->Cell(160,5,utf8_decode('RESCISÃO'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 8);
   $pdf->MultiCell(180,5,utf8_decode('Este instrumento poderá ser rescindido, no interesse da administração, devidamente justificado ou em virtude da inexecução total ou parcial do serviço sem prejuízo de multa, nos termos da legislação vigente.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 9);
   $pdf->Cell(160,5,utf8_decode('FORO'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(180,5,utf8_decode('Fica eleito o foro da Fazenda Pública para todo e qualquer procedimento judicial oriundo deste instrumento.'));

 	$pdf->Ln();


   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(180,$l,"Data: _________ / _________ / "."$ano".".",0,0,'L');

   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();



//RODAPÉ PERSONALIZADO
	$pdf->SetX($x,262);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(85,4,utf8_decode($rep01Nome),'T',0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(85,4,utf8_decode($rep02Nome),'T',0,'L');
	}

	$pdf->Ln();

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(85,4,"RG: ".$rep01RG,0,0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(85,4,"RG: ".$rep02RG,0,0,'L');
	}

	$pdf->Ln();

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(1005,4,"CPF: ".$rep01CPF,0,0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(100,4,"CPF: ".$rep02CPF,0,0,'L');
	}

  $pdf->Ln();
  $pdf->Ln();


   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(180,5,utf8_decode('Autorizo a execução do serviço.'));

  $pdf->Ln();
  $pdf->Ln();




   $pdf->SetX($x,262);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,utf8_decode('Carla Mingolla'),'T',1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"Chefe de Gabinete",0,1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(100,4,"Secretaria Municipal de Cultura",0,0,'L');


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

	$pdf->SetFont('Arial','', 10);

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
		$pdf->Ln();
	}

//RODAPÉ PERSONALIZADO
	$pdf->SetXY($x,262);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(85,4,utf8_decode($rep01Nome),'T',0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(85,4,utf8_decode($rep02Nome),'T',0,'L');
	}

	$pdf->Ln();

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(85,4,"RG: ".$rep01RG,0,0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(85,4,"RG: ".$rep02RG,0,0,'L');
	}

	$pdf->Ln();

	$pdf->SetX($x);
	$pdf->SetFont('Arial','', 10);
	$pdf->Cell(1005,4,"CPF: ".$rep01CPF,0,0,'L');
	if ($rep02Nome != '')
	{
		$pdf->Cell(100,4,"CPF: ".$rep02CPF,0,0,'L');
	}

$pdf->Output();
?>
