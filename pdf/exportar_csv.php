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
        $filtro_local = "E.idInstituicao = '$local' AND";
    } else {
        $filtro_local = "";
    }

    $sql = "SELECT
                INST.idInstituicao,
                INST.instituicao,
                E.nomeEvento AS 'nome',
                TE.tipoEvento AS 'categoria',
                O.dataInicio AS 'data',
                DATE_FORMAT(O.horaInicio, '%H:%i') AS 'horario_inicial',
                O.valorIngresso AS 'valor',
                E.sinopse AS 'descricao',
                L.sala AS 'nome_local'
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
                INNER JOIN ig_instituicao AS INST ON L.idInstituicao = INST.idInstituicao
            WHERE
                E.publicado = 1 AND
                $filtro_local
                E.statusEvento = 'Enviado'
                $filtro_data
            ORDER BY dataInicio";

    $query = mysqli_query($con, $sql);

    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=eventos.csv');

    $arquivo = fopen("php://output", "w");
    fputcsv($arquivo, array('Nome do Evento', 'Categoria', 'Data de Inicio', 'Horario de Inicio', 'Valor', 'Descrição', 'Local'));

    $instituicoes = ['13', '5', '6', '9', '29', '35', '34', '8', '24', '25', '10'];

    while ($linha = mysqli_fetch_assoc($query)) {
        $registro['Nome do Evento'] = $linha['nome'];
        $registro['Categoria'] = $linha['categoria'];
        $registro['Data de Inicio'] = $linha['data'];
        $registro['Horario de Inicio'] = $linha['horario_inicial'];
        $registro['Valor'] = $linha['valor'];
        $registro['Descrição'] = $linha['descricao'];

        if (in_array($linha['idInstituicao'], $instituicoes)) {
            $registro['Local'] = $linha['instituicao'];
        } else {
            $registro['Local'] = $linha['nome_local'];
        }

        fputcsv($arquivo, $registro);
    }

    fclose($arquivo);

}