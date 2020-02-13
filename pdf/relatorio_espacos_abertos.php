<?php
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

$dataAtual = date('Y:m:d H:i:s');

header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=$dataAtual proponentes_espacos_abertos.xls" );

$con = bancoMysqli();
?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<table class="table table-condensed">
    <thead>
    <tr class="list_menu">
        <td>Nome do Evento</td>
        <td>Local</td>
        <td>Proponente</td>
        <td>E-mail</td>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT
                    eve.idEvento,
                    ped.idPedidoContratacao,
                    eve.nomeEvento,
                    ped.tipoPessoa,
                    ped.idPessoa,
                    loc.sala
                FROM igsis.igsis_pedido_contratacao AS ped
                INNER JOIN igsis.ig_ocorrencia AS oco ON ped.idEvento = oco.idEvento
                INNER JOIN igsis.ig_evento AS eve ON eve.idEvento = ped.idEvento
                INNER JOIN igsis.ig_local AS loc ON loc.idLocal = oco.local
                WHERE oco.local IN (SELECT idLocal FROM igsis.ig_local WHERE idInstituicao = 18)
                    AND ped.publicado = 1
                    AND oco.publicado = 1
                GROUP BY ped.idEvento";
    $query = $con->query($sql)->fetch_all(MYSQLI_ASSOC);
    foreach ($query as $dados) {
        ?>
        <tr>
            <td><?=$dados['nomeEvento']?></td>
            <td><?=$dados['sala']?></td>
            <?php
            if ($dados['tipoPessoa'] == 1 ) {
                $proponente = recuperaDados('sis_pessoa_fisica', $dados['idPessoa'], 'Id_PessoaFisica');
                echo "<td>{$proponente['Nome']}</td>";
                echo "<td>{$proponente['Email']}</td>";
            } else {
                $proponente = recuperaDados('sis_pessoa_juridica', $dados['idPessoa'], 'Id_PessoaJuridica');
                echo "<td>{$proponente['RazaoSocial']}</td>";
                echo "<td>{$proponente['Email']}</td>";
            }
            ?>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
</body>
</html>