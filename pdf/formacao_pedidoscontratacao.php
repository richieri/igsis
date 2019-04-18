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
    ->setCellValue('A1', 'Nome Completo' )
    ->setCellValue('B1', "Programa" )
    ->setCellValue("C1", "Função" )
    ->setCellValue("D1", "Linguagem" )
    ->setCellValue("E1", "E-mail")
    ->setCellValue("F1", "Telefone 1")
    ->setCellValue("G1", "Telefone 2")
    ->setCellValue("H1", "Telefone 3")
    ->setCellValue("I1", "Estado do Pedido");

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
$con = bancoMysqli();

$ano = $_GET['ano'];

$sql= "SELECT 
            pf.Nome,
            pro.Programa,
            cg.Cargo,
            l.Linguagem,
            pf.Email,
            pf.Telefone1,
            pf.Telefone2,
            pf.Telefone3,
            st.estado
        FROM igsis_pedido_contratacao AS ped
            INNER JOIN sis_formacao AS form ON form.idPedidoContratacao = ped.idPedidoContratacao
           INNER JOIN sis_pessoa_fisica AS pf ON ped.idPessoa = pf.Id_PessoaFisica
           INNER JOIN sis_formacao_programa AS pro ON pro.Id_Programa = form.IdPrograma
           INNER JOIN sis_formacao_cargo AS cg ON form.IdCargo = cg.Id_Cargo
           INNER JOIN sis_formacao_linguagem AS l ON form.IdLinguagem = l.Id_Linguagem
           LEFT JOIN sis_estado AS st ON st.idEstado = ped.estado
        WHERE tipoPessoa = '4'
            AND form.Ano = $ano
            AND ped.publicado = '1'";

$query = (mysqli_query($con,$sql));

$cont = 2;
while($pf = mysqli_fetch_array($query))
{
    $a = "A".$cont;
    $b = "B".$cont;
    $c = "C".$cont;
    $d = "D".$cont;
    $e = "E".$cont;
    $f = "F".$cont;
    $g = "G".$cont;
    $h = "H".$cont;
    $i = "I".$cont;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $pf['Nome'])
        ->setCellValue($b, $pf['Programa'])
        ->setCellValue($c, $pf['Cargo'])
        ->setCellValue($d, $pf['Linguagem'])
        ->setCellValue($e, $pf['Email'])
        ->setCellValue($f, $pf['Telefone1'])
        ->setCellValue($g, $pf['Telefone2'])
        ->setCellValue($h, $pf['Telefone3'])
        ->setCellValue($i, ($pf['estado'] == null) ? "Pedido Não Enviado" : $pf['estado']);
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

$nome_arquivo = "pedidos_contratação_formação_$ano.xls";


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
