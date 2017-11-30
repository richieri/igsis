<?php

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
					<p align="justify"><strong>Faixa Etária:</strong> <?php echo $faixaEtaria['faixaEtaria'] ?></p>
					<p align="justify"><strong>Sinopse:</strong> <?php echo $evento['sinopse'] ?></p>
					<p align="justify"><strong>Release:</strong> <?php echo $evento['releaseCom'] ?></p>
					<p align="justify"><strong>Links:</strong> <?php echo $evento['link'] ?></p>
					<p align="justify"><strong>Data Cadastro:</strong> <?php echo exibirDataHoraBr($evento['dataCadastro']) ?></p>
					<br/>
					<h5>Informações de Produção</h5>
					<p align="justify"><strong>Nome do Produtor:</strong> <?php echo $produtor['nome'] ?></p>
					<p align="justify"><strong></strong> <?php echo $produtor['email'] ?></p>
					<p align="justify"><strong></strong> <?php echo $produtor['telefone1']." | ".$produtor['telefone2'] ?></p>
					<br/>
					<h5>Informações de Contratação</h5>
					<p align="justify"><strong>Tipo:</strong> <?php echo $tipoPessoa['tipoPessoa'] ?></p>
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
					<p align="justify"><strong>Estado Civil:</strong> <?php echo recuperaEstadoCivilCapac($pessoaFisica['idEstadoCivil']) ?></p>
					<!--
					<p align="justify"><strong></strong> <?php //echo   ?></p>-->
				</div>
			</div>
		</div>
	</div>
</section>