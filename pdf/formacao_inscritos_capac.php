<?php

// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


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
    ->setCellValue('A1', 'Nome' )
    ->setCellValue('B1', "RG" )
    ->setCellValue("C1", "CPF" )
    ->setCellValue("D1", "CCM" )
    ->setCellValue("E1", "Data de Nascimento")
    ->setCellValue("F1", "Local de Nascimento")
    ->setCellValue("G1", "Programa")
    ->setCellValue("H1", "Linguagem")
    ->setCellValue("I1", "Função");

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


//Consulta
$con = bancoMysqliProponente();

$ano = $_POST['ano'];

$sql= "SELECT pf.id, pf.nome, pf.rg, pf.ccm, pf.cpf, pf.localNascimento, pf.dataNascimento, tf.descricao, fl.linguagem, ff.funcao, pf.formacao_ano
        FROM pessoa_fisica AS pf
        INNER JOIN tipo_formacao AS tf ON pf.tipo_formacao_id =  tf.id
        INNER JOIN formacao_linguagem AS fl ON pf.formacao_linguagem_id = fl.id
        INNER JOIN formacao_funcoes AS ff ON pf.formacao_funcao_id = ff.id
        WHERE pf.tipo_formacao_id > 0 AND pf.formacao_ano = '$ano'";

$query = (mysqli_query($con,$sql));

$i = 2;
while($pf = mysqli_fetch_array($query))
{
    $a = "A".$i;
    $b = "B".$i;
    $c = "C".$i;
    $d = "D".$i;
    $e = "E".$i;
    $f = "F".$i;
    $g = "G".$i;
    $h = "H".$i;
    $I = "I".$i;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $pf['nome'])
        ->setCellValue($b, $pf['rg'])
        ->setCellValue($c, $pf['cpf'])
        ->setCellValue($d, $pf['ccm'])
        ->setCellValue($e, exibirDataBr($pf['dataNascimento']))
        ->setCellValue($f, $pf['localNascimento'])
        ->setCellValue($g, $pf['descricao'])
        ->setCellValue($h, $pf['linguagem'])
        ->setCellValue($I, $pf['funcao']);
    $i++;
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

$nome_arquivo = date("Y-m-d")."_capac_inscritos.xls";


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
