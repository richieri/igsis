<?php

unset($_SESSION['idEvento']);
unset($_SESSION['cinema']);
unset($_SESSION['subEvento']);

$idUsuario = $_SESSION['idUsuario'];
$idInstituicao = $_SESSION['idInstituicao'];

$con = bancoMysqli();
$idEvento = (isset($_POST['idEvento'])) ? $_POST['idEvento'] : null;

if (isset($_POST['cadastra'])) {
    $nomeEvento = $_POST['nomeEvento'];
    $idProjetoEspecial = $_POST['projetoEspecial'];
    $artistas = $_POST['artistas'];
    $idTipoEvento = $_POST['tipoEvento'];
    $idFaixaEtaria = $_POST['faixaEtaria'];
    $sinopse = $_POST['sinopse'];
    $links = $_POST['linksCom'];
    $nApresentacao = $_POST['nApresentacao'];
    $espacoPublico = $_POST['espacoPublico'];
    $fomento = $_POST['fomento'];
    $tipoFomento = $_POST['tipoFomento'] ?? 0;

    $sqlInsereEvento = "INSERT INTO ig_evento (nomeEvento, projetoEspecial, fichaTecnica, ig_tipo_evento_idTipoEvento, faixaEtaria, sinopse, linksCom, idUsuario, idInstituicao, statusEvento, ocupacao, numero_apresentacao, espaco_publico, fomento, tipo_fomento, publicado)
                        VALUES ('$nomeEvento', '$idProjetoEspecial', '$artistas', '$idTipoEvento', '$idFaixaEtaria', '$sinopse', '$links', '$idUsuario', '$idInstituicao', 'Em elaboração', '1', '$nApresentacao', '$espacoPublico', '$fomento', '$tipoFomento','1')";
    if ($con->query($sqlInsereEvento)) {
        $idEvento = $con->insert_id;

        if (isset($_POST['linguagem'])) {
            atualizaRelacionamentoEvento('igsis_evento_linguagem', $idEvento, $_POST['linguagem']);
        }

        if (isset($_POST['representatividade'])) {
            atualizaRelacionamentoEvento('igsis_evento_representatividade', $idEvento, $_POST['representatividade']);
        }

        $mensagem = "Evento Cadastrado com Sucesso!";
        gravarLog($sqlInsereEvento);
    }
}

if (isset($_POST['atualiza'])) {
    $idEvento = $_POST['idEvento'];
    $nomeEvento = $_POST['nomeEvento'];
    $idProjetoEspecial = $_POST['projetoEspecial'];
    $artistas = $_POST['artistas'];
    $idTipoEvento = $_POST['tipoEvento'];
    $idFaixaEtaria = $_POST['faixaEtaria'];
    $sinopse = $_POST['sinopse'];
    $links = $_POST['linksCom'];
    $nApresentacao = $_POST['nApresentacao'];
    $espacoPublico = $_POST['espacoPublico'];
    $fomento = $_POST['fomento'];
    $tipoFomento = $_POST['tipoFomento'] ?? 0;

    $sqlAtualizaEvento = "UPDATE ig_evento SET 
                        nomeEvento = '$nomeEvento',
                        projetoEspecial = '$idProjetoEspecial',
                        fichaTecnica = '$artistas',
                        ig_tipo_evento_idTipoEvento = '$idTipoEvento',
                        faixaEtaria = '$idFaixaEtaria',
                        sinopse = '$sinopse',
                        linksCom = '$links',
                        numero_apresentacao = '$nApresentacao',
                        espaco_publico = '$espacoPublico',
                        fomento = '$fomento',
                        tipo_fomento = '$tipoFomento'
                        WHERE idEvento = '$idEvento'";

    if ($con->query($sqlAtualizaEvento)) {
        if (isset($_POST['linguagem'])) {
            atualizaRelacionamentoEvento('igsis_evento_linguagem', $idEvento, $_POST['linguagem']);
        }

        if (isset($_POST['representatividade'])) {
            atualizaRelacionamentoEvento('igsis_evento_representatividade', $idEvento, $_POST['representatividade']);
        }

        $mensagem = "Evento Atualizado com Sucesso!";
        gravarLog($sqlAtualizaEvento);
    }
}

$campo = recuperaDados("ig_evento", $idEvento, "idEvento");

include "include/menu.php";
?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-hide">
                    <h3>Evento - Informações Gerais</h3>
                    <h4><?php if (isset($mensagem)) {
                            echo $mensagem;
                        } ?></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=evento_cadastra" class="form-horizontal" role="form">
                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="nomeEvento">Nome do evento *</label>
                            <input type="text" name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?php echo $campo['nomeEvento'] ?>"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="projetoEspecial">Projeto especial *</label>
                            <select class="form-control" id="projetoEspecial" name="projetoEspecial" required>
                                <option value="">Selecione...</option>
                                <?php echo geraOpcao("ig_projeto_especial", $campo['projetoEspecial'], "") ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="artistas">Artistas *</label>
                            <textarea id="artistas" name="artistas" class="form-control"
                                      rows="5"><?php echo $campo["fichaTecnica"] ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-1 col-md-5">
                            <label for="espacoPublico">Espaço público? *</label><br>
                            <input type="radio" name="espacoPublico" id="espacoPublico"
                                   value="1" <?= $campo['espaco_publico'] == 1 ? 'checked' : NULL ?>> Sim
                            <input type="radio" name="espacoPublico" id="espacoPublico"
                                   value="0" <?= $campo['espaco_publico'] == 0 ? 'checked' : NULL ?>> Não
                        </div>

                        <div class="col-md-5">
                            <label for="nApresentacao">Quantidade de apresentação *</label>
                            <input type="number" name="nApresentacao" id="nApresentacao" class="form-control"
                                   value="<?= $campo['numero_apresentacao'] ?>" min='1' required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-5">
                                <label for="fomento">É fomento/programa? *</label><br>
                                <input type="radio" name="fomento" class="fomento" id="sim" value="1" <?= $campo['fomento'] == 1 ? 'checked' : NULL ?>> Sim
                                <input type="radio" name="fomento" class="fomento" id="nao" value="0" <?= $campo['fomento'] == 0 ? 'checked' : NULL ?>> Não
                            </div>

                            <div class="col-md-5">
                                <label for="espacoPublico">Selecione o fomento/programa</label><br>
                                <select name="tipoFomento" id="tipoFomento" class="form-control">
                                    <option value="">Selecione o fomento/programa da SMC</option>
                                    <?php
                                        geraOpcaoPadrao('fomento', $campo['tipo_fomento']);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label>Tipo de Evento *</label>
                            <select class="form-control" name="tipoEvento" id="inputSubject" required>
                                <option value=""></option>
                                <?php echo geraOpcao("ig_tipo_evento", $campo['ig_tipo_evento_idTipoEvento'], "") ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                            <label>Ações (Expressões Artístico-culturais) * <i>(multipla escolha) </i></label>
                            <button class='btn btn-default' type='button' data-toggle='modal'
                                    data-target='#modalAcoes' style="border-radius: 30px;">
                                <i class="fa fa-question-circle"></i></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <?php
                            geraCheckboxEvento('igsis_linguagem', 'linguagem', 'igsis_evento_linguagem', $idEvento);
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                            <label>Público (Representatividade e Visibilidade Sócio-cultural)* <i>(multipla
                                    escolha) </i></label>
                            <button class='btn btn-default' type='button' data-toggle='modal'
                                    data-target='#modalPublico' style="border-radius: 30px;">
                                <i class="fa fa-question-circle"></i></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <?php
                            geraCheckboxEvento('igsis_representatividade', 'representatividade', 'igsis_evento_representatividade', $idEvento);
                            ?>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label>Classificação/indicação etária *</label>
                            <select class="form-control" name="faixaEtaria" id="faixaEtaria" required>
                                <option value="">Selecione...</option>
                                <?php echo geraOpcao("ig_etaria", $campo['faixaEtaria'], "") ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="sinopse">Sinopse *</label>
                            <textarea id="sinopse" name="sinopse" class="form-control" rows="6"
                                      placeholder="Texto para divulgação e sob editoria da area de comunicação. Não ultrapassar 400 caracteres."
                                      required><?php echo $campo["sinopse"] ?></textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="links">Links de divulgação *</label>
                            <textarea id="links" name="linksCom" class="form-control" rows="3"
                                      placeholder="Links para auxiliar a divulgação. Site oficinal, vídeos, clipping, artigos, etc "
                                      required><?php echo $campo["linksCom"] ?></textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                            <input type="submit" class="btn btn-theme btn-lg btn-block"
                                   name="<?= ($idEvento == null) ? "cadastra" : "atualiza" ?>" value="Gravar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <?php
        if ($idEvento != null) {
            ?>
            <div class="row col-md-offset-1 col-md-10">
                <div class="col-md-2 pull-left">
                    <form method="POST" action="?perfil=agendao&p=lista_eventos" class="form-horizontal" role="form">
                        <input type="submit" class="btn btn-theme btn-lg btn-block" value="Voltar">
                    </form>
                </div>
                <div class="col-md-2 pull-right">
                    <form method="POST" action="?perfil=agendao&p=produtor_cadastra" class="form-horizontal"
                          role="form">
                        <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                        <input type="submit" class="btn btn-theme btn-lg btn-block" value="Avançar">
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="modal fade" id="modalAcoes" role="dialog" aria-labelledby="lblmodalAcoes" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Ações (Expressões Artístico-culturais)</h4>
                </div>
                <div class="modal-body" style="text-align: left;">
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Ação</th>
                            <th>Descrição</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sqlConsultaLinguagens = "SELECT linguagem, descricao FROM igsis_linguagem WHERE publicado = '1' ORDER BY 1";
                        foreach ($con->query($sqlConsultaLinguagens)->fetch_all(MYSQLI_ASSOC) as $linguagem) {
                            ?>
                            <tr>
                                <td><?= $linguagem['linguagem'] ?></td>
                                <td><?= $linguagem['descricao'] ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPublico" role="dialog" aria-labelledby="lblmodalPublico" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Público (Representatividade e Visibilidade Sócio-cultural)</h4>
                </div>
                <div class="modal-body" style="text-align: left;">
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Representatividade</th>
                            <th>Descrição</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sqlConsultaLinguagens = "SELECT representatividade_social, descricao FROM igsis_representatividade WHERE publicado = '1' ORDER BY 1";
                        foreach ($con->query($sqlConsultaLinguagens)->fetch_all(MYSQLI_ASSOC) as $linguagem) {
                            ?>
                            <tr>
                                <td><?= $linguagem['representatividade_social'] ?></td>
                                <td><?= $linguagem['descricao'] ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var fomento = $('.fomento');
    fomento.on("change", verificaFomento);
    $(document).ready(verificaFomento());

    function verificaFomento() {
        if ($('#sim').is(':checked')) {
            $('#tipoFomento')
                .attr('disabled', false)
        } else {
            $('#tipoFomento')
                .attr('disabled', true);
        }
    }
</script>