<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Evento - Inserir ocorrências</h3>
                    <h1><?php echo $campo["nomeEvento"] ?> </h1>
                    <p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=ocorrencia_lista" class="form-horizontal" role="form">

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
                            <input type="submit" class="btn btn-theme btn-lg btn-block" value="Inserir ocorrência"  />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
