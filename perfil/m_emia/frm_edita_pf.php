<?php
	include 'includes/menu.php';
	$ultimo = $_GET['id_pf']; //recupera o id da pessoa
	if(isset($_POST['idPedido']))
	{
		$id_pedido = $_POST['idPedido']; //recupera o id do pedido
		$mensagem = $id_pedido;
	}
	$con = bancoMysqli();
	if(isset($_POST['insereFisica']))
	{
		//cadastra e insere pessoa física
		$cpf = $_POST['CPF'];
		$verificaCPF = verificaExiste("sis_pessoa_fisica","CPF",$cpf,"");
		if($verificaCPF['numero'] > 0)
		{ //verifica se o cpf já existe
			$mensagem = "O CPF já consta no sistema. Faça uma busca e insira diretamente";
		}
		else
		{
			// o CPF não existe, inserir.
			$Nome = addslashes($_POST['Nome']);
			$NomeArtistico = addslashes($_POST['NomeArtistico']);
			$RG = $_POST['RG'];
			$CPF = $_POST['CPF'];
			$CCM = $_POST['CCM'];
			$IdEstadoCivil = $_POST['IdEstadoCivil'];
			$DataNascimento = exibirDataMysql($_POST['DataNascimento']);
			$Nacionalidade = $_POST['Nacionalidade'];
			$CEP = $_POST['CEP'];
			$Endereco = $_POST['Endereco'];
			$Numero = $_POST['Numero'];
			$Complemento = $_POST['Complemento'];
			$Bairro = $_POST['Bairro'];
			$Cidade = $_POST['Cidade'];
			$Telefone1 = $_POST['Telefone1'];
			$Telefone2 = $_POST['Telefone2'];
			$Telefone3 = $_POST['Telefone3'];
			$Email = $_POST['Email'];
			$DRT = $_POST['DRT'];
			$cbo = $_POST['cbo'];
			$Funcao = $_POST['Funcao'];
			$InscricaoINSS = $_POST['InscricaoINSS'];
			$OMB = $_POST['OMB'];
			$codBanco = $_POST['codBanco'];
			$agencia = $_POST['agencia'];
			$conta = $_POST['conta'];
			$Observacao = addslashes($_POST['Observacao']);
			$Pis = 0;
			$data = date('Y-m-d');
			$idUsuario = $_SESSION['idUsuario'];
			$sql_insert_pf = "INSERT INTO `sis_pessoa_fisica` (`Id_PessoaFisica`, `Foto`, `Nome`, `NomeArtistico`, `RG`, `CPF`, `CCM`, `IdEstadoCivil`, `DataNascimento`, `LocalNascimento`, `Nacionalidade`, `CEP`, `Numero`, `Complemento`, `Telefone1`, `Telefone2`, `Telefone3`, `Email`, `DRT`, `Funcao`, `InscricaoINSS`, `Pis`, `OMB`, `DataAtualizacao`, `Observacao`, `IdUsuario`, `codBanco`, `agencia`, `conta`, `cbo`) VALUES (NULL, NULL, '$Nome', '$NomeArtistico', '$RG', '$CPF', '$CCM', '$IdEstadoCivil', '$DataNascimento', NULL, '$Nacionalidade', '$CEP', '$Numero', '$Complemento', '$Telefone1', '$Telefone2', '$Telefone3', '$Email', '$DRT', '$Funcao', '$InscricaoINSS', '$Pis', '$OMB', '$data', '$Observacao', '$idUsuario', '$codBanco', '$agencia', '$conta', '$cbo');";
			$query_insert_pf = mysqli_query($con,$sql_insert_pf);
			if($query_insert_pf)
			{
				gravarLog($sql_insert_pf);
				echo "<h3>Inserido com sucesso!</h3>";
				$ultimo = mysqli_insert_id($con);
			}
			else
			{
				echo "<h3>Erro ao inserir!</h3>";
			}
		}
	}
	if(isset($_POST['cadastrarFisica']))
	{
		$idPessoaFisica = $_POST['cadastrarFisica'];
		$Nome = addslashes($_POST['Nome']);
		$NomeArtistico = addslashes($_POST['NomeArtistico']);
		$RG = $_POST['RG'];
		$CPF = $_POST['CPF'];
		$CCM = $_POST['CCM'];
		$IdEstadoCivil = $_POST['IdEstadoCivil'];
		$DataNascimento = exibirDataMysql($_POST['DataNascimento']);
		$Nacionalidade = $_POST['Nacionalidade'];
		$CEP = $_POST['CEP'];
		$Numero = $_POST['Numero'];
		$Complemento = $_POST['Complemento'];
		$Bairro = $_POST['Bairro'];
		$Cidade = $_POST['Cidade'];
		$Telefone1 = $_POST['Telefone1'];
		$Telefone2 = $_POST['Telefone2'];
		$Telefone3 = $_POST['Telefone3'];
		$Email = $_POST['Email'];
		$DRT = $_POST['DRT'];
		$cbo = $_POST['cbo'];
		$Funcao = $_POST['Funcao'];
		$InscricaoINSS = $_POST['InscricaoINSS'];
		$OMB = $_POST['OMB'];
		$codBanco = $_POST['codBanco'];
		$agencia = $_POST['agencia'];
		$conta = $_POST['conta'];
		$Observacao = addslashes($_POST['Observacao']);
		$tipoDocumento = $_POST['tipoDocumento'];
		$Pis = 0;
		$data = date('Y-m-d');
		$idUsuario = $_SESSION['idUsuario'];
		$sql_atualizar_pessoa = "UPDATE sis_pessoa_fisica SET
			`Nome` = '$Nome',
			`NomeArtistico` = '$NomeArtistico',
			`RG` = '$RG', 
			`CPF` = '$CPF', 
			`CCM` = '$CCM', 
			`IdEstadoCivil` = '$IdEstadoCivil' , 
			`DataNascimento` = '$DataNascimento', 
			`Nacionalidade` = '$Nacionalidade', 
			`CEP` = '$CEP', 
			`Numero` = '$Numero', 
			`Complemento` = '$Complemento', 
			`Telefone1` = '$Telefone1', 
			`Telefone2` = '$Telefone2',  
			`Telefone3` = '$Telefone3', 
			`Email` = '$Email', 
			`DRT` = '$DRT', 
			`cbo` = '$cbo',
			`Funcao` = '$Funcao', 
			`InscricaoINSS` = '$InscricaoINSS', 
			`Pis` = '$Pis', 
			`OMB` = '$OMB', 
			`DataAtualizacao` = '$data', 
			`Observacao` = '$Observacao', 
			`IdUsuario` = '$idUsuario', 
			`tipoDocumento` = '$tipoDocumento', 
			`codBanco` = '$codBanco', 
			`agencia` = '$agencia', 
			`conta` = '$conta'  
			WHERE `Id_PessoaFisica` = '$idPessoaFisica'";
		if(mysqli_query($con,$sql_atualizar_pessoa))
		{
			$mensagem = "Atualizado com sucesso!";	
		}
		else
		{
			$mensagem = "Erro ao atualizar! Tente novamente.";
		}
	}
	$fisica = recuperaDados("sis_pessoa_fisica",$ultimo,"Id_PessoaFisica");
	
	$server = "http://".$_SERVER['SERVER_NAME']."/igsis/"; 
	$http = $server."/pdf/";
	$link1 = $http."rlt_fac_pf.php"."?id_pf=".$fisica['Id_PessoaFisica'];
?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA FÍSICA</h3>
			<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=emia&p=frm_edita_pf&id_pf=<?php echo $ultimo ?>" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome *:</strong><br/>
							<input type="text" class="form-control" id="Nome" name="Nome" placeholder="Nome" value="<?php echo $fisica['Nome']; ?>" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome Artístico:</strong><br/>
							<input type="text" class="form-control" id="NomeArtistico" name="NomeArtistico" placeholder="Nome Artístico" value="<?php echo $fisica['NomeArtistico']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Tipo de documento *:</strong><br/>
							<select class="form-control" id="tipoDocumento" name="tipoDocumento" >
								<?php geraOpcao("igsis_tipo_documento",$fisica['tipoDocumento'],""); ?>  
							</select>
						</div>
						<div class=" col-md-6"><strong>Documento *:</strong><br/>
							<input type="text" class="form-control" id="RG" name="RG" placeholder="Documento" value="<?php echo $fisica['RG']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>CPF *:</strong><br/>
							<input type="text" readonly class="form-control" id="cpf" name="CPF" placeholder="CPF" value="<?php echo $fisica['CPF']; ?>">
						</div>	
						<div class=" col-md-6"><strong>CCM *:</strong><br/>
							<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" value="<?php echo $fisica['CCM']; ?>" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Estado civil:</strong><br/>
							<select class="form-control" id="IdEstadoCivil" name="IdEstadoCivil" >
								<?php geraOpcao("sis_estado_civil",$fisica['IdEstadoCivil'],""); ?>
							</select>
						</div>	
						<div class=" col-md-6"><strong>Data de nascimento:</strong><br/>
							<input type="text" class="form-control" id="datepicker01" name="DataNascimento" placeholder="Data de Nascimento" value="<?php echo exibirDataBr($fisica['DataNascimento']); ?>">
						</div>
					</div>			  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Nacionalidade:</strong><br/>
							<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade" value="<?php echo $fisica['Nacionalidade']; ?>">
						</div>
						<div class=" col-md-6"><strong>CEP *:</strong><br/>
							<input type="text" class="form-control" id="CEP" name="CEP" placeholder="CEP" value="<?php echo $fisica['CEP']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Endereço:</strong><br/>
							<input type="text" class="form-control" id="Endereco" name="Endereco" placeholder="Endereço">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Número *:</strong><br/>
							<input type="text" class="form-control" id="Numero" name="Numero" placeholder="Numero" value="<?php echo $fisica['Numero']; ?>">
						</div>		
						<div class=" col-md-6"><strong>Bairro:</strong><br/>
							<input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Complemento:</strong><br/>
							<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento" value="<?php echo $fisica['Complemento']; ?>">
						</div>
						<div class="col-md-6"><strong>Cidade:</strong><br/>
							<input type="text" class="form-control" id="Cidade" name="Cidade" placeholder="Cidade">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Estado:</strong><br/>
							<input type="text" class="form-control" id="Estado" name="Estado" placeholder="Estado">
						</div>
						<div class="col-md-6"><strong>E-mail:</strong><br/>
							<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail" value="<?php echo $fisica['Email']; ?>" >
						</div>
					</div>	  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone #1 *:</strong><br/>
							<input type="text" class="form-control" id="Telefone1" name="Telefone1" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $fisica['Telefone1']; ?>">
						</div>
						<div class="col-md-6"><strong>Telefone #2:</strong><br/>
							<input type="text" class="form-control" id="Telefone1" name="Telefone2" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98755-9876" value="<?php echo $fisica['Telefone2']; ?>">
						</div>
					</div>  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone #3:</strong><br/>
							<input type="text" class="form-control" id="Telefone3" name="Telefone3" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 4321-4321" value="<?php echo $fisica['Telefone3']; ?>" >
						</div>
						<div class="col-md-6"><strong>DRT:</strong><br/>
							<input type="text" class="form-control" id="DRT" name="DRT" placeholder="DRT" value="<?php echo $fisica['DRT']; ?>">
						</div>
					</div>   
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>C.B.O.:</strong><br/>
							<input type="text" class="form-control" id="cbo" name="cbo" placeholder="C.B.O."value="<?php echo $fisica['cbo']; ?>" >
						</div> 
						<div class=" col-md-6"><strong>Função:</strong><br/>
							<input type="text" class="form-control" id="Funcao" name="Funcao" placeholder="Função" value="<?php echo $fisica['Funcao']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Inscrição do INSS ou PIS/PASEP:</strong><br/>
							<input type="text" class="form-control" id="InscricaoINSS" name="InscricaoINSS" placeholder="Inscrição no INSS ou PIS/PASEP" value="<?php echo $fisica['InscricaoINSS']; ?>">
						</div>			
						<div class=" col-md-6"><strong>OMB:</strong><br/>
							<input type="text" class="form-control" id="OMB" name="OMB" placeholder="OMB" value="<?php echo $fisica['OMB']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Banco:</strong><br/>
							<select class="form-control" name="codBanco" id="codBanco">
								<option></option>
								<option value='32'>Banco do Brasil S.A.</option>
								<?php geraOpcao("igsis_bancos",$fisica['codBanco'],""); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Agência</strong><br/>
							<input type="text" class="form-control" id="agencia" name="agencia" placeholder="" value="<?php echo $fisica['agencia']; ?>">
						</div>
						<div class=" col-md-6"><strong>Conta:</strong><br/>
							<input type="text" class="form-control" id="conta" name="conta" placeholder="" value="<?php echo $fisica['conta']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
							<textarea name="Observacao" class="form-control" rows="10" placeholder=""><?php echo $fisica['Observacao'] ?></textarea>
						</div>
					</div>		  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="cadastrarFisica" value="<?php echo $fisica['Id_PessoaFisica'] ?>" />
<?php
	if(isset($id_pedido))
	{
?>
							<input type="hidden" name="idPedido" value="<?php echo $id_pedido ?>" />
<?php
	}
?>
							<input type="hidden" name="Sucesso" id="Sucesso" />
							<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
                <!-- Botão para verificar arquivos da pessoa -->		
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<form class="form-horizontal" role="form" action="?perfil=emia&p=frm_arquivos&idPessoa=<?php echo $ultimo; ?>&tipoPessoa=1" method="post">
							<input type="hidden" name="cadastrarFisica" value="<?php echo $fisica['Id_PessoaFisica'] ?>" />
							<input type="hidden" name="fisica" value="<?php echo $fisica['Id_PessoaFisica'] ?>" />
<?php
	if(isset($id_pedido))
	{
?>
							<input type="hidden" name="idPedido" value="<?php echo $id_pedido ?>" />
<?php
	}
?>
							<input type="hidden" name="Sucesso" id="Sucesso" />
							<input type="submit" value="Anexos" class="btn btn-theme btn-block">
						</form>
					</div>	
					<div class="col-md-6">
						<a target="_blank" class="btn btn-theme btn-block" href="<?php echo $link1; ?>"><strong>EMITIR FACC</strong></a>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">

					</div>
				</div>	
			</div>
		</div>		
	</div>
</section>