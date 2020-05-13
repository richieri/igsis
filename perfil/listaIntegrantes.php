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
                        </tr>
                        <?php foreach ($eventos as $evento): ?>
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
                                    <th colspan="2" class="text-center">PEDIDOS</th>
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