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
$objPHPExcel->getProperties()->setTitle("Relatório Busca por Período");
$objPHPExcel->getProperties()->setSubject("Relatório Busca por Período");
$objPHPExcel->getProperties()->setDescription("Gerado automaticamente a partir do Sistema IGSIS");
$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
$objPHPExcel->getProperties()->setCategory("Período");


// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);


// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Codigo do Pedido" )
            ->setCellValue('B1', "Proponente" )
            ->setCellValue("C1", "Tipo" )
            ->setCellValue("D1", "Objeto" );
			->setCellValue("E1", "Local" );
			->setCellValue("F1", "Instituição" );
			->setCellValue("G1", "Periodo" );
			->setCellValue("H1", "Status" );
			->setCellValue("I1", "Operador" );
		
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

$sql_busca = "SELECT DISTINCT idEvento FROM igsis_agenda WHERE data BETWEEN '$inicio' AND '$final'  ORDER BY data ASC";
$query_busca = mysqli_query($con,$sql_busca);
$i = 2;
while($evento = mysqli_fetch_array($query_busca))
{ 
	$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
	$status = recuperaDados("sis_estado",$x[$h]['status'],"idEstado");
	$usuario = recuperaDados("ig_usuario",$event['idUsuario'],"idUsuario");
	$instituicao = recuperaDados("ig_instituicao",$event['idInstituicao'],"idInstituicao");
	$local = listaLocais($pedido['idEvento']);
	$periodo = retornaPeriodo($pedido['idEvento']);
	$operador = recuperaUsuario($pedido['idContratos']);
	$a = "A".$i;
	$b = "B".$i;
	$c = "C".$i;
	$d = "D".$i;
	$e = "E".$i;
	$f = "F".$i;
	$g = "G".$i;
	$h = "H".$i;
	$i = "I".$i;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($a, $evento['ig_evento_idEvento'])
				->setCellValue($b, $evento['nomeEvento'])
				->setCellValue($c, $nome['nomeCompleto'])
				->setCellValue($d, retornaPeriodo($evento['ig_evento_idEvento']));
	$i++;
}

// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('IGSIS');

foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) 
{
    $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
} 


$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
    ob_start();
	
$nome_arquivo = date("Y-m-d")."_igsis_relatorio.xls";



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

?>