<?php
$con = bancoMysqli();
$idUsuario = $_SESSION['idUsuario'];

$sqlConsultaEventos = "SELECT * FROM ig_evento
                        WHERE publicado = 1
                        AND (idUsuario = '$idUsuario') 
                        AND dataEnvio IS NULL ORDER BY idEvento DESC";
$eventos = $con->query($sqlConsultaEventos)->fetch_all(MYSQLI_ASSOC);

if(isset($_POST['apagar']))
{
    $idApagar = $_POST['apagar'];
    $sqlApagarEvento = "UPDATE ig_evento SET publicado = 0 WHERE idEvento = $idApagar";

    if($con->query($sqlApagarEvento)) {
        $mensagem = "Evento apagado com sucesso!";
        gravarLog($sqlApagarEvento);
    } else {
        $mensagem = "Erro ao apagar o evento...";
    }
}

$sqlConsultaEventos = "SELECT * FROM ig_evento
                        WHERE publicado = 1
                        AND (idUsuario = '$idUsuario') 
                        AND dataEnvio IS NULL ORDER BY idEvento DESC";
$eventos = $con->query($sqlConsultaEventos)->fetch_all(MYSQLI_ASSOC);

include "include/menu.php";
?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-hide">
                    <h2>Eventos gravados mas não enviados</h2>
                    <h4>Selecione o evento para carregar.</h4>
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
                                <td width='5%'>ID evento</td>
                                <td>Nome do evento</td>
                                <td>Tipo de evento</td>
                                <td>Data/Período</td>
                                <td>Status do evento</td>
                                <td width='10%'></td>
                                <td width='10%'></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($eventos as $evento) {
                                $tipoEvento = recuperaDados('ig_tipo_evento', $evento['ig_tipo_evento_idTipoEvento'], 'idTipoEvento')['tipoEvento']
                            ?>
                                <tr>
                                    <td class="list-description"><?=$evento['idEvento']?></td>
                                    <td class="list-description"><?=$evento['nomeEvento']?></td>
                                    <td class="list-description"><?=$tipoEvento?></td>
                                    <td class="list-description"><?="a"?></td>
                                    <td class="list-description"><?=$evento['statusEvento']?></td>
                                    <td class='list_description'>
                                        <form method='POST' action='?perfil=agendao&p=evento_cadastra'>
                                            <input type='hidden' name='idEvento' value='<?=$evento['idEvento']?>'>
                                            <input type ='submit' class='btn btn-theme btn-block' value='carregar'>
                                        </form>
                                    </td>
                                    <?php
                                    if ($evento['ocupacao'] == 1) {
                                    ?>
                                        <td class='list_description'>
                                            <button id="btnApagar" class='btn btn-theme' type='button' data-toggle='modal' data-target='#confirmApagar' onclick="confirmApagar('<?=$evento['idEvento']?>', '<?=$evento['nomeEvento']?>');">
                                                Apagar
                                            </button>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- TODO: Incluir um botão de avançar após o usuário gravar -->
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

<script>
    function confirmApagar(id, nomeEvento) {
        document.querySelector('#idEvento').value = id;

        var titulo = document.querySelector('.modal-title');
        titulo.innerHTML = "Remover o Evento";

        var mensagem = document.querySelector('.modal-body p');
        mensagem.innerHTML = "Deseja realmente remover o evento " + nomeEvento + "?";
    }
</script>