<?php
include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$sql = $_POST['sql'];
$query = mysqli_query($con, $sql);

// Instanciamos a classe
$objPHPExcel = new PHPExcel();


// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
$objPHPExcel->getProperties()->setCreator("Sistema IGSIS");
$objPHPExcel->getProperties()->setLastModifiedBy("Sistema IGSIS");
$objPHPExcel->getProperties()->setTitle("Relatório de Controle de Fotos");
$objPHPExcel->getProperties()->setSubject("Relatório de Controle de Fotos");
$objPHPExcel->getProperties()->setDescription("Gerado automaticamente a partir do Sistema IGSIS");
$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
$objPHPExcel->getProperties()->setCategory("Inscritos");

// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Instituição' )
    ->setCellValue('B1', "Equipamento / Local" )
    ->setCellValue("C1", "Endereço" )
    ->setCellValue("D1", "Telefone" )
    ->setCellValue("E1", "Nome do Evento")
    ->setCellValue("F1", "Projeto Especial")
    ->setCellValue("G1", "Artista")
    ->setCellValue("H1", "Data")
    ->setCellValue("I1", "Hora")
    ->setCellValue("J1", "Duração")
    ->setCellValue("K1", "Nº de Apresentações")
    ->setCellValue("L1", "Linguagem")
    ->setCellValue("M1", "Valor")
    ->setCellValue("N1", "Classificação Indicativa")
    ->setCellValue("O1", "Links de Divulgação")
    ->setCellValue("P1", "Sinopse")
    ->setCellValue("Q1", "Produtor do Evento")
    ->setCellValue("R1", "Email")
    ->setCellValue("S1", "Telefone")
    ->setCellValue("T1", "Inserido por (usuário)");


// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray
(
    array
    (
        'fill' => array
        (
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E0EEEE')
        ),
    )
);

$cont = 2;
while($linha = mysqli_fetch_array($query))
{
    $sqlConsultaOcorrencias = "SELECT idEvento FROM ig_ocorrencia WHERE idEvento = '" . $linha['idEvento'] . "'";
    $apresentacoes = $con->query($sqlConsultaOcorrencias)->num_rows;


    $a = "A".$cont;
    $b = "B".$cont;
    $c = "C".$cont;
    $d = "D".$cont;
    $e = "E".$cont;
    $f = "F".$cont;
    $g = "G".$cont;
    $h = "H".$cont;
    $i = "I".$cont;
    $j = "J".$cont;
    $k = "K".$cont;
    $l = "L".$cont;
    $m = "M".$cont;
    $n = "N".$cont;
    $o = "O".$cont;
    $p = "P".$cont;
    $q = "Q".$cont;
    $r = "R".$cont;
    $s = "S".$cont;
    $t = "T".$cont;


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['instituicao'])
        ->setCellValue($b, $linha['equipamento'] . " - " . $linha['nome_local'])
        ->setCellValue($c, $linha['endereco'])
        ->setCellValue($d, $linha['telefone'])
        ->setCellValue($e, $linha['nome'])
        ->setCellValue($f, $linha['projetoEspecial'])
        ->setCellValue($g, $linha['artista'])
        ->setCellValue($h, $linha['data'])
        ->setCellValue($i, $linha['horario_inicial'])
        ->setCellValue($j, $linha['duracao'] . " minutos")
        ->setCellValue($k, $apresentacoes)
        ->setCellValue($l, $linha['categoria'])
        ->setCellValue($m, $linha['valor'])
        ->setCellValue($n, $linha['classificacao'])
        ->setCellValue($o, $linha['divulgacao'])
        ->setCellValue($p, $linha['sinopse'])
        ->setCellValue($q, $linha['produtor_nome'])
        ->setCellValue($r, $linha['produtor_email'])
        ->setCellValue($s, $linha['produtor_fone'])
        ->setCellValue($t, $linha['nomeCompleto']);

    $cont++;
}

// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col)
{
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}


$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = "eventos_pesquisa.xls";


// Cabeçalho do arquivo para ele baixar(Excel2007)
header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nome_arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necessário
header('Cache-Control: max-age=1');

// Acessamos o 'Writer' para poder salvar o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
$objWriter->save('php://output');

exit;
