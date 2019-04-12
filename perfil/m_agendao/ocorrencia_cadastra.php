<?php
if (isset($_POST['cadastra'])) {


    $ig_comunicao_idCom = 0;
    $local_id = $_POST['local'];

    $id_evento = 0;

    $segunda    = $_POST['segunda'] ?? 0;
    $terca      = $_POST['terca']   ?? 0;
    $quarta     = $_POST['quarta']  ?? 0;
    $quinta     = $_POST['quinta']  ?? 0;
    $sexta      = $_POST['sexta']   ?? 0;
    $sabado     = $_POST['sabado']  ?? 0;
    $domingo    = $_POST['domingo'] ?? 0;

    $data_inicio = $_POST['dataInicio'];
    $data_fim   = $_POST['dataFinal'] ?? NULL;

    $espaco_id = $_POST['espaco'] ?? NULL;


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
    $con = bancoMysqli();
    $sql = "INSERT INTO ig_ocorrencia 
(idTipoOcorrencia, ig_comunicao_idCom, local, idEvento, segunda, terca, quarta, quinta, sexta, sabado, domingo, dataInicio, dataFinal, horaInicio, valorIngresso, retiradaIngresso, duracao, publicado )  
            VALUES 
('$tipoOcorrencia', '$ig_comunicao_idCom', '$local_id', '$id_evento', $segunda, $terca, $quarta, $quinta, $sexta, $sabado, $domingo, '$data_inicio', '$data_fim', '$horario_inicio', $valor_ingresso, $retirada_ingresso_id, $duracao, 1  )";

    if (mysqli_query($con, $sql))
    {
        //$idOcorrencia = recuperaUltimo('ig_produtor');
        $mensagem =  "Cadastrado com sucesso!";
        gravarLog($sql);
    } else {
        $mensagem = "Erro ao gravar! Tente novamente.";
        gravarLog($sql);
    }

    $ocorrencia = recuperaDados("ig_produtor","$idOcorrencia","idProdutor");

  
}



include "include/menu.php";
?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Evento - Inserir ocorrências</h3>
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
                            <input type="text" name="dataInicio" class="form-control" id="datepicker01" placeholder="">
                        </div>
                        <div class=" col-md-6">
                            <label>Data encerramento</label>
                            <input type="text" name="dataFinal" class="form-control" id="datepicker02" onblur="validate()" placeholder="só preencha em caso de temporada">
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
                            <input type="text" name="hora" class="form-control"id="hora" placeholder="hh:mm"/>
                        </div>
                        <div class="col-md-3">
                            <label>Valor ingresso *</label>
                            <input type="text" name="valorIngresso" class="form-control" id="valor" placeholder="em reais">
                        </div>
                        <div class=" col-md-3">
                            <label>Duração *</label>
                            <input type="text" id="duracao" name="duracao" class="form-control" id="" placeholder="em minutos">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Sistema de retirada de ingressos</label>
                            <select class="form-control" name="retiradaIngresso" id="inputSubject" >
                                <option>Selecione</option>
                                <?php geraOpcao("ig_retirada","","") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Local / instituição *</label><img src="images/loading.gif" class="loading" style="display:none" />
                            <select class="form-control" name="instituicao" id="instituicao" >
                                <option>Selecione</option>
                                <?php geraOpcao("ig_instituicao","","") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Sala / espaço (antes selecione a instituição)</label>
                            <select class="form-control" name="local" id="local" ></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="hidden" name="inserir" value="1"  />
                            <input type="submit" class="btn btn-theme btn-lg btn-block"  name="cadastra" value="Inserir"  />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- TODO: Incluir um botão de avançar após o usuário gravar -->
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
                    for (var i = 0; i < j.length; i++)
                    {
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
