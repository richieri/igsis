<?php
$con = bancoMysqliProponente();

unset($_SESSION['id']);
unset($_SESSION['idCapacPf']);
unset($_SESSION['proponente']);
unset($_SESSION['programa']);
unset($_SESSION['ano']);

if (isset($_GET['erro']))
{
    $mensagem = "<span style='color: #ef0000'>É necessario ao menos um item na pesquisa. Tente novamente.</span>";
}

include 'includes/menu_administrativo.php';
?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <h3>LISTAR CADASTRADOS NO CAPAC</h3>
        </div>

        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h5><?= (isset($mensagem)) ? $mensagem : null ?></h5>

                <form method="POST" action="?perfil=formacao&p=frm_adm_capac_importar_resultado" class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Ano de inscrição:</strong><br/>
                            <input type="text" name="ano" class="form-control" placeholder="<?= date('Y') ?>" value="<?= date('Y') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Visualizar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>