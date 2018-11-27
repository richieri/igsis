<?php

include 'includes/menu.php';

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

// Endereço da página
$link = "?perfil=m_formacao&p=frm_capac_compara";

$cpf_busca = $_POST['cpf'];

// Localiza no IGSIS
$sql1 = $con1->query("SELECT * FROM sis_pessoa_fisica where CPF = '$cpf_busca'");
$query1 = $sql1->fetch_array(MYSQLI_ASSOC);

$Nome = $query1["Nome"];
$NomeArtistico = $query1["NomeArtistico"];
$RG = $query1["RG"];
$CPF = $query1["CPF"];
$CCM = $query1["CCM"];
$DataNascimento = $query1["DataNascimento"];
$LocalNascimento = $query1["LocalNascimento"];
$Nacionalidade = $query1["Nacionalidade"];
$CEP = $query1["CEP"];
$Numero = $query1["Numero"];
$Complemento = $query1["Complemento"];
$Telefone1 = $query1["Telefone1"];
$Telefone2 = $query1["Telefone2"];
$Telefone3 = $query1["Telefone3"];
$Email = $query1["Email"];
$DRT = $query1["DRT"];
$Funcao = $query1["Funcao"];
$Pis = $query1["Pis"];
$OMB = $query1["OMB"];
$DataAtualizacao = $query1["DataAtualizacao"];
$tipoDocumento = $query1["tipoDocumento"];
$codBanco = $query1["codBanco"];
$Agencia = $query1["agencia"];
$Conta = $query1["conta"];
$Cbo = $query1["cbo"];


//Localiza no proponente
$sql2 = $con2->query("SELECT * FROM pessoa_fisica where cpf = '$cpf_busca'");
$query2 = $sql2->fetch_array(MYSQLI_ASSOC);

$idPessoaMac = $query2['id'];
$nome = $query2["nome"];
$nomeArtistico = $query2["nomeArtistico"];
$rg = $query2["rg"];
$cpf = $query2["cpf"];
$ccm = $query2["ccm"];
$dataNascimento = $query2["dataNascimento"];
$localNascimento = $query2["localNascimento"];
$nacionalidade = $query2["nacionalidade"];
$cep = $query2["cep"];
$numero = $query2["numero"];
$complemento = $query2["complemento"];
$telefone1 = $query2["telefone1"];
$telefone2 = $query2["telefone2"];
$telefone3 = $query2["telefone3"];
$email = $query2["email"];
$drt = $query2["drt"];
$funcao = $query2["funcao"];
$pis = $query2["pis"];
$omb = $query2["omb"];
$dataAtualizacao = $query2["dataAtualizacao"];
$idTipoDocumento = $query2["idTipoDocumento"];
$codigoBanco = $query2["codigoBanco"];
$agencia = $query2["agencia"];
$conta = $query2["conta"];
$cbo = $query2["cbo"];

$sql_funcao = "SELECT ff.funcao FROM formacao_funcoes AS ff
                INNER JOIN pessoa_fisica AS pf ON ff.id = pf.formacao_funcao_id
                INNER JOIN pessoa_fisica AS p ON ff.tipo_formacao_id = p.tipo_formacao_id
                WHERE pf.id = '".$query2['id']."'";
$cargo = $con2->query($sql_funcao)->fetch_array();

//retorna uma array com os dados de qualquer tabela do IGSIS. Serve apenas para 1 registro.
function recuperaDadosIgsis($tabela_dados_ig,$campo_dados_ig,$variavelCampo_dados_ig)
{
    $con1 = bancoMysqli();
    $sql_dados_ig = "SELECT * FROM $tabela_dados_ig WHERE ".$campo_dados_ig." = '$variavelCampo_dados_ig' LIMIT 0,1";
    $query_dados_ig = mysqli_query($con1,$sql_dados_ig);
    $campo_dados_ig = mysqli_fetch_array($query_dados_ig);
    return $campo_dados_ig;
}

//retorna uma array com os dados de qualquer tabela do CAPAC. Serve apenas para 1 registro.
function recuperaDadosCapac($tabela,$campo,$variavelCampo)
{
    $con2 = bancoMysqliProponente();
    $sql = "SELECT * FROM $tabela WHERE ".$campo." = '$variavelCampo' LIMIT 0,1";
    $query = mysqli_query($con2,$sql);
    $campo = mysqli_fetch_array($query);
    return $campo;
}

function recuperaBanco($campoY)
{
    $banco = recuperaDadosCapac("banco",'id', $campoY);
    $nomeBanco = $banco['banco'];
    return $nomeBanco;
}

$estadoCivil = recuperaDadosCapac('estado_civil', 'id', $query2['idEstadoCivil']);
$etnia = recuperaDadosCapac('etnias', 'id', $query2['etnia_id']);
$grauInstrucao = recuperaDadosCapac('grau_instrucoes', 'id', $query2['grau_instrucao_id']);
$programa = recuperaDadosCapac('tipo_formacao', 'id', $query2['tipo_formacao_id']);
$documento = recuperaDadosCapac('tipo_documento', 'id', $query2['idTipoDocumento']);

if(isset($_POST['atualizaIgsis']))
{
    $campo = $_POST['campo'];
    $varCampo = $_POST['varCampo'];
    $nomeCampo = $_POST['nomeCampo'];
    $sql_update_nome = "UPDATE sis_pessoa_fisica SET ".$campo." = '$varCampo' WHERE CPF = '$cpf'";
    if(mysqli_query($con1,$sql_update_nome))
    {
        $mensagem =	$nomeCampo." atualizado com sucesso!";
        echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=".$link."&busca=".$cpf_busca."'>";
    }
    else
    {
        $mensagem = "Erro ao atualizar ".$nomeCampo.". Tente novamente!";
    }
}

if(isset($_POST['importarCapacIgsis']))
{
    $sql_insere_pf = "INSERT INTO sis_pessoa_fisica (`Nome`, `NomeArtistico`, `RG`, `CPF`, `CCM`, `DataNascimento`, `LocalNascimento`,	`Nacionalidade`, `CEP`,	`Numero`, `Complemento`, `Telefone1`, `Telefone2`, `Telefone3`, `Email`, `DRT`,	`Funcao`, `Pis`, `OMB`, `DataAtualizacao`,`tipoDocumento`, `codBanco`, `agencia`, `conta`, `cbo`) VALUES ('$nome', '$nomeArtistico', '$rg', '$cpf', '$ccm', '$dataNascimento', '$localNascimento', '$nacionalidade', '$cep', '$numero', '$complemento', '$telefone1', '$telefone2', '$telefone3', '$email', '$drt', '$funcao', '$pis', '$omb', '$dataAtualizacao', '$idTipoDocumento', '$codigoBanco', '$agencia', '$conta', '$cbo')";

    if(mysqli_query($con1,$sql_insere_pf))
    {
        $mensagem = "Importado com sucesso!";

        //gravarLog($sql_insert_pf);
        $sql_ultimo = "SELECT * FROM sis_pessoa_fisica ORDER BY Id_PessoaFisica DESC LIMIT 0,1"; //recupera ultimo id
        $query_ultimo = mysqli_query($con1,$sql_ultimo);
        $id = mysqli_fetch_array($query_ultimo);
        $idFisica = $id['Id_PessoaFisica'];
        $idEvento = $_SESSION['idEvento'];
        $sql_insert_pedido = "INSERT INTO `igsis_pedido_contratacao` (`idEvento`, `tipoPessoa`, `idPessoa`, `integrantes`, `publicado`) VALUES ('$idEvento', '1', '$idFisica', $integrantes, '1')";
        $query_insert_pedido = mysqli_query($con1,$sql_insert_pedido);
        if($query_insert_pedido)
        {
            gravarLog($sql_insert_pedido);
            $mensagem = "Inserido com sucesso!";
            echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=contratados'>";
        }
        else
        {
            $mensagem = "Erro ao importar! Tente novamente. [COD-01]";
        }
    }
    else
    {
        $mensagem = "Erro ao importar! Tente novamente.";
    }
}

//Se existir no CAPAC e não no IGSIS
if($query1 == '' && $query2 != '')
{
    ?>
    <section id="list_items" class="home-section bg-white">
        <div class="container">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <h6>Não foi encontrado registro com o CPF <strong><?php echo $cpf_busca ?></strong> no IGSIS. Deseja realmente importar do CAPAC?</h6>
                    <h5><?php if(isset($mensagem)){echo $mensagem;}; ?></h5>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-6">
                    <form method='POST' action='?perfil=formacao&p=frm_capac_compara' enctype='multipart/form-data'>
                        <input type='hidden' name='busca' value='<?php echo $cpf_busca ?>'>
                        <input type='submit' name='importarCapacIgsis' class='btn btn-theme btn-lg btn-block' value='Importar'>
                    </form><br/>
                </div>
                <div class="col-md-6">
                    <form method='POST' action='?perfil=m_formacao&p=frm_capac_importar' enctype='multipart/form-data'>
                        <input type='submit' name='' class='btn btn-theme btn-lg btn-block' value='Voltar'>
                    </form>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-8"><hr/></div>
            </div>

            <div class="container">
                <div class="page-header"> <h5>Informações Pessoais</h5><br></div>
                <div class="well">
                    <p align="justify"><strong>Nome:</strong> <?= $query2['nome']; ?></p>
                    <p align="justify"><strong>Nome artístico:</strong> <?= $query2['nomeArtistico']; ?></p>
                    <p align="justify"><strong>Data de Nascimento:</strong> <?= date_format(date_create($query2['dataNascimento']), 'd/m/Y'); ?></p>
                    <p align="justify"><strong>RG:</strong> <?= $query2['rg']; ?><p>
                    <p align="justify"><strong>CPF:</strong> <?= $query2['cpf']; ?><p>
                    <p align="justify"><strong>CCM:</strong> <?= $query2['ccm']; ?><p>
                    <p align="justify"><strong>Email:</strong> <?= $query2['email']; ?><p>
                    <p align="justify"><strong>Telefone:</strong> <?= $query2['telefone1']; ?><p>
                    <p align="justify"><strong>Estado Civil:</strong> <?= $estadoCivil['estadoCivil']; ?><p>
                    <p align="justify"><strong>Nacionalidade:</strong> <?= $query2['nacionalidade']; ?><p>
                    <p align="justify"><strong>PIS/PASEP/NIT:</strong> <?= $query2['pis']; ?><p>
                    <p align="justify"><strong>Programa Selecionado:</strong> <?= $programa['descricao']; ?><p>

                <div class = "page-header"><h5>Endereço: </h5><br></div>
                    <p align="justify"><strong>CEP:</strong> <?php echo $query2['cep']; ?></p>
                    <p align="justify"><strong>Logradouro:</strong> <?php echo $query2['logradouro']; ?></p>
                    <p align="justify"><strong>Número:</strong> <?php echo $query2['numero']; ?></p>
                    <p align="justify"><strong>Complemento:</strong> <?php echo $query2['complemento']; ?></p>
                    <p align="justify"><strong>Bairro:</strong> <?php echo $query2['bairro']; ?></p>
                    <p align="justify"><strong>Cidade:</strong> <?php echo $query2['cidade']; ?></p>
                    <p align="justify"><strong>Estado:</strong> <?php echo $query2['estado']; ?></p>

                    <div class = "page-header"><h5>Informações Complementares: </h5><br></div>
                    <p align="justify"><strong>DRT:</strong> <?php echo $query2['drt']; ?></p>
                    <p align="justify"><strong>Etnia:</strong> <?= $etnia['etnia']; ?><p>
                    <p align="justify"><strong>Grau de Instrução:</strong> <?= $grauInstrucao['grau_instrucao']; ?><p>
                    <p align="justify"><strong>Função:</strong> <?= $cargo[0]; ?><p>
                    <p align="justify"><strong>Banco:</strong> <?php echo recuperaBanco($query2['codigoBanco']); ?></p>
                    <p align="justify"><strong>Agência:</strong> <?php echo $query2['agencia']; ?></p>
                    <p align="justify"><strong>Conta:</strong> <?php echo $query2['conta']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-8"><hr/></div>
            </div>

        </div>
    </section>
    <?php
}

//Se existir no CAPAC e também no IGSIS
If($query1 != '' && $query2 != '')
{
    ?>
    <section id="list_items" class="home-section bg-white">
        <div class="container">
            <p>O CPF nº <strong><?php echo $cpf_busca ?></strong> possui cadastro no sistema CAPAC e no IGSIS. Abaixo está a lista com as divergências apontadas o cadastro.</p>
            <div class="table-responsive list_info">
                <h6>Divergências</h6>
                <h5><?php if(isset($mensagem)){echo $mensagem;}; ?></h5>
                <table class='table table-condensed'>
                    <thead>
                    <tr class='list_menu'>
                        <td><strong>Campo Divergente</strong></td>
                        <td><strong>CAPAC</strong></td>
                        <td><strong>IGSIS</strong></td>
                        <td width='20%'></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($Nome != $nome)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Nome</td>";
                        echo "<td class='list_description'>".$nome."</td>";
                        echo "<td class='list_description'>".$Nome."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Nome' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='Nome'  />
										<input type='hidden' name='varCampo' value='".$nome."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($NomeArtistico != $nomeArtistico)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Nome Artístico</td>";
                        echo "<td class='list_description'>".$nomeArtistico."</td>";
                        echo "<td class='list_description'>".$NomeArtistico."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Nome Artístico' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='NomeArtistico'  />
										<input type='hidden' name='varCampo' value='".$nomeArtistico."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($RG != $rg)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>RG</td>";
                        echo "<td class='list_description'>".$rg."</td>";
                        echo "<td class='list_description'>".$RG."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='RG' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='RG'  />
										<input type='hidden' name='varCampo' value='".$rg."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($CCM != $ccm)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>CCM</td>";
                        echo "<td class='list_description'>".$ccm."</td>";
                        echo "<td class='list_description'>".$CCM."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='CCM' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='CCM'  />
										<input type='hidden' name='varCampo' value='".$ccm."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($DataNascimento != $dataNascimento)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Data de Nascimento</td>";
                        echo "<td class='list_description'>".exibirDataBr($dataNascimento)."</td>";
                        echo "<td class='list_description'>".exibirDataBr($DataNascimento)."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Data de Nascimento' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='DataNascimento'  />
										<input type='hidden' name='varCampo' value='".$dataNascimento."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($LocalNascimento != $localNascimento)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Local de Nascimento</td>";
                        echo "<td class='list_description'>".$localNascimento."</td>";
                        echo "<td class='list_description'>".$LocalNascimento."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Local de Nascimento' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='LocalNascimento'  />
										<input type='hidden' name='varCampo' value='".$localNascimento."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Nacionalidade != $nacionalidade)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Nacionalidade</td>";
                        echo "<td class='list_description'>".$nacionalidade."</td>";
                        echo "<td class='list_description'>".$Nacionalidade."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Nacionalidade' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='Nacionalidade'  />
										<input type='hidden' name='varCampo' value='".$nacionalidade."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($CEP != $cep)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>CEP</td>";
                        echo "<td class='list_description'>".$cep."</td>";
                        echo "<td class='list_description'>".$CEP."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='CEP' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />
										<input type='hidden' name='campo' value='CEP'  />
										<input type='hidden' name='varCampo' value='".$cep."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Numero != $numero)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Número</td>";
                        echo "<td class='list_description'>".$numero."</td>";
                        echo "<td class='list_description'>".$Numero."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Número' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Numero'  />
										<input type='hidden' name='varCampo' value='".$numero."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Complemento != $complemento)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Complemento</td>";
                        echo "<td class='list_description'>".$complemento."</td>";
                        echo "<td class='list_description'>".$Complemento."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Complemento' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Complemento'  />
										<input type='hidden' name='varCampo' value='".$complemento."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Telefone1 != $telefone1)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Telefone #1</td>";
                        echo "<td class='list_description'>".$telefone1."</td>";
                        echo "<td class='list_description'>".$Telefone1."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Telefone #1' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Telefone1'  />
										<input type='hidden' name='varCampo' value='".$telefone1."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Telefone2 != $telefone2)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Telefone #2</td>";
                        echo "<td class='list_description'>".$telefone2."</td>";
                        echo "<td class='list_description'>".$Telefone2."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Telefone #2' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Telefone2'  />
										<input type='hidden' name='varCampo' value='".$telefone2."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Telefone3 != $telefone3)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Telefone #3</td>";
                        echo "<td class='list_description'>".$telefone3."</td>";
                        echo "<td class='list_description'>".$Telefone3."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Telefone #3' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Telefone3'  />
										<input type='hidden' name='varCampo' value='".$telefone3."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Email != $email)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>E-mail</td>";
                        echo "<td class='list_description'>".$email."</td>";
                        echo "<td class='list_description'>".$Email."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='E-mail' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Email'  />
										<input type='hidden' name='varCampo' value='".$email."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($DRT != $drt)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>DRT</td>";
                        echo "<td class='list_description'>".$drt."</td>";
                        echo "<td class='list_description'>".$DRT."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='DRT' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='DRT'  />
										<input type='hidden' name='varCampo' value='".$drt."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Funcao != $funcao)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Função</td>";
                        echo "<td class='list_description'>".$funcao."</td>";
                        echo "<td class='list_description'>".$Funcao."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Função' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Funcao'  />
										<input type='hidden' name='varCampo' value='".$funcao."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Cbo != $cbo)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>CBO</td>";
                        echo "<td class='list_description'>".$cbo."</td>";
                        echo "<td class='list_description'>".$Cbo."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='CBO' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Cbo'  />
										<input type='hidden' name='varCampo' value='".$cbo."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Pis != $pis)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>NIT</td>";
                        echo "<td class='list_description'>".$pis."</td>";
                        echo "<td class='list_description'>".$Pis."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='NIT' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Pis'  />
										<input type='hidden' name='varCampo' value='".$pis."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($OMB != $omb)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>OMB</td>";
                        echo "<td class='list_description'>".$omb."</td>";
                        echo "<td class='list_description'>".$OMB."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='OMB' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='OMB'  />
										<input type='hidden' name='varCampo' value='".$omb."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($codBanco != $codigoBanco)
                    {
                        $igNomeBanco = recuperaDadosIgsis("igsis_bancos","ID","$codBanco");
                        $propNomeBanco = recuperaDadosCapac("banco","id","$codigoBanco");
                        echo "<tr>";
                        echo "<td class='list_description'>Banco</td>";
                        echo "<td class='list_description'>".$propNomeBanco['banco']."</td>";
                        echo "<td class='list_description'>".$igNomeBanco['banco']."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Banco' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='codBanco'  />
										<input type='hidden' name='varCampo' value='".$codigoBanco."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Agencia != $agencia)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Agência</td>";
                        echo "<td class='list_description'>".$agencia."</td>";
                        echo "<td class='list_description'>".$Agencia."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Agência' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Agencia'  />
										<input type='hidden' name='varCampo' value='".$agencia."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($Conta != $conta)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Conta</td>";
                        echo "<td class='list_description'>".$conta."</td>";
                        echo "<td class='list_description'>".$Conta."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Conta' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='Conta'  />
										<input type='hidden' name='varCampo' value='".$conta."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    if($DataAtualizacao != $dataAtualizacao)
                    {
                        echo "<tr>";
                        echo "<td class='list_description'>Última Atualização</td>";
                        echo "<td class='list_description'>".exibirDataBr($dataAtualizacao)."</td>";
                        echo "<td class='list_description'>".exibirDataBr($DataAtualizacao)."</td>";
                        echo "<td>
									<form method='POST' action='".$link."&busca=".$cpf_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Última Atualização' />
										<input type='hidden' name='busca' value='".$cpf_busca."'  />	
										<input type='hidden' name='campo' value='DataAtualizacao'  />
										<input type='hidden' name='varCampo' value='".$dataAtualizacao."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <h5>Lista de Arquivos Anexados pelo CAPAC</h5>
                    <div class="table-responsive list_info">
                        <?php
                        $sql = "SELECT *
								FROM upload_lista_documento as list
								INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
								WHERE arq.idPessoa = '$idPessoaMac'
								AND arq.idTipoPessoa = '6'
								AND arq.publicado = '1'";
                        $query = mysqli_query($con2,$sql);
                        $linhas = mysqli_num_rows($query);

                        if ($linhas > 0)
                        {
                            echo "
								<table class='table table-condensed'>

									<tbody>";
                            while($arquivo = mysqli_fetch_array($query))
                            {
                                echo "<tr>";
                                echo "<td align = 'left' class='list_description'><a href='../../proponente/uploadsdocs/".$arquivo['arquivo']."' target='_blank'>".$arquivo['arquivo']."</a> (".$arquivo['documento'].")</td>";
                                echo "</tr>";
                            }
                            echo "
									</tbody>
								</table>";
                        }
                        else
                        {
                            echo "<p>Não há arquivo(s) inserido(s).<p/><br/>";
                        }
                        ?>
                        <a href="../include/arquivos_pessoa_capac.php?idPessoa=<?php echo $idPessoaMac ?>&tipo=1" class="btn btn-theme btn-md btn-block" target="_blank">Baixar todos os arquivos</a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if(isset($_POST['edicaoPessoa']))
        {
            $_SESSION['edicaoPessoa'] = $_POST['edicaoPessoa'];
        }
        $edicaoPessoa = $_SESSION['edicaoPessoa'];

        if($edicaoPessoa == 1)
        {
            ?>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <form method='POST' action='?perfil=contratados'>
                        <input type='submit' class='btn btn-theme btn-lg btn-block' value='voltar para a lista de contratados'>
                    </form>
                </div>
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-6">
                    <form method='POST' action='?perfil=contratados&p=fisica'>
                        <input type='submit' class='btn btn-theme btn-lg btn-block' value='Pesquisar outro cpf'>
                    </form>
                </div>
                <div class="col-md-6">
                    <form method='POST' action='?perfil=contratados&p=lista'>
                        <input type='hidden' name='insereFisica' value='1'>
                        <input type='hidden' name='Id_PessoaFisica' value='<?php echo $query1['Id_PessoaFisica'] ?>'>
                        <input type ='submit' class='btn btn-theme btn-lg btn-block' value='Criar Pedido'>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </section>
    <?php
}
?>