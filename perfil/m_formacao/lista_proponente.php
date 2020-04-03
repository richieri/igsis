<?php
include 'includes/menu.php';

$con = bancoPDO();
$formacao = $con->query("
    SELECT igsis_pedido_contratacao.NumeroProcesso, sis_estado.estado, sis_pessoa_fisica.Nome, sis_pessoa_fisica.CPF, igsis_pedido_contratacao.idPedidoContratacao, sis_formacao.Ano
    FROM sis_formacao
    INNER JOIN igsis_pedido_contratacao ON igsis_pedido_contratacao.idPedidoContratacao = sis_formacao.idPedidoContratacao
    INNER JOIN sis_estado ON sis_estado.idEstado = igsis_pedido_contratacao.estado
    INNER JOIN sis_pessoa_fisica ON sis_pessoa_fisica.Id_PessoaFisica = igsis_pedido_contratacao.idPessoa
    WHERE sis_formacao.publicado = '1' AND igsis_pedido_contratacao.publicado = '1' AND  igsis_pedido_contratacao.tipoPessoa = 4 ORDER BY sis_pessoa_fisica.Nome
")->fetchAll(PDO::FETCH_ASSOC);
?>
<section id="services" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <h3>Lista de proponentes</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Número Processo</th>
                        <th>Codigo do Pedido</th>
                        <th>Proponente</th>
                        <th>CPF</th>
                        <th>Ano</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($formacao as $row): ?>
                        <tr>
                            <td><?= $row['NumeroProcesso'] ?></td>
                            <td><?= $row['idPedidoContratacao'] ?></td>
                            <td><?= $row['Nome'] ?></td>
                            <td><?= $row['CPF'] ?></td>
                            <td><?= $row['Ano'] ?></td>
                            <td><?= $row['estado'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Número Processo</th>
                        <th>Codigo do Pedido</th>
                        <th>Proponente</th>
                        <th>CPF</th>
                        <th>Ano</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" defer src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" defer src="//cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js"></script>
<script type="text/javascript" defer>

    $(function () {
        $.fn.dataTable.moment( 'DD/M/YYYY' );
        $.fn.dataTable.moment( 'DD/M/YYYY a D/M/YYYY' );
        $('#example1').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6 text-left'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5 text-left'i><'col-sm-7 text-right'p>>",
        });
    })
</script>