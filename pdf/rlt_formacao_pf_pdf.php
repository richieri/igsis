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

$pf = recuperaDados('sis_pessoa_fisica', $idPf, 'Id_PessoaFisica');

$estadoCivil = recuperaDados('sis_estado_civil', $pf['IdEstadoCivil'], 'Id_EstadoCivil')['EstadoCivil'];

$sqlFoto = "SELECT arquivo FROM igsis_arquivos_pessoa WHERE idTipoPessoa = '1' AND idPessoa = '".$pf['Id_PessoaFisica']."' AND tipo = '29' AND publicado = '1'";
$foto = $con->query($sqlFoto)->fetch_assoc()['arquivo'];

$formacao = recuperaDados("sis_pessoa_fisica_formacao",$pf['Id_PessoaFisica'],"IdPessoaFisica");

$dadosPf = [
    'Nome' => $pf['Nome'],
    'Nome Artístico' => $pf['NomeArtistico'],
    'RG' => $pf['RG'],
    'CPF' => $pf['CPF'],
    'CCM' => $pf['CCM'],
    'Data de Nascimento' => exibirDataBr($pf['DataNascimento']),
    'Endereço' => "Rua, ".$pf['Numero']."",
    'Bairro' => '',
    'CEP' => $pf['CEP'],
    'Cidade / Estado' => '',
    'Email' => $pf['Email'],
    'Telefone #1' => $pf['Telefone1'],
    'Telefone #2' => $pf['Telefone2'],
    'Estado Civil' => $estadoCivil,
    'Nacionalidade' => $pf['Nacionalidade'],
    'PIS/PASEP/NIT' => $pf['Pis']
];

if ($foto == null) {
    $fotoImg = "./images/avatar_default.png";
} else {
    $fotoImg = "../uploadsdocs/$foto";
}

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=6; //DEFINE A ALTURA DA LINHA

$pdf->SetXY( $x , 50);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(180,15,utf8_decode("REGISTRO DE PESSOA FÍSICA"),0,1,'C');

foreach ($dadosPf as $titulo => $dado) {
    $pdf->SetX(40);
    $pdf->SetFont('Arial','B', 10);
    $pdf->Cell(180,$l,utf8_decode($titulo).": ",0,1,'L');

    $pdf->SetX(60);
    $pdf->SetFont('Arial','', 10);
    $pdf->Cell(180,$l,utf8_decode($dado),0,1,'L');
}

$pdf->Output();
?>