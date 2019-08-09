<?php
include 'includes/menu.php';
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];

if (isset($_POST['pesquisar'])) {
    $cnpj = $_POST['cnpj'];
    $validacao = validaCNPJ($cnpj);
    if ($validacao) {
        $sql = "SELECT * FROM sis_pessoa_juridica WHERE CNPJ = '$cnpj'";
        $query = mysqli_query($con, $sql);
        $num_arraow = mysqli_num_rows($query);
    } else {
        $mensagem = "CNPJ INVÁLIDO!";
    }
}

if (isset($_POST['selecionar'])) {
    $idProponente = $_POST['idProponente'];
    $pj = recuperaDados('sis_pessoa_juridica', $idProponente, 'Id_PessoaJuridica');
    $idRepresentante1 = $pj['IdRepresentanteLegal1'] ?? null;
    $idRepresentante2 = $pj['IdRepresentanteLegal2'] ?? null;

    $sqlUpdate = "UPDATE igsis_pedido_contratacao SET idRepresentante01 = '$idRepresentante1', idRepresentante02 = '$idRepresentante2', idPessoa = '$idProponente', tipoPessoa = 2 WHERE idPedidoContratacao = '$idPedido'";

    if (mysqli_query($con, $sqlUpdate)) {
        gravarLog($sqlUpdate);

        $mensagem2 = "Proponente adicionado com sucesso!";
        $url = "?perfil=contratos&p=frm_edita_propostapj&id_ped=" . $idPedido . "";
        echo "<script type=\"text/javascript\">
                          const url = `$url`;
                          console.log(url);
						  window.setTimeout(\"location.href='\"+ url + \"';\", 4000);
					  </script>";
    } else {
        $mensagem = "Erro ao trocar proponente! Tente novamente";
    }
}

if (isset($_POST['cadastrar'])) {
    $RazaoSocial = addslashes($_POST['RazaoSocial']);
    $CNPJ = $_POST['CNPJ'];
    $CCM = $_POST['CCM'];
    $CEP = $_POST['CEP'];
    $Numero = $_POST['Numero'];
    $Complemento = $_POST['Complemento'];
    $Telefone1 = $_POST['Telefone1'] ?? null;
    $Telefone2 = $_POST['Telefone2'] ?? null;
    $Telefone3 = $_POST['Telefone3'] ?? null;
    $Email = $_POST['Email'];
    $Observacao = $_POST['Observacao'] ?? null;
    $data = date("Y-m-d");
    $idUsuario = $_SESSION['idUsuario'];
    $sql_inserir_pj = "INSERT INTO `sis_pessoa_juridica` 
    (`Id_PessoaJuridica` , `RazaoSocial` ,`CNPJ` ,`CCM` ,`CEP` ,`Numero` ,`Complemento` ,`Telefone1` ,`Telefone2` ,`Telefone3` ,`Email` , `DataAtualizacao` ,`Observacao` ,`IdUsuario`) 
    VALUES ( NULL ,  '$RazaoSocial',  '$CNPJ', '$CCM' , '$CEP' , '$Numero' , '$Complemento' ,  '$Telefone1', '$Telefone2' , '$Telefone3' , '$Email' , '$data', '$Observacao' ,  '$idUsuario')";

    if (mysqli_query($con, $sql_inserir_pj)) {
        gravarLog($sql_inserir_pj);
        $mensagem2 = "Proponente cadastrado com sucesso!";
        $idPessoaJuridica = recuperaUltimo("sis_pessoa_juridica");

        $sqlUpdate = "UPDATE igsis_pedido_contratacao SET  tipoPessoa = 2, idPessoa = '$idPessoaJuridica' WHERE idPedidoContratacao = '$idPedido'";
        mysqli_query($con, $sqlUpdate);

        $url = "?perfil=contratos&p=frm_edita_pj&id_pj=". $idPessoaJuridica ."&id_ped=" . $idPedido . "";
        echo "<script type=\"text/javascript\">
                          const url = `$url`;
                          console.log(url);
						  window.setTimeout(\"location.href='\"+ url + \"';\", 4000);
					  </script>";
    } else {
        $mensagem = "Erro ao cadastrar proponente! Tente novamente";
    }

}
?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <?php
                    if (isset($mensagem)) {
                        ?>
                        <h4 style="color: red"><?= $mensagem ?></h4>
                        <?php
                    }
                    if (isset($mensagem2)) {
                        ?>
                        <h4 style="color: #94E85D"><?= $mensagem2 ?></h4>
                        <h5 style="color: #94E85D">Você será redirecionado</h5>
                        <?php
                    }
                    ?>
                    <h3>Procurar Pessoa Jurídica</h3>
                </div>
                <p></p>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <form method="POST" action="?perfil=contratos&p=busca_pj" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="cnpj">Insira o CNPJ* </label>
                            <input type="text" name="cnpj" id="CNPJ" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="pesquisar">Pesquisar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($num_arraow)) {
        if ($num_arraow > 0) {
            ?>
            <br>
            <div class="container">
                <div class="table-responsive list_info">
                    <table class="table table-condensed">
                        <thead>
                        <tr class="list_menu">
                            <td>Razão Social</td>
                            <td>CNPJ</td>
                            <td width="30%"></td>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        while ($descricao = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td class="list_description"><b><?= $descricao['RazaoSocial'] ?></b></td>
                                <td class="list_description"><b><?= $descricao['CNPJ'] ?></b></td>
                                <td class="list_description">
                                    <form action="?perfil=contratos&p=busca_pj" method="POST">
                                        <input type="hidden" id="idProponente" name="idProponente"
                                               value="<?= $descricao['Id_PessoaJuridica'] ?>">
                                        <button class="btn btn-primary btn-medium" name="selecionar">Selecionar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container">
                <div class="form-group">
                    <h3>CADASTRO DE PESSOA JURÍDICA</h3>
                    <p style="color: red">Não foram encontradas nenhuma pessoa física com o cnpj:
                        <strong><?= $cnpj ?></strong></p>
                </div>
                <form action="?perfil=contratos&p=busca_pj" method="POST">

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Razão Social:</strong><br/>
                            <input type="text" class="form-control" id="RazaoSocial" name="RazaoSocial"
                                   placeholder="RazaoSocial">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6"><strong>CNPJ:</strong><br/>
                            <input type="text" readonly class="form-control" id="CNPJ" name="CNPJ" placeholder="CNPJ"
                                   value="<?= $cnpj ?>">
                        </div>
                        <div class="col-md-6"><strong>CCM:</strong><br/>
                            <input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6"><strong>CEP *:</strong><br/>
                            <input type="text" class="form-control" id="CEP" name="CEP" placeholder="XXXXX-XXX">
                        </div>
                        <div class=" col-md-6"><strong>Estado *:</strong><br/>
                            <input type="text" class="form-control" id="Estado" name="Estado" placeholder="Estado">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Endereço *:</strong><br/>
                            <input type="text" class="form-control" id="Endereco" name="Endereco"
                                   placeholder="Endereço">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6"><strong>Número *:</strong><br/>
                            <input type="text" class="form-control" id="Numero" name="Numero" placeholder="Numero">
                        </div>
                        <div class=" col-md-6"><strong>Complemento:</strong><br/>
                            <input type="text" class="form-control" id="Complemento" name="Complemento"
                                   placeholder="Complemento">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6"><strong>Bairro *:</strong><br/>
                            <input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
                        </div>
                        <div class=" col-md-6"><strong>Cidade *:</strong><br/>
                            <input type="text" class="form-control" id="Cidade" name="Cidade" placeholder="Cidade">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6"><strong>Telefone:</strong><br/>
                            <input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );"
                                   maxlength="15" name="Telefone1" placeholder="Exemplo: (11) 98765-4321">
                        </div>
                        <div class=" col-md-6"><strong>Telefone:</strong><br/>
                            <input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );"
                                   maxlength="15" name="Telefone2" placeholder="Exemplo: (11) 98765-4321">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-6"><strong>Telefone:</strong><br/>
                            <input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );"
                                   maxlength="15" name="Telefone3" placeholder="Exemplo: (11) 98765-4321">
                        </div>
                        <div class=" col-md-6"><strong>E-mail:</strong><br/>
                            <input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Observações:</strong><br/>
                            <textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
                        </div>
                    </div>

                    <div class="col-md-offset-2 col-md-8">
                        <button class="btn btn-block btn-primary" name="cadastrar" id="cadastrar">CADASTRAR</button>
                    </div>
                </form>
            </div>
            <?php
        }
    }
    ?>
</section>