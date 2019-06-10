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
                E.idEvento,
                E.nomeEvento AS 'nome',
                TE.tipoEvento AS 'categoria',
                DATE_FORMAT(O.dataInicio, '%d/%m/%Y') AS 'data',
                DATE_FORMAT(O.horaInicio, '%H:%i') AS 'horario_inicial',
                O.valorIngresso AS 'valor',
                E.sinopse AS 'descricao',
                L.sala AS 'nome_local',
                I.sigla AS 'instituicao',
                I.instituicao AS 'equipamento',
                L.rua AS 'endereco',
                I.telefone AS 'telefone',
                E.nomeGrupo AS 'artista',
                O.duracao AS 'duracao',
                CI.faixa AS 'classificacao',
                E.linksCom AS 'divulgacao',
                E.sinopse AS 'sinopse',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone AS 'produtor_fone',
                SUB_PRE.subprefeitura AS 'subprefeitura'
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
                INNER JOIN ig_instituicao AS I ON E.idInstituicao = I.idInstituicao
                INNER JOIN ig_etaria AS CI ON E.faixaEtaria = CI.idIdade
                INNER JOIN ig_produtor AS P ON E.ig_produtor_idProdutor = P.idProdutor
                LEFT JOIN igsis_subprefeitura AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
            WHERE
                $filtro_local AND
                E.publicado = 1 AND
                E.statusEvento = 'Enviado'
                $filtro_data
            ORDER BY dataInicio";

    $query = mysqli_query($con, $sql);

}

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=eventos_teste.xls");
?>

<html>
    <meta http-equiv="Content-Type" content="application/vnd.ms-excel; charset=utf-8">
    <body>
    <table border="1">
        <thead>
        <tr>
            <th>Instituição</th>
            <th>Equipamento / Local</th>
            <th>Endereço</th>
            <th>Subprefeitura</th>
            <th>Telefone</th>
            <th>Nome do Evento</th>
            <th>Artista</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Duração em min.</th>
            <th>Nº de Apresentações</th>
            <th>Linguagem IGSIS</th>
            <th>Linguagem / Expressão Artística</th>
            <th>Público / Representatividade social</th>
            <th>Atividade realizada em espaço público</th>
            <th>Cobrança de ingresso</th>
            <th>Valor do ingresso</th>
            <th>Classificação Indicativa</th>
            <th>Links de Divulgação</th>
            <th>Sinopse</th>
            <th>Produtor do Evento</th>
            <th>Email</th>
            <th>Telefone</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while($linha = mysqli_fetch_array($query))
        {
            $sqlConsultaOcorrencias = "SELECT idEvento FROM ig_ocorrencia WHERE idEvento = '".$linha['idEvento']."'";
            $apresentacoes = $con->query($sqlConsultaOcorrencias)->num_rows;
            ?>
            <tr>
                <td><?=$linha['instituicao']?></td>
                <td><?=$linha['equipamento']?> - <?=$linha['nome_local']?></td>
                <td><?=$linha['endereco']?></td>
                <td><?=$linha['subprefeitura']?></td>
                <td><?=$linha['telefone']?></td>
                <td><?=$linha['nome']?></td>
                <td><?=$linha['artista']?></td>
                <td><?= $linha['data']?></td>
                <td><?= $linha['horario_inicial']?></td>
                <td><?= $linha['duracao']?></td>
                <td><?= $apresentacoes?></td>
                <td><?=$linha['categoria']?></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?=($linha['valor'] == 0 ? "Gratuito" : "R$ ".dinheiroParaBr($linha['valor']))?></td>
                <td><?=($linha['valor'] == 0 ? "Gratuito" : "R$ ".dinheiroParaBr($linha['valor']))?></td>
                <td><?=$linha['classificacao']?></td>
                <td><?=$linha['divulgacao']?></td>
                <td><?=$linha['sinopse']?></td>
                <td><?=$linha['produtor_nome']?></td>
                <td><?=$linha['produtor_email']?></td>
                <td><?=$linha['produtor_fone']?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    </body>
</html>