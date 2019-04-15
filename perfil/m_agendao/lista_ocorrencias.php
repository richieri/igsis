<?php
$con = bancoMysqli();
$idUsuario = $_SESSION['idUsuario'];
$idEvento = $_POST['idEvento'];

$sqlConsultaOcorrencias = "SELECT * FROM ig_ocorrencia
                            WHERE idEvento = '$idEvento' AND publicado = 1";
$ocorrencias = $con->query($sqlConsultaOcorrencias)->fetch_all(MYSQLI_ASSOC);

if(isset($_POST['apagar']))
{
    $idApagar = $_POST['apagar'];
    $sqlApagarOcorrencia = "UPDATE ig_ocorrencia SET publicado = 0 WHERE idEvento = $idApagar";

    if($con->query($sqlApagarOcorrencia)) {
        $mensagem = "Ocorrência apagada com sucesso!";
        gravarLog($sqlApagarOcorrencia);
    } else {
        $mensagem = "Erro ao apagar a ocorrência...";
    }
}

$queryOcorrencias = $con->query($sqlConsultaOcorrencias);
$numOcorrencias = $queryOcorrencias->num_rows;
$ocorrencia = $queryOcorrencias->fetch_all(MYSQLI_ASSOC);

include "include/menu.php";
?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-hide">
                    <h2>Ocorrencias</h2>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-offset-1 col-md-10"><hr/></div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="table-responsive list_info">
                    <table class='table table-condensed'>
                        <thead>
                            <tr class='list_menu'>
                                <td>Ocorrência</td>
                                <td width='10%'></td>
                                <td width='10%'></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($numOcorrencias) {
                            foreach ($ocorrencias as $ocorrencia) { ?>
                                <tr>
                                    <td>Aeoo</td>
                                    <td class='list_description'>
                                        <form action="?perfil=agendao&p=ocorrencia_cadastra" method="post">
                                            <input type="hidden" name="idOcorrencia" value="<?=$ocorrencia['idOcorrencia']?>">
                                            <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                                            <input class='btn btn-theme' type="submit" value="Editar">
                                        </form>
                                    </td><td class='list_description'>
                                        <button id="btnApagar" class='btn btn-theme' type='button' data-toggle='modal'
                                                data-target='#confirmApagar'
                                        <!--onclick="confirmApagar('<? /*=$evento['idEvento']*/ ?>', '<? /*=$evento['nomeEvento']*/ ?>')-->
                                        ;">
                                        Apagar
                                        </button>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <th colspan="2" class="text-center">Não existe ocorrências cadastradas</th>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-4 col-md-10">
                <div class="col-md-5 text-center">
                    <form method="POST" action="?perfil=agendao&p=ocorrencia_cadastra" class="form-horizontal" role="form">
                        <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                        <input type="submit" class="btn btn-theme btn-lg btn-block" value="Cadastrar Nova Ocorrência">
                    </form>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="col-md-2 pull-left">
                    <form method="POST" action="?perfil=agendao&p=produtor_cadastra" class="form-horizontal" role="form">
                        <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                        <input type="submit" class="btn btn-theme btn-lg btn-block" value="Voltar">
                    </form>
                </div>
                <div class="col-md-2 pull-right">
                    <form method="POST" action="#" class="form-horizontal" role="form">
                        <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                        <input type="submit" class="btn btn-theme btn-lg btn-block" value="Avançar">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmação de Exclusão -->
    <div class="modal fade" id="confirmApagar" role="dialog" aria-labelledby="confirmApagarLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Aeoo?</h4>
                </div>
                <div class="modal-body">
                    <p>Confirma?</p>
                </div>
                <div class="modal-footer">
                    <form action="?perfil=agendao&p=lista_eventos" method="post">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="hidden" name="apagar" id="idEvento">
                        <button type="submit" class="btn btn-danger" id="confirm">Remover</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Confirmação de Exclusão -->
</section>

<!--<script>
    function confirmApagar(id, nomeEvento) {
        document.querySelector('#idEvento').value = id;

        var titulo = document.querySelector('.modal-title');
        titulo.innerHTML = "Remover o Evento";

        var mensagem = document.querySelector('.modal-body p');
        mensagem.innerHTML = "Deseja realmente remover o evento " + nomeEvento + "?";
    }
</script>-->