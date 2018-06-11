<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

if(isset($_POST['exportar'])) {
    $datainicio = exibirDataMysql($_POST['dataInicio']);
    $datafim = $_POST['dataFim'];
    $local = $_POST['local'];

    if ($datainicio != '') {
        if ($datafim != '') {

            $datafim = exibirDataMysql($_POST['datafim']);
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
                E.nomeEvento AS 'nome',
                TE.tipoEvento AS 'categoria',
                O.dataInicio AS 'data',
                O.horaInicio AS 'horario_inicial',
                O.valorIngresso AS 'valor',
                E.sinopse AS 'descricao',
                L.sala AS 'nome_local'
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
            WHERE
                $filtro_local AND
                E.publicado = 1 AND
                E.statusEvento = 'Enviado'
                $filtro_data
            ORDER BY dataInicio";

    $query = mysqli_query($con, $sql);

    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=eventos.csv');

    $arquivo = fopen("php://output", "w");
    fputcsv($arquivo, array('Nome do Evento', 'Categoria', 'Data de Inicio', 'Horario de Inicio', 'Valor', 'Descrição', 'Local'));

    while ($linha = mysqli_fetch_assoc($query)) {
        fputcsv($arquivo, $linha);
    }

    fclose($arquivo);

}