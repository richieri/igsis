<?php include 'includes/menu.php';
?>
<section id="services" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <h3>Busca por operador</h3>
                </div>
            </div>
        </div>
        <div class="row">
        <form method="POST" action="?perfil=contratos&p=frm_busca_operador_resultado" class="form-horizontal" role="form">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <label>Operador do Contrato</label>
                    <select class="form-control" name="operador" required>
                        <option value="">Selecione...</option>
                        <?php  geraOpcaoContrato(""); ?>
                    </select>
                </div>
            </div>
            <br />
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Pesquisar">
                </div>
            </div>
        </form>
        </div>
    </div>
</section>