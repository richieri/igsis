<?php
$con = bancoMysqli();

$id_evento = $_POST['idEvento'] ?? null;
$idOcorrencia = $_POST['idOcorrencia'] ?? null;

if (isset($_POST['id'])) {
    $idOcorrencia = $_POST['id'];
    $ocorrencia = recuperaDados("ig_ocorrencia","$idOcorrencia","idOcorrencia");
    $id_evento = $ocorrencia['idEvento'];
}

if (isset($_POST['cadastra'])) {

    $ig_comunicao_idCom = 0;
    $local_id = $_POST['local'] ;

    $segunda    = $_POST['segunda'] ?? 0;
    $terca      = $_POST['terca']   ?? 0;
    $quarta     = $_POST['quarta']  ?? 0;
    $quinta     = $_POST['quinta']  ?? 0;
    $sexta      = $_POST['sexta']   ?? 0;
    $sabado     = $_POST['sabado']  ?? 0;
    $domingo    = $_POST['domingo'] ?? 0;

    $data_inicio = exibirDataMysql($_POST['dataInicio']);
    $data_fim   = (isset($_POST['dataFinal']) && $_POST['dataFinal'] != '') ? exibirDataMysql($_POST['dataFinal']) : NULL;

    $horario_inicio = $_POST['hora'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];

    $duracao = $_POST['duracao'];
    $valor_ingresso = dinheiroDeBr($_POST['valorIngresso']);

    if(($data_fim == NULL))
    {
        $tipoOcorrencia = 3; // Tipo de Ocorrência data única
    }
    else
    {
        $tipoOcorrencia = 4; // Tipo de Ocorrência por temporada
    }

    $sql = "INSERT INTO ig_ocorrencia 
(idTipoOcorrencia, ig_comunicao_idCom, local, idEvento, segunda, terca, quarta, quinta, sexta, sabado, domingo, dataInicio, dataFinal, horaInicio, valorIngresso, retiradaIngresso, duracao, publicado )  
            VALUES 
('$tipoOcorrencia', '$ig_comunicao_idCom', '$local_id', '$id_evento', $segunda, $terca, $quarta, $quinta, $sexta, $sabado, $domingo, '$data_inicio', '$data_fim', '$horario_inicio', $valor_ingresso, $retirada_ingresso_id, $duracao, 1  )";

    if (mysqli_query($con, $sql))
    {
        $idOcorrencia = recuperaUltimo('ig_ocorrencia');
        $mensagem =  "Cadastrado com sucesso!";
        gravarLog($sql);
    } else {
        $mensagem = "Erro ao gravar! Tente novamente.";
        gravarLog($sql);
    }

}

if (isset($_POST['atualiza'])) {
    $ig_comunicao_idCom = 0;
    $local_id = $_POST['local'] ;

    $segunda    = $_POST['segunda'] ?? 0;
    $terca      = $_POST['terca']   ?? 0;
    $quarta     = $_POST['quarta']  ?? 0;
    $quinta     = $_POST['quinta']  ?? 0;
    $sexta      = $_POST['sexta']   ?? 0;
    $sabado     = $_POST['sabado']  ?? 0;
    $domingo    = $_POST['domingo'] ?? 0;

    $data_inicio = exibirDataMysql($_POST['dataInicio']);
    $data_fim   = (isset($_POST['dataFinal']) && $_POST['dataFinal'] != '') ? exibirDataMysql($_POST['dataFinal']) : NULL;

    $horario_inicio = $_POST['hora'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];

    $duracao = $_POST['duracao'];
    $valor_ingresso = dinheiroDeBr($_POST['valorIngresso']);

    if(($data_fim == NULL))
    {
        $tipoOcorrencia = 3; // Tipo de Ocorrência data única
    }
    else
    {
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
                duracao = $duracao  
             WHERE idOcorrencia = '$idOcorrencia'";

    if (mysqli_query($con, $sql))
    {
        $mensagem =  "Atualizado com sucesso!";
        gravarLog($sql);
    } else {
        $mensagem = "Erro ao atualizar! Tente novamente.";
        gravarLog($sql);
    }
}

$ocorrencia = recuperaDados("ig_ocorrencia","$idOcorrencia","idOcorrencia");

include "include/menu.php";
?>
<script type="application/javascript">
    $(function()
    {
        $('#instituicao').change(function()
        {
            if( $(this).val() )
            {
                $('#local').hide();
                $('.carregando').show();
                $.getJSON('local.ajax.php?instituicao=',{instituicao: $(this).val(), ajax: 'true'}, function(j)
                {
                    var options = '<option value=""></option>';


                    for (var i = 0; i < j.length; i++)                    {

                        options += '<option value="' + j[i].idEspaco + '">' + j[i].espaco + '</option>';

                    }
                    $('#local').html(options).show();
                    $('.carregando').hide();
                });
            }
            else
            {
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
                    <h3>Evento - <?=($idOcorrencia == null) ? "Inserir" : "Atualizar"?> ocorrência</h3>
                    <h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=ocorrencia_cadastra" class="form-horizontal" role="form">

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6">
                            <label>Data início *</label>
                            <input type="text" name="dataInicio" class="form-control" id="datepicker01" placeholder="" value="<?php echo isset($ocorrencia['dataInicio']) ? exibirDataBr($ocorrencia['dataInicio']) : '' ?>">
                        </div>
                        <div class=" col-md-6">
                            <label>Data encerramento</label>
                            <input type="text" name="dataFinal" class="form-control" id="datepicker02" onblur="validate()" placeholder="só preencha em caso de temporada" value="<?php echo (isset($ocorrencia['dataFinal']) && $ocorrencia['dataFinal'] != '0000-00-00') ? exibirDataBr($ocorrencia['dataFinal']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="checkbox" name="segunda" id="diasemana01" disabled="disabled"/><label style="padding:0 10px 0 5px;"> Segunda</label>
                            <input type="checkbox" name="terca" id="diasemana02" disabled="disabled"/><label  style="padding:0 10px 0 5px;"> Terça</label>
                            <input type="checkbox" name="quarta" id="diasemana03" disabled="disabled"/><label style="padding:0 10px 0 5px;"> Quarta</label>
                            <input type="checkbox" name="quinta" id="diasemana04" disabled="disabled"/><label style="padding:0 10px 0 5px;"> Quinta</label>
                            <input type="checkbox" name="sexta" id="diasemana05" disabled="disabled"/><label  style="padding:0 10px 0 5px;"> Sexta</label>
                            <input type="checkbox" name="sabado" id="diasemana06" disabled="disabled"/><label style="padding:0 10px 0 5px;"> Sábado</label>
                            <input type="checkbox" name="domingo" id="diasemana07" disabled="disabled"/><label  style="padding:0 10px 0 5px;"> Domingo</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-2">
                            <label>Horário de início *</label>
                            <input type="text" name="hora" class="form-control"id="hora" placeholder="hh:mm" value="<?php echo isset($ocorrencia['horaInicio']) ? $ocorrencia['horaInicio'] : '' ?>"/>
                        </div>
                        <div class="col-md-3">
                            <label>Valor ingresso *</label>
                            <input type="text" name="valorIngresso" class="form-control" id="valor" placeholder="em reais" value="<?php echo isset($ocorrencia['valorIngresso']) ? dinheiroParaBr($ocorrencia['valorIngresso']) : '' ?>">
                        </div>
                        <div class=" col-md-3">
                            <label>Duração *</label>
                            <input type="text" id="duracao" name="duracao" class="form-control" id="" placeholder="em minutos" value="<?php echo isset($ocorrencia['duracao']) ? $ocorrencia['duracao'] : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Sistema de retirada de ingressos</label>
                            <select class="form-control" name="retiradaIngresso" id="inputSubject" >
                                <option>Selecione</option>
                                <?php
                                geraOpcao("ig_retirada",$ocorrencia['retiradaIngresso'],"") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Local / instituição *</label><img src="images/loading.gif" class="loading" style="display:none" />
                            <select class="form-control" name="instituicao" id="instituicao" required>
                                <option value="">Selecione...</option>
                                <?php
                                $inst = retornaInstituicao($ocorrencia['local']);
                                geraOpcao("ig_instituicao",$inst,"")
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Sala / espaço (antes selecione a instituição)</label>
                            <select class="form-control" name="local" id="local" required>
                                <option value="">Selecione...</option>
                                <?php geraOpcao("ig_local",$ocorrencia['local'],$inst); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="hidden" name="idOcorrencia" value="<?=$idOcorrencia?>">
                            <input type="hidden" name="idEvento" value="<?=$id_evento?>">
                            <input type="submit" class="btn btn-theme btn-lg btn-block"  name="<?=($idOcorrencia == null) ? "cadastra" : "atualiza"?>" value="<?=($idOcorrencia == null) ? "Inserir" : "Atualizar"?>"  />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=lista_ocorrencias" class="form-horizontal" role="form">
                    <input type="hidden" name="idEvento" value="<?=$id_evento?>">
                    <input type="submit" class="btn btn-theme btn-sm" value="Voltar a lista de Ocorrências">
                </form>
            </div>
        </div>
    </div>
</section>

<script type="application/javascript">
    $(function()
    {
        $('#instituicao').change(function()
        {
            if( $(this).val() )
            {
                $('#local').hide();
                $('.carregando').show();
                $.getJSON('local.ajax.php?instituicao=',{instituicao: $(this).val(), ajax: 'true'}, function(j)
                {
                    var options = '<option value=""></option>';


                    for (var i = 0; i < j.length; i++)                    {

                        options += '<option value="' + j[i].idEspaco + '">' + j[i].espaco + '</option>';

                    }
                    $('#local').html(options).show();
                    $('.carregando').hide();
                });
            }
            else
            {
                $('#local').html('<option value="">-- Escolha uma instituição --</option>');
            }
        });
    });
</script>

<script type="application/javascript">

       window.onload = function()
        {

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
