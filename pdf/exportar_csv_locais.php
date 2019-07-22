<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

if(isset($_POST['exportar'])) {
    $datainicio = $_POST['dataInicio'];
    $datafim = $_POST['dataFim'];
    $local = $_POST['local'];

    if ($datainicio != '') {
        if ($datafim != '') {

            //$datafim = $_POST['dataFim'];
            $filtro_data = " AND O.dataInicio BETWEEN '$datainicio' AND '$datafim'";
        } else {
            $filtro_data = " AND O.dataInicio > '$datainicio'";
        }
    } else {
        $mensagem = "Informe uma data para inicio da consulta";
    }

    if ($local != '') {
        $filtro_local = "E.idInstituicao = '$local'";
    }

    $sql = "SELECT
                INST.idInstituicao,
                INST.instituicao,
                L.sala AS 'Nome do Local',
                L.rua AS 'Logradouro',
                L.cidade AS 'Cidade',
                L.estado AS 'Estado',
                L.cep AS 'CEP',
                L.pais AS 'Pais'
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
                INNER JOIN ig_instituicao AS INST ON L.idInstituicao = INST.idInstituicao
            WHERE
                E.publicado = 1 AND
                E.statusEvento = 'Enviado'
                $filtro_data
            ORDER BY dataInicio";

    $query = mysqli_query($con, $sql);

    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=locais.csv');

    $arquivo = fopen("php://output", "w");
    fputcsv($arquivo, array('Nome do Local', 'Logradouro', 'Cidade', 'Estado', 'CEP', 'Pais'));

    $instituicoes = ['13', '5', '6', '9', '29', '35', '34', '8', '24', '25', '10'];

    while ($linha = mysqli_fetch_assoc($query)) {
        if (in_array($linha['idInstituicao'], $instituicoes)) {
            $registro['Nome do Local'] = $linha['instituicao'];
        } else {
            $registro['Nome do Local'] = $linha['Nome do Local'];
        }

        $registro['Logradouro'] = (($linha['Logradouro'] == NULL) || ($linha['Logradouro'] == "")) ? " " : $linha['Logradouro'];
        $registro['Cidade'] = (($linha['Cidade'] == NULL) || ($linha['Cidade'] == "")) ? " " : $linha['Cidade'];
        $registro['Estado'] = (($linha['Estado'] == NULL) || ($linha['Estado'] == "")) ? " " : $linha['Estado'];
        $registro['CEP'] = (($linha['CEP'] == NULL) || ($linha['CEP'] == "")) ? " " : $linha['CEP'];
        $registro['Pais'] = (($linha['Pais'] == NULL) || ($linha['Pais'] == "")) ? " " : $linha['Pais'];

        fputcsv($arquivo, $registro);
    }

    fclose($arquivo);

}