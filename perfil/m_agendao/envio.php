<?php
$idEvento = (isset($_POST['idEvento'])) ? $_POST['idEvento'] : null;
$habilita = 0;

if(isset($_POST['finalizar']))
{
    $con = bancoMysqli();
    $datetime = date("Y-m-d H:i:s");
    $idEvento = $_POST['idEvento'];
    $sql_atualiza_evento = "UPDATE ig_evento SET dataEnvio = '$datetime', statusEvento = 'Enviado' WHERE idEvento = '$idEvento'";
    if(mysqli_query($con,$sql_atualiza_evento)) {
        gravarLog($sql_atualiza_evento);
        atualizarAgenda($idEvento);
        $sql_data_envio = "INSERT INTO `ig_data_envio`(`idEvento`, `dataEnvio`) VALUES ('$idEvento', '$datetime')";
        if(mysqli_query($con, $sql_data_envio)){
            $mensagem = "Evento enviado com sucesso!";
            $habilita = 1;
        }
        else{
            $mensagem = "Erro ao enviar! Por favor tente novamente!";
            $habilita = 0;
        }
    }
    else{
        $mensagem = "Erro ao enviar. Por favor tente novamente!";
        $habilita = 0;
    }
}
include "include/menu.php";
?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Cadastro de Evento</h3>
                    <h4><?= $mensagem ? $mensagem : NULL ?></h4>
                </div>
            </div>
        </div>
        <?php
        if($habilita == 1) {
            ?>
            <div class="row">
                <div class="col-md-offset-1 col-md-10">
                    <div class="col-md-4">
                        <form method="POST" action="?perfil=agendao&p=evento_cadastra" class="form-horizontal"
                              role="form">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" value="Cadastrar novo">
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="POST" action="?perfil=agendao&p=exportar_filtra" class="form-horizontal" role="form">
                            <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" value="Exportar Excel">
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="POST" target="_blank" action="?perfil=agenda" class="form-horizontal" role="form">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" value="Consultar Agenda">
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</section>