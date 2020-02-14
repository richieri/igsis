<?php
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");
$con = bancoMysqli();

$dataAtual = date('Y:m:d H:i:s');

$sql = "SELECT
            ped.idPedidoContratacao,
            ie.idEvento,
            ie.nomeEvento,
            ipe.projetoEspecial,
            ped.tipoPessoa,
            ped.idPessoa
        FROM igsis.igsis_pedido_contratacao AS ped
        INNER JOIN igsis.ig_evento AS ie on ped.idEvento = ie.idEvento
        LEFT JOIN igsis.ig_projeto_especial AS ipe on ie.projetoEspecial = ipe.idProjetoEspecial
        WHERE ped.publicado = 1
        ORDER BY ie.idEvento";
$query = $con->query($sql)->fetch_all(MYSQLI_ASSOC);

header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=$dataAtual proponentes_espacos_abertos.xls" );

?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<table class="table table-condensed">
    <thead>
    <tr class="list_menu">
        <td>Nome do Evento</td>
        <td>Projeto Especial</td>
        <td>Proponente</td>
        <td>E-mail</td>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($query as $dados) {
        ?>
        <tr>
            <td><?=$dados['nomeEvento']?></td>
            <td><?=$dados['projetoEspecial']?></td>
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