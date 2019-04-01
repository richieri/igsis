<?php
$con = bancoMysqli();

if (isset($_POST['cadastrarPrograma'])) {
    $programa = $_POST['programa'];
    $verba = $_POST['verba'];
    $edital = $_POST['edital'];
    $descricao = addslashes($_POST['descricao']);

    $sqlPrograma = "INSERT INTO sis_formacao_programa (Programa, verba, edital, descricao) VALUE ('$programa', '$verba', '$edital', '$descricao')";
    if ($con->query($sqlPrograma))
    {
        $mensagem = "<span style='color: green'>Programa Cadastrado com Sucesso</span>";
    } else {
        $mensagem = "<span style='color: red'>Erro ao cadastrar o programa</span>";
    }
}

if(isset($_POST['atualizarPrograma'])) {
    $idPrograma = $_POST['atualizarPrograma'];
    $programa = $_POST['programa'];
    $edital = $_POST['edital'];
    $verba = $_POST['verba'];
    $descricao = addslashes($_POST['descricao']);

    $sqlAtualizaPrograma = "UPDATE sis_formacao_programa SET Programa = '$programa', edital = '$edital', verba = '$verba', descricao = '$descricao' WHERE Id_Programa = '$idPrograma'";

    if ($con->query($sqlAtualizaPrograma)) {
        $mensagem = "<span style='color: green'>Programa Editado com Sucesso</span>";
    } else {
        $mensagem = "<span style='color: red'>Erro ao editar o programa</span>";
    }
}

$sqlConsultaProgramas = "SELECT * FROM sis_formacao_programa";
$programas = $con->query($sqlConsultaProgramas);

?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <div class="sub-title">
                <h2>LISTAGEM DE PROGRAMAS</h2>
                <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-offset-4 col-md-6">
                    <a href="?perfil=formacao&p=administrativo&pag=add_programa" class="btn btn-theme btn-block">Adicionar
                        novo Programa</a>
                </div>
            </div>

            <div class="row"><hr></div>

            <div class="row">
                <div class="table-responsive list_info">
                    <table class="table table-condensed">
                        <thead>
                            <tr class="list_menu">
                                <td>Id</td>
                                <td>Programa</td>
                                <td>Edital</td>
                                <td>Verba</td>
                                <td>Descrição</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($dadoPrograma = mysqli_fetch_assoc($programas)) {
                                ?>
                                <tr>
                                    <form action="?perfil=formacao&p=administrativo&pag=list_programa" method="post">
                                        <td><?=$dadoPrograma['Id_Programa']?></td>
                                        <td>
                                            <input type="text" name="programa" class="form-control"
                                                   value="<?=$dadoPrograma['Programa']?>"/>
                                        </td>
                                        <td>
                                            <input type="text" name="edital" class="form-control"
                                                   value="<?=$dadoPrograma['edital']?>"/>
                                        </td>
                                        <td>
                                            <select class="form-control" name="verba" id="verba">
                                                <option value="44" <?=$dadoPrograma['verba'] == 44 ? "selected" : ""?>>Programa Vocacional</option>
                                                <option value="51" <?=$dadoPrograma['verba'] == 51 ? "selected" : ""?>>Programa Piá</option>
                                            </select>
                                        </td>
                                        <td><textarea name="descricao" class="form-control"
                                                      rows="2"><?=$dadoPrograma['descricao']?></textarea></td>
                                        <td>
                                            <input type="hidden" name="atualizarPrograma" value="<?=$dadoPrograma['Id_Programa']?>"/>
                                            <input type='submit' class='btn btn-theme btn-block' value='atualizar'>
                                        </td>
                                    </form>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
