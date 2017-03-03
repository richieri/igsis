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

// Simple table
function Cabecalho($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data

}

// Simple table
function Tabela($header, $data)
{
    //Data
    foreach($data as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data

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

$ano=date('Y');
$dataAtual = date("d/m/Y");

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
$rfFiscal = $pedido["RfFiscal"];
$Suplente = $pedido["Suplente"];
$rfSuplente = $pedido["RfSuplente"];
$NumeroProcesso = $pedido["NumeroProcesso"];
$notaempenho = $pedido["NotaEmpenho"];
$data_entrega_empenho = exibirDataBr($pedido['EntregaNE']);
$data_emissao_empenho = exibirDataBr($pedido['EmissaoNE']);
$ingresso = dinheiroParaBr($pedido['ingresso']);
$ingressoExtenso = valorPorExtenso($pedido['ingresso']);

$grupo = grupos($id_ped);
$integrantes = $grupo["texto"];

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

	//Executante
   
   $pdf->SetXY( $x , 20 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÃGINA
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,$l,utf8_decode('PREFEITURA DO MUNICÃPIO DE SÃO PAULO'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,$l,utf8_decode('SECRETARIA MUNICIPAL DE CULTURA'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,$l,utf8_decode('PROCESSO SEI Nº ' .$NumeroProcesso),0,1,'C');
     
   $pdf->Ln();

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,$l,utf8_decode('Ordem de execuÃ§Ã£o de serviço nº ___/2017'),0,1,'C');
   
   $pdf->Ln();
      
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(23,$l,utf8_decode('Emanada de:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(170,$l,utf8_decode('DivisiÃ£o de Administração'),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(27,$l,utf8_decode('Suporte Legal:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(153,$l,utf8_decode('Artigo 25, inciso III, da Lei Federal nº 8.666/93 e alterações posteriores e artigo 1º da Lei Municipal nº 13.278/02, nos termos dos artigos 16 e 17 do Decreto nº 44.279/03.'));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Prestador e/ou executor do serviço'),0,1,'C');
      
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(13,$l,'Nome:',0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(155,$l,utf8_decode($Nome));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(8,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(70,$l,utf8_decode($CPF),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(65,$l,utf8_decode($RG),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(20,$l,utf8_decode('Endereço:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(160,$l,utf8_decode($Endereco));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(26,$l,utf8_decode('Representante:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(160,$l,utf8_decode($Nome));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(23,$l,utf8_decode('Estado Civil:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(65,$l,utf8_decode($EstadoCivil),0,0,'L');
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('Nacionalidade:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(65,$l,utf8_decode($Nacionalidade),0,1,'L');
   
  
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Serviço'),0,1,'C');
     
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Especificações: Contratação dos serviços profissionais de natureza artística de ".$Objeto.", através dos integrantes mencionados na Declaração de Exclusividade, por ".$Nome.", CPF: ".$CPF.", para realização de evento no "."$Local".", no período "."$Periodo"." conforme proposta e cronograma."));		
   
   	$pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Fica designado como fiscal do contrato ".$Fiscal.", RF ".$rfFiscal." e como suplente ".$Suplente.", RF ".$rfSuplente."."));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Valor do Ingresso: ".$ingresso." ( ".$ingressoExtenso." )."));    
   
   $pdf->Ln();
     
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Pagamento'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode($FormaPagamento));
   
//	QUEBRA DE PÃGINA
$pdf->AddPage('','');
$pdf->SetXY( $x , 40 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÃGINA  
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Penalidades'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("- Multa de 10% (dez por cento) sobre o valor do contrato ou sobre o valor integral da venda de todos os ingressos disponíveis por atraso de até 30 (trinta) minutos no evento. Ultrapassado esse tempo, e independentemente da aplicação de penalidade, fica a critéio do equipamento da Secretaria Municipal de Cultura autorizar a realização do evento, visando evitar prejuízos à grade de programação. NÃO sendo autorizada a realização do evento, será considerada inexecução total do contrato, com aplicação de multa prevista por inexecução total.
   
- Multa de 10% (dez por cento) para casos de infração de cláusula contratual e/ou inexecução parcial do ajuste e de 30% (trinta por cento) para casos de inexecução total do ajuste. O valor da multa será calculado sobre o valor do contrato ou sobre o valor integral da venda de todos os ingressos disponíveis.
   
- Multa de 10% (dez por cento) sobre o valor do contrato ou sobre o valor integral da venda de todos os ingressos disponíveis, em função da falta de regularidade fiscal do contratado, bem como, pela verificação de que possui pendências junto ao Cadastro Informativo Municipal (CADIN).
   
- As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria."));

   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Cancelamento'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Esta O.E.S. poderá ser cancelada no interesse da administração, devidamente justificada ou em virtude da inexecução total ou parcial do serviço sem prejuízo de multa."));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Foro'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Fica eleito o foro desta comarca para todo e qualquer procedimento judicial oriundo desta ordem de execução de serviços."));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('Observações'),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode(" - Compete à contratada a realização do espetáculo, e a fazer constar o crédito à “ PMSP/SECRETARIA MUNICIPAL DE CULTURA, em toda divulgação escrita ou falada, realizada sobe o espetáculo programado.

- A empresa contratada fica sujeita ao atendimento no disposto nas Leis Municipais nº 10.973/9, regulamentada pelo DM 30.730/91; 11.113/91; 11.357/93; 12.975/2000 e portaria 66/SMC/2007; Leis Estaduais nº 7.844/92; Medida Provisória Federal 12.933/2013 e Lei Federal 10.741/2013.

- A contratada é responsável por qualquer prejuízo ou dano causado ao patrimônio municipal ou a bens de terceiros que estejam sob a guarda do equipamento local de realização do evento.

- Quaisquer outras despesas não ressalvadas aqui serão de responsabilidade da contratada, que se compromete a adotar as providências necessárias junto á OMB.

- As providências administrativas para liberação da autorização do ECAD serão de responsabilidade da contratada, sendo que eventuais pagamento serão efetuados pela SMC.

- A Municipalidade não é responsável por qualquer material ou equipamento que não lhe pertença utilizado no espetáculo, devendo esse material ser retirado no seu término.

- A Cia. deverá designar uma pessoa para atuar na Bilheteria durante toda a temporada, cabendo a esta a responsabilidade exclusiva pela venda dos ingressos.

- A Bilheteria deverá abrir 1 (uma) hora antes do início de cada espetáculo.

- Após o término de cada espetáculo um servidor designado pela Coordenação do Teatro efetuará o fechamento do borderô com o bilheteiro responsável.

- Caberá a Cia. efetuar o repasse do percentual do FEPAC e a Coordenação do Teatro caberá o recolhimento do valor.

- Em havendo contratação pela Secretaria de Cultura de empresa prestadora de serviços de gerenciamento da bilheteria, caberá a esta efetuar a venda dos ingressos.

- Compete, ainda, à  Municipalidade, o fornecimento da sonorização necessária à realização de espetáculos e dos equipamentos de iluminação disponíveis no local do evento, assim como providências quanto à divulgação de praxe (confecção de cartaz a ser afixado no equipamento cultural e encaminhamento de release à  mídia impressa e televisiva).

- A Coordenadoria dos Centros Culturais reserva-se o direito de disponibilizar 6 (seis) ingressos por apresentação, que não poderão ser comercializados pela Cia. Sendo que haverá comunicado com antecedência quando da utilização desses ingressos. Caso não haja manifestação por parte da SMC a comercialização desses ingressos será livre.

- A/o contratada/o se compromete a realizar o espetáculo para um número mínimo de 10 (dez) pagantes.

Aceito as condições dessa O.E.S para todos os efeitos de direito."));
   
   $pdf->Ln();
     
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(25,$l,utf8_decode('Local e data:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(160,$l,utf8_decode('São Paulo, '.$dataAtual),0,1,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(120,$l,utf8_decode($Nome),'T',1,'L');
   $pdf->SetX($x);
   $pdf->Cell(120,$l,utf8_decode('CPF '.$CPF),0,0,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Determino a execução do serviço na forma desta O.E.S."));
   
    $pdf->Ln();
	  
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(25,$l,utf8_decode('Local e data:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(160,$l,utf8_decode('SÃ£o Paulo, '.$dataAtual),0,1,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(120,$l,utf8_decode('Giovanna M. R. Lima'),'T',1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(120,$l,utf8_decode('Chefe de Gabinete'),'',1,'L');

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(120,$l,utf8_decode('Secretaria Municipal de Cultura'),'',1,'L');
    
   
   $pdf->Ln();
    

  


   

//for($i=1;$i<=20;$i++)
   // $pdf->Cell(0,10,'Printing line number '.$i,0,1);
$pdf->Output();


?>