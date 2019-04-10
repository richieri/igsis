<?php
$campo = recuperaDados("ig_evento","1234","idEvento");

include "include/menu.php";
?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-hide">
                    <h3>Evento - Informações Gerais</h3>
                    <h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=evento_cadastra" class="form-horizontal" role="form">
                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="nomeEvento">Nome do evento *</label>
                            <input type="text" name="nomeEvento" class="form-control" id="nomeEvento" value="<?php echo $campo['nomeEvento'] ?>"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="projetoEspecial">Projeto especial *</label>
                            <select class="form-control" id="projetoEspecial" name="projetoEspecial" required >
                                <option value="">Selecione...</option>
                                <?php echo geraOpcao("ig_projeto_especial",$campo['projetoEspecial'],"") ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="artistas">Artistas *</label>
                            <textarea id="artistas" name="artistas" class="form-control" rows="5"><?php echo $campo["sinopse"] ?></textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="linguagem">Linguagem / Expressão artística</label>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="representatividade">Público / Representatividade social</label>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label>Atividade realizada em espaço público</label>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label>Classificação/indicação etária</label>
                            <select class="form-control" name="faixaEtaria" id="faixaEtaria" required>
                                <option value="">Selecione...</option>
                                <?php echo geraOpcao("ig_etaria",$campo['faixaEtaria'],"") ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="sinopse">Sinopse *</label>
                            <textarea id="sinopse" name="sinopse" class="form-control" rows="6" placeholder="Texto para divulgação e sob editoria da area de comunicação. Não ultrapassar 400 caracteres." required><?php echo $campo["sinopse"] ?></textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <label for="links">Links de divulgação *</label>
                            <textarea id="links" name="linksCom" class="form-control" rows="3" placeholder="Links para auxiliar a divulgação. Site oficinal, vídeos, clipping, artigos, etc " required><?php echo $campo["linksCom"] ?></textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" name="cadastra" value="Gravar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>