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
    ->setCellValue('A1', 'Instituição/Coordenadoria' )
    ->setCellValue('B1', "Equipamento" )
    ->setCellValue("C1", "Espaço Público?" )
    ->setCellValue("D1", "Local do Evento")
    ->setCellValue("E1", "Logradouro")
    ->setCellValue("F1", "Número")
    ->setCellValue("G1", "Complemento")
    ->setCellValue("H1", "Bairro")
    ->setCellValue("I1", "Cidade")
    ->setCellValue("J1", "Estado")
    ->setCellValue("K1", "CEP")
    ->setCellValue("L1", "SubPrefeitura")
    ->setCellValue("M1", "Telefone")
    ->setCellValue("N1", "Data Início")
    ->setCellValue("O1", "Data Fim")
    ->setCellValue("P1", "Dias da semana")
    ->setCellValue("Q1", "Horário de início")
    ->setCellValue("R1", "Período")
    ->setCellValue("S1", "Duração (em minutos)")
    ->setCellValue("T1", "Nº de atividades")
    ->setCellValue("U1", "Cobrança de ingresso")
    ->setCellValue("V1", "Valor do ingresso")
    ->setCellValue("W1", "Nome do Evento")
    ->setCellValue("X1", "Projeto Especial?")
    ->setCellValue("Y1", "Artistas")
    ->setCellValue("Z1", "Ação")
    ->setCellValue("AA", "Público")
    ->setCellValue("AB", "É Fomento/Programa?")
    ->setCellValue("AC", "Classificação indicativa")
    ->setCellValue("AD", "Link de Divulgação")
    ->setCellValue("AE", "Sinopse")
    ->setCellValue("AF", "Produtor do Evento")
    ->setCellValue("AG", "E-mail de contato")
    ->setCellValue("AH", "Telefone de contato")
    ->setCellValue("AI", "Imagem para divulgação");


// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->getFont()->setBold(true);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->applyFromArray
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
    $dias = "";
    $linha['segunda'] == 1 ?? $dias = "Segunda, ";
    $linha['terca'] == 1 ?? $dias .= "Terça, ";
    $linha['quarta'] == 1 ?? $dias .= "Quarta, ";
    $linha['quinta'] == 1 ?? $dias .= "Quinta, ";
    $linha['sexta'] == 1 ?? $dias .= "Sexta, ";
    $linha['sabado'] == 1 ?? $dias .= "Sabádo, ";
    $linha['domingo'] == 1 ?? $dias .= "Domingo. ";

    //Ações
    $sqlAcao = "SELECT * FROM igsis_evento_linguagem WHERE idEvento = '". $linha['idEvento'] . "'";
    $queryAcao = mysqli_query($con, $sqlAcao);
    $acoes = [];
    $i = 0;

    while ($arrayAcoes = mysqli_fetch_array($queryAcao)) {
        $idAcao = $arrayAcoes['idLinguagem'];
        $sqlLinguagens = "SELECT * FROM igsis_linguagem WHERE id = '$idAcao'";
        $linguagens = $con->query($sqlLinguagens)->fetch_assoc();
        $acoes[$i] = $linguagens['linguaguem'];
    }

    if (count($acoes) != 0) {
        $stringAcoes = implode(", ", $acoes);
    }


    //Público
    $sqlPublico = "SELECT * FROM igsis_evento_representatividade WHERE idEvento = '". $linha['idEvento'] . "'";
    $queryPublico = mysqli_query($con, $sqlPublico);
    $representatividade = [];
    $i = 0;

    while ($arrayPublico = mysqli_fetch_array($queryPublico)) {
        $idRepresentatividade = $arrayPublico['idRepresentatividade'];
        $sqlRepresen = "SELECT * FROM igsis_representatividade WHERE id = '$idRepresentatividade'";
        $publicos = $con->query($sqlRepresen)->fetch_assoc();
        $representatividade[$i] = $publicos['representatividade_social'];
    }

    if (count($acoes) != 0) {
        $stringPublico = implode(", ", $representatividade);
    }

    if ($linha['fomento'] == 1) {
        $sqlFomento = "SELECT * FROM "
    }




    $sqlPrefeitura = "SELECT * FROM igsis_subprefeitura WHERE id = '" . $linha['id_subprefeitura'] . "'";
    $prefeitura = $con->query($sqlPrefeitura)->fetch_assoc();

    $sqlPeriodo = "SELECT * FROM ig_periodo_dia WHERE id = '" . $linha['idPeriodo'] . "'";
    $periodo = $con->query($sqlPeriodo)->fetch_assoc();

    $sqlIngresso = "SELECT * FROM ig_retirada WHERE idRetirada = '" . $linha['ingresso'] . "'";
    $retirada = $con->query($sqlIngresso)->fetch_assoc();

    $sqlProjeto = "SELECT * FROM ig_projeto_especial WHERE idProjetoEspecial = '" . $linha['idProjetoEspecial'] . "'";
    $projeto = $con->query($sqlProjeto)->fetch_assoc();

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
    $u = "U".$cont;
    $v = "V".$cont;
    $w = "W".$cont;
    $x = "X".$cont;
    $y = "Y".$cont;
    $z = "Z".$cont;
    $aa = "AA".$cont;
    $ab = "AB".$cont;
    $ac = "AC".$cont;
    $ad = "AD".$cont;
    $ae = "AE".$cont;
    $af = "AF".$cont;
    $ag = "AG".$cont;


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['instituicao'])
        ->setCellValue($b, $linha['equipamento'])
        ->setCellValue($c, $linha['espaco_publico'] == 1 ? "SIM" : "NÃO")
        ->setCellValue($d, $linha['nome_local'])
        ->setCellValue($e, $linha['logradouro'])
        ->setCellValue($f, $linha['numero'])
        ->setCellValue($g, $linha['complemento'])
        ->setCellValue($h, $linha['bairro'])
        ->setCellValue($i, $linha['cidade'])
        ->setCellValue($j, $linha['estado'])
        ->setCellValue($k, $linha['cep'])
        ->setCellValue($l, $prefeitura['subprefeitura'])
        ->setCellValue($m, $linha['telefone'])
        ->setCellValue($n, $linha['data_inicio'])
        ->setCellValue($o, $linha['data_fim'])
        ->setCellValue($p, $dias)
        ->setCellValue($q, $linha['hora_inicio'])
        ->setCellValue($r, $periodo['periodo'])
        ->setCellValue($s, $linha['duracao'] . " minutos.")
        ->setCellValue($t, $apresentacoes)
        ->setCellValue($u, $retirada['retirada'])
        ->setCellValue($v, $linha['nome'])
        ->setCellValue($w, $projeto['projetoEspecial'])
        ->setCellValue($x, $linha['artista'])
        ->setCellValue($y, $stringAcoes)
        ->setCellValue($z, $stringPublico)
        ->setCellValue($aa, $stringAcoes)
        ->setCellValue($ab, $stringAcoes)
        ->setCellValue($ac, $stringAcoes)
        ->setCellValue($ad, $stringAcoes)
        ->setCellValue($ae, $stringAcoes)
        ->setCellValue($af, $stringAcoes)
        ->setCellValue($ag, $stringAcoes);

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
