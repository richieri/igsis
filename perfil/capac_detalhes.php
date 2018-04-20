<?php

unset($_SESSION['idCapac']);
unset($_SESSION['nomeEvento']);
unset($_SESSION['nomeGrupo']);
unset($_SESSION['tipoEvento']);

$idCapac = $_GET['id_capac'];
$con = bancoMysqliProponente();


include '../include/menuEventoInicial.php';

function recuperaDadosCapac($tabela,$idEvento,$campo)
{
	$con = bancoMysqliProponente();
	$sql = "SELECT * FROM $tabela WHERE ".$campo." = '$idEvento' LIMIT 0,1";
	$query = mysqli_query($con,$sql);
	$campo = mysqli_fetch_array($query);
	return $campo;
}

function recuperaEstadoCivilCapac($campoX)
{
	$estadoCivil = recuperaDadosCapac("estado_civil",$campoX,"id");
	$nomeEstadoCivil = $estadoCivil['estadoCivil'];
	return $nomeEstadoCivil;
}

function recuperaBanco($campoY)
{
	$banco = recuperaDadosCapac("banco",$campoY,"id");
	$nomeBanco = $banco['banco'];
	return $nomeBanco;
}

function recuperaUsuarioCapac($campoZ)
{
	$usuario = recuperaDadosCapac("usuario",$campoZ,"id");
	$nomeUsuario = $usuario['nome'];
	return $nomeUsuario;
}

function listaArquivoCamposMultiplos($idPessoa,$pf)
{
	$con = bancoMysqliProponente();
	switch ($pf) {
		case 1: //todos os arquivos de pf
			$sql = "SELECT *
				FROM upload_lista_documento as list
				INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
				WHERE arq.idPessoa = '$idPessoa'
				AND arq.idTipoPessoa = '1'
				AND arq.publicado = '1'
				ORDER BY documento";
		break;
		case 2: //todos os arquivos de pj
			$sql = "SELECT *
				FROM upload_lista_documento as list
				INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
				WHERE arq.idPessoa = '$idPessoa'
				AND arq.idTipoPessoa = '2'
				AND arq.publicado = '1'
				AND list.id NOT IN (20,21,103,104)
				ORDER BY documento";
		break;
		case 3: //representante_legal1
			$arq1 = "AND (list.id = '20' OR ";
			$arq2 = "list.id = '21'OR ";
			$arq3 = "list.id = '103' OR ";
			$arq4 = "list.id = '104')";
			$sql = "SELECT *
				FROM upload_lista_documento as list
				INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
				WHERE arq.idPessoa = '$idPessoa'
				AND arq.idTipoPessoa = '2'
				$arq1 $arq2 $arq3 $arq4
				AND arq.publicado = '1'";
		break;
		case 4: //grupo
			$arq1 = "AND (list.id = '99' OR ";
			$arq2 = "list.id = '100' OR";
			$arq3 = "list.id = '101' OR";
			$arq4 = "list.id = '102')";
			$sql = "SELECT *
				FROM upload_lista_documento as list
				INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
				WHERE arq.idPessoa = '$idPessoa'
				AND arq.idTipoPessoa = '3'
				$arq1 $arq2 $arq3 $arq4
				AND arq.publicado = '1'";
		break;
		case 5: //evento
			$sql = "SELECT *
				FROM upload_lista_documento as list
				INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
				WHERE arq.idPessoa = '$idPessoa'
				AND arq.idTipoPessoa = '3'
				AND edital = 0
				AND arq.publicado = '1'";
		break;
		default:
		break;
	}
	$query = mysqli_query($con,$sql);
	$linhas = mysqli_num_rows($query);

	if ($linhas > 0)
	{
	echo "
		<table class='table table-condensed'>
			<thead>
				<tr class='list_menu'>
					<td>Nome do arquivo</td>
					<td width='10%'></td>
				</tr>
			</thead>
			<tbody>";
				while($arquivo = mysqli_fetch_array($query))
				{
					echo "<tr>";
					echo "<td class='list_description' width='5%'>".$arquivo['documento']."</td>";
					echo "<td class='list_description'><a href='../../igsiscapac/uploadsdocs/".$arquivo['arquivo']."' target='_blank'>".$arquivo['arquivo']."</td>";
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
}

function listaArquivosComProd($idEvento)
{
	//lista arquivos de determinado evento
	$con = bancoMysqliProponente();
	$sql = "SELECT * FROM upload_arquivo_com_prod WHERE idEvento = '$idEvento' AND publicado = '1'";
	$query = mysqli_query($con,$sql);
	echo "
		<table class='table table-condensed'>
			<thead>
				<tr class='list_menu'>
					<td>Nome do arquivo</td>
					<td width='10%'></td>
				</tr>
			</thead>
			<tbody>";
	while($campo = mysqli_fetch_array($query))
	{
		echo "<tr>";
		echo "<td class='list_description'><a href='../igsiscapac/uploads/".$campo['arquivo']."' target='_blank'>".$campo['arquivo']."</a></td>";
		echo "</tr>";
	}
	echo "
		</tbody>
		</table>";
}

$evento = recuperaDadosCapac("evento",$idCapac,"id");
$tipoEvento = recuperaDadosCapac("tipo_evento",$evento['idTipoEvento'],"id");
$faixaEtaria = recuperaDadosCapac("faixa_etaria",$evento['idFaixaEtaria'],"id");
$produtor = recuperaDadosCapac("produtor",$evento['idProdutor'],"id");
$tipoPessoa = recuperaDadosCapac("tipo_pessoa",$evento['idTipoPessoa'],"id");
$pessoaJuridica = recuperaDadosCapac("pessoa_juridica",$evento['idPj'],"id");
$representante1 = recuperaDadosCapac("representante_legal",$pessoaJuridica['idRepresentanteLegal1'],"id");
$representante2 = recuperaDadosCapac("representante_legal",$pessoaJuridica['idRepresentanteLegal2'],"id");
$pessoaFisica = recuperaDadosCapac("pessoa_fisica",$evento['idPf'],"id");
$usuario = recuperaDadosCapac("usuario",$evento['idUsuario'],"id");

 ?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="form-group"><h3><?php echo $evento['nomeEvento'] ?></h3>
			<h5><?php if(isset($mensagem)){ echo $mensagem; } ?></h5>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="left">
					<p align="justify"><strong>Tipo de evento:</strong> <?php echo $tipoEvento['tipoEvento'] ?></p>
					<p align="justify"><strong>Nome de Grupo:</strong> <?php echo $evento['nomeGrupo'] ?></p>
					<p align="justify"><strong>Ficha Técnica:</strong> <?php echo $evento['fichaTecnica'] ?></p>
					<p align="justify"><strong>Integrantes do elenco:</strong> <?php echo $evento['integrantes'] ?></p>
					<p align="justify"><strong>Faixa Etária:</strong> <?php echo $faixaEtaria['faixaEtaria'] ?></p>
					<p align="justify"><strong>Sinopse:</strong> <?php echo $evento['sinopse'] ?></p>
					<p align="justify"><strong>Release:</strong> <?php echo $evento['releaseCom'] ?></p>
					<p align="justify"><strong>Links:</strong> <?php echo $evento['link'] ?></p>
					<p align="justify"><strong>Data Cadastro:</strong> <?php echo exibirDataHoraBr($evento['dataCadastro']) ?></p>
					<br/>
					<h5>Informações de Produção</h5>
					<p align="justify"><strong>Nome do Produtor:</strong> <?php echo $produtor['nome'] ?></p>
					<p align="justify"><strong>E-mail:</strong> <?php echo $produtor['email'] ?></p>
					<p align="justify"><strong>Telefone:</strong> <?php echo $produtor['telefone1']." | ".$produtor['telefone2'] ?></p>
					<br/>
					<h5>Informações de Contratação</h5>
					<p align="justify"><strong>Tipo:</strong> <?php echo $tipoPessoa['tipoPessoa'] ?></p>
					<br/>
					<?php
					if($evento['idTipoPessoa'] == 2)
					{
					?>
						<p align="justify"><strong>Razão Social:</strong> <?php echo $pessoaJuridica['razaoSocial'] ?></p>
						<p align="justify"><strong>CNPJ:</strong> <?php echo $pessoaJuridica['cnpj'] ?></p>
						<p align="justify"><strong>CCM:</strong> <?php echo $pessoaJuridica['ccm'] ?></p>
						<p align="justify"><strong>CEP:</strong> <?php echo $pessoaJuridica['cep'] ?></p>
						<p align="justify"><strong>Número:</strong> <?php echo $pessoaJuridica['numero'] ?></p>
						<p align="justify"><strong>Complemento:</strong> <?php echo $pessoaJuridica['complemento'] ?></p>
						<p align="justify"><strong>Telefone:</strong> <?php echo $pessoaJuridica['telefone1']." | ".$pessoaJuridica['telefone2']." | ".$pessoaJuridica['telefone3'] ?></p>
						<p align="justify"><strong>E-mail:</strong> <?php echo $pessoaJuridica['email'] ?></p>
						<br/>
						<p align="justify"><strong>REPRESENTANTE LEGAL #1</strong></p>
						<p align="justify"><strong>Nome:</strong> <?php echo $representante1['nome'] ?></p>
						<p align="justify"><strong>RG:</strong> <?php echo $representante1['rg'] ?></p>
						<p align="justify"><strong>CPF:</strong> <?php echo $representante1['cpf'] ?></p>
						<p align="justify"><strong>Estado Civil:</strong> <?php echo recuperaEstadoCivilCapac($representante1['idEstadoCivil']) ?></p>
						<p align="justify"><strong>Nacionalidade:</strong> <?php echo $representante1['nacionalidade'] ?></p>
						<br/>
						<?php
						if($representante2 != NULL)
						{
						?>
							<p align="justify"><strong>REPRESENTANTE LEGAL #2</strong></p>
							<p align="justify"><strong>Nome:</strong> <?php echo $representante2['nome'] ?></p>
							<p align="justify"><strong>RG:</strong> <?php echo $representante2['rg'] ?></p>
							<p align="justify"><strong>CPF:</strong> <?php echo $representante2['cpf'] ?></p>
							<p align="justify"><strong>Estado Civil:</strong> <?php echo recuperaEstadoCivilCapac($representante2['idEstadoCivil']) ?></p>
							<p align="justify"><strong>Nacionalidade:</strong> <?php echo $representante2['nacionalidade'] ?></p>
							<br/>
						<?php
						}
						?>
						<p align="justify"><strong>Banco:</strong> <?php echo recuperaBanco($pessoaJuridica['codigoBanco']) ?></p>
						<p align="justify"><strong>Agência:</strong> <?php echo $pessoaJuridica['agencia'] ?></p>
						<p align="justify"><strong>Conta:</strong> <?php echo $pessoaJuridica['conta'] ?></p>
						<p align="justify"><strong>Data da última atualização do cadastro:</strong> <?php echo exibirDataHoraBr($pessoaJuridica['dataAtualizacao']) ?></p>
						<p align="justify"><strong>Usuário que inseriu no sistema:</strong> <?php echo recuperaUsuarioCapac($pessoaJuridica['idUsuario']) ?></p>
						<br/>
					<?php
					}
					?>
					<p align="justify"><strong>ARTISTA</strong></p>
					<p align="justify"><strong>Nome:</strong> <?php echo $pessoaFisica['nome'] ?></p>
					<p align="justify"><strong>Nome Artístico:</strong> <?php echo $pessoaFisica['nomeArtistico'] ?></p>
					<p align="justify"><strong>RG:</strong> <?php echo $pessoaFisica['rg'] ?></p>
					<p align="justify"><strong>CPF:</strong> <?php echo $pessoaFisica['cpf'] ?></p>
					<p align="justify"><strong>Telefone:</strong> <?php echo $pessoaFisica['telefone1']." | ".$pessoaFisica['telefone2']." | ".$pessoaFisica['telefone3'] ?></p>
					<p align="justify"><strong>E-mail:</strong> <?php echo $pessoaFisica['email'] ?></p>
					<p align="justify"><strong>DRT:</strong> <?php echo $pessoaFisica['drt'] ?></p>
					<?php
					if($evento['idTipoPessoa'] == 1)
					{
					?>
						<p align="justify"><strong>Estado Civil:</strong> <?php echo recuperaEstadoCivilCapac($pessoaFisica['idEstadoCivil']) ?></p>
						<p align="justify"><strong>Data de Nascimento:</strong> <?php echo exibirDataBr($pessoaFisica['dataNascimento']) ?></p>
						<p align="justify"><strong>Nacionalidade:</strong> <?php echo $pessoaFisica['nacionalidade'] ?></p>
						<p align="justify"><strong>PIS / PASEP / NIT:</strong> <?php echo $pessoaFisica['pis'] ?></p>
						<p align="justify"><strong>CEP:</strong> <?php echo $pessoaFisica['cep'] ?></p>
						<p align="justify"><strong>Número:</strong> <?php echo $pessoaFisica['numero'] ?></p>
						<p align="justify"><strong>Complemento:</strong> <?php echo $pessoaFisica['complemento'] ?></p>
						<p align="justify"><strong>Banco:</strong> <?php echo recuperaBanco($pessoaFisica['codigoBanco']) ?></p>
						<p align="justify"><strong>Agência:</strong> <?php echo $pessoaFisica['agencia'] ?></p>
						<p align="justify"><strong>Conta:</strong> <?php echo $pessoaFisica['conta'] ?></p>
						<p align="justify"><strong>Data da última atualização do cadastro:</strong> <?php echo exibirDataHoraBr($pessoaFisica['dataAtualizacao']) ?></p>
						<p align="justify"><strong>Usuário que inseriu no sistema:</strong> <?php echo recuperaUsuarioCapac($pessoaFisica['idUsuario']) ?></p>
					<?php
					}
					?>
					<br/>

					<div class="table-responsive list_info"><h6>Arquivo(s) de Eventos</h6>
						<?php listaArquivoCamposMultiplos($idCapac,5); ?>
					</div>

					<div class="table-responsive list_info"><h6>Arquivo(s) para Comunicação/Produção</h6>
						<?php listaArquivosComProd($idCapac); ?>
					</div>

					<?php
					if($evento['idTipoPessoa'] == 2)
					{
					?>
						<div class="table-responsive list_info"><h6>Arquivo(s) de Pessoa Jurídica <?php echo $pessoaJuridica['id'] ?></h6>
							<?php listaArquivoCamposMultiplos($pessoaJuridica['id'],2); ?>
						</div>

						<div class="table-responsive list_info"><h6>Arquivo(s) Representante Legal</h6>
							<?php listaArquivoCamposMultiplos($pessoaJuridica['id'],3); ?>
						</div>
					<?php
					}
					?>

					<div class="table-responsive list_info"><h6>Arquivo(s) de Pessoa Física</h6>
						<?php listaArquivoCamposMultiplos($pessoaFisica['id'],1); ?>
					</div>

					<div class="table-responsive list_info"><h6>Arquivo(s) do Grupo</h6>
						<?php listaArquivoCamposMultiplos($idCapac,4); ?>
					</div>

				</div>

				<?php
				if($evento['idTipoPessoa'] == 2)
				{
				?>
					<div class="col-md-offset-2 col-md-8">
						<a href="../include/arquivos_evento_capac.php?idEvento=<?php echo $idCapac ?>&idPj=<?php echo $pessoaJuridica['id'] ?>&idPf=<?php echo $pessoaFisica['id'] ?>" class="btn btn-theme btn-md btn-block" target="_blank">Baixar todos os arquivos</a><br/>
					</div>
				<?php
				}
				else
				{
				?>
					<div class="col-md-offset-2 col-md-8">
						<a href="../include/arquivos_evento_capac.php?idEvento=<?php echo $idCapac ?>&idPf=<?php echo $pessoaFisica['id'] ?>" class="btn btn-theme btn-md btn-block" target="_blank">Baixar todos os arquivos</a><br/>
					</div>
				<?php
				}
				?>

				<div class="col-md-offset-2 col-md-8">
					<form method='POST' action='?perfil=importar_evento_capac'>
						<input type="hidden" name="idCapac" value="<?php echo $idCapac ?>" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Importar">
					</form>
				</div>
			</div>
		</div>
	</div>
</section>