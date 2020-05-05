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
   $pdf->MultiCell(165,$l,utf8_decode($Objeto));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Data / Período:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(153,$l,utf8_decode($Periodo));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(82,$l,utf8_decode('Tempo Aproximado de Duração do Espetáculo:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(40,$l,utf8_decode("$Duracao"."utos"),0,0,'L');
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
$x=15;
$pdf->SetXY( $x , 30 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,$l,'(D)',0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(160,$l,utf8_decode('OBSERVAÇÕES'),0,1,'C');

   $pdf->SetX($x);
   $pdf->PrintChapter('txt/proposta_observacao_padrao.txt');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(160,$l,utf8_decode('DECLARAÇÕES'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(0,4,utf8_decode($txtPenalidade),0,'J');

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 9);
   $pdf->Cell(160,5,utf8_decode('NOS CASOS DE REVERSÃO DE BILHETERIA'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('1) No caso de pagamento do cachê por reversão de bilheteria, fica o valor dos ingressos sujeito ao atendimento no disposto nas Leis Municipais nº 10.973/91, regulamentada pelo Decreto Municipal nº 30.730/91; Leis Municipais 11.113/91; 11.357/93 e 12.975/2000 e Portaria nº 66/SMC/2007; Lei Estadual nº 7844/92, regulamentada pelo Decreto Estadual nº 35.606/92; Lei Estadual nº 10.858/2001, com as alterações da Lei Estadual 14.729/2012 e Lei Federal nº 12.933/2013.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('2) O pagamento do cachê corresponderá à reversão integral da renda obtida na bilheteria a/o ontratada/o, deduzidos os impostos e taxas pertinentes.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('3) Os ingressos poderão ser vendidos com preços reduzidos, em face de promoções realizadas pela produção do evento.'));

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 9);
   $pdf->Cell(160,5,utf8_decode('NOS CASOS DE CONTRATAÇÕES COM APRESENTAÇÕES EM MODO VIRTUAL (ONLINE)'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('Declaro Que:'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('1) Sou responsável por todas as informações contidas no projeto, incluindo conteúdo e direitos autorais relacionados a atividade proposta.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('2) Estou ciente e tenho condições de executar a atividade no formato online, em redes sociais, bem como enviar o vídeo da atividade desenvolvida para a Secretaria Municipal de Cultura.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('3) Tenho ciência de que a habilitação da atividade não gera automaticamente direito às contratações e que, mesmo habilitado e selecionado para contratação, a Secretaria Municipal de Cultura não tem obrigatoriedade de efetivar a contratação.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('4) Me responsabilizo pelo cumprimento da agenda acordada, no tocante ao local, data e horário, para a realização da atividade.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('5) Estou ciente de que a contratação não gera vínculo trabalhista entre a municipalidade e o contratado.'));

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('6) A apresentação contratada não oferecerá risco à minha saúde e à de terceiros, pois estou ciente que fica vedada qualquer forma de aglomeração ou encontro entre artistas e técnicos que residam em diferentes endereços.'));

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 9);
   $pdf->Cell(160,5,utf8_decode('RESCISÃO'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('Este instrumento poderá ser rescindido, no interesse da administração, devidamente justificado ou em virtude da inexecução total ou parcial do serviço sem prejuízo de multa, nos termos da legislação vigente.'));

   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(10,$l,'',0,0,'L');
   $pdf->SetFont('Arial','B', 9);
   $pdf->Cell(160,5,utf8_decode('FORO'),0,1,'C');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 9);
   $pdf->MultiCell(185,5,utf8_decode('Fica eleito o foro da Fazenda Pública para todo e qualquer procedimento judicial oriundo deste instrumento.'));

   $pdf->Ln();
   $pdf->Ln();


   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(185,$l,"Data: _________ / _________ / "."$ano".".",0,0,'L');

   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();


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
   $pdf->SetFont('Arial','', 12);
   $pdf->MultiCell(170,$l,utf8_decode($Objeto));

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
