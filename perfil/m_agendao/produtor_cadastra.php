<?php
$produtor = recuperaDados("ig_produtor","1234","idProdutor");

include "include/menu.php";
?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Evento - Dados do Produtor</h3>
                    <h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <form method="POST" action="?perfil=agendao&p=produtor_cadastra" class="form-horizontal" role="form">
                    <div class="row form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label for="produtorNome">Nome do produtor do evento *</label>
                            <input type="text" id="produtorNome" name="produtorNome" class="form-control" required value="<?php echo $produtor['nome'] ?>"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-offset-2 col-md-6">
                            <label for="telefone">Telefone #1 *</label>
                            <input type="text" name="ig_produtor_telefone" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" required value="<?php echo $produtor['telefone'] ?>"/>
                        </div>
                        <div class="col-md-6">
                            <label for="telefone">Telefone #2 </label>
                            <input type="text" name="telefone2" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" value="<?php echo $produtor['telefone2'] ?>"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label for="email">Email *</label>
                            <input type="text" id="email" name="email" class="form-control" required value="<?php echo $produtor['email'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" name="cadastra" value="Gravar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- TODO: Incluir um botão de avançar após o usuário gravar -->
    </div>
</section>