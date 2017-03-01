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
$objPHPExcel->getProperties()->setCategory("Foto");


// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);


// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Número do Evento' )
            ->setCellValue('B1', "Nome do Evento " )
            ->setCellValue("C1", "Enviado por" )
            ->setCellValue("D1", "Período" );
		
		
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


//Consulta
$con = bancoMysqli();
//Foto Aprovada
$sql_busca = "SELECT DISTINCT idCom, com.idInstituicao,com.ig_evento_idEvento, com.nomeEvento  FROM ig_comunicacao AS com 
INNER JOIN igsis_agenda AS age ON age.idEvento=com.ig_evento_idEvento
WHERE com.idInstituicao = 5 AND com.foto = 1 AND  MONTH(age.data) = MONTH(NOW()) ORDER BY age.data DESC";
$query_busca_dic = mysqli_query($con,$sql_busca);
$i = 2;
while($evento = mysqli_fetch_array($query_busca_dic))
{ 
	$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
	$nome = recuperaUsuario($event['idUsuario']);
	$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);
	$a = "A".$i;
	$b = "B".$i;
	$c = "C".$i;
	$d = "D".$i;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($a, $evento['ig_evento_idEvento'])
				->setCellValue($b, $evento['nomeEvento'])
				->setCellValue($c, $nome['nomeCompleto'])
				->setCellValue($d, retornaPeriodo($evento['ig_evento_idEvento']));
	$i++;
}

// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Aprovadas');

foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) 
{
    $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
} 

/*****************************************************************************/
// Cria uma planilha nova	
$objPHPExcel->createSheet();

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);

// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'Número do Evento' )
            ->setCellValue('B1', "Nome do Evento " )
            ->setCellValue("C1", "Enviado por" )
            ->setCellValue("D1", "Período" );
		
		
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

//Foto Não Aprovada
/*
$sql_busca = "SELECT DISTINCT idCom, com.idInstituicao,com.ig_evento_idEvento, com.nomeEvento  FROM ig_comunicacao AS com 
INNER JOIN igsis_agenda AS age ON age.idEvento=com.ig_evento_idEvento
WHERE com.idInstituicao = 5 AND com.foto != 1 AND age.data > '2017-01-01' ORDER BY age.data DESC;";
*/
$sql_busca = "SELECT DISTINCT idCom, com.idInstituicao,com.ig_evento_idEvento, com.nomeEvento  FROM ig_comunicacao AS com 
INNER JOIN igsis_agenda AS age ON age.idEvento=com.ig_evento_idEvento
WHERE com.idInstituicao = 5 AND com.foto != 1 AND  MONTH(age.data) = MONTH(NOW()) ORDER BY age.data DESC";
 
$query_busca_dic = mysqli_query($con,$sql_busca);
$i = 2;
while($evento = mysqli_fetch_array($query_busca_dic))
{ 
	$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
	$nome = recuperaUsuario($event['idUsuario']);
	$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);
	$a = "A".$i;
	$b = "B".$i;
	$c = "C".$i;
	$d = "D".$i;
	$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue($a, $evento['ig_evento_idEvento'])
				->setCellValue($b, $evento['nomeEvento'])
				->setCellValue($c, $nome['nomeCompleto'])
				->setCellValue($d, retornaPeriodo($evento['ig_evento_idEvento']));
	$i++;
}

// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Não Aprovadas');

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