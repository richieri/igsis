<?php
include 'includes/menu.php';

$ultimo = $_GET['id_pj']; //recupera o id da pessoa
$con = bancoMysqli();
$id_ped = $_GET['id_ped'];

if(isset($_POST['idPedido']))
{
	$id_pedido = $_POST['idPedido']; //recupera o id do pedido
	$mensagem = $id_pedido;
}

// representante
if(isset($_POST['apagarRepresentante']))
{
	$x = "IdRepresentanteLegal".$_POST['apagarRepresentante'];
	$sql_retira_representante = "UPDATE sis_pessoa_juridica SET $x = NULL WHERE Id_PessoaJuridica = '$ultimo'";
	$query_retira_represenante = mysqli_query($con,$sql_retira_representante);
	if($query_retira_represenante)
	{
		$mensagem = "Representante ".$_POST['apagar_representante']." retirado com sucesso!";	
	}
	else
	{
		$mesnagem = "Erro ao retirar representante.";	
	}	
}

if(isset($_POST['cadastraRepresentante']))
{
	$cpf = $_POST['CPF'];
	$verificaCPF = verificaExiste("sis_representante_legal","CPF",$cpf,"");
	if($verificaCPF['numero'] > 0)
	{ //verifica se o cpf já existe
		$mensagem = "O CPF já consta no sistema. Faça uma busca e insira diretamente.";
	}
	else
	{ // o CPF não existe, inserir.
		if($_POST['numero'] == 1)
		{
			$campo = "idRepresentante01";
		}
		else
		{
			$campo = "idRepresentante02";
		}
		$RepresentanteLegal = addslashes($_POST['RepresentanteLegal']);
		$RG = $_POST['RG'];
		$CPF = $_POST['CPF'];
		$Nacionalidade = $_POST['Nacionalidade'];
		$idUsuario = $_SESSION['idUsuario'];
		$sql_insert_representante = "INSERT INTO `sis_representante_legal` (`Id_RepresentanteLegal`, `RepresentanteLegal`, `RG`, `CPF`, `Nacionalidade`, `idEvento`) VALUES (NULL, '$RepresentanteLegal', '$RG', '$CPF', '$Nacionalidade', NULL);";
		$query_insert_representante = mysqli_query($con,$sql_insert_representante);
		if($query_insert_representante)
		{
			gravarLog($sql_insert_representante);
			$sql_ultimo = "SELECT * FROM sis_representante_legal ORDER BY Id_ResponsavelLegal DESC LIMIT 0,1"; //recupera ultimo id
			$id_evento = mysqli_query($con,$sql_ultimo);
			$id = mysqli_fetch_array($id_evento);
			$idRepresentante = $id['Id_RepresentanteLegal'];
			$idPedido = $_SESSION['idPedido'];		
			$sql_insert_pedido = "UPDATE `igsis_pessoa_juridica` SET `$campo` = '$idRepresentante' WHERE `Id_PessoaJuridica` = '$idJuridica';";
			$query_insert_pedido = mysqli_query($con,$sql_insert_pedido);
			if($query_insert_pedido)
			{
				gravarLog($sql_insert_pedido);
				echo "<h1>Inserido com sucesso!</h1>";
			}
			else
			{
				echo "<h1>Erro ao inserir!</h1>";
			}
		}
		else
		{
			echo "<h1>Erro ao inserir!</h1>";
		}
	}
}
if(isset($_POST['insereRepresentante']))
{ 
	$id_representante = $_POST['insereRepresentante'];
	if($_POST['numero'] == 1)
	{
		$campo = "IdRepresentanteLegal1";
	}
	else
	{
		$campo = "IdRepresentanteLegal2";
	}
	$idPedido = $_SESSION['idPedido'];
	
	$sql_atualiza_representante = "UPDATE `sis_pessoa_juridica` SET `$campo` = '$id_representante' 
	WHERE `Id_PessoaJuridica` = '$ultimo';";
	$query_atualiza_representante = mysqli_query($con,$sql_atualiza_representante);	
	if($query_atualiza_representante)
	{
		$mensagem = "Representante legal inserido com sucesso!";	
	}
}

//PJ
if(isset($_POST['editaJuridica']))
{
	$idJuridica = $_POST['editaJuridica'];
	$RazaoSocial = addslashes($_POST['RazaoSocial']);
	$CNPJ = $_POST['CNPJ'];
	$CCM = $_POST['CCM'];
	$CEP = $_POST['CEP'];
	$Numero = $_POST['Numero'];
	$Complemento = $_POST['Complemento'];
	$Telefone1 = $_POST['Telefone1'];
	$Telefone2 = $_POST['Telefone2'];
	$Telefone3 = $_POST['Telefone3'];
	$Email = $_POST['Email'];
	$codBanco = $_POST['codBanco'];
	$agencia = $_POST['agencia'];
	$conta = $_POST['conta'];
	$Observacao = $_POST['Observacao'];
	$data = date("Y-m-d");
	$idUsuario = $_SESSION['idUsuario'];
	
	$sql_atualizar_juridica = "UPDATE `sis_pessoa_juridica` SET `RazaoSocial` = '$RazaoSocial', `CNPJ` = '$CNPJ', `CCM` = '$CCM', `CEP` = '$CEP', `Numero` = '$Numero', `Complemento` = '$Complemento', `Telefone1` = '$Telefone1', `Telefone2` = '$Telefone2', `Telefone3` = '$Telefone3', `Email` = '$Email',  `DataAtualizacao` = '$data', `Observacao` = '$Observacao', `codBanco` = '$codBanco', `agencia` = '$agencia', `conta` = '$conta'   WHERE `sis_pessoa_juridica`.`Id_PessoaJuridica` = '$idJuridica';";
	if(mysqli_query($con,$sql_atualizar_juridica))
	{
		$mensagem = "Atualizado com sucesso!";	
	}
	else
	{
		$mensagem = "Erro ao atualizar! Tente novamente.";
	}		
}

$pj = recuperaDados("sis_pessoa_juridica",$ultimo,"Id_PessoaJuridica");
$res01 = siscontratDocs($pj['IdRepresentanteLegal1'],3);
$res02 = siscontratDocs($pj['IdRepresentanteLegal2'],3);

?>

<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA JURÍDICA</h3>
            <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
        </div>
		<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
                <div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Representante legal #01:</strong><br/>
						<input type='text' readonly class='form-control' name='RazaoSocial' id='RazaoSocial' value="<?php echo $res01['Nome']; ?>">
					</div>
                </div>
				
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8">
					<form class="form-horizontal" role="form"  method="post" action="?perfil=contratos_lite&p=frm_edita_representantelegal&pj=<?php echo $ultimo; ?>&num=1&id_rep=<?php echo $pj['IdRepresentanteLegal1']?>">
						<input type="submit" class="btn btn-theme btn-med btn-block" value="Abrir Representante legal #01">
                    </form><br />
					</div>
				</div>
				
                <div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Representante legal #02:</strong><br/>
						<input type='text' readonly class='form-control' name='RazaoSocial' id='RazaoSocial' value="<?php echo $res02['Nome']; ?>">
					</div>
                </div>  
                
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
					<form class="form-horizontal" role="form"  method="post" action="?perfil=contratos_lite&p=frm_edita_representantelegal&pj=<?php echo $ultimo; ?>&num=2&id_rep=<?php echo $pj['IdRepresentanteLegal2']?>">
						<input type="submit" class="btn btn-theme btn-med btn-block" value="Abrir Representante legal #02">
                    </form><br />
					</div>
				</div>

 				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
                
				<form class="form-horizontal" role="form" action="?perfil=contratos_lite&p=frm_edita_pj&id_pj=<?php echo $ultimo ?>" method="post">
			
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Razão Social *:</strong><br/>
						<input type="text" class="form-control" id="RazaoSocial" name="RazaoSocial" placeholder="RazaoSocial" value="<?php echo $pj['RazaoSocial']; ?>">
					</div>
				</div>

                <div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>CNPJ *:</strong><br/>
						<input type="text" class="form-control" readonly id="CNPJ" name="CNPJ" placeholder="CNPJ" value="<?php echo $pj['CNPJ']; ?>" >
					</div>
					<div class="col-md-6"><strong>CCM:</strong><br/>
						<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" value="<?php echo $pj['CCM']; ?>">
					</div>
				</div>
				  
				<div class="form-group">				  
					<div class="col-md-offset-2 col-md-8"><strong>CEP *:</strong><br/>
						<input type="text" class="form-control" id="CEP" name="CEP" placeholder="CEP" value="<?php echo $pj['CEP']; ?>">
					</div>
				</div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Endereço:</strong><br/>
						<input type="text" class="form-control" id="Endereco" name="Endereco" placeholder="Endereço">
					</div>
				</div>
				  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Número *:</strong><br/>
						<input type="text" class="form-control" id="Numero" name="Numero" placeholder="Numero" value="<?php echo $pj['Numero']; ?>">
					</div>				  
					<div class=" col-md-6"><strong>Bairro:</strong><br/>
						<input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
					</div>
				</div>
				  
                <div class="form-group">                     
					<div class="col-md-offset-2 col-md-8"><strong>Complemento *:</strong><br/>
					    <input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento" value="<?php echo $pj['Complemento']; ?>">
					</div>
				</div>		
                  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Cidade *:</strong><br/>
						<input type="text" class="form-control" id="Cidade" name="Cidade" placeholder="Cidade">
					</div>				  
					<div class=" col-md-6"><strong>Estado *:</strong><br/>
						<input type="text" class="form-control" id="Estado" name="Estado" placeholder="Estado">
					</div>
				</div>
				  
				<div class="form-group">
                  	<div class="col-md-offset-2 col-md-6"><strong>E-mail *:</strong><br/>
						<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail" value="<?php echo $pj['Email']; ?>" >
					</div>
					<div class=" col-md-6"><strong>Telefone #1 *:</strong><br/>
						<input type="text" class="form-control" name="Telefone1" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $pj['Telefone1']; ?>">
					</div>
				</div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Telefone #2:</strong><br/>
						<input type="text" class="form-control" name="Telefone2" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $pj['Telefone2']; ?>">
					</div>				  
					<div class="col-md-6"><strong>Telefone #3:</strong><br/>
						<input type="text" class="form-control" name="Telefone3" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $pj['Telefone3']; ?>" >
					</div>
				</div>
				 
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Banco:</strong><br/>
						<select class="form-control" name="codBanco" id="codBanco">
							<option></option>
							<option value='32'>Banco do Brasil S.A.</option>
							<?php geraOpcao("igsis_bancos",$pj['codBanco'],""); ?>
						</select>
					</div>
				</div> 
                  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Agência</strong><br/>
						<input type="text" class="form-control" id="agencia" name="agencia" placeholder="" value="<?php echo $pj['agencia']; ?>">
					</div>				  
					<div class=" col-md-6"><strong>Conta:</strong><br/>
						<input type="text" class="form-control" id="conta" name="conta" placeholder="" value="<?php echo $pj['conta']; ?>">
					</div>
				</div> 
				                 
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
						<textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
					</div>
				</div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="editaJuridica" value="<?php echo $pj['Id_PessoaJuridica'] ?>" />
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

				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"></div>
                </div>
				
                <!-- Botão para verificar arquivos da pessoa -->
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
					<form class="form-horizontal" role="form" action="?perfil=contratos_lite&p=frm_arquivos&idPessoa=<?php echo $ultimo; ?>&tipoPessoa=2&id_ped=<?php echo $id_ped ?>" method="post">
						<input type="hidden" name="editaJuridica" value="<?php echo $pj['Id_PessoaJuridica'] ?>" />
						<input type="hidden" name="Juridica" value="<?php echo $pj['Id_PessoaJuridica'] ?>" />
						<?php 
							if(isset($id_pedido))
							{ 
						?>
							<input type="hidden" name="idPedido" value="<?php echo $id_pedido ?>" />
						<?php 
							} 
						?>
						<input type="hidden" name="Sucesso" id="Sucesso" /><br />
						<input type="submit" value="Anexos" class="btn btn-theme btn-block">
					</form>
					</div>                    
					<div class="col-md-6">
                    <?php 
						if(isset($id_pedido))
						{ 
					?>
							<br/><a href="?perfil=contratos_lite&p=frm_edita_propostapj&id_ped=<?php echo $id_pedido ?>"><input type="submit" value="Voltar ao pedido" class="btn btn-theme btn-block"></a>
					<?php 
						}
						elseif($id_ped <> "")
						{ 
					?>
							<a href="?perfil=contratos_lite&p=frm_edita_propostapj&id_ped=<?php echo $id_ped ?>"><input type="submit" value="Voltar ao pedido" class="btn btn-theme btn-block"></a>
					<?php 
						}  
					?>
					</div>
				</div>
	  		</div>
		</div>
	</div>
</section>  