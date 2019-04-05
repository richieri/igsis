<?php
include 'includes/menu.php';

unset($_SESSION['idPedido']);
$con = bancoMysqli();

$sqlFormacaoPf = "SELECT pf.Id_PessoaFisica, pf.Nome, pf.CPF, pf.Telefone1, pf.Telefone2, pf.Email FROM sis_pessoa_fisica AS pf";

$pfs = $con->query($sqlFormacaoPf);

?>
<section id="services" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <h2>Listagem de Pessoa Fisica</h2>
                </div>
                <div class="section-heading" id="loader">
                    <img src="./images/carregando.gif" alt="" style="max-width: 20%">
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <table id="formacaoPF" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Telefone #1</th>
                            <th>Telefone #2</th>
                            <th>Email</th>
                            <th>Visualizar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($pf = $pfs->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($pf as $campo => $dadoPf) {
                            if ($campo == "Id_PessoaFisica") {
                                continue;
                            } else {
                                echo "<td>$dadoPf</td>";
                            }
                        }
                        echo "<td><a class='btn btn-theme btn-block' href='?perfil=formacao&p=frm_resumo_pf&idPf=".$pf['Id_PessoaFisica']."'>Resumo</a></td>";
                        echo "</tr>";
                    } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Telefone #1</th>
                            <th>Telefone #2</th>
                            <th>Email</th>
                            <th>Visualizar</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" defer>
    $(function () {
        $('#formacaoPF').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6 text-left'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5 text-left'i><'col-sm-7 text-right'p>>",
        });
    });

    $(window).load(function(){
        $('div#loader').fadeOut(500);
    });
</script>