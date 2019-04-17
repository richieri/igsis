<?php
    include 'includes/menu.php';
?>

<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <div class="sub-title">
                <h2>RELATÓRIO DE PEDIDOS DE CONTRATAÇÃO</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form class="form-horizontal" role="form" action="../pdf/formacao_pedidoscontratacao.php" method="get">
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-6"><strong>Pedidos para o ano: *</strong>
                            <input type="text" class="form-control" id="ano" name="ano" maxlength="4" placeholder="Exemplo: 2019" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-6"><strong><br/></strong>
                            <input type="submit" class="btn btn-theme btn-md btn-block" value="Gerar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>