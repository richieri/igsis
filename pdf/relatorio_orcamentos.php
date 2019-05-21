<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$soma = $_POST['soma'];
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
    ->setCellValue('A1', 'Evento')
    ->setCellValue('B1', "Espaço" )
    ->setCellValue("C1", "Data" )
    ->setCellValue("D1", "Valor do evento");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray
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
    $espaco = $linha['espaco'];
    $a = "A".$cont;
    $b = "B".$cont;
    $c = "C".$cont;
    $d = "D".$cont;
    $e = "E".$cont;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['nome'])
        ->setCellValue($b, $linha['espaco'])
        ->setCellValue($c, exibirDataBr($linha['data_pagamento']))
        ->setCellValue($d, dinheiroParaBr($linha['valor_evento']));

    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $e)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $e)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $e)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $cont++;

}
// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

for ($col = 'A'; $col !== 'E'; $col++){
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = "relatorio_".$espaco.".xls";


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
