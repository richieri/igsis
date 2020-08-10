<?php
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");
$con = bancoMysqli();

$dataAtual = date('Y:m:d H:i:s');

$sql = "SELECT
          pc.idPedidoContratacao,
          pc.idEvento,
          eve.nomeEvento,
          pc.integrantes,
          pc.tipoPessoa,
          pc.idPessoa,
          pc.valor,
          eve.dataEnvio
        FROM igsis_pedido_contratacao AS pc
        INNER JOIN ig_evento AS eve ON eve.idEvento = pc.idEvento
        WHERE pc.valor > 10000
          AND pc.publicado = 1
          AND pc.parecerArtistico LIKE '%composto por profissionais consagrados%'
          AND eve.publicado = 1
          AND eve.dataEnvio IS NOT NULL
          AND (eve.statusEvento IS NULL OR eve.statusEvento = 'Enviado')";
$query = $con->query($sql)->fetch_all(MYSQLI_ASSOC);

header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=$dataAtual pedidos_artistas_consagrados.xls" );

?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<table class="table table-condensed" border="1">
    <thead>
    <tr class="list_menu">
        <th>Proponente</th>
        <th>Documento</th>
        <th>Nome do Evento</th>
        <th>Integrantes</th>
        <th>Valor Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($query as $dados): ?>
        <tr>
            <?php if ($dados['tipoPessoa'] == 1 ):
                $proponente = recuperaDados('sis_pessoa_fisica', $dados['idPessoa'], 'Id_PessoaFisica');
                ?>
                <td><?=$proponente['Nome'] ?? ''?> (Nome Artístico: <?=$proponente['NomeArtistico'] ?? ''?>)</td>
                <td>RG: <?=$proponente['RG'] ?? ''?> - CPF: <?=$proponente['CPF'] ?? ''?></td>
            <?php else:
                $proponente = recuperaDados('sis_pessoa_juridica', $dados['idPessoa'], 'Id_PessoaJuridica');
                ?>
                <td><?=$proponente['RazaoSocial'] ?? ''?></td>
                <td>CNPJ: <?=$proponente['CNPJ'] ?? ''?></td>
            <?php endif; ?>
            <td><?=$dados['nomeEvento']?></td>
            <td><?=$dados['integrantes']?></td>
            <td><?=dinheiroParaBr($dados['valor'])?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>