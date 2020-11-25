<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../include/phpexcel/Classes/PHPExcel.php");

$con = bancoMysqli();

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array(' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
$objPHPExcel->getProperties()->setCreator("Sistema IGSIS");
$objPHPExcel->getProperties()->setLastModifiedBy("Sistema IGSIS");
$objPHPExcel->getProperties()->setTitle("Relatório de Eventos");
$objPHPExcel->getProperties()->setSubject("Relatório de Eventos");
$objPHPExcel->getProperties()->setDescription("Gerado automaticamente a partir do Sistema IGSIS");
$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
$objPHPExcel->getProperties()->setCategory("Relatório de Eventos");

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', "Proponente")
    ->setCellValue('B1', "Documento")
    ->setCellValue('C1', "Número de Processo")
    ->setCellValue('D1', "Nome do Evento")
    ->setCellValue('E1', "Intefrantes")
    ->setCellValue('F1', "Valor Total")
    ->setCellValue('G1', "Data de Inicio")
    ->setCellValue('H1', "Data de Encerramento (Caso temporada)")
    ->setCellValue('I1', "Qtde. Apresentações")
    ->setCellValue('J1', "Local")
    ->setCellValue('K1', "Status de Contratação")
    ->setCellValue('L1', "Local do Solicitante");


//Colorir a primeira fila
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getFill()->getStartColor()->setARGB('#29bb04');
// Add some data
$objPHPExcel->getActiveSheet()->getStyle("A1:AD1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);

// Dados do evento
$sql = "SELECT 	ev.idEvento, ev.nomeEvento, pe.integrantes, pe.valor, pe.NumeroProcesso, 
                pe.tipoPessoa, pe.idPessoa, ev.idResponsavel, es.estado
        FROM ig_evento AS ev
        LEFT JOIN igsis_pedido_contratacao  AS pe ON ev.idEvento = pe.idEvento
        LEFT JOIN sis_estado AS es ON pe.estado = es.idEstado
        WHERE 
           ev.publicado = 1 AND nomeEvento NOT LIKE '%TESTE%' AND nomeEvento != '' AND nomeEvento NOT LIKE '%[CANCELADO]%'
           AND pe.NumeroProcesso != 'NULL' AND pe.NumeroProcesso != '' AND pe.estado != 'NULL'";
$query = mysqli_query($con, $sql);

$i = 2;

while ($row = mysqli_fetch_array($query)) {
    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . '')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $i . '')->getNumberFormat()->setFormatCode("#,##0.00");
    $objPHPExcel->getActiveSheet()->getStyle('F' . $i . '')->getNumberFormat()->setFormatCode("#,##0.00");

    $sqlOcorrencia = "SELECT MIN(dataInicio) AS 'dataInicio', MAX(dataFinal) AS 'dataFim', COUNT(idOcorrencia) AS 'qtd'
                        FROM ig_ocorrencia AS oco
                        WHERE oco.publicado = 1 AND oco.idEvento = " . $row['idEvento'];

    $queryOcorrencia = mysqli_query($con, $sqlOcorrencia);
    $ocorrencia = mysqli_fetch_array($queryOcorrencia);

    if ($row['tipoPessoa'] == 2) {
        $sqlPessoa = "SELECT RazaoSocial, CNPJ
                        FROM sis_pessoa_juridica
                        WHERE Id_PessoaJuridica = " . $row['idPessoa'];
        $queryPJ = mysqli_query($con, $sqlPessoa);
        $PJ = mysqli_fetch_array($queryPJ);

        $proponente = $PJ['RazaoSocial'];
        $documento = $PJ['CNPJ'];
    } else {
        $sqlPessoa = "SELECT Nome, RG
                        FROM sis_pessoa_fisica
                        WHERE  Id_PessoaFisica = " . $row['idPessoa'];
        $queryPF = mysqli_query($con, $sqlPessoa);
        $PF = mysqli_fetch_array($queryPF);

        $proponente = $PF['Nome'];
        $documento = $PF['RG'];
    }

    $sqlResponsavel = "SELECT sala
                        FROM ig_local AS l 
                        LEFT JOIN ig_usuario AS us ON l.idLocal = us.local
                        WHERE idUsuario = " . $row['idResponsavel'];
    $queryResponsavel = mysqli_query($con, $sqlResponsavel);
    $responsavel = mysqli_fetch_array($queryResponsavel);

    $sqlLocais = "SELECT CONCAT(ins.instituicao, ' (',ins.sigla,')') AS 'local', l.sala
                    FROM ig_local AS l
                    LEFT JOIN ig_ocorrencia AS oc ON l.idLocal = oc.`local`
                    LEFT JOIN ig_instituicao AS ins ON ins.idInstituicao = l.idInstituicao
                    WHERE oc.idEvento = {$row['idEvento']} GROUP BY l.idLocal";
    $queryLocais = mysqli_query($con, $sqlLocais);

    $local = '';
    $x = 0;

    while ($locais = mysqli_fetch_array($queryLocais)) {
        $str = $locais['sala'] != '' ? "{$locais['local']} - {$locais['sala']}" : $locais['local'];
        if ($x > 0) {
            $local .= " / {$str}";
        } else {
            $local .= $str;
        }
        $x++;
    }


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $i, $proponente)
        ->setCellValue('B' . $i, $documento)
        ->setCellValue('C' . $i, $row['NumeroProcesso'])
        ->setCellValue('D' . $i, $row['nomeEvento'])
        ->setCellValue('E' . $i, $row['integrantes'])
        ->setCellValue('F' . $i, $row['valor'])
        ->setCellValue('G' . $i, exibirDataBr($ocorrencia['dataInicio']))
        ->setCellValue('H' . $i, $ocorrencia['dataFim'] != '0000-00-00' ? exibirDataBr($ocorrencia['dataFim']) : '')
        ->setCellValue('I' . $i, $ocorrencia['qtd'])
        ->setCellValue('J' . $i, $local)
        ->setCellValue('K' . $i, $row['estado'])
        ->setCellValue('L' . $i, $responsavel['sala']);

    $i++;
}

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = date("Y-m-d H:i:s") . "Eventos.xls";

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nome_arquivo . '"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');