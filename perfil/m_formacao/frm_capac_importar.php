<?php
$con = bancoMysqliProponente();

unset($_SESSION['id']);
unset($_SESSION['idCapacPf']);
unset($_SESSION['proponente']);
unset($_SESSION['programa']);
unset($_SESSION['ano']);

if (isset($_GET['erro']))
{
    $mensagem = "<span style='color: #ef0000'>Digite o código, nome ou programa a ser pesquisado.</span>";
}

include 'includes/menu.php';
?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <h3>BUSCAR CADASTROS NO CAPAC</h3>
        </div>

        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h5><?= (isset($mensagem)) ? $mensagem : null ?></h5>

                <form method="POST" action="?perfil=formacao&p=frm_capac_importar_resultado" class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Código do cadastro no CAPAC</strong><br/>
                            <input type="text" name="idCapacPf" class="form-control" placeholder="Insira o Código do Cadastro">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Nome do Proponente</strong><br/>
                            <input type="text" name="proponente" class="form-control" placeholder="Insira nome do proponente" >
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Programa</strong><br/>
                            <select class="form-control" name="programa" id="inputSubject" >
                                <option value="">Selecione...</option>
                                <option value="">Todos</option>
                                <?php
                                $sql = "SELECT * FROM `tipo_formacao`";
                                $query = mysqli_query($con,$sql);
                                while($option = mysqli_fetch_row($query))
                                {
                                    echo "<option value='".$option[0]."'>".$option[1]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Ano de inscrição:</strong><br/>
                            <input type="text" name="ano" class="form-control" placeholder="<?= date('Y') ?>" value="<?= date('Y') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Pesquisar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>