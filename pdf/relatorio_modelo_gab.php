<?php
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");
$con = bancoMysqli();

$dataAtual = date('Y:m:d H:i:s');

$sql = "SELECT
           pc.idPedidoContratacao,
           pc.NumeroProcesso,
           pc.idEvento,
           eve.nomeEvento,
           pc.tipoPessoa,
           pc.idPessoa,
           pc.integrantes,
           pc.valor,
           pc.qtdApresentacoes,
           eve.dataEnvio
        FROM igsis_pedido_contratacao AS pc
        INNER JOIN ig_evento AS eve ON eve.idEvento = pc.idEvento
        WHERE pc.publicado = 1 AND dataEnvio BETWEEN '2020-04-01' AND NOW()";
$query = $con->query($sql)->fetch_all(MYSQLI_ASSOC);

header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=$dataAtual processos_fiscal_suplente.xls" );

?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<table class="table table-condensed" border="1">
    <thead>
    <tr class="list_menu">
        <th>Proponente</th>
        <th>Documento</th>
        <th>Número do Processo</th>
        <th>Nome do Evento</th>
        <th>Integrantes</th>
        <th>Valor Total</th>
        <th>Data de Inicio</th>
        <th>Data de Encerramento (Caso temporada)</th>
        <th>Qtde Apresentações</th>
        <th>Local</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($query as $dados) {
        $queryOcorrencia = $con->query("SELECT dataInicio, dataFinal FROM ig_ocorrencia WHERE idEvento = '{$dados['idEvento']}' AND publicado = '1'");
        $ocorrencias = $queryOcorrencia->fetch_all(MYSQLI_ASSOC);
        $registros = $queryOcorrencia->num_rows;
        $registros = ($registros <= 1) ? 1 : $registros;
        ?>
        <tr>
            <?php if ($dados['tipoPessoa'] == 1 ):
                $proponente = recuperaDados('sis_pessoa_fisica', $dados['idPessoa'], 'Id_PessoaFisica');
                ?>
                <td rowspan="<?=$registros?>"><?=$proponente['Nome'] ?? ''?></td>
                <td rowspan="<?=$registros?>"><?=$proponente['CPF'] ?? ''?></td>
            <?php else:
                $proponente = recuperaDados('sis_pessoa_juridica', $dados['idPessoa'], 'Id_PessoaJuridica');
                ?>
                <td rowspan="<?=$registros?>"><?=$proponente['RazaoSocial'] ?? ''?></td>
                <td rowspan="<?=$registros?>"><?=$proponente['CNPJ'] ?? ''?></td>
            <?php endif; ?>
            <td rowspan="<?=$registros?>"><?=$dados['NumeroProcesso']?></td>
            <td rowspan="<?=$registros?>"><?=$dados['nomeEvento']?></td>
            <td rowspan="<?=$registros?>"><?=$dados['integrantes']?></td>
            <td rowspan="<?=$registros?>"><?=dinheiroParaBr($dados['valor'])?></td>
            <td><?=exibirDataBr($ocorrencias[0]['dataInicio'])?></td>
            <td><?=$ocorrencias[0]['dataFinal'] == '0000-00-00' ? 'Não é temporada' : exibirDataBr($ocorrencias[0]['dataFinal'])?></td>
            <?php unset($ocorrencias[0]); ?>
            <td rowspan="<?=$registros?>"><?=$dados['qtdApresentacoes']?></td>
            <td rowspan="<?=$registros?>"><?= substr(listaLocais($dados['idEvento']),1) ?></td>
        </tr>
        <?php
        if (count($ocorrencias)) {
            foreach ($ocorrencias as $ocorrencia) {
            ?>
                <tr>
                    <td><?=exibirDataBr($ocorrencia['dataInicio'])?></td>
                    <td><?=$ocorrencia['dataFinal'] == '0000-00-00' ? 'Não é temporada' : exibirDataBr($ocorrencia['dataFinal'])?></td>
                </tr>
            <?php
            }
        }
    }
    ?>
    </tbody>
</table>
</body>
</html>