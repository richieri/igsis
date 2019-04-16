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
    $sqlApagarOcorrencia = "UPDATE ig_ocorrencia SET publicado = 0 WHERE idOcorrencia = $idApagar";

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
                    <?php listaOcorrencias($idEvento, "?perfil=agendao&p=ocorrencia_cadastra", "?perfil=agendao&p=lista_ocorrencias"); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-4 col-md-10">
                <div class="col-md-5 text-center">
                    <form method="POST" action="?perfil=agendao&p=ocorrencia_cadastra" class="form-horizontal" role="form">
                        <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
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
                        <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
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
                    <h4 class="modal-title">Remover?</h4>
                </div>
                <div class="modal-body">
                    <p>Deseja Realmente remover esta ocorrência?</p>
                </div>
                <div class="modal-footer">
                    <form action="?perfil=agendao&p=lista_ocorrencias" method="post">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="hidden" name="apagar" id="idOcorrencia">
                        <input type="hidden" name="idEvento" id="idEvento">
                        <button type="submit" class="btn btn-danger" id="confirm">Remover</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Confirmação de Exclusão -->
</section>

<script>
    var btnsApagar = document.querySelectorAll('#btnApagar');
    btnsApagar.forEach(function (btnApagar) {
        btnApagar.addEventListener('click', function (event) {
            event.preventDefault();
            var idOcorrencia = btnApagar.getAttribute("data-idOcorrencia");
            var idEvento = btnApagar.getAttribute("data-idEvento");
            confirmApagar(idOcorrencia, idEvento);
            $("#confirmApagar").modal();
        });
    });

    function confirmApagar(idOcorrencia, idEvento) {
        document.querySelector('#idOcorrencia').value = idOcorrencia;
        document.querySelector('#idEvento').value = idEvento;

        var titulo = document.querySelector('.modal-title');
        titulo.innerHTML = "Remover Ocorrência";
    }
</script>