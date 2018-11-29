<?php
    $con = bancoMysqliProponente();

    include 'includes/menu_administrativo.php';

    if (isset($_POST['cadastro']))
    {
        $situacao = $_POST['situacao'];
        if ($situacao == 1)
        {
            $status = ['0', 'BLOQUEADO'];
        }
        else
        {
            $status = ['1', 'LIBERADO'];
        }

        $sqlCadastro = "UPDATE `formacao_cadastro` SET `situacao` = '".$status[0]."', `descricao` = '".$status[1]."' WHERE `id` = '1'";
        $queryCadastro = $con->query($sqlCadastro);

        if ($queryCadastro)
        {
            gravarLog($sqlCadastro);
        }
    }

$formacaoCadastro = $con->query('SELECT * FROM `formacao_cadastro`')->fetch_assoc();

    $situacao = $formacaoCadastro['situacao'];
    $descricao = $formacaoCadastro['descricao'];

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

                <div class="col-md-offset-4 col-md-4">
                    <form method='POST' action='?perfil=formacao&p=frm_habilita_cadastro_capac' enctype='multipart/form-data'>
                        <input type="hidden" name="situacao" value="<?=$situacao?>">
                        <input type='submit' name='cadastro' class='btn btn-theme btn-lg btn-block' value='SIM' onclick="return confirm('Tem certeza que deseja realizar essa ação?')">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
