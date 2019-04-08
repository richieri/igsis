<?php
session_start();

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


class PDF extends FPDF
{
// Page header
    function Header()
    {
        // Move to the right
        $this->Image('../visual/img/logo_smc.jpg',30,10);
        $inst = recuperaDados("ig_instituicao",$_SESSION['idInstituicao'],"idInstituicao");	$logo = "img/".$inst['logo']; // Logo
        $this->Image($logo,130,20,50);
        // Line break
        $this->Ln(20);
    }

}


//CONSULTA  (copia inteira em todos os docs)
$idPf=$_GET['idPf'];

$ano=date('Y');
$dataAtual = date("d/m/Y");

$sqlFoto = "SELECT arquivo FROM igsis_arquivos_pessoa WHERE idTipoPessoa = '1' AND idPessoa = '".$idPf."' AND tipo = '29' AND publicado = '1'";
$foto = $con->query($sqlFoto)->fetch_assoc()['arquivo'];

$pessoa = siscontratDocs($idPf,1);
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

$sqlFormacao = "SELECT * FROM sis_formacao WHERE IdPessoaFisica = $idPf AND publicado = '1' ORDER BY Ano DESC LIMIT 0,1";
$formacao = $con->query($sqlFormacao)->fetch_assoc();

$equipamento1 = recuperaDados('ig_local', $formacao['IdEquipamento01'], 'idLocal')['sala'];
$equipamento2 = recuperaDados('ig_local', $formacao['IdEquipamento02'], 'idLocal')['sala'];
$cargo = recuperaDados('sis_formacao_cargo', $formacao['IdCargo'], 'Id_Cargo')['Cargo'];
$vigencia = recuperaDados('sis_formacao_vigencia', $formacao['IdVigencia'], 'Id_Vigencia')['descricao'];
$valor = recuperaDados('igsis_pedido_contratacao', $formacao['idPedidoContratacao'], 'idPedidoContratacao')['valor'];
$miniCurriculo = recuperaDados('sis_pessoa_fisica_formacao', $formacao['IdPessoaFisica'], 'IdPessoaFisica')['Curriculo'];
$numProcesso = recuperaDados('igsis_pedido_contratacao', $formacao['idPedidoContratacao'], 'idPedidoContratacao')['NumeroProcesso'];
$status = ($formacao['Status'] == 1) ? "Ativo" : "Inativo";

if ($foto == null) {
    $fotoImg = "../visual/images/avatar_default.png";
} else {
    $fotoImg = "../uploadsdocs/$foto";
}

$teste = "Texto de teste";
$ValorPorExtenso = valorPorExtenso($valor);

$pfDet = recuperaDados("sis_pessoa_fisica_formacao",$idPf,"IdPessoaFisica");
$curriculo = $pfDet['Curriculo'];

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=8; //DEFINE A ALTURA DA LINHA

$pdf->SetXY( $x , 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(180,15,utf8_decode("REGISTRO DE PESSOA FÍSICA"),0,1,'C');

$pdf->Ln(5);

$pdf->Image($fotoImg,160,56,-200);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,'Nome:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(168,$l,utf8_decode($Nome));

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
$pdf->Cell(10,$l,utf8_decode('CCM:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(45,$l,utf8_decode($CCM),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(36,$l,utf8_decode('Data de Nascimento:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
if($DataNascimento == "31/12/1969"){
    $pdf->Cell(25,$l, " " ,0,1,'L');
}else {
    $pdf->Cell(25,$l,utf8_decode($DataNascimento),0,1,'L');
}

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

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(27,$l,utf8_decode('Equipamento 1:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(153,$l,utf8_decode($equipamento1),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(27,$l,utf8_decode('Equipamento 2:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(153,$l,utf8_decode($equipamento2),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(13,$l,utf8_decode('Cargo:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(165,$l,utf8_decode($cargo),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(17,$l,utf8_decode('Vigência:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(165,$l,utf8_decode($vigencia),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,'Valor:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(168,$l,utf8_decode("R$ ".dinheiroParaBr($valor)." "."($ValorPorExtenso )"));

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(27,$l,utf8_decode('Mini currículo:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(153,$l,utf8_decode($miniCurriculo));

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(21,$l,utf8_decode('Chamados:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(20,$l,utf8_decode($formacao['Chamados']),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(14,$l,utf8_decode('Status:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(40,$l,utf8_decode($status),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(20,$l,utf8_decode('Pontuação:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode($formacao['Pontuacao']),0,1,'L');

$pdf->Output();
?>