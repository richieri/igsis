<?php
include "../include/menuAdmin.php";
$con = bancoMysqli();

if(isset($_GET['executar'])) {
    $eventos = $con->query("SELECT * FROM ig_evento WHERE projetoEspecial IN (92, 93, 94, 95) AND publicado = 1")->fetch_all(MYSQLI_ASSOC);
}
?>
<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Bem-vindo(a) à IGSIS!</h3>
                    <p>&nbsp;</p>
                    <h2>Insere integrantes na tabela correta</h2>
                    <p>&nbsp;</p>
                    <h6>Através desse módulo é possível pesquisar dados sobre eventos, detalhes e status de pedidos de contratação, pessoas físicas e jurídicas, instituições, usuários e espaços participantes da IGSIS.</h6>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <?php if (!isset($_GET['executar'])): ?>
                    <div class="text-hide">
                        <h2>Iniciar Execução?</h2>
                        <p>&nbsp;</p>
                        <a href="?perfil=listaIntegrantes&executar=1" class="btn btn-block btn-primary">SIM</a>
                    </div>
                <?php else: ?>
                    <table border="">
                        <tr>
                            <th class="text-center">idEvento</th>
                            <th class="text-center">Nome do Evento</th>
                            <th class="text-center">Numero de ocorrencias</th>
                        </tr>
                        <?php foreach ($eventos as $evento): ?>
                            <?php
                                $queryOcorrencias = $con->query("SELECT * FROM ig_ocorrencia WHERE idEvento = '{$evento['idEvento']}' AND publicado = '1'");
                                $nOcorrencias = $queryOcorrencias->num_rows;
                            ?>
                            <tr>
                                <th colspan="2" class="text-center">EVENTO</th>
                            </tr>
                            <tr>
                                <td><?=$evento['idEvento']?></td>
                                <td><?=$evento['nomeEvento']?></td>
                            </tr>
                            <?php
                                $queryPedido = $con->query("SELECT * FROM igsis_pedido_contratacao WHERE idEvento = '{$evento['idEvento']}' AND publicado = '1'");
                                if ($queryPedido->num_rows > 0):
                                    $pedidos = $queryPedido->fetch_all(MYSQLI_ASSOC);
                            ?>
                                <tr>
                                    <th colspan="2" class="text-center" bgcolor="#e6e6fa">PEDIDOS</th>
                                </tr>
                                <tr>
                                    <th class="text-center">idPedido</th>
                                    <th class="text-center">Integrantes</th>
                                </tr>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td><?=$pedido['idPedidoContratacao']?></td>
                                        <td><?=$pedido['integrantes']?></td>
                                    </tr>
                                    <?php
                                        $queryIntegrantes = $con->query("SELECT * FROM igsis_grupos WHERE idPedido = {$pedido['idPedidoContratacao']} AND publicado = 1");
                                        if ($queryIntegrantes->num_rows > 0):
                                            $integrantes = $queryIntegrantes->fetch_all(MYSQLI_ASSOC);
                                        ?>
                                            <tr>
                                                <th colspan="2" class="text-center" bgcolor="aqua">INTEGRANTES</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">CPF</th>
                                                <th class="text-center">Nome do Integrante</th>
                                            </tr>
                                            <?php foreach ($integrantes as $integrante): ?>
                                                <tr>
                                                    <td><?=$integrante['cpf']?></td>
                                                    <td><?=$integrante['nomeCompleto']?></td>
                                                </tr>

                                                <?php
                                                    if($nOcorrencias) {
                                                        $ocorrencias = $queryOcorrencias->fetch_all(MYSQLI_ASSOC);
                                                        foreach ($ocorrencias as $ocorrencia) {
                                                            if ($ocorrencia['dataInicio'] != '0000-00-00') {
                                                                $sqlInsert = "INSERT INTO ig_evento_integrante (idEvento, idPedidoContratacao, cpf, data_apresentacao) VALUES 
                                                                        ('{$evento['idEvento']}', '{$pedido['idPedidoContratacao']}', '{$integrante['cpf']}', '{$ocorrencia['dataInicio']}')";
                                                                $con->query($sqlInsert);
                                                            } else {
                                                                $sqlInsert = "INSERT INTO ig_evento_integrante (idEvento, idPedidoContratacao, cpf) VALUES 
                                                                        ('{$evento['idEvento']}', '{$pedido['idPedidoContratacao']}', '{$integrante['cpf']}')";
                                                                $con->query($sqlInsert);
                                                            }
                                                        }
                                                    } else {
                                                        $sqlInsert = "INSERT INTO ig_evento_integrante (idEvento, idPedidoContratacao, cpf) VALUES 
                                                                        ('{$evento['idEvento']}', '{$pedido['idPedidoContratacao']}', '{$integrante['cpf']}')";
                                                        $con->query($sqlInsert);
                                                    }
                                                ?>
                                            <?php endforeach ?>
                                        <?php endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>