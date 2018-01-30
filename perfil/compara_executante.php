<?php

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

$cpf_busca = $_GET['busca'];
$id_ped = $_GET['id_ped'];

unset($_SESSION['cpfPf']);

// Endereço da página
$link = "?perfil=compara_executante";

// Localiza no IGSIS
$sql1 = $con1->query("SELECT * FROM sis_pessoa_fisica where CPF = '$cpf_busca'");
$query1 = $sql1->fetch_array(MYSQLI_ASSOC);

$Nome = $query1["Nome"];
$NomeArtistico = $query1["NomeArtistico"];
$RG = $query1["RG"];
$CPF = $query1["CPF"];
$Telefone1 = $query1["Telefone1"];
$Telefone2 = $query1["Telefone2"];
$Telefone3 = $query1["Telefone3"];
$Email = $query1["Email"];
$DRT = $query1["DRT"];
$DataAtualizacao = $query1["DataAtualizacao"];
$tipoDocumento = $query1["tipoDocumento"];


//Localiza no proponente
$sql2 = $con2->query("SELECT * FROM pessoa_fisica where cpf = '$cpf_busca'");
$query2 = $sql2->fetch_array(MYSQLI_ASSOC);

$idPessoaMac = $query2['id'];
$nome = $query2["nome"];
$nomeArtistico = $query2["nomeArtistico"];
$rg = $query2["rg"];
$cpf = $query2["cpf"];
$telefone1 = $query2["telefone1"];
$telefone2 = $query2["telefone2"];
$telefone3 = $query2["telefone3"];
$email = $query2["email"];
$drt = $query2["drt"];
$dataAtualizacao = $query2["dataAtualizacao"];
$idTipoDocumento = $query2["idTipoDocumento"];

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

if(isset($_POST['inserePedido']))
{
	$idJuridica = $_POST['Id_PessoaJuridica'];
	$idEvento = $_SESSION['idEvento'];
	$sql_insert_pedido = "INSERT INTO `igsis_pedido_contratacao` (`idEvento`, `tipoPessoa`, `idPessoa`, `publicado`) VALUES ('$idEvento', '2', '$idJuridica', '1')";
	$query_insert_pedido = mysqli_query($con1,$sql_insert_pedido);
	if($query_insert_pedido)
	{
		gravarLog($sql_insert_pedido);
		$mensagem = "Inserido com sucesso!";
		$sql_pedido = "SELECT  * FROM igsis_pedido_contratacao ORDER BY idPedidoContratacao DESC LIMIT 0,1";
		$query_pedido = mysqli_query($con1,$sql_pedido);
		$pedido = mysqli_fetch_array($query_pedido);
		$id_ped = $pedido['idPedidoContratacao'];
		echo "<meta HTTP-EQUIV='refresh' CONTENT='0.5;URL=?perfil=compara_executante&busca=".$cpf_busca."&id_ped=".$id_ped."'>";
	}
	else
	{
		$mensagem = "Erro ao criar pedido! Tente novamente.";
	}
}

if(isset($_POST['inserirExecutante']))
{
	$idFisica = $_POST['Id_PessoaFisica'];
	$sql_insert_pedido = "UPDATE igsis_pedido_contratacao SET idExecutante = '$idFisica' WHERE idPedidoContratacao = '$id_ped'";
	$query_insert_pedido = mysqli_query($con1,$sql_insert_pedido);
	if($query_insert_pedido)
	{
		gravarLog($sql_insert_pedido);
		$mensagem = "Executante inserido com sucesso!";
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=contratados'>";
	}
	else
	{
		$mensagem = "Erro ao inserir o executante no pedido! Tente novamente.";
	}
}

if(isset($_POST['atualizaIgsis']))
{
	$campo = $_POST['campo'];
	$varCampo = $_POST['varCampo'];
	$nomeCampo = $_POST['nomeCampo'];
	$sql_update_nome = "UPDATE sis_pessoa_fisica SET ".$campo." = '$varCampo' WHERE CPF = '$cpf'";
	if(mysqli_query($con1,$sql_update_nome))
	{
		$mensagem =	$nomeCampo." atualizado com sucesso!";
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=".$link."&busca=".$cpf_busca."&id_ped=".$id_ped."'>";
	}
	else
	{
		$mensagem = "Erro ao atualizar ".$nomeCampo.". Tente novamente!";
	}
}

if(isset($_POST['importarCapacIgsis']))
{
	$sql_insere_pf = "INSERT INTO sis_pessoa_fisica (`Nome`, `NomeArtistico`, `RG`, `CPF`, `Telefone1`, `Telefone2`, `Telefone3`, `Email`, `DRT`, `DataAtualizacao`,`tipoDocumento`) VALUES ('$nome', '$nomeArtistico', '$rg', '$cpf', '$telefone1', '$telefone2', '$telefone3', '$email', '$drt', '$dataAtualizacao', '$idTipoDocumento')";

	if(mysqli_query($con1,$sql_insere_pf))
	{
		$mensagem = "Importado com sucesso!";

		//gravarLog($sql_insert_pf);
		$sql_ultimo = "SELECT * FROM sis_pessoa_fisica ORDER BY Id_PessoaFisica DESC LIMIT 0,1"; //recupera ultimo id
		$query_ultimo = mysqli_query($con1,$sql_ultimo);
		$id = mysqli_fetch_array($query_ultimo);
		$idFisica = $id['Id_PessoaFisica'];

		$sql_insert_pedido = "UPDATE igsis_pedido_contratacao SET idExecutante = '$idFisica' WHERE idPedidoContratacao = '$id_ped'";
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
If($query1 == '' && $query2 != '')
{
	?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<h6>Não foi encontrado registro com o CPF <strong><?php echo $cpf_busca ?></strong> no IGSIS. Deseja importar do Cadastro de Proponente?</h6>
					<h5><?php if(isset($mensagem)){echo $mensagem;}; ?></h5>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<form method='POST' action='?perfil=compara_executante' enctype='multipart/form-data'>
						<input type='hidden' name='busca' value='<?php echo $cpf_busca ?>'>
						<input type='submit' name='importarCapacIgsis' class='btn btn-theme btn-lg btn-block'>
						<input type='submit' class='btn btn-theme btn-lg btn-block' value='Importar'>
					</form><br/>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-8"><hr/></div>
			</div>

			<div align="left">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<strong>Nome:</strong> <?php echo $nome ?><br/>
						<strong>Nome Artístico:</strong> <?php echo $nomeArtistico ?><br/>
						<strong>RG:</strong> <?php echo $rg ?> |
						<strong>CPF:</strong> <?php echo $cpf ?> |<br/>
						<strong>Telefone #1:</strong> <?php echo $telefone1 ?> / <?php echo $telefone2 ?> / <?php echo $telefone3 ?><br/>
						<strong>E-mail:</strong> <?php echo $email ?><br/>
						<strong>DRT:</strong> <?php echo $drt ?><br/>
						<strong>Última Atualização:</strong> <?php echo exibirDataBr($dataAtualizacao) ?>
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
		<p>O CPF nº <strong><?php echo $cpf_busca ?></strong> possui cadastro no sistema de proponente e no IGSIS. Abaixo está a lista com as divergências apontadas o cadastro.</p>
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
										<input type='hidden' name='busca' value='".$cpf."'  />
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
					<h5>Lista de Arquivos Anexados Pelo Proponente</h5>
					<p><i>Obs.: Os arquivos não são importados automaticamente. Faça o download e verifique os mesmos antes de efetuar o upload.</i></p>
					<div class="table-responsive list_info">
					<?php
						$sql = "SELECT *
								FROM upload_lista_documento as list
								INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
								WHERE arq.idPessoa = '$idPessoaMac'
								AND arq.idTipoPessoa = '1'
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
			<div class="form-group">
				<div class="col-md-offset-2 col-md-6">
					<form method='POST' action='?perfil=contratados'>
						<input type='submit' class='btn btn-theme btn-lg btn-block' value='Ir para Contratados'>
					</form>
				</div>
				<div class="col-md-6">
					<form method='POST' action='?perfil=compara_executante&busca=<?php echo $cpf_busca ?>&id_ped=<?php echo $id_ped?>'>
						<input type='hidden' name='inserirExecutante'>
						<input type='hidden' name='Id_PessoaFisica' value='<?php echo $query1['Id_PessoaFisica'] ?>'>
						<input type ='submit' class='btn btn-theme btn-lg btn-block' value='Inserir Executante no Pedido'>
					</form>
				</div>
			</div>
	</section>
<?php
}