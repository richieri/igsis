<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Evento - Informações Gerais</h3>
                    <h1><?php echo $campo["nomeEvento"] ?></h1>
                    <h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=evento_cadastra" class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Nome do Evento *</label>
                            <input type="text" name="nomeEvento" class="form-control" id="inputSubject" value="<?php echo $campo['nomeEvento'] ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Nome do Projeto </label>
                            <input type="text" name="projeto" class="form-control" id=""  value="<?php echo $campo['projeto'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">j
                            <label>Tipo de Evento *</label>
                            <select class="form-control" name="ig_tipo_evento_idTipoEvento" id="inputSubject" >
                                <option value="1"></option>
                                <?php echo geraOpcao("ig_tipo_evento",$campo['ig_tipo_evento_idTipoEvento'],"") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Classificação/indicação etária</label>
                            <select class="form-control" name="faixaEtaria" id="inputSubject" >
                                <option value="0"></option>
                                <?php echo geraOpcao("ig_etaria",$campo['faixaEtaria'],"") ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Sinopse *</label>
                            <textarea name="sinopse" class="form-control" rows="10" placeholder="Texto para divulgação e sob editoria da area de comunicação. Não ultrapassar 400 caracteres."><?php echo $campo["sinopse"] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Links *</label>
                            <textarea name="linksCom" class="form-control" rows="10" placeholder="Links para auxiliar a divulgação e o jurídico. Site oficinal, vídeos, clipping, artigos, etc "><?php echo $campo["linksCom"] ?></textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Nome do produtor do evento</label>
                            <input type="text" name="ig_produtor_nome" class="form-control" id="ig_produtor_nome" value="<?php echo $produtor['nome'] ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Telefones</label>
                            <input type="text" name="ig_produtor_telefone" class="form-control" id="inputSubject" value="<?php echo $produtor['telefone'] ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label>Email</label>
                            <input type="text" name="ig_produtor_email" class="form-control" id="inputSubject" value="<?php echo $produtor['email'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="hidden" name="atualizar" value="1" />
                            <input type="submit" class="btn btn-theme btn-lg btn-block" value="Gravar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>