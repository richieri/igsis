<?php
$con = bancoMysqli();

$id_evento = $_POST['idEvento'] ?? null;
$idOcorrencia = $_POST['idOcorrencia'] ?? null;

if (isset($_POST['id'])) {
    $idOcorrencia = $_POST['id'];
    $ocorrencia = recuperaDados("ig_ocorrencia", "$idOcorrencia", "idOcorrencia");
    $id_evento = $ocorrencia['idEvento'];
}

if (isset($_POST['inserirInstituicao'])) {
    $instituicao = $_POST['nomeInstituicao'];
    $sigla = $_POST['siglaInstituicao'];
    $instituicaoPai = 3;

    $sql = "INSERT INTO ig_instituicao (instituicao, instituicaoPai, sigla) VALUES ('$instituicao', '$instituicaoPai', '$sigla')";

    if (mysqli_query($con, $sql)) {
        $mensagem = "Instituição inserida com sucesso!";
    } else {
        $mensagem = "Ocorreu um erro ao inserir a instituição! Tente novamente.";
    }
}

if (isset($_POST['inserirLocal'])) {
    $sala = $_POST['nomeSala'];
    $instituicaoId = $_POST['instituicaoId'];
    $cep = $_POST['cep'];
    $logradouro = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO ig_local (sala, lotacao, idInstituicao, publicado, logradouro, numero, bairro, cidade, estado, cep, telefone)
            VALUES ('$sala', 0, '$instituicaoId', 1, '$logradouro', '$numero', '$bairro', '$cidade', '$estado', '$cep', '$telefone')";


    if (mysqli_query($con, $sql)) {
        $mensagem = "Sala/Espaço inserida com sucesso!";
    } else {
        $mensagem = "Ocorreu um erro ao inserir a Sala/Espaço! Tente novamente.";
    }
}

if (isset($_POST['cadastra'])) {

    $ig_comunicao_idCom = 0;
    $local_id = $_POST['local'];

    $segunda = $_POST['segunda'] ?? 0;
    $terca = $_POST['terca'] ?? 0;
    $quarta = $_POST['quarta'] ?? 0;
    $quinta = $_POST['quinta'] ?? 0;
    $sexta = $_POST['sexta'] ?? 0;
    $sabado = $_POST['sabado'] ?? 0;
    $domingo = $_POST['domingo'] ?? 0;

    $data_inicio = exibirDataMysql($_POST['dataInicio']);
    $data_fim = (isset($_POST['dataFinal']) && $_POST['dataFinal'] != '') ? exibirDataMysql($_POST['dataFinal']) : NULL;

    $horario_inicio = $_POST['hora'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];

    $duracao = $_POST['duracao'];
    $valor_ingresso = dinheiroDeBr($_POST['valorIngresso']);

    $subprefeitura = $_POST['subprefeitura'];
    $periodo = $_POST['periodo'];

    if (($data_fim == NULL)) {
        $tipoOcorrencia = 3; // Tipo de Ocorrência data única
    } else {
        $tipoOcorrencia = 4; // Tipo de Ocorrência por temporada
    }

    $sql = "INSERT INTO ig_ocorrencia 
(idTipoOcorrencia, ig_comunicao_idCom, local, idEvento, segunda, terca, quarta, quinta, sexta, sabado, domingo, dataInicio, dataFinal, horaInicio, valorIngresso, retiradaIngresso, duracao, subprefeitura_id, idPeriodoDia, publicado )  
            VALUES 
('$tipoOcorrencia', '$ig_comunicao_idCom', '$local_id', '$id_evento', $segunda, $terca, $quarta, $quinta, $sexta, $sabado, $domingo, '$data_inicio', '$data_fim', '$horario_inicio', $valor_ingresso, $retirada_ingresso_id, $duracao, '$subprefeitura', '$periodo', 1  )";

    echo $sql;
    if (mysqli_query($con, $sql)) {
        $idOcorrencia = recuperaUltimo('ig_ocorrencia');
        $mensagem = "Cadastrado com sucesso!";
        gravarLog($sql);
    } else {
        $mensagem = "Erro ao gravar! Tente novamente.";
        gravarLog($sql);
    }

}

if (isset($_POST['atualiza'])) {
    $ig_comunicao_idCom = 0;
    $local_id = $_POST['local'];

    $segunda = $_POST['segunda'] ?? 0;
    $terca = $_POST['terca'] ?? 0;
    $quarta = $_POST['quarta'] ?? 0;
    $quinta = $_POST['quinta'] ?? 0;
    $sexta = $_POST['sexta'] ?? 0;
    $sabado = $_POST['sabado'] ?? 0;
    $domingo = $_POST['domingo'] ?? 0;

    $data_inicio = exibirDataMysql($_POST['dataInicio']);
    $data_fim = (isset($_POST['dataFinal']) && $_POST['dataFinal'] != '') ? exibirDataMysql($_POST['dataFinal']) : NULL;

    $horario_inicio = $_POST['hora'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];

    $duracao = $_POST['duracao'];
    $valor_ingresso = dinheiroDeBr($_POST['valorIngresso']);

    $subprefeitura = $_POST['subprefeitura'];
    $periodo = $_POST['periodo'];

    if (($data_fim == NULL)) {
        $tipoOcorrencia = 3; // Tipo de Ocorrência data única
    } else {
        $tipoOcorrencia = 4; // Tipo de Ocorrência por temporada
    }

    $sql = "UPDATE ig_ocorrencia SET 
                idTipoOcorrencia = '$tipoOcorrencia',
                ig_comunicao_idCom = '$ig_comunicao_idCom',
                local = '$local_id',
                idEvento = '$id_evento',
                segunda = $segunda,
                terca = $terca,
                quarta = $quarta,
                quinta = $quinta,
                sexta = $sexta,
                sabado = $sabado,
                domingo = $domingo,
                dataInicio = '$data_inicio',
                dataFinal = '$data_fim',
                horaInicio = '$horario_inicio',
                valorIngresso = $valor_ingresso,
                retiradaIngresso = $retirada_ingresso_id,
                duracao = $duracao,
                subprefeitura_id = '$subprefeitura',
                idPeriodoDia = '$periodo'  
             WHERE idOcorrencia = '$idOcorrencia'";

    if (mysqli_query($con, $sql)) {
        $mensagem = "Atualizado com sucesso!";
        gravarLog($sql);
    } else {
        $mensagem = "Erro ao atualizar! Tente novamente.";
        gravarLog($sql);
    }
}

$ocorrencia = recuperaDados("ig_ocorrencia", "$idOcorrencia", "idOcorrencia");

include "include/menu.php";
?>
<script type="application/javascript">
    $(function () {
        $('#instituicao').change(function () {
            if ($(this).val()) {
                $('#local').hide();
                $('.carregando').show();
                $.getJSON('local.ajax.php?instituicao=', {instituicao: $(this).val(), ajax: 'true'}, function (j) {
                    var options = '<option value=""></option>';


                    for (var i = 0; i < j.length; i++) {

                        options += '<option value="' + j[i].idEspaco + '">' + j[i].espaco + '</option>';

                    }
                    $('#local').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#local').html('<option value="">-- Escolha uma instituição --</option>');
            }
        });
    });
</script>

<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Evento - <?= ($idOcorrencia == null) ? "Inserir" : "Atualizar" ?> ocorrência</h3>
                    <h4><?php if (isset($mensagem)) {
                            echo $mensagem;
                        } ?></h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=ocorrencia_cadastra" class="form-horizontal" role="form">

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6">
                            <label>Data início *</label>
                            <input type="text" name="dataInicio" class="form-control" id="datepicker01" placeholder=""
                                   value="<?php echo isset($ocorrencia['dataInicio']) ? exibirDataBr($ocorrencia['dataInicio']) : '' ?>">
                        </div>
                        <div class=" col-md-6">
                            <label>Data encerramento</label>
                            <input type="text" name="dataFinal" class="form-control" id="datepicker02"
                                   onblur="validate()" placeholder="só preencha em caso de temporada"
                                   value="<?php echo (isset($ocorrencia['dataFinal']) && $ocorrencia['dataFinal'] != '0000-00-00') ? exibirDataBr($ocorrencia['dataFinal']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="checkbox" name="segunda" id="diasemana01" disabled="disabled"/><label
                                    style="padding:0 10px 0 5px;"> Segunda</label>
                            <input type="checkbox" name="terca" id="diasemana02" disabled="disabled"/><label
                                    style="padding:0 10px 0 5px;"> Terça</label>
                            <input type="checkbox" name="quarta" id="diasemana03" disabled="disabled"/><label
                                    style="padding:0 10px 0 5px;"> Quarta</label>
                            <input type="checkbox" name="quinta" id="diasemana04" disabled="disabled"/><label
                                    style="padding:0 10px 0 5px;"> Quinta</label>
                            <input type="checkbox" name="sexta" id="diasemana05" disabled="disabled"/><label
                                    style="padding:0 10px 0 5px;"> Sexta</label>
                            <input type="checkbox" name="sabado" id="diasemana06" disabled="disabled"/><label
                                    style="padding:0 10px 0 5px;"> Sábado</label>
                            <input type="checkbox" name="domingo" id="diasemana07" disabled="disabled"/><label
                                    style="padding:0 10px 0 5px;"> Domingo</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-2">
                            <label>Horário de início *</label>
                            <input type="text" name="hora" class="form-control" id="hora" placeholder="hh:mm"
                                   value="<?php echo isset($ocorrencia['horaInicio']) ? $ocorrencia['horaInicio'] : '' ?>"/>
                        </div>
                        <div class="col-md-3">
                            <label>Valor ingresso *</label>
                            <input type="text" name="valorIngresso" class="form-control" id="valor"
                                   placeholder="em reais"
                                   value="<?php echo isset($ocorrencia['valorIngresso']) ? dinheiroParaBr($ocorrencia['valorIngresso']) : '' ?>">
                        </div>
                        <div class=" col-md-3">
                            <label>Duração *</label>
                            <input type="text" id="duracao" name="duracao" class="form-control" id=""
                                   placeholder="em minutos"
                                   value="<?php echo isset($ocorrencia['duracao']) ? $ocorrencia['duracao'] : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Período</label>
                            <select class="form-control" name="periodo" id="periodo">
                                <option>Selecione</option>
                                <?php
                                geraOpcao("ig_periodo_dia", $ocorrencia['idPeriodoDia'], "") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Subprefeitura</label>
                            <select class="form-control" name="subprefeitura" id="subprefeitura">
                                <option>Selecione</option>
                                <?php
                                geraOpcao("igsis_subprefeitura", $ocorrencia['subprefeitura_id'], "") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Sistema de retirada de ingressos</label>
                            <select class="form-control" name="retiradaIngresso" id="inputSubject">
                                <option>Selecione</option>
                                <?php
                                geraOpcao("ig_retirada", $ocorrencia['retiradaIngresso'], "") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Local / instituição *</label><img src="images/loading.gif" class="loading"
                                                                     style="display:none"/>
                            <button class='btn btn-default' type='button' data-toggle='modal'
                                    data-target='#adicionaInstituicao' style="border-radius: 30px;">
                                <i class="fa fa-plus-circle"></i></button>
                            <select class="form-control" name="instituicao" id="instituicao" required>
                                <option value="">Selecione...</option>
                                <?php
                                $inst = retornaInstituicao($ocorrencia['local']);
                                geraOpcao("ig_instituicao", $inst, "")
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Sala / espaço (antes selecione a instituição)</label>
                            <button class='btn btn-default' type='button' data-toggle='modal'
                                    data-target='#adicionaLocal' style="border-radius: 30px;">
                                <i class="fa fa-plus-circle"></i></button>
                            <select class="form-control" name="local" id="local" required>
                                <option value="">Selecione...</option>
                                <?php geraOpcao("ig_local", $ocorrencia['local'], $inst); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="hidden" name="idOcorrencia" value="<?= $idOcorrencia ?>">
                            <input type="hidden" name="idEvento" value="<?= $id_evento ?>">
                            <input type="submit" class="btn btn-theme btn-lg btn-block"
                                   name="<?= ($idOcorrencia == null) ? "cadastra" : "atualiza" ?>"
                                   value="<?= ($idOcorrencia == null) ? "Inserir" : "Atualizar" ?>"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=lista_ocorrencias" class="form-horizontal" role="form">
                    <input type="hidden" name="idEvento" value="<?= $id_evento ?>">
                    <input type="submit" class="btn btn-theme btn-sm" value="Voltar a lista de Ocorrências">
                </form>
            </div>
        </div>
    </div>

    <!-- ADICIONAR INSTITUIÇÃO -->
    <div class="modal fade" id="adicionaInstituicao" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id='instituicaoAdiciona' action="?perfil=agendao&p=ocorrencia_cadastra"
                      class="form-horizontal" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Cadastrar Instituição</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="instituicao">Instituicao </label>
                            <input type="text" class="form-control" name="nomeInstituicao" id="nomeInstituicao">
                        </div>

                        <div class="form-group">
                            <label for="instituicao">Sigla </label>
                            <input type="text" class="form-control" name="siglaInstituicao" id="siglaInstituicao">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                        <?php
                        if ($idOcorrencia != null) {
                            ?>
                            <input type="hidden" name="idOcorrencia" value="<?= $idOcorrencia ?>">
                            <?php
                        }
                        ?>
                        <input type="hidden" name="idEvento" value="<?= $id_evento ?>">
                        <button type='submit' class='btn btn-info btn-sm' name="inserirInstituicao">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ADICIONAR LOCAL -->
    <div class="modal fade" id="adicionaLocal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id='localAdiciona' action="?perfil=agendao&p=ocorrencia_cadastra"
                      class="form-horizontal" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Cadastrar Sala/Espaço</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nomeSala">Sala </label>
                            <input type="text" class="form-control" name="nomeSala" id="nomeSala">
                        </div>

                        <div class="form-group">
                            <label for="instituicaoId">Instituição</label>
                            <select class="form-control" name="instituicaoId" id="instituicaoId" required>
                                <option value="">Selecione...</option>
                                <?php
                                $inst = retornaInstituicao($ocorrencia['local']);
                                geraOpcao("ig_instituicao", $inst, "")
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cep">CEP: *</label>
                            <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                   placeholder="Digite o CEP" required data-mask="00000-000">
                        </div>

                        <div class="form-group">
                            <label for="rua">Rua: *</label>
                            <input type="text" class="form-control" name="rua" id="rua" placeholder="Digite a rua"
                                   maxlength="200" readonly>
                        </div>
                        <div class="form-group">
                            <label for="numero">Número: *</label>
                            <input type="number" name="numero" class="form-control" placeholder="Ex.: 10" required>
                        </div>

                        <div class="form-group">
                            <label for="bairro">Bairro: *</label>
                            <input type="text" class="form-control" name="bairro" id="bairro"
                                   placeholder="Digite o Bairro" maxlength="80" readonly>
                        </div>
                        <div class="form-group">
                            <label for="cidade">Cidade: *</label>
                            <input type="text" class="form-control" name="cidade" id="cidade"
                                   placeholder="Digite a cidade" maxlength="50" readonly>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado: *</label>
                            <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                   placeholder="Ex.: SP" readonly>
                        </div>
                        <div class="form-group">
                            <label for="estado">Telefone: *</label>
                            <input type="text" class="form-control" name="telefone" id="telefone" required data-mask="(00)0000-0000">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                        <?php
                        if ($idOcorrencia != null) {
                            ?>
                            <input type="hidden" name="idOcorrencia" value="<?= $idOcorrencia ?>">
                            <?php
                        }
                        ?>
                        <input type="hidden" name="idEvento" value="<?= $id_evento ?>">
                        <button type='submit' class='btn btn-info btn-sm' name="inserirLocal">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>

<script type="application/javascript">
    $(function () {
        $('#instituicao').change(function () {
            if ($(this).val()) {
                $('#local').hide();
                $('.carregando').show();
                $.getJSON('local.ajax.php?instituicao=', {instituicao: $(this).val(), ajax: 'true'}, function (j) {
                    var options = '<option value=""></option>';


                    for (var i = 0; i < j.length; i++) {

                        options += '<option value="' + j[i].idEspaco + '">' + j[i].espaco + '</option>';

                    }
                    $('#local').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#local').html('<option value="">-- Escolha uma instituição --</option>');
            }
        });
    });
</script>

<script type="application/javascript">

    window.onload = function () {

        let espaco = '<?=$espaco_id?>';
        let local = '<?=$local_id?>'


        $.getJSON('local.ajax.php?instituicao=', {instituicao: local, ajax: 'true'}, function (j) {
            var options = '<option value=""></option>';


            for (var i = 0; i < j.length; i++) {
                if (j[i].idEspaco == espaco) {
                    options += '<option value="' + j[i].idEspaco + '" selected>' + j[i].espaco + '</option>';
                } else {
                    options += '<option value="' + j[i].idEspaco + '">' + j[i].espaco + '</option>';
                }
            }
            $('#local').html(options).show();

        });


    };

</script>

<script>
    //Script CEP
    $(document).ready(function () {
        function limpa_formulário_cep() {
            // Limpa valores do formulário de cep.
            $("#rua").val("");
            $("#bairro").val("");
            $("#cidade").val("");
            $("#estado").val("");
        }

        //Quando o campo cep perde o foco.
        $("#cep").blur(function () {
            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $("#rua").val("...");
                    $("#bairro").val("...");
                    $("#cidade").val("...");
                    $("#estado").val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#rua").prop('readonly', true);
                            $("#bairro").prop('readonly', true);
                            $("#cidade").prop('readonly', true);
                            $("#estado").prop('readonly', true);

                            $("#rua").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#estado").val(dados.uf);

                            if (dados.logradouro == "") {
                                alert("Por favor preencha o formulário");
                                $("#rua").prop('readonly', false);
                                $("#bairro").prop('readonly', false);
                                $("#cidade").prop('readonly', false);
                                $("#estado").prop('readonly', false);
                            }
                        } else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                } else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    });
</script>
