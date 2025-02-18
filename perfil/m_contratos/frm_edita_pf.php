<?php
include 'includes/menu.php';

$con = bancoMysqli();
$ultimo = $_GET['id_pf']; //recupera o id da pessoa
$id_ped = $_GET['id_ped'];

if(isset($_POST['idPedido']))
{
	$id_pedido = $_POST['idPedido']; //recupera o id do pedido
	$mensagem = $id_pedido;
}

if(isset($_POST['cadastrarFisica']))
{
	$idPessoaFisica = $_POST['cadastrarFisica'];
	$Nome = addslashes($_POST['Nome']);
	$NomeArtistico = addslashes($_POST['NomeArtistico']);
	$RG = $_POST['RG'];
	$CPF = $_POST['CPF'];
	$CCM = $_POST['CCM'];
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

$pf = recuperaDados("sis_pessoa_fisica",$ultimo,"Id_PessoaFisica");

//Localiza no proponente
$con2 = bancoMysqliProponente();
$cpf = $pf['CPF'];
$sql2 = $con2->query("SELECT * FROM pessoa_fisica where cpf = '$cpf'");
$query2 = $sql2->fetch_array(MYSQLI_ASSOC);
?>

<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA FÍSICA</h3>
			<?php
			If($query2 != '')
				{
				?>
					<div class="col-md-offset-1 col-md-10">
						<div class="col-md-offset-2 col-md-8">
							<form method='POST' action='?perfil=contratos&p=frm_compara_pf&busca=<?php echo $cpf; ?>&id_ped=<?php echo $id_ped; ?>'>
								<input type='submit' class='btn btn-theme btn-md btn-block' value='Verifique aqui se há atualização no CAPAC'>
							</form><br/>				
						</div>
					</div>
				<?php
				}	
			?>		
			<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
			<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_edita_pf&id_pf=<?php echo $ultimo ?>" method="post">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Nome *:</strong><br/>
						<input type="text" class="form-control" id="Nome" name="Nome" placeholder="Nome" value="<?php echo $pf['Nome']; ?>" >
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Nome Artístico:</strong><br/>
						<input type="text" class="form-control" id="NomeArtistico" name="NomeArtistico" placeholder="Nome Artístico" value="<?php echo $pf['NomeArtistico']; ?>">
					</div>
				</div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Tipo de documento *:</strong><br/>
						<select class="form-control" id="tipoDocumento" name="tipoDocumento" >
							<?php geraOpcao("igsis_tipo_documento",$pf['tipoDocumento'],""); ?>  
						</select>
					</div>				  
					<div class=" col-md-6"><strong>Documento *:</strong><br/>
						<input type="text" class="form-control" id="RG" name="RG" placeholder="Documento" value="<?php echo $pf['RG']; ?>">
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>CPF *:</strong><br/>
						<input type="text" class="form-control" id="cpf" name="CPF" placeholder="CPF" readonly value="<?php echo $pf['CPF']; ?>">
					</div>				  
					<div class=" col-md-6"><strong>CCM *:</strong><br/>
						<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" value="<?php echo $pf['CCM']; ?>" >
					</div>
				</div>
		  
				<div class="form-group">			  
					<div class="col-md-offset-2 col-md-6"><strong>Data de nascimento:</strong><br/>
						<input type="text" class="form-control" id="datepicker01" name="DataNascimento" placeholder="Data de Nascimento" value="<?php echo exibirDataBr($pf['DataNascimento']); ?>">
					</div>
					<div class="col-md-6"><strong>Nacionalidade:</strong><br/>
						<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade" value="<?php echo $pf['Nacionalidade']; ?>">
					</div>	
				</div>
		  
				<div class="form-group">			  
					<div class="col-md-offset-2 col-md-"><strong>CEP *:</strong><br/>
						<input type="text" class="form-control" id="CEP" name="CEP" placeholder="CEP" value="<?php echo $pf['CEP']; ?>">
					</div>
				</div>
		  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Endereço:</strong><br/>
						<input type="text" class="form-control" id="Endereco" name="Endereco" placeholder="Endereço">
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Número *:</strong><br/>
						<input type="text" class="form-control" id="Numero" name="Numero" placeholder="Numero" value="<?php echo $pf['Numero']; ?>">
					</div>				  
					<div class=" col-md-6"><strong>Bairro:</strong><br/>
						<input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Complemento:</strong><br/>
						<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento" value="<?php echo $pf['Complemento']; ?>">
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
						<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail" value="<?php echo $pf['Email']; ?>" >
					</div>
				</div>	  

				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Telefone #1 *:</strong><br/>
						<input type="text" class="form-control" name="Telefone1" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $pf['Telefone1']; ?>">
					</div>
					<div class="col-md-6"><strong>Telefone #2:</strong><br/>
						<input type="text" class="form-control" name="Telefone2" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $pf['Telefone2']; ?>">
					</div>
				</div>
						  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Telefone #3:</strong><br/>
						<input type="text" class="form-control" name="Telefone3" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $pf['Telefone3']; ?>" >
					</div>
					<div class="col-md-6"><strong>DRT:</strong><br/>
						<input type="text" class="form-control" id="DRT" name="DRT" placeholder="DRT" value="<?php echo $pf['DRT']; ?>">
					</div>
				</div>
		 
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>C.B.O.:</strong><br/>
						<input type="text" class="form-control" id="cbo" name="cbo" placeholder="C.B.O."value="<?php echo $pf['cbo']; ?>" >
					</div> 				  
					<div class=" col-md-6"><strong>Função:</strong><br/>
						<input type="text" class="form-control" id="Funcao" name="Funcao" placeholder="Função" value="<?php echo $pf['Funcao']; ?>">
					</div>
				</div>
		  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Inscrição do INSS ou PIS/PASEP:</strong><br/>
						<input type="text" class="form-control" id="InscricaoINSS" name="InscricaoINSS" placeholder="Inscrição no INSS ou PIS/PASEP" value="<?php echo $pf['InscricaoINSS']; ?>">
					</div>				  
					<div class=" col-md-6"><strong>OMB:</strong><br/>
						<input type="text" class="form-control" id="OMB" name="OMB" placeholder="OMB" value="<?php echo $pf['OMB']; ?>">
					</div>
				</div>
		 
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Banco:</strong><br/>
						<select class="form-control" name="codBanco" id="codBanco">
							<option></option>
							<option value='32'>Banco do Brasil S.A.</option>
							<?php geraOpcao("igsis_bancos",$pf['codBanco'],"");	?>
						</select>	
					</div>
				</div> 
		  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Agência</strong><br/>
						<input type="text" class="form-control" id="agencia" name="agencia" placeholder="" value="<?php echo $pf['agencia']; ?>">
					</div>				  
					<div class=" col-md-6"><strong>Conta:</strong><br/>
						<input type="text" class="form-control" id="conta" name="conta" placeholder="" value="<?php echo $pf['conta']; ?>">
					</div>
				</div> 
		  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
						<textarea name="Observacao" class="form-control" rows="10" placeholder="<?php echo $pf['Observacao']; ?>"></textarea>
					</div>
				</div>
		  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="cadastrarFisica" value="<?php echo $pf['Id_PessoaFisica'] ?>" />
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
					<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_arquivos&idPessoa=<?php echo $ultimo; ?>&tipoPessoa=1&id_ped=<?php echo $id_ped ?>" method="post">
						<input type="hidden" name="cadastrarFisica" value="<?php echo $pf['Id_PessoaFisica'] ?>" />
						<input type="hidden" name="fisica" value="<?php echo $pf['Id_PessoaFisica'] ?>" />
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
                    <?php
						if($id_ped != '')
						{
					?>
							<br/><a href="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $id_ped ?>"><input type="submit" value="Voltar ao pedido" class="btn btn-theme btn-block"></a>	
					<?php	
						}
						else
						{
							?>
							<br/><a href="?perfil=contratos&p=frm_lista_pf"><input type="submit" value="Voltar" class="btn btn-theme btn-block"></a>
							<?php
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>  