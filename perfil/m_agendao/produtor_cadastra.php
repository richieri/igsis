<?php
$idEvento = (isset($_POST['idEvento'])) ? $_POST['idEvento'] : null;

$con = bancoMysqli();

if(isset($_POST['cadastra']) || isset($_POST['atualiza'])){
    $nome = $_POST['produtorNome'];
    $telefone1 = $_POST['ig_produtor_telefone'];
    $email = $_POST['email'];
    $telefone2 = $_POST['telefone2'] ?? null;
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO ig_produtor (nome, email, telefone, telefone2)  VALUES ('$nome', '$email', '$telefone1', '$telefone2' )";

    if (mysqli_query($con, $sql))
    {
        $idProdutor = recuperaUltimo('ig_produtor');
        $sql_evento = "UPDATE ig_evento SET ig_produtor_idProdutor = '$idProdutor' WHERE idEvento = '$idEvento'";
        if($con->query($sql_evento)){
            $mensagem =  "Cadastrado com sucesso!";
            gravarLog($sql);
        }
        else{
            $mensagem = "Erro ao gravar! Tente novamente.";
        }
    }
    else {
        $mensagem = "Erro ao gravar! Tente novamente.";
        gravarLog($sql);
    }
}

if(isset($_POST['atualiza'])){
    $idProdutor = $_POST['idProdutor'];
    $sql = "UPDATE ig_produtor SET nome = '$nome', email = '$email', telefone = '$telefone1', telefone2 = '$telefone2' WHERE idProdutor = '$idProdutor'";
    if ($con->query($sql)){
        $mensagem =  "Atualizado com sucesso!";
        gravarLog($sql);
    }
    else{
        $mensagem = "Erro ao atualizar! Tente novamente.";
    }
}

$evento = $con->query("SELECT ig_produtor_idProdutor FROM ig_evento WHERE idEvento = '$idEvento'")-> fetch_assoc();

$idProdutor = $evento['ig_produtor_idProdutor'];
$produtor = recuperaDados("ig_produtor",$idProdutor,"idProdutor");

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
                            <input type="text" id="produtorNome" name="produtorNome" class="form-control" required value="<?php echo isset($produtor['nome']) ? $produtor['nome'] : '' ?>"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-offset-2 col-md-6">
                            <label for="telefone">Telefone #1 *</label>
                            <input type="text" name="ig_produtor_telefone" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" required value="<?php echo isset($produtor['telefone']) ? $produtor['telefone'] : '' ?>"/>
                        </div>
                        <div class="col-md-6">
                            <label for="telefone">Telefone #2 </label>
                            <input type="text" name="telefone2" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" value="<?php echo isset($produtor['telefone2']) ? $produtor['telefone2'] : '' ?>"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <label for="email">Email *</label>
                            <input type="text" id="email" name="email" class="form-control" required value="<?php echo isset($produtor['email']) ? $produtor['email'] : '' ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                            <input type="hidden" name="idProdutor" value="<?=$idProdutor?>">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" name="<?=($idProdutor == 0) ? "cadastra" : "atualiza"?>" value="Gravar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="row col-md-offset-1 col-md-10">
            <div class="col-md-2 pull-left">
                <form method="POST" action="?perfil=agendao&p=evento_cadastra" class="form-horizontal" role="form">
                    <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                    <input type="submit" class="btn btn-theme btn-lg btn-block" value="Voltar">
                </form>
            </div>
            <div class="col-md-2 pull-right">
                <form method="POST" action="?perfil=agendao&p=lista_ocorrencias" class="form-horizontal" role="form">
                    <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                    <input type="submit" class="btn btn-theme btn-lg btn-block" value="Avançar">
                </form>
            </div>
        </div>
    </div>
</section>