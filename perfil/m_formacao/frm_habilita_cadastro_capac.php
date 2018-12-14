<?php
    $con = bancoMysqliProponente();

    include 'includes/menu_administrativo.php';

    if (isset($_POST['cadastro']))
    {
        $situacao = $_POST['situacao'];
        if ($situacao == 1)
        {
            $status = ['0', 'BLOQUEADO'];
            $anoReferencia = "";
        }
        else
        {
            $ano = $_POST['ano'];
            $status = ['1', 'LIBERADO'];
            $anoReferencia = ", `ano` = '$ano'";
        }

        $sqlCadastro = "UPDATE `formacao_cadastro` SET `situacao` = '".$status[0]."', `descricao` = '".$status[1]."'$anoReferencia WHERE `id` = '1'";
        $queryCadastro = $con->query($sqlCadastro);

        if ($queryCadastro)
        {
            gravarLog($sqlCadastro);
        }
    }

$formacaoCadastro = $con->query('SELECT * FROM `formacao_cadastro`')->fetch_assoc();

    $situacao = $formacaoCadastro['situacao'];
    $descricao = $formacaoCadastro['descricao'];
    $anoCadastro = $formacaoCadastro['ano'];

    switch ($situacao)
    {
        case 1:
            $msgStatus = "<span style='color: #ef0000'>BLOQUEAR</span>";
            break;
        case 0:
            $msgStatus = "<span style='color: #00a201'>LIBERAR</span>";
            break;
        default:
            $status = null;
            $msgStatus = null;
            break;
    }

?>

<section id="services" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="alert <?= ($situacao == 1) ? 'alert-success' : 'alert-danger' ?>">
                    <p><strong>CADASTRO NO CAPAC: <?= $descricao ?></strong></p>
                </div>
                <div class="row">
                    <h6>Deseja <?=$msgStatus?> o cadastro de artistas para formação no CAPAC?</h6>
                </div>

                <div class="col-md-offset-2 col-md-8">
                    <form method='POST' action='?perfil=formacao&p=frm_habilita_cadastro_capac' enctype='multipart/form-data'>
                        <input type="hidden" name="situacao" value="<?=$situacao?>">

                        <?php if ($situacao == 0) { ?>
                            <div class="row">
                                <div class="form-group col-md-offset-4 col-md-4">
                                    <strong>Ano Referência do cadastro:</strong><br/>
                                    <input value="<?= $anoCadastro ?>" type="text" name="ano" class="form-control" maxlength="4" placeholder="AAAA">
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <div class="form-group">
                                <input type='submit' name='cadastro' class='btn btn-theme btn-lg btn-block for' value='SIM'
                                       onclick="return confirm('Tem certeza que deseja realizar essa ação?')">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
