<?php

// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// Instanciamos a classe
$objPHPExcel = new PHPExcel();

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Número IGSIS' )
            ->setCellValue('B1', "Nome do Evento " )
            ->setCellValue("C1", "Enviado por" )
            ->setCellValue("D1", "Data Início" );

// Podemos configurar diferentes larguras paras as colunas como padrão
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(90);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);


//Consulta
$con = bancoMysqli();
//$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '4' AND foto = 1 ORDER BY idCom DESC";
//$query_busca_dic = mysqli_query($con,$sql_busca_dic);
$i = 1;
$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '4' AND foto = 1 ORDER BY idCom DESC";
$query_busca_dic = mysqli_query($con,$sql_busca_dic);
while($evento = mysqli_fetch_array($query_busca_dic)){ // inicio do while
	$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
	$nome = recuperaUsuario($event['idUsuario']);
	$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);

$row = $i

$ig = $evento['ig_evento_idEvento'];
$nomeEvento = $evento['nomeEvento'];
$nomeCompleto = $nome['nomeCompleto'];
$data = retornaPeriodo($evento['ig_evento_idEvento']);
$i++;


}
/*
while($evento = mysqli_fetch_array($query_busca_dic))
{ 
	$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
	$nome = recuperaUsuario($event['idUsuario']);
	$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("B". $linha, $evento->ig_evento_idEvento)
        ->setCellValue("C". $linha, $evento->ig_evento_idEvento)
        ->setCellValue("D". $linha, $evento->ig_evento_idEvento);
    $linha++;
}
*/
// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
$objPHPExcel->getActiveSheet()->setTitle('Credenciamento para o Evento');

// Cabeçalho do arquivo para ele baixar
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="arquivo_de_exemplo01.xls"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necessário
header('Cache-Control: max-age=1');

// Acessamos o 'Writer' para poder salvar o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
$objWriter->save('php://output'); 

exit;

?>