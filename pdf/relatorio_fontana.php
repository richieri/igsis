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
       pc.tipoPessoa,
       pc.idPessoa,
       pc.NumeroProcesso
FROM igsis_pedido_contratacao AS pc
INNER JOIN ig_evento AS eve ON eve.idEvento = pc.idEvento
WHERE (eve.idResponsavel = 390 OR eve.suplente = 390 OR idUsuario = 390) AND
      pc.publicado = 1 AND
      dataEnvio BETWEEN '2019-01-01' AND '2020-03-06'";
$query = $con->query($sql)->fetch_all(MYSQLI_ASSOC);

header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=$dataAtual processos_fiscal_suplente.xls" );

?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<table class="table table-condensed">
    <thead>
    <tr class="list_menu">
        <td>ID do Pedido de Contratação</td>
        <td>Nome do Evento</td>
        <td>Nº SEI</td>
        <td>Proponente</td>
        <td>Documento</td>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($query as $dados) {
        ?>
        <tr>
            <td><?=$dados['idPedidoContratacao']?></td>
            <td><?=$dados['nomeEvento']?></td>
            <td><?=$dados['NumeroProcesso']?></td>
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