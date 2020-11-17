<?php include 'includes/menu.php';

function consultaSimples($consulta) {
    $conn = bancoPDO();
    $statement = $conn->prepare($consulta);
    $statement->execute();
    return $statement;
}

if (isset($_GET['pag'])) {
    $p = $_GET['pag'];
} else {
    $p = 'inicial';
}

switch ($p) {
    /* =========== INICIAL ===========*/
    case 'inicial':
        /*POST*/
        if (isset($_POST['periodo'])) {
            $inicio = exibirDataMysql($_POST['inicio']);
            $final = exibirDataMysql($_POST['final']);
            $idContratos = $_POST['operador'];

            if ($idContratos == 0) {
                $operador = " ";
            } else {
                $operador = "AND ped.idContratos = '$idContratos'";
            }

            //$con = bancoMysqli();

            $sql = "SELECT ped.idPedidoContratacao, eve.idEvento, ped.NumeroProcesso, eve.ig_tipo_evento_idTipoEvento, eve.nomeEvento, ped.tipoPessoa, ped.idPessoa, ii.sigla, ped.valor, ped.pendenciaDocumento, se.estado, iu.nomeCompleto, MIN(oco.dataInicio) as dtInicio
                    FROM ig_ocorrencia AS oco
                    INNER JOIN ig_evento eve on oco.idEvento = eve.idEvento
                    INNER JOIN igsis_pedido_contratacao AS ped on oco.idEvento = ped.idEvento 
                    LEFT JOIN ig_instituicao ii on eve.idInstituicao = ii.idInstituicao
                    LEFT JOIN sis_estado se on ped.estado = se.idEstado    
                    LEFT JOIN ig_usuario iu on ped.idContratos = iu.idUsuario
                    WHERE oco.dataInicio BETWEEN '$inicio' AND '$final' $operador
                    AND ped.estado NOT IN (1, 11, 12)
                    AND eve.dataEnvio IS NOT NULL
                    AND ped.publicado = '1'
                    AND eve.publicado = '1'
                    AND oco.publicado = 1
                    GROUP BY oco.idEvento
                    ORDER BY oco.dataInicio";
            $query_evento = consultaSimples($sql)->fetchAll();
            //$sql_evento = $con->query($sql);
            //$query_evento = $sql_evento->fetch_all(MYSQLI_ASSOC);

            /*foreach ($query_evento as $key => $query) {
                $evento_id = $query['idEvento'];
                $dataInicio = $con->query("SELECT MIN(dataInicio) AS 'inicio' FROM ig_ocorrencia WHERE idEvento = '$evento_id' AND publicado = 1")->fetch_assoc()['inicio'];
                if ($dataInicio < $inicio) {
                    unset($query_evento[$key]);
                }
            }*/

            $num = count($query_evento);

            if ($num > 0) {
                ?>
                <br/>
                <br/>
                <section id="services" class="home-section bg-white">
                    <div class="container">
                        <h3>Resultado da busca</3>
                        <h5>Foram encontrados <?php echo $num; ?> pedidos de contratação.</h5>
                        <h5><a href="?perfil=contratos&p=frm_busca_periodo_operador_lite">Fazer outra busca</a></h5>
                        <div class="row">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr class="list_menu">
                                    <th>Codigo do Pedido</th>
                                    <th>Número Processo</th>
                                    <th>Proponente</th>
                                    <th>Evento</th>
                                    <th>Instituição</th>
                                    <th>Início</th>
                                    <th>Valor</th>
                                    <th>Pendências</th>
                                    <th>Status</th>
                                    <th>Operador</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $i = 0;
                                foreach ($query_evento as $lista){
                                    if ($lista['tipoPessoa'] == 1) {
                                        $pessoa = recuperaDados("sis_pessoa_fisica", $lista['idPessoa'], "Id_PessoaFisica");
                                        $proponente = $pessoa['Nome'];
                                        $link = "?perfil=contratos&p=frm_edita_propostapf&id_ped=";
                                    } else {
                                        $pessoa = recuperaDados("sis_pessoa_juridica", $lista['idPessoa'], "Id_PessoaJuridica");
                                        $proponente = $pessoa['RazaoSocial'];
                                        $link = "?perfil=contratos&p=frm_edita_propostapj&id_ped=";
                                    }
                                    ?>
                                    <tr>
                                        <td class="list_description"><a href="<?= $link.$lista['idPedidoContratacao'] ?>"><?= substr($lista['idPedidoContratacao'],5,10) ?></a></td>
                                        <td class="list_description"><?= $lista['NumeroProcesso'] ?></td>
                                        <td class="list_description"><?= $proponente ?></td>
                                        <td class="list_description"><?= $lista['nomeEvento'] ?></td>
                                        <td class="list_description"><?= $lista['sigla'] ?></td>
                                        <td class="list_description"><?= $lista['dtInicio'] ?></td>
                                        <td class="list_description"><?= dinheiroParaBr($lista['valor']) ?></td>
                                        <td class="list_description"><?= $lista['pendenciaDocumento'] ?></td>
                                        <td class="list_description"><?= $lista['estado'] ?></td>
                                        <td class="list_description"><?= $lista['nomeCompleto'] ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <?php
            } else {
                ?>
                <section id="services" class="home-section bg-white">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <h5>| Busca por período | <a href="?perfil=contratos&p=frm_busca_periodo&pag=relatorio">Relatório
                                        por período</a> | </h5>
                                <div class="section-heading">
                                    <h2>Busca por período / operador</h2>
                                    <p><?php if (isset($mensagem)) {
                                            echo $num;
                                        } ?></p>
                                    <p>É preciso ao menos um critério de busca ou você pesquisou por um pedido
                                        inexistente. Tente novamente.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <form method="POST" action="?perfil=contratos&p=frm_busca_periodo_operador_lite"
                                  class="form-horizontal" role="form">
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-8">
                                        <h5><?php if (isset($mensagem)) {
                                                echo $mensagem;
                                            } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-6">
                                        <label>Data início *</label>
                                        <input type="text" name="inicio" class="form-control" id="datepicker01"
                                               placeholder="">
                                    </div>
                                    <div class=" col-md-6">
                                        <label>Data encerramento *</label>
                                        <input type="text" name="final" class="form-control" id="datepicker02"
                                               placeholder="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-8">
                                        <label>Operador do Contrato</label>
                                        <select class="form-control" name="operador" id="inputSubject">
                                            <option value='0'></option>
                                            <?php geraOpcaoContrato(""); ?>
                                        </select>
                                    </div>
                                </div>
                                <br/>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-8">
                                        <input type="hidden" name="periodo" value="1"/>
                                        <input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <?php
            }
        } else {
            ?>
            <section id="services" class="home-section bg-white">
                <div class="container">
                    <div class="row">
                        <h5>| Busca por período | <a href="?perfil=contratos&p=frm_busca_periodo&pag=relatorio">Relatório
                                por período</a> | </h5>
                        <div class="col-md-offset-2 col-md-8">
                            <div class="section-heading">
                                <h2>Busca por período / operador</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <form method="POST" action="?perfil=contratos&p=frm_busca_periodo_operador_lite"
                              class="form-horizontal" role="form">
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-6">
                                    <label>Data início *</label>
                                    <input type="text" name="inicio" class="form-control" id="datepicker01" required>
                                </div>
                                <div class=" col-md-6">
                                    <label>Data encerramento *</label>
                                    <input type="text" name="final" class="form-control" id="datepicker02" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-8">
                                    <label>Operador do Contrato</label>
                                    <select class="form-control" name="operador" id="inputSubject">
                                        <option value='0'></option>
                                        <?php geraOpcaoContrato(""); ?>
                                    </select>
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-8">
                                    <input type="hidden" name="periodo" value="1"/>
                                    <input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <?php
        }
        /* =========== INICIAL ===========*/
        break;

} //fim da switch
?>
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
