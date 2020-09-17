<?php
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");
$con = bancoMysqli();

$dataAtual = date('Y:m:d H:i:s');

$sql = "SELECT 
            eve.idEvento,
            eve.nomeEvento,
            igmod.modalidade,
            proj.projetoEspecial,
            eve.numero_apresentacao,
            tipo.tipoEvento,
            usu.nomeCompleto AS 'Fiscal',
            usua.nomeCompleto AS 'Suplente',
            eve.nomeGrupo,
            eve.fichaTecnica,
            eve.faixaEtaria,
            eve.sinopse,
            eve.releaseCom,
            eve.linksCom
        FROM `ig_evento` AS eve
        LEFT JOIN ig_modalidade AS igmod ON eve.ig_modalidade_IdModalidade = igmod.idModalidade
        LEFT JOIN ig_projeto_especial AS proj ON eve.projetoEspecial = proj.idProjetoEspecial
        LEFT JOIN ig_tipo_evento AS tipo ON eve.ig_tipo_evento_idTipoEvento = tipo.idTipoEvento
        LEFT JOIN ig_usuario AS usu ON eve.idResponsavel = usu.idUsuario
        LEFT JOIN ig_usuario AS usua ON eve.suplente = usua.idUsuario
        WHERE eve.statusEvento = 'Enviado' AND
        eve.dataEnvio BETWEEN '2020-03-01' AND '2020-10-01' AND
        eve.publicado = 1 AND
        eve.projetoEspecial IN ('43', '92')";

$query = $con->query($sql)->fetch_all(MYSQLI_ASSOC);

header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=".$dataAtual."_eventos_bibliotecas.xls" );

?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<table class="table table-condensed" border="1">
    <thead>
    <tr>
        <th colspan="14">Dados do Evento</th>
        <th colspan="6">Dados do Pedido</th>
        <th colspan="4">Dados da Ocorrencia</th>
    </tr>
    <tr class="list_menu">
        <th>ID do Evento</th>
        <th>Nome do Evento</th>
        <th>Tipo de relação jurídica</th>
        <th>Nome do Projeto especial</th>
        <th>Quantidade de apresentações</th>
        <th>Tipo do Evento</th>
        <th>Primeiro responsável (Fiscal)</th>
        <th>Segundo responsável (Suplente)</th>
        <th>Nome do Grupo</th>
        <th>Ficha técnica completa</th>
        <th>Classificação indicativa</th>
        <th>Sinopse</th>
        <th>Release</th>
        <th>Links</th>
        <th>Proponente</th>
        <th>Documento</th>
        <th>Integrantes</th>
        <th>Valor</th>
        <th>Numero de Processo</th>
        <th>Processo Mãe</th>
        <th>Data de Inicio</th>
        <th>Hora de Inicio</th>
        <th>Duração (minutos)</th>
        <th>Local</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($query as $dados):
        $sqlPedido = "SELECT 
                        tipoPessoa,
                        idPessoa,
                        integrantes,
                        valor,
                        NumeroProcesso,
                        processoMae
                     FROM igsis_pedido_contratacao WHERE idEvento = '{$dados['idEvento']}' AND publicado = 1";
        $queryPedidos = $con->query($sqlPedido);
        $numPedidos = $queryPedidos->num_rows;
        $pedido = $queryPedidos->fetch_assoc();

        $sqlOcorrencias = "SELECT
                                DATE_FORMAT(oco.dataInicio, '%d/%m/%Y'),
                                DATE_FORMAT(oco.horaInicio, '%H:%i'),
                                oco.duracao,
                                loc.sala
                            FROM `ig_ocorrencia` AS oco
                            INNER JOIN ig_local AS loc ON oco.local = loc.idLocal
                            WHERE oco.idEvento = '{$dados['idEvento']}' AND oco.publicado = 1";
        $queryOcorrencias = $con->query($sqlOcorrencias);
        $numOcorrencias = $queryOcorrencias->num_rows;
        $ocorrencias = $queryOcorrencias->fetch_all(MYSQLI_ASSOC);
    ?>
        <tr>
            <?php foreach ($dados as $dado): ?>
                <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>>
                    <?= $dado ?>
                </td>
            <?php endforeach; ?>

            <?php if($numPedidos > 0): ?>
                <?php if ($pedido['tipoPessoa'] == 1 ):
                    $proponente = recuperaDados('sis_pessoa_fisica', $pedido['idPessoa'], 'Id_PessoaFisica');
                    ?>
                    <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>>
                        <?=$proponente['Nome'] ?? ''?> (Nome Artístico: <?=$proponente['NomeArtistico'] ?? ''?>)
                    </td>
                    <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>>
                        RG: <?=$proponente['RG'] ?? ''?> - CPF: <?=$proponente['CPF'] ?? ''?>
                    </td>
                <?php else:
                    $proponente = recuperaDados('sis_pessoa_juridica', $pedido['idPessoa'], 'Id_PessoaJuridica');
                    ?>
                    <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>>
                        <?=$proponente['RazaoSocial'] ?? ''?>
                    </td>
                    <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>>
                        CNPJ: <?=$proponente['CNPJ'] ?? ''?>
                    </td>
                <?php endif; ?>
                <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>><?=$pedido['integrantes']?></td>
                <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>><?=dinheiroParaBr($pedido['valor'])?></td>
                <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>><?=$pedido['NumeroProcesso']?></td>
                <td <?=$numOcorrencias != 0 ? "rowspan='$numOcorrencias'" : ""?>><?=$pedido['processoMae']?></td>
            <?php else: ?>
                <td colspan="5">Evento não possui Pedido Cadastrado</td>
            <?php endif; ?>

            <?php if($numOcorrencias > 0): ?>
                <?php foreach ($ocorrencias[0] as $dadoOcorrencia): ?>
                    <td><?= $dadoOcorrencia ?></td>
                <?php
                endforeach;
                unset($ocorrencias[0])
                ?>
            <?php else: ?>
                <td colspan="4">Este Evento não possui ocorrencias</td>
            <?php endif; ?>
        </tr>
        <?php if(($numOcorrencias > 0) && (count($ocorrencias))): ?>
            <?php foreach ($ocorrencias as $ocorrencia): ?>
            <tr>
                <?php foreach ($ocorrencia as $dadoOcorrencia): ?>
                    <td><?= $dadoOcorrencia ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php
    $i++;
    endforeach; ?>
    </tbody>
</table>
</body>
</html>