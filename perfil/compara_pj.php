<?php

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

// Endereço da página
$link = "?perfil=compara_pj";

function comparaArquivosOficineiros ($query, $queryOficineiro)
{
    $registrosPf = [];
    $registrosOficineiro = [];
    while ($registroPf = mysqli_fetch_assoc($query))
    {
        array_push($registrosPf, $registroPf);
    }
    while ($registroOficineiro = mysqli_fetch_assoc($queryOficineiro))
    {
        array_push($registrosOficineiro, $registroOficineiro);
    }
    echo "
								<table class='table table-condensed'>

									<tbody>";
    foreach ($registrosPf as $documentoPf => $arquivoPf)
    {
        foreach ($registrosOficineiro as $documentoPfOficineiro => $arquivoPfOficineiro)
        {
            if ($arquivoPf['documento'] == $arquivoPfOficineiro['documento'])
            {
                if ($arquivoPf['dataEnvio'] > $arquivoPfOficineiro['dataEnvio'])
                {
                    echo "<tr>";
                    echo "<td align = 'left' class='list_description'><a href='../../proponente/uploadsdocs/".$arquivoPf['arquivo']."' target='_blank'>".$arquivoPf['arquivo']."</a> (".$arquivoPf['documento'].")</td>";
                    echo "</tr>";
                    unset($registrosOficineiro[$documentoPfOficineiro]);
                    unset($registrosPf[$documentoPf]);
                }
                else
                {
                    echo "<tr>";
                    echo "<td align = 'left' class='list_description'><a href='../../proponente/uploadsdocs/".$arquivoPfOficineiro['arquivo']."' target='_blank'>".$arquivoPfOficineiro['arquivo']."</a> (".$arquivoPfOficineiro['documento'].")</td>";
                    echo "</tr>";
                    unset($registrosOficineiro[$documentoPfOficineiro]);
                    unset($registrosPf[$documentoPf]);
                }
            }
        }
    }
    $documentos = array_merge($registrosPf, $registrosOficineiro);
    foreach ($documentos as $arquivo)
    {
        echo "<tr>";
        echo "<td align = 'left' class='list_description'><a href='../../proponente/uploadsdocs/".$arquivo['arquivo']."' target='_blank'>".$arquivo['arquivo']."</a> (".$arquivo['documento'].")</td>";
        echo "</tr>";
    }
}

// inicia a busca por CNPJ
If($_GET['busca'] == '')
{
	//validação
	$validacao = validaCNPJ($_POST['busca']);
	if($validacao == false)
	{
		echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?perfil=contratados&p=erro_pj'>";
	}
	else
	{
		$cnpj_busca = $_POST['busca'];//original
		//$cnpj_busca = "88.888.888/0001-88";//Se existir no IGSIS e não no CAPAC
		//$cnpj_busca = "88.888.888/8888-88";//Se existir no CAPAC e não no IGSIS
		//$cnpj_busca = "00.000.000/0000-00";//Se existir no CAPAC e também no IGSIS
		//$cnpj_busca = "12.345.678/0001-99";//Se não existir no IGSIS e nem no CAPAC
	}
}
else
{
	$cnpj_busca = $_GET['busca'];
}


// Localiza no IGSIS
$sql1 = $con1->query("SELECT * FROM sis_pessoa_juridica where CNPJ = '$cnpj_busca'");
$query1 = $sql1->fetch_array(MYSQLI_ASSOC);

$RazaoSocial = $query1["RazaoSocial"];
$CNPJ = $query1["CNPJ"];
$CCM = $query1["CCM"];
$CEP = $query1["CEP"];
$Numero = $query1["Numero"];
$Complemento = $query1["Complemento"];
$Telefone1 = $query1["Telefone1"];
$Telefone2 = $query1["Telefone2"];
$Telefone3 = $query1["Telefone3"];
$Email = $query1["Email"];
$IdRepresentanteLegal1 = $query1["IdRepresentanteLegal1"];
$IdRepresentanteLegal2 = $query1["IdRepresentanteLegal2"];
$DataAtualizacao = $query1["DataAtualizacao"];
$codBanco = $query1["codBanco"];
$Agencia = $query1["agencia"];
$Conta = $query1["conta"];

//Recupera representante 1
$sqlRep1 = $con1->query("SELECT * FROM sis_representante_legal where Id_RepresentanteLegal = '$IdRepresentanteLegal1'");
$rep1 = $sqlRep1->fetch_array(MYSQLI_ASSOC);

$NomeRep1 = addslashes($rep1['RepresentanteLegal']);
$RgRep1 = $rep1['RG'];
$CpfRep1 = $rep1['CPF'];

//Recupera representante 2
$sqlRep2 = $con1->query("SELECT * FROM sis_representante_legal where Id_RepresentanteLegal = '$IdRepresentanteLegal2'");
$rep2 = $sqlRep2->fetch_array(MYSQLI_ASSOC);

$NomeRep2 = addslashes($rep2['RepresentanteLegal']);
$RgRep2 = $rep2['RG'];
$CpfRep2 = $rep2['CPF'];


//Localiza no proponente
$sql2 = $con2->query("SELECT * FROM pessoa_juridica where cnpj = '$cnpj_busca'");
$query2 = $sql2->fetch_array(MYSQLI_ASSOC);

$idPessoaMac = $query2['id'];
$razaoSocial = $query2['razaoSocial'];
$cnpj = $query2['cnpj'];
$ccm = $query2["ccm"];
$cep = $query2["cep"];
$numero = $query2["numero"];
$complemento = $query2["complemento"];
$telefone1 = $query2["telefone1"];
$telefone2 = $query2["telefone2"];
$telefone3 = $query2["telefone3"];
$email = $query2["email"];
$idRepresentanteLegal1 = $query2["idRepresentanteLegal1"];
$idRepresentanteLegal2 = $query2["idRepresentanteLegal2"];
$dataAtualizacao = $query2["dataAtualizacao"];
$codigoBanco = $query2["codigoBanco"];
$agencia = $query2["agencia"];
$conta = $query2["conta"];

//Recupera representante 1
$sqlRep1 = $con2->query("SELECT * FROM representante_legal where id = '$idRepresentanteLegal1'");
$rep1 = $sqlRep1->fetch_array(MYSQLI_ASSOC);

$nomeRep1 = addslashes($rep1['nome']);
$rgRep1 = $rep1['rg'];
$cpfRep1 = $rep1['cpf'];

//Recupera representante 2
$sqlRep2 = $con2->query("SELECT * FROM representante_legal where id = '$idRepresentanteLegal2'");
$rep2 = $sqlRep2->fetch_array(MYSQLI_ASSOC);

$nomeRep2 = addslashes($rep2['nome']);
$rgRep2 = $rep2['rg'];
$cpfRep2 = $rep2['cpf'];


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
function recuperaDadosProp($tabela,$campo,$variavelCampo)
{
	$con2 = bancoMysqliProponente();
	$sql = "SELECT * FROM $tabela WHERE ".$campo." = '$variavelCampo' LIMIT 0,1";
	$query = mysqli_query($con2,$sql);
	$campo = mysqli_fetch_array($query);
	return $campo;
}

if(isset($_POST['atualizaIgsis']))
{
	$campo = $_POST['campo'];
	$varCampo = $_POST['varCampo'];
	$nomeCampo = $_POST['nomeCampo'];
	$sql_update_nome = "UPDATE sis_pessoa_juridica SET ".$campo." = '$varCampo' WHERE CNPJ = '$cnpj'";
	if(mysqli_query($con1,$sql_update_nome))
	{
		$mensagem =	$nomeCampo." atualizado com sucesso!";
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=".$link."&busca=".$cnpj_busca."'>";
	}
	else
	{
		$mensagem = "Erro ao atualizar ".$nomeCampo.". Tente novamente!";
	}
}

if(isset($_POST['importarRep01']))
{
	$campo = $_POST['campo'];
	$varCampo = $_POST['varCampo'];
	$nomeCampo = $_POST['nomeCampo'];
	$rep1 = recuperaDadosProp("representante_legal","id",$varCampo);
	$nomeRep1 = addslashes($rep1['nome']);
	$rgRep1 = $rep1['rg'];
	$cpfRep1 = $rep1['cpf'];

	$sql_verifica = "SELECT * FROM sis_representante_legal WHERE CPF = '$cpfRep1'";
	$query_verifica = mysqli_query($con1,$sql_verifica);
	$num = mysqli_num_rows($query_verifica);
	if($num > 0)
	{
		$sql_insere_rep01 = "UPDATE sis_representante_legal SET RepresentanteLegal = '$nomeRep1', RG = '$rgRep1' WHERE CPF = '$cpfRep1'";
		//recupera id
		$sql_ultimo = "SELECT * FROM sis_representante_legal WHERE CPF = '$cpfRep1'";
		$query_ultimo = mysqli_query($con1,$sql_ultimo);
		$id = mysqli_fetch_array($query_ultimo);
		$idRepresentanteLegal1 = $id['Id_RepresentanteLegal'];
	}
	else
	{
		$sql_insere_rep01 = "INSERT INTO `sis_representante_legal`(`RepresentanteLegal`, `RG`, `CPF`) VALUES ('$nomeRep1', '$rgRep1', '$cpfRep1')";
		//recupera ultimo id
		$sql_ultimo = "SELECT * FROM sis_representante_legal WHERE CPF = '$cpfRep1'";
		$query_ultimo = mysqli_query($con1,$sql_ultimo);
		$id = mysqli_fetch_array($query_ultimo);
		$idRepresentanteLegal1 = $id['Id_RepresentanteLegal'];
	}

	if(mysqli_query($con1,$sql_insere_rep01))
	{
		$sql_update_nome = "UPDATE sis_pessoa_juridica SET ".$campo." = '$idRepresentanteLegal1' WHERE CNPJ = '$cnpj'";
		if(mysqli_query($con1,$sql_update_nome))
		{
			$mensagem =	$nomeCampo." atualizado com sucesso!";
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=".$link."&busca=".$cnpj_busca."'>";
		}
		else
		{
			$mensagem = "Erro ao atualizar ".$nomeCampo.". Tente novamente!";
		}
	}
	else
	{
		$mensagem = "Erro ao atualizar ".$nomeCampo.". Tente novamente!";
	}
}

if(isset($_POST['importarRep02']))
{
	$campo = $_POST['campo'];
	$varCampo = $_POST['varCampo'];
	$nomeCampo = $_POST['nomeCampo'];
	$rep2 = recuperaDadosProp("representante_legal","id",$varCampo);
	$nomeRep2 = addslashes($rep1['nome']);
	$rgRep2 = $rep1['rg'];
	$cpfRep2 = $rep1['cpf'];

	$sql_verifica = "SELECT * FROM sis_representante_legal WHERE CPF = '$cpfRep2'";
	$query_verifica = mysqli_query($con1,$sql_verifica);
	$num = mysqli_num_rows($query_verifica);
	if($num > 0)
	{
		$sql_insere_rep02 = "UPDATE sis_representante_legal SET RepresentanteLegal = '$nomeRep2', RG = '$rgRep2' WHERE CPF = '$cpfRep2'";
		//recupera id
		$sql_ultimo = "SELECT * FROM sis_representante_legal WHERE CPF = '$cpfRep2'";
		$query_ultimo = mysqli_query($con1,$sql_ultimo);
		$id = mysqli_fetch_array($query_ultimo);
		$idRepresentanteLegal2 = $id['Id_RepresentanteLegal'];
	}
	else
	{
		$sql_insere_rep02 = "INSERT INTO `sis_representante_legal`(`RepresentanteLegal`, `RG`, `CPF`) VALUES ('$nomeRep2', '$rgRep2', '$cpfRep2')";
		//recupera ultimo id
		$sql_ultimo = "SELECT * FROM sis_representante_legal WHERE CPF = '$cpfRep2'";
		$query_ultimo = mysqli_query($con1,$sql_ultimo);
		$id = mysqli_fetch_array($query_ultimo);
		$idRepresentanteLegal2 = $id['Id_RepresentanteLegal'];
	}

	if(mysqli_query($con1,$sql_insere_rep02))
	{
		$sql_update_nome = "UPDATE sis_pessoa_juridica SET ".$campo." = '$idRepresentanteLegal2' WHERE CNPJ = '$cnpj'";
		if(mysqli_query($con1,$sql_update_nome))
		{
			$mensagem =	$nomeCampo." atualizado com sucesso!";
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=".$link."&busca=".$cnpj_busca."'>";
		}
		else
		{
			$mensagem = "Erro ao atualizar ".$nomeCampo.". Tente novamente!".$sql_update_nome;
		}
	}
	else
	{
		$mensagem = "Erro ao atualizar ".$nomeCampo.". Tente novamente!".$sql_insere_rep01;
	}
}

if(isset($_POST['importarCapacIgsis']))
{
	$sql_insere_rep01 = "INSERT INTO `sis_representante_legal`(`RepresentanteLegal`, `RG`, `CPF`) VALUES ('$nomeRep1', '$rgRep1', '$cpfRep1')";
	if(mysqli_query($con1,$sql_insere_rep01))
	{
		//recupera ultimo id
		$sql_ultimo = "SELECT * FROM sis_representante_legal ORDER BY Id_RepresentanteLegal DESC LIMIT 0,1";
		$query_ultimo = mysqli_query($con1,$sql_ultimo);
		$id = mysqli_fetch_array($query_ultimo);
		$idRepresentanteLegal1 = $id['Id_RepresentanteLegal'];

		if($nomeRep2 != '')
		{
			$sql_insere_rep02 = "INSERT INTO `sis_representante_legal`(`RepresentanteLegal`, `RG`, `CPF`) VALUES ('$nomeRep2', '$rgRep2', '$cpfRep2')";
			$sql_ultimo = "SELECT * FROM sis_representante_legal ORDER BY Id_RepresentanteLegal DESC LIMIT 0,1"; //recupera ultimo id
			$query_ultimo = mysqli_query($con1,$sql_ultimo);
			$id = mysqli_fetch_array($query_ultimo);
			$idRepresentanteLegal2 = $id['Id_RepresentanteLegal'];
		}
		else
		{
			$idRepresentanteLegal2 == NULL;
		}
		$sql_insere_pj = "INSERT INTO `sis_pessoa_juridica`(`RazaoSocial`, `CNPJ`, `CCM`, `CEP`, `Numero`, `Complemento`, `Telefone1`, `Telefone2`, `Telefone3`, `Email`, `IdRepresentanteLegal1`, `IdRepresentanteLegal2`, `DataAtualizacao`, `codBanco`, `agencia`, `conta`) VALUES ('$razaoSocial', '$cnpj', '$ccm', '$cep', '$numero', '$complemento', '$telefone1', '$telefone2', '$telefone3', '$email', '$idRepresentanteLegal1', '$idRepresentanteLegal2', '$dataAtualizacao', '$codigoBanco', '$agencia', '$conta')";
		if(mysqli_query($con1,$sql_insere_pj))
		{
			$mensagem = "Importado com sucesso!";

			//gravarLog($sql_insert_pf);
			$sql_ultimo = "SELECT * FROM sis_pessoa_juridica ORDER BY Id_PessoaJuridica DESC LIMIT 0,1"; //recupera ultimo id
			$query_ultimo = mysqli_query($con1,$sql_ultimo);
			$id = mysqli_fetch_array($query_ultimo);
			$idJuridica = $id['Id_PessoaJuridica'];
			$idEvento = $_SESSION['idEvento'];
			$sql_insert_pedido = "INSERT INTO `igsis_pedido_contratacao` (`idEvento`, `tipoPessoa`, `idPessoa`, `publicado`) VALUES ('$idEvento', '2', '$idJuridica', '1')";
			$query_insert_pedido = mysqli_query($con1,$sql_insert_pedido);
			if($query_insert_pedido)
			{
				gravarLog($sql_insert_pedido);
				$mensagem = "Inserido com sucesso!";
				if(isset($_SESSION['edicaoPessoa']))
				{
					$edicaoPessoa = $_SESSION['edicaoPessoa'];
					if($edicaoPessoa = 2)
					{
						$cpfPf = $_SESSION['cpfPf'];
						$sql_pedido = "SELECT  * FROM igsis_pedido_contratacao ORDER BY idPedidoContratacao DESC LIMIT 0,1";
						$query_pedido = mysqli_query($con1,$sql_pedido);
						$pedido = mysqli_fetch_array($query_pedido);
						$id_ped = $pedido['idPedidoContratacao'];
						echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=compara_executante$busca=".$cpfPf."&id_ped=".$id_ped."'>";
					}
				}
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
	else
	{
		$mensagem = "Erro ao importar! Tente novamente. [COD-02]";
	}
}

//Se existir no IGSIS e não no CAPAC
If($query1 != '' && $query2 == '')
{
?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h4>Contratados - Pessoa Jurídica</h4>
						<p></p>
					</div>
				</div>
			</div>

			<div class="table-responsive list_info">
				<table class="table table-condensed">
					<thead>
						<tr class="list_menu">
							<td>Razão Social</td>
							<td>CNPJ</td>
							<td width="25%"></td>
							<td width="5%"></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class='list_description'><b><?php echo $RazaoSocial ?></b></td>
							<td class='list_description'><b><?php echo $CNPJ ?></b></td>
							<td class='list_description'>
								<form method='POST' action='?perfil=contratados&p=lista'>
								<input type='hidden' name='insereJuridica' value='1'>
								<input type='hidden' name='Id_PessoaJuridica' value='<?php echo $query1['Id_PessoaJuridica'] ?>'>
								<input type ='submit' class='btn btn-theme btn-md btn-block' value='inserir'></form>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
<?php
}

//Se existir no CAPAC e não no IGSIS
If($query1 == '' && $query2 != '')
{
	?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<h6>Não foi encontrado registro com o CNPJ <strong><?php echo $cnpj_busca ?></strong> no IGSIS. Deseja importar do CAPAC?</h6>
					<h5><?php if(isset($mensagem)){echo $mensagem;}; ?></h5>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-6">
					<form method='POST' action='?perfil=contratados&p=juridica' enctype='multipart/form-data'>
						<input type='submit' name='' class='btn btn-theme btn-lg btn-block' value='Voltar'>
					</form><br/>
				</div>
				<div class="col-md-6">
					<form method='POST' action='<?php echo $link ?>' enctype='multipart/form-data'>
						<input type='hidden' name='busca' value='<?php echo $cnpj_busca ?>'>
						<input type='submit' name='importarCapacIgsis' class='btn btn-theme btn-lg btn-block' value='Importar'>
					</form>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-8"><hr/></div>
			</div>

			<div align="left">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<strong>Razão Social:</strong> <?php echo $razaoSocial ?><br/>
						<strong>CNPJ:</strong> <?php echo $cnpj ?> |
						<strong>CCM:</strong> <?php echo $ccm ?><br/>
						<strong>CEP:</strong> <?php echo $cep ?> |
						<strong>Número:</strong> <?php echo $numero ?> |
						<strong>Complemento:</strong> <?php echo $complemento ?><br/>
						<strong>Telefone #1:</strong> <?php echo $telefone1 ?> / <?php echo $telefone2 ?> / <?php echo $telefone3 ?><br/>
						<strong>E-mail:</strong> <?php echo $email ?><br/>
						<strong>Representante Legal #1:</strong> <?php echo $idRepresentanteLegal1 ?><br/>
						<strong>Representante Legal #2:</strong> <?php echo $idRepresentanteLegal2 ?><br/>
						<strong>Banco:</strong> <?php echo $codigoBanco ?><br/>
						<strong>Agência:</strong> <?php echo $agencia ?> |
						<strong>Conta:</strong> <?php echo $conta ?><br/>
						<strong>Última Atualização:</strong> <?php echo exibirDataBr($dataAtualizacao) ?>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<h5>Lista de Arquivos Anexados pelo CAPAC</h5>
					<p><i>Obs.: Os arquivos não são importados automaticamente. Faça o download e verifique os mesmos antes de efetuar o upload.</i></p>
					<div align="left">
						<div class="table-responsive list_info">
                            <?php
                            $sql = "SELECT *
								FROM upload_lista_documento as list
								INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
								WHERE arq.idPessoa = '$idPessoaMac'
								AND arq.idTipoPessoa = '2'
								AND arq.publicado = '1'";
                            $query = mysqli_query($con2,$sql);
                            $linhas = mysqli_num_rows($query);

                            $sqlOficineiro = "SELECT *
								FROM upload_lista_documento as list
								INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
								WHERE arq.idPessoa = '$idPessoaMac'
								AND arq.idTipoPessoa = '5'
								AND arq.publicado = '1'";
                            $queryOficineiro = $con2->query($sqlOficineiro);
                            $linhasOficineiro = $queryOficineiro->num_rows;

                            if ($linhas > 0)
                            {
                                if ($linhasOficineiro > 0)
                                {
                                    $tipoPessoa = [2,5];
                                    comparaArquivosOficineiros($query, $queryOficineiro);
                                    echo "
									</tbody>
								</table>";
                                }
                                else
                                {
                                    $tipoPessoa = [2];
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
                            }
                            elseif ($linhasOficineiro > 0)
                            {
                                $tipoPessoa = [5];
                                echo "
								<table class='table table-condensed'>

									<tbody>";
                                while($arquivo = $queryOficineiro->fetch_assoc())
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
							<a href="../include/arquivos_pessoa_capac.php?idPessoa=<?php echo $idPessoaMac ?>&tipo[]=<?=implode('&tipo[]=', $tipoPessoa)?>" class="btn btn-theme btn-md btn-block" target="_blank">Baixar todos os arquivos da empresa</a>
						</div>
					</div>
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
		<p>O CNPJ nº <strong><?php echo $cnpj_busca ?></strong> possui cadastro no CAPAC e no IGSIS. Abaixo está a lista com as divergências apontadas o cadastro.</p>
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
						if($RazaoSocial != $razaoSocial)
						{
							echo "<tr>";
							echo "<td class='list_description'>Razão Social</td>";
							echo "<td class='list_description'>".$razaoSocial."</td>";
							echo "<td class='list_description'>".$RazaoSocial."</td>";
							echo "<td>
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Razão Social' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
										<input type='hidden' name='campo' value='RazaoSocial'  />
										<input type='hidden' name='varCampo' value='".$razaoSocial."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='CCM' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
										<input type='hidden' name='campo' value='CCM'  />
										<input type='hidden' name='varCampo' value='".$ccm."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='CEP' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Número' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Complemento' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Telefone #1' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Telefone #2' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Telefone #3' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='E-mail' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
										<input type='hidden' name='campo' value='Email'  />
										<input type='hidden' name='varCampo' value='".$email."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
							echo "</tr>";
						}
						if($NomeRep1 != $nomeRep1)
						{
							$NomeRepresentante1 = recuperaDadosIgsis("sis_representante_legal","Id_RepresentanteLegal","$IdRepresentanteLegal1");
							$nomeRepresentante1 = recuperaDadosProp("representante_legal","id","$idRepresentanteLegal1");
							echo "<tr>";
							echo "<td class='list_description'>Representante Legal #1</td>";
							echo "<td class='list_description'>".$nomeRepresentante1['nome']."</td>";
							echo "<td class='list_description'>".$NomeRepresentante1['RepresentanteLegal']."</td>";
							echo "<td>
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Representante Legal #1' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
										<input type='hidden' name='campo' value='IdRepresentanteLegal1'  />
										<input type='hidden' name='varCampo' value='".$idRepresentanteLegal1."'  />
										<input type='submit' name='importarRep01' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
							echo "</tr>";
						}
						if($NomeRep2 != $nomeRep2)
						{
							$NomeRepresentante2 = recuperaDadosIgsis("sis_representante_legal","Id_RepresentanteLegal","$IdRepresentanteLegal2");
							$nomeRepresentante2 = recuperaDadosProp("representante_legal","id","$idRepresentanteLegal2");
							echo "<tr>";
							echo "<td class='list_description'>Representante Legal #2</td>";
							echo "<td class='list_description'>".$nomeRepresentante2['nome']."</td>";
							echo "<td class='list_description'>".$NomeRepresentante2['RepresentanteLegal']."</td>";
							echo "<td>
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Representante Legal #2' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
										<input type='hidden' name='campo' value='IdRepresentanteLegal2'  />
										<input type='hidden' name='varCampo' value='".$idRepresentanteLegal2."'  />
										<input type='submit' name='importarRep02' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
							echo "</tr>";
						}
						if($codBanco != $codigoBanco)
						{
							$igNomeBanco = recuperaDadosIgsis("igsis_bancos","ID","$codBanco");
							$propNomeBanco = recuperaDadosProp("banco","id","$codigoBanco");
							echo "<tr>";
							echo "<td class='list_description'>Banco</td>";
							echo "<td class='list_description'>".$propNomeBanco['banco']."</td>";
							echo "<td class='list_description'>".$igNomeBanco['banco']."</td>";
							echo "<td>
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Banco' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Agência' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Conta' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
									<form method='POST' action='".$link."&busca=".$cnpj_busca."' enctype='multipart/form-data'>
										<input type='hidden' name='nomeCampo' value='Última Atualização' />
										<input type='hidden' name='busca' value='".$cnpj_busca."'  />
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
					<p><i>Obs.: Os arquivos não são importados automaticamente. Faça o download e verifique os mesmos antes de efetuar o upload.</i></p>
					<div align="left">
						<div class="table-responsive list_info">
                            <?php
                            $sql = "SELECT *
								FROM upload_lista_documento as list
								INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
								WHERE arq.idPessoa = '$idPessoaMac'
								AND arq.idTipoPessoa = '2'
								AND arq.publicado = '1'";
                            $query = mysqli_query($con2,$sql);
                            $linhas = mysqli_num_rows($query);

                            $sqlOficineiro = "SELECT *
								FROM upload_lista_documento as list
								INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
								WHERE arq.idPessoa = '$idPessoaMac'
								AND arq.idTipoPessoa = '5'
								AND arq.publicado = '1'";
                            $queryOficineiro = $con2->query($sqlOficineiro);
                            $linhasOficineiro = $queryOficineiro->num_rows;

                            if ($linhas > 0)
                            {
                                if ($linhasOficineiro > 0)
                                {
                                    $tipoPessoa = [2,5];
                                    comparaArquivosOficineiros($query, $queryOficineiro);
                                    echo "
									</tbody>
								</table>";
                                }
                                else
                                {
                                    $tipoPessoa = [2];
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
                            }
                            elseif ($linhasOficineiro > 0)
                            {
                                $tipoPessoa = [5];
                                echo "
								<table class='table table-condensed'>

									<tbody>";
                                while($arquivo = $queryOficineiro->fetch_assoc())
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
							<a href="../include/arquivos_pessoa_capac.php?idPessoa=<?php echo $idPessoaMac ?>&tipo[]=<?=implode('&tipo[]=', $tipoPessoa)?>" class="btn btn-theme btn-md btn-block" target="_blank">Baixar todos os arquivos da empresa</a>
						</div>
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
			elseif($edicaoPessoa == 2)
			{
				$cpfPf = $_SESSION['cpfPf'];
		?>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<form method='POST' action='?perfil=compara_executante&busca=<?php echo $cpfPf ?>'>
							<input type='hidden' name='Id_PessoaJuridica' value='<?php echo $query1['Id_PessoaJuridica'] ?>'>
							<input type ='submit' name='inserePedido' class='btn btn-theme btn-lg btn-block' value='Ir para o executante'>
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
						<form method='POST' action='?perfil=contratados&p=juridica'>
							<input type='submit' class='btn btn-theme btn-lg btn-block' value='Pesquisar outro cnpj'>
						</form>
					</div>
					<div class="col-md-6">
						<form method='POST' action='?perfil=contratados&p=lista'>
							<input type='hidden' name='insereJuridica' value='1'>
							<input type='hidden' name='Id_PessoaJuridica' value='<?php echo $query1['Id_PessoaJuridica'] ?>'>
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

//Se não existir no IGSIS e nem no CAPAC
If($query1 == '' && $query2 == '')
{
	$ultimo = cadastroPessoa($_SESSION['idEvento'],$CNPJ,'2');
	$campo = recuperaDados("sis_pessoa_juridica",$ultimo,"Id_PessoaJuridica");
?>
	<section id="contact" class="home-section bg-white">
		<div class="container">
			<div class="form-group">
				<h3>CADASTRO DE PESSOA JURÍDICA</h3>
				<p> O CNPJ nº <strong><?php echo $cnpj_busca; ?></strong> não está cadastrado no nosso sistema. <br />Por favor, insira as informações da Pessoa Jurídica a ser contratada. </p>
				<p><a href="?perfil=contratados&p=juridica$pag=pesquisar">Pesquisar outro CNPJ</a></p>
			</div>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<form class="form-horizontal" role="form" action="?perfil=contratados&p=lista" method="post">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Razão Social *:</strong><br/>
								<input type="text" class="form-control" name="RazaoSocial" placeholder="Nome" >
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>CNPJ *:</strong><br/>
								<input type="text" class="form-control" id="cnpj" name="CNPJ" placeholder="CNPJ" readonly value="<?php echo $cnpj_busca; ?> ">
							</div>
							<div class=" col-md-6"><strong>CCM *:</strong><br/>
								<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" >
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
								<input type="text" class="form-control" id="Endereco" name="Endereco" placeholder="Endereço">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Número *:</strong><br/>
								<input type="text" class="form-control" id="Numero" name="Numero" placeholder="Numero">
							</div>
							<div class=" col-md-6"><strong>Complemento:</strong><br/>
								<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento">
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
							<div class="col-md-offset-2 col-md-6"><strong>E-mail *:</strong><br/>
								<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail" >
							</div>
							<div class=" col-md-6"><strong>Telefone #1 *:</strong><br/>
								<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone1" placeholder="Exemplo: (11) 98765-4321" >
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Telefone #2:</strong><br/>
								<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone2" placeholder="Exemplo: (11) 98765-4321" >
							</div>
							<div class="col-md-6"><strong>Telefone #3:</strong><br/>
								<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone3" placeholder="Exemplo: (11) 98765-4321" >
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
								<textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="cadastrarJuridica" value="1" />
								<input type="hidden" name="Sucesso" id="Sucesso" />
								<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
<?php
}
?>