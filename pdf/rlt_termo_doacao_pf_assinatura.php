<?php 
	session_start();
   
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

}


//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];

$ano=date('Y');
$dataAtual = date("d/m/Y");

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],1);

$setor = $pedido["Setor"];

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

$grupo = grupos($id_ped);
$integrantes = $grupo["texto"];

//PessoFisica

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
   
   $pdf->SetXY( $x , 20 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("PROCESSO SEI Nº ".$NumeroProcesso),0,1,'C');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("TERMO DE DOAÇÃO Nº________/".$ano),0,1,'C');
         
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("A PREFEITURA DO MUNICÍPIO DE SÃO PAULO, por intermédio da SECRETARIA MUNICIPAL DE CULTURA, neste ato representada  André Sturm, Secretário Municipal de Cultura, doravante denominada donatária e ".$Nome.", portador/a da cédula de identidade RG: ".$RG.", inscrito no CPF: ".$CPF.", residente na ".$Endereco.", denominado/a doador/a, com fundamento no artigo 1º do Decreto nº 40.384/2001, resolvem, firmar o presente termo de doação, mediante as seguintes cláusulas e condições:"));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("CLÁUSULA 1 - OBJETO"),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Doação de serviços artísticos para o evento ".$Objeto.", no ".$Local.", no período ".$Periodo.", conforme proposta e cronograma constantes no processo eletrônico."));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("CLÁUSULA 2 - OBRIGAÇÕES DO DOADOR"),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('O/a doador/a compromete-se a:'),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("2.1. Executar os serviços no período e horários constantes na proposta de doação, garantindo sua qualidade e adequação aos propósitos do evento."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("2.2. Fazer menção dos créditos da Prefeitura da Cidade de São Paulo, Secretaria Municipal de Cultura, Centro Cultural São Paulo, em toda divulgação, escrita ou falada, realizada sobre o evento programado."));

   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("CLÁUSULA 3 - DOS DIREITOS E ENCARGOS DA DONATÁRIA"),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(180,$l,utf8_decode('A donatária:'),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("3.1. Compete o fornecimento da sonorização necessária à realização de espetáculos e dos equipamentos de iluminação disponíveis no local do evento, assim como providências quanto à divulgação de praxe (confecção de cartaz a ser afixado no equipamento cultural e encaminhamento de release à mídia impressa e televisiva)."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("3.2. Exercer a coordenação e comunicações necessárias, bem como dirimir dúvidas, para o bom cumprimento das obrigações descritas neste termo."));
   
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("CLÁUSULA 4 - DISPOSIÇÕES GERAIS"),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.1. O/a doador/a, nos termos do artigo 8° do Decreto Municipal n° 40.384/01, declara, sob as penas da lei, que não está em débito com a Fazenda Municipal."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.2. A presente doação não acarretará ônus para a Municipalidade."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.3. A donatária fica autorizada a reproduzir, por processo fotográfico ou digital, e a utilizar, sem qualquer ônus, as imagens do evento realizado em anúncio, catálogo, exposição, folder e outras publicações, sem fins lucrativos, nos eventos promovidos e/ou produzidos pela Prefeitura do Município de São Paulo. Essa autorização terá validade a partir da presente assinatura e vigorará pelo prazo previsto no artigo 41 da Lei Federal nº 9.610/98."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("Nos termos do art. 6 do Decreto nº. 54.873/2014, fica designado como fiscal do contrato ".$Fiscal.", RF ".$rfFiscal." e como suplente ".$Suplente.", RF ".$rfSuplente."."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("4.5. Fica eleito o foro da Comarca da Capital, através de uma de suas varas da Fazenda Pública, para qualquer procedimento judicial oriundo do presente Termo, com a renúncia de qualquer outro, por mais especial ou privilegiado que seja."));
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->MultiCell(180,$l,utf8_decode("E por estarem justas e pactuadas firmam as Partes o presente Termo, em 4 (quatro) vias de igual teor, forma e data para um só efeito na presença das testemunhas abaixo."));
   
   $pdf->Ln();
   $pdf->Ln();
    
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(180,$l,utf8_decode("São Paulo, _________ de ________________________________ de "."$ano"."."),0,0,'C');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("DONATÁRIA:"),0,1,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(90,$l,utf8_decode('André Luiz Pompeia Sturm'),'T',1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode('Secretário Municipal de Cultura'),0,0,'L');

   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("DOADOR:"),0,1,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(90,$l,utf8_decode($Nome),'T',1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($RG),0,1,'L');
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 10);
   $pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(45,$l,utf8_decode($CPF),0,1,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   
   
   $pdf->SetX($x);
   $pdf->SetFont('Arial','B', 12);
   $pdf->Cell(180,5,utf8_decode("TESTEMUNHAS:"),0,1,'L');
   
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln();   

   $pdf->SetX($x);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(80,$l,utf8_decode(''),'T',0,'L');
   $pdf->Cell(20,$l,utf8_decode(''),'',0,'L');
   $pdf->Cell(80,$l,utf8_decode(''),'T',0,'R');


$pdf->Output();
?>

