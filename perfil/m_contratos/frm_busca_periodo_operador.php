<?php include 'includes/menu.php';

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

            $con = bancoMysqli();
            $sql_evento = "
                 SELECT ped.idPedidoContratacao, eve.idEvento, ped.NumeroProcesso, eve.ig_tipo_evento_idTipoEvento, eve.nomeEvento, ped.tipoPessoa, ped.idPessoa, ii.sigla, ped.valor, ped.pendenciaDocumento, se.estado, iu.nomeCompleto
                    FROM igsis_agenda AS age
                    INNER JOIN ig_evento eve on age.idEvento = eve.idEvento
                    INNER JOIN igsis_pedido_contratacao AS ped on age.idEvento = ped.idEvento 
                    LEFT JOIN ig_instituicao ii on eve.idInstituicao = ii.idInstituicao
                    LEFT JOIN sis_estado se on ped.estado = se.idEstado    
                    LEFT JOIN ig_usuario iu on ped.idContratos = iu.idUsuario
                    WHERE data BETWEEN '$inicio' AND '$final' $operador
                    AND ped.estado NOT IN (1, 11, 12)
                    AND eve.dataEnvio IS NOT NULL
                    AND ped.publicado = '1'
                    AND eve.publicado = '1'
                    GROUP BY age.idEvento, data 
                    ORDER BY data";
            $query_evento = mysqli_query($con, $sql_evento);
            $num = mysqli_num_rows($query_evento);

            $i = 0;
            while ($lista = mysqli_fetch_array($query_evento)) {
                $x[$i]['id'] = $lista['idPedidoContratacao'];
                $x[$i]['NumeroProcesso'] = $lista['NumeroProcesso'];
                $x[$i]['objeto'] = retornaTipo($lista['ig_tipo_evento_idTipoEvento']) . " - " . $lista['nomeEvento'];
                if ($lista['tipoPessoa'] == 1) {
                    $pessoa = recuperaDados("sis_pessoa_fisica", $lista['idPessoa'], "Id_PessoaFisica");
                    $x[$i]['proponente'] = $pessoa['Nome'];
                    $x[$i]['tipo'] = "Física";
                } else {
                    $pessoa = recuperaDados("sis_pessoa_juridica", $lista['idPessoa'], "Id_PessoaJuridica");
                    $x[$i]['proponente'] = $pessoa['RazaoSocial'];
                    $x[$i]['tipo'] = "Jurídica";
                }
                $x[$i]['local'] = substr(listaLocais($lista['idEvento']), 1);
                $x[$i]['instituicao'] = $lista['sigla'];
                $x[$i]['periodo'] = retornaPeriodo($lista['idEvento']);
                $x[$i]['valor'] = $lista['valor'];
                $x[$i]['pendencia'] = $lista['pendenciaDocumento'];
                $x[$i]['status'] = $lista['estado'];
                $x[$i]['operador'] = $lista['nomeCompleto'];
                $i++;
            }
            $x['num'] = $i;
            if ($num > 0) {
                ?>
                <br/>
                <br/>
                <section id="list_items">
                    <div class="container">
                        <h3>Resultado da busca</3>
                        <h5>Foram encontrados <?php echo $x['num']; ?> pedidos de contratação.</h5>
                        <h5><a href="?perfil=contratos&p=frm_busca_periodo_operador">Fazer outra busca</a></h5>
                        <div class="table-responsive list_info">
                            <table class="table table-condensed">
                                <thead>
                                <tr class="list_menu">
                                    <td>Codigo do Pedido</td>
                                    <td>Número Processo</td>
                                    <td>Proponente</td>
                                    <td>Tipo</td>
                                    <td>Objeto</td>
                                    <td width="20%">Local</td>
                                    <td>Instituição</td>
                                    <td>Periodo</td>
                                    <td>Valor</td>
                                    <td>Pendências</td>
                                    <td>Status</td>
                                    <td>Operador</td>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $data = date('Y');
                                for ($h = 0; $h < $x['num']; $h++) {
                                    if ($x[$h]['tipo'] == 'Física') {
                                        echo "<tr><td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapf&id_ped=" . $x[$h]['id'] . "'>" . $x[$h]['id'] . "</a></td>";
                                    } else {
                                        echo "<tr><td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapj&id_ped=" . $x[$h]['id'] . "'>" . $x[$h]['id'] . "</a></td>";
                                    }
                                    echo '<td class="list_description">' . $x[$h]['NumeroProcesso'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['proponente'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['tipo'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['objeto'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['local'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['instituicao'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['periodo'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['valor'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['pendencia'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['status'] . '</td> ';
                                    echo '<td class="list_description">' . $x[$h]['operador'] . '</td> </tr>';
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
                            <form method="POST" action="?perfil=contratos&p=frm_busca_periodo_operador"
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
                        <form method="POST" action="?perfil=contratos&p=frm_busca_periodo_operador"
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