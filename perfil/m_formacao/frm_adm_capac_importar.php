<?php
$con = bancoMysqliProponente();

unset($_SESSION['id']);
unset($_SESSION['idCapacPf']);
unset($_SESSION['proponente']);
unset($_SESSION['programa']);
unset($_SESSION['ano']);
unset($_SESSION['tipoCadastro']);
unset($_SESSION['pesquisaGeral']);

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

                <button type="button" class="btn btn-theme btn-lg btn-block" data-toggle="collapse" data-target="#formacao">Cadastros na área Formação</button>
                <div id="formacao" class="collapse">
                    <form method="POST" action="?perfil=formacao&p=frm_adm_capac_importar_resultado" class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Ano de inscrição:</strong><br/>
                                <input type="text" name="ano" class="form-control" placeholder="<?= date('Y') ?>" value="<?= date('Y') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Nome do Proponente</strong><br/>
                                <input type="text" name="proponente" class="form-control" placeholder="Insira nome do proponente" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Tipo do Cadastro:</strong><br/>
                                <select class="form-control" name="tipoCadastro" id="tipoCadastro" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Somente Cadastros Válidos</option>
                                    <option value="2">Todos os cadastros iniciados</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8">
                                <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Visualizar">
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <button type="button" class="btn btn-theme btn-lg btn-block" data-toggle="collapse" data-target="#geral">Todos Cadastros do Capac</button>
                <div id="geral" class="collapse">
                    <form method="POST" action="?perfil=formacao&p=frm_adm_capac_importar_resultado" class="form-horizontal" role="form">

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Nome do Proponente</strong><br/>
                                <input type="text" name="proponente" class="form-control" placeholder="Insira nome do proponente" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8">
                                <input type="hidden" name="pesquisaGeral">
                                <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Visualizar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>