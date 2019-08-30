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
                E.idEvento,
                E.nomeEvento AS 'nome',
                E.espaco_publico AS 'espaco_publico',
                E.projetoEspecial AS 'idProjetoEspecial',
                LI.linguagem AS 'categoria',
                O.idOcorrencia AS 'idOcorrencia',
                O.horaInicio AS 'horaInicio',
                O.dataInicio AS 'dataInicio',
                O.dataFinal AS 'dataFinal',
                O.duracao AS 'duracao',
                O.valorIngresso AS 'valorIngresso',
                O.segunda AS 'segunda',
                O.terca AS 'terca',
                O.quarta AS 'quarta',
                O.quinta AS 'quinta',
                O.sexta AS 'sexta',
                O.sabado AS 'sabado',
                O.domingo AS 'domingo',
                L.sala AS 'nome_local',
                I.sigla AS 'sigla',
                I.idInstituicao,
                I.instituicao AS 'equipamento',
                L.logradouro AS 'logradouro',
                L.numero AS 'numero',
                L.complemento AS 'complemento',
                L.bairro AS 'bairro',
                L.cidade AS 'cidade',
                L.estado AS 'estado',
                L.cep AS 'cep',
                I.telefone AS 'telefone',
                E.fichaTecnica AS 'artista',
                CI.faixa AS 'classificacao',
                E.linksCom AS 'divulgacao',
                E.sinopse AS 'sinopse',
                E.fomento AS 'fomento',
                E.tipo_fomento AS 'tipoFomento',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone AS 'produtor_fone',
                U.nomeCompleto AS 'nomeCompleto',
                PE.projetoEspecial,
                SUB_PRE.subprefeitura AS 'subprefeitura',
                DIA_PERI.periodo AS 'periodo',
                retirada.retirada AS 'retirada',
                RE.representatividade_social AS publico
            FROM ig_evento AS E
            LEFT JOIN igsis_evento_representatividade AS ER ON ER.idEvento = E.idEvento
            LEFT JOIN igsis_representatividade AS RE ON RE.id = ER.idRepresentatividade
            LEFT JOIN igsis_evento_linguagem AS EL ON E.idEvento = EL.idEvento
            LEFT JOIN  igsis_linguagem AS LI ON LI.id = EL.idLinguagem
            INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
            INNER JOIN ig_local AS L ON O.local = L.idLocal
            INNER JOIN ig_instituicao AS I ON L.idInstituicao = I.idInstituicao
            LEFT JOIN ig_etaria AS CI ON E.faixaEtaria = CI.idIdade
            INNER JOIN ig_produtor AS P ON E.ig_produtor_idProdutor = P.idProdutor
            INNER JOIN ig_usuario AS U ON E.idUsuario = U.idUsuario
            LEFT JOIN ig_projeto_especial AS PE ON E.projetoEspecial = PE.idProjetoEspecial
            LEFT JOIN igsis_subprefeitura AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
            LEFT JOIN ig_periodo_dia AS DIA_PERI ON O.idPeriodoDia = DIA_PERI.id
            LEFT JOIN ig_retirada AS retirada ON O.retiradaIngresso = retirada.idRetirada 
            WHERE
                E.publicado = 1 AND
                E.statusEvento = 'Enviado'
                $filtro_data
            ORDER BY dataInicio";

    $query = mysqli_query($con, $sql);

    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=eventos.csv');

    $arquivo = fopen("php://output", "w");
//    fputcsv($arquivo, array('Nome do Evento', 'Categoria', 'Data de Inicio', 'Horario de Inicio', 'Valor', 'Descrição', 'Local'));
    fputcsv($arquivo, [
        'Nome do Evento',
        'Categoria',
        'Horario de Início',
        'Data de Início',
        'Duração',
        'Valor do Ingresso',
        'Local',
        'Classificação Indicativa',
        'Divulgação',
        'Descrição',
        'Projeto Especial',
        'Subprefeitura',
        'Periodo',
        'Público',
    ]);

    $instituicoes = ['4', '5', '6', '8', '9', '10', '13', '24', '25', '29', '34', '35', '45', '68'];

    while ($linha = mysqli_fetch_assoc($query)) {
        $registro['Nome do Evento'] = $linha['nome'];
        $registro['Categoria'] = $linha['categoria'];
        $registro['Horario de Início'] = $linha['horaInicio'];
        $registro['Data de Início'] = $linha['dataInicio'];
        $registro['Duração'] = $linha['duracao'];
        $registro['Valor do Ingresso'] = $linha['valorIngresso'];

        if (in_array($linha['idInstituicao'], $instituicoes)) {
            $registro['Local'] = $linha['equipamento'];
        } else {
            $registro['Local'] = $linha['nome_local'];
        }

        $registro['Classificação Indicativa'] = $linha['classificacao'];
        $registro['Divulgação'] = $linha['divulgacao'];
        $registro['Descrição'] = $linha['sinopse'];
        $registro['Projeto Especial'] = $linha['projetoEspecial'];
        $registro['Subprefeitura'] = $linha['subprefeitura'];
        $registro['Periodo'] = $linha['periodo'];
        $registro['Público'] = $linha['publico'];
        

        fputcsv($arquivo, $registro);
    }

    fclose($arquivo);

}