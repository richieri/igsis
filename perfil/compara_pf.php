<?php

//$cpf = "000.000.000-00";

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

// Endereço da página
$link = "?perfil=compara_pf";

if(isset($_POST['pesquisar']))
{
	// inicia a busca por CPF
	$cpf = $_POST['busca'];	

	// Localiza no IGSIS
	$sql1 = $con1->query("SELECT * FROM sis_pessoa_fisica where CPF = '$cpf'");
	$query1 = $sql1->fetch_array(MYSQLI_ASSOC);

	$Nome = $query1["Nome"];
	$NomeArtistico = $query1["NomeArtistico"];
	$RG = $query1["RG"];
	$CPF = $query1["CPF"];
	$CCM = $query1["CCM"];
	$IdEstadoCivil = $query1["IdEstadoCivil"];
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
	$sql2 = $con2->query("SELECT * FROM usuario_pf where cpf = '$cpf'");
	$query2 = $sql2->fetch_array(MYSQLI_ASSOC);

	$nome = $query2["nome"];
	$nomeArtistico = $query2["nomeArtistico"];
	$rg = $query2["rg"];
	$cpf = $query2["cpf"];
	$ccm = $query2["ccm"];
	$idEstadoCivil = $query2["idEstadoCivil"];
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


	//retorna uma array com os dados de qualquer tabela do IGSIS. Serve apenas para 1 registro.
	function recuperaDadosIgsis($tabela_dados_ig,$campo_dados_ig,$variavelCampo_dados_ig)
	{	
		$con1 = bancoMysqli();
		$sql_dados_ig = "SELECT * FROM $tabela_dados_ig WHERE ".$campo_dados_ig." = '$variavelCampo_dados_ig' LIMIT 0,1";
		$query_dados_ig = mysqli_query($con1,$sql_dados_ig);
		$campo_dados_ig = mysqli_fetch_array($query_dados_ig);
		return $campo_dados_ig;		
	}

	//retorna uma array com os dados de qualquer tabela do MACAPAC. Serve apenas para 1 registro.
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
		$sql_update_nome = "UPDATE sis_pessoa_fisica SET ".$campo." = '$varCampo' WHERE CPF = '$cpf'";
		if(mysqli_query($con1,$sql_update_nome))
		{
			$mensagem =	$nome." atualizado com sucesso!";
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=".$link."'>";
		}
		else
		{
			$mensagem = "Erro ao atualizar ".$nome.". Tente novamente!";
		}
	}

	if(isset($_POST['importarMacapacIgsis']))
	{
		$sql_insere_pf = "INSERT INTO sis_pessoa_fisica (`Nome`, `NomeArtistico`, `RG`, `CPF`, `CCM`, `IdEstadoCivil`, `DataNascimento`, `LocalNascimento`,	`Nacionalidade`, `CEP`,	`Numero`, `Complemento`, `Telefone1`, `Telefone2`, `Telefone3`, `Email`, `DRT`,	`Funcao`, `Pis`, `OMB`, `DataAtualizacao`,`tipoDocumento`, `codBanco`, `agencia`, `conta`, `cbo`) VALUES ('$nome', '$nomeArtistico', '$rg', '$cpf', '$ccm', '$idEstadoCivil', '$dataNascimento', '$localNascimento', '$nacionalidade', '$cep', '$numero', '$complemento', '$telefone1', '$telefone2', '$telefone3', '$email', '$drt', '$funcao', '$pis', '$omb', '$dataAtualizacao', '$idTipoDocumento', '$codigoBanco', '$agencia', '$conta', '$cbo')";
		 
		if(mysqli_query($con1,$sql_insere_pf))
		{
			$mensagem = "Importado com sucesso!";
			echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=".$link."'>";		
		}
		else
		{
			$mensagem = "Erro ao importar! Tente novamente.";
		}	  
	}	


	//Se existir no IGSIS e não no MACAPAC
	If($query1 != '' && $query2 == '')
	{
		echo "<br><br><br>";
		echo "Carregar o resultado do IGSIS.";
	?>
		<section id="services" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h2>Contratados - Pessoa Física</h2>                      
							<p></p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="table-responsive list_info">
					<table class="table table-condensed">
						<thead>
							<tr class="list_menu">
								<td>Nome</td>
								<td>CPF</td>
								<td width="25%"></td>
								<td width="5%"></td>
							</tr>
						</thead>
						<tbody>
						<?php
							while($descricao = mysqli_fetch_array($query1))
							{			
								echo "<tr>";
								echo "<td class='list_description'><b>".$descricao['Nome']."</b></td>";
								echo "<td class='list_description'>".$descricao['CPF']."</td>";
								echo "
									<td class='list_description'>
									<form method='POST' action='?perfil=contratados&p=lista'>
									<input type='hidden' name='insereFisica' value='1'>
									<input type='hidden' name='Id_PessoaFisica' value='".$descricao['Id_PessoaFisica']."'>
									<input type ='submit' class='btn btn-theme btn-md btn-block' value='inserir'></td></form>"	;
								echo "</tr>";
							}
						?>		
						</tbody>
					</table>
				</div>
			</div>
		</section>
	<?php
	}

	//Se existir no MACAPAC e não no IGSIS
	If($query1 == '' && $query2 != '')
	{
		?>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h6>Não foi encontrado registro com esse CPF no IGSIS. Deseja importar do Cadastro de Proponente?</h6>
						<h5><?php if(isset($mensagem)){echo $mensagem;}; ?></h5>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<?php 
						if($query1 == '' && $query2 != '')
						{
						?>	
							<form method='POST' action='<?php echo $link ?>' enctype='multipart/form-data'>
								<input type='hidden' name='botaoImportar' value='1'  />
								<input type='submit' name='importarMacapacIgsis' class='btn btn-theme btn-lg btn-block' value='Importar'>
							</form><br/>
						<?php 	
						}
						else
						{
						?>
							<br/>
						<?php 	
						}	
						?>	
					</div>
					<div class="col-md-6">
						<form method='POST' action='".$link."' enctype='multipart/form-data'>
							<input type='submit' name='' class='btn btn-theme btn-lg btn-block' value='Voltar'>
						</form>
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
							<strong>CPF:</strong> <?php echo $cpf ?> | 
							<strong>CCM:</strong> <?php echo $ccm ?><br/>
							<strong>Estado Civil:</strong> <?php echo $idEstadoCivil ?><br/>
							<strong>Data de Nascimento:</strong> <?php echo exibirDataBr($dataNascimento) ?><br/>
							<strong>Local de Nascimento:</strong> <?php echo $localNascimento ?> | 
							<strong>Nacionalidade:</strong> <?php echo $nacionalidade ?><br/>
							<strong>CEP:</strong> <?php echo $cep ?><br/>
							<strong>Número:</strong> <?php echo $numero ?> | 
							<strong>Complemento:</strong> <?php echo $complemento ?><br/>
							<strong>Telefone #1:</strong> <?php echo $telefone1 ?> / <?php echo $telefone2 ?> / <?php echo $telefone3 ?><br/>
							<strong>E-mail:</strong> <?php echo $email ?><br/>
							<strong>DRT:</strong> <?php echo $drt ?><br/>
							<strong>Função:</strong> <?php echo $funcao ?> | 
							<strong>CBO:</strong> <?php echo $cbo ?><br/>
							<strong>NIT:</strong> <?php echo $pis ?><br/>
							<strong>OMB:</strong> <?php echo $omb ?><br/>
							<strong>Banco:</strong> <?php echo $codigoBanco ?><br/>
							<strong>Agência:</strong> <?php echo $agencia ?> | 
							<strong>Conta:</strong> <?php echo $conta ?><br/>
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

	//Se existir no MACAPAC e também no IGSIS
	If($query1 != '' && $query2 != '')
	{
		echo "Comparar os campos para atulizar o cadastro do IGSIS, com um botão de atualizar na frente de cada campo com divergência.";
		?>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="table-responsive list_info">
					<h6>Divergências</h6>
					<h5><?php if(isset($mensagem)){echo $mensagem;}; ?></h5>
					<table class='table table-condensed'>
						<thead>
							<tr class='list_menu'>
								<td><strong>Campo Divergente</strong></td>
								<td><strong>IGSIS</strong></td>
								<td><strong>MACAPAC</strong></td>
								<td width='20%'></td>
							</tr>
						</thead>
						<tbody>
						<?php
							if($Nome != $nome)
							{
								echo "<tr>";
								echo "<td class='list_description'>Nome</td>";
								echo "<td class='list_description'>".$Nome."</td>";
								echo "<td class='list_description'>".$nome."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Nome'  />
											<input type='hidden' name='varCampo' value='".$nome."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($NomeArtistico != $nomeArtistico)
							{
								echo "<tr>";
								echo "<td class='list_description'>Nome Artístico</td>";
								echo "<td class='list_description'>".$NomeArtistico."</td>";
								echo "<td class='list_description'>".$nomeArtistico."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='NomeArtistico'  />
											<input type='hidden' name='varCampo' value='".$nomeArtistico."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($RG != $rg)
							{
								echo "<tr>";
								echo "<td class='list_description'>RG</td>";
								echo "<td class='list_description'>".$RG."</td>";
								echo "<td class='list_description'>".$rg."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='RG'  />
											<input type='hidden' name='varCampo' value='".$rg."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($CCM != $ccm)
							{
								echo "<tr>";
								echo "<td class='list_description'>CCM</td>";
								echo "<td class='list_description'>".$CCM."</td>";
								echo "<td class='list_description'>".$ccm."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='CCM'  />
											<input type='hidden' name='varCampo' value='".$ccm."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($IdEstadoCivil != $idEstadoCivil)
							{
								$igEstadoCivil = recuperaDadosIgsis("sis_estado_civil","Id_EstadoCivil","$IdEstadoCivil");
								$propEstadoCivil = recuperaDadosProp("estado_civil","id","$idEstadoCivil");
								echo "<tr>";
								echo "<td class='list_description'>Estado Civil</td>";
								echo "<td class='list_description'>".$igEstadoCivil['EstadoCivil']."</td>";
								echo "<td class='list_description'>".$propEstadoCivil['estadoCivil']."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='IdEstadoCivil'  />
											<input type='hidden' name='varCampo' value='".$idEstadoCivil."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($DataNascimento != $dataNascimento)
							{
								echo "<tr>";
								echo "<td class='list_description'>Data de Nascimento</td>";
								echo "<td class='list_description'>".exibirDataBr($DataNascimento)."</td>";
								echo "<td class='list_description'>".exibirDataBr($dataNascimento)."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='DataNascimento'  />
											<input type='hidden' name='varCampo' value='".$dataNascimento."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($LocalNascimento != $localNascimento)
							{
								echo "<tr>";
								echo "<td class='list_description'>Local de Nascimento</td>";
								echo "<td class='list_description'>".$LocalNascimento."</td>";
								echo "<td class='list_description'>".$localNascimento."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='LocalNascimento'  />
											<input type='hidden' name='varCampo' value='".$localNascimento."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Nacionalidade != $nacionalidade)
							{
								echo "<tr>";
								echo "<td class='list_description'>Nacionalidade</td>";
								echo "<td class='list_description'>".$Nacionalidade."</td>";
								echo "<td class='list_description'>".$nacionalidade."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Nacionalidade'  />
											<input type='hidden' name='varCampo' value='".$nacionalidade."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($CEP != $cep)
							{
								echo "<tr>";
								echo "<td class='list_description'>CEP</td>";
								echo "<td class='list_description'>".$CEP."</td>";
								echo "<td class='list_description'>".$cep."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='CEP'  />
											<input type='hidden' name='varCampo' value='".$cep."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Numero != $numero)
							{
								echo "<tr>";
								echo "<td class='list_description'>Número</td>";
								echo "<td class='list_description'>".$Numero."</td>";
								echo "<td class='list_description'>".$numero."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Numero'  />
											<input type='hidden' name='varCampo' value='".$numero."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Complemento != $complemento)
							{
								echo "<tr>";
								echo "<td class='list_description'>Complemento</td>";
								echo "<td class='list_description'>".$Complemento."</td>";
								echo "<td class='list_description'>".$complemento."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Complemento'  />
											<input type='hidden' name='varCampo' value='".$complemento."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Telefone1 != $telefone1)
							{
								echo "<tr>";
								echo "<td class='list_description'>Telefone #1</td>";
								echo "<td class='list_description'>".$Telefone1."</td>";
								echo "<td class='list_description'>".$telefone1."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Telefone1'  />
											<input type='hidden' name='varCampo' value='".$telefone1."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Telefone2 != $telefone2)
							{
								echo "<tr>";
								echo "<td class='list_description'>Telefone #2</td>";
								echo "<td class='list_description'>".$Telefone2."</td>";
								echo "<td class='list_description'>".$telefone2."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Telefone2'  />
											<input type='hidden' name='varCampo' value='".$telefone2."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Telefone3 != $telefone3)
							{
								echo "<tr>";
								echo "<td class='list_description'>Telefone #3</td>";
								echo "<td class='list_description'>".$Telefone3."</td>";
								echo "<td class='list_description'>".$telefone3."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Telefone3'  />
											<input type='hidden' name='varCampo' value='".$telefone3."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Email != $email)
							{
								echo "<tr>";
								echo "<td class='list_description'>E-mail</td>";
								echo "<td class='list_description'>".$Email."</td>";
								echo "<td class='list_description'>".$email."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Email'  />
											<input type='hidden' name='varCampo' value='".$email."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($DRT != $drt)
							{
								echo "<tr>";
								echo "<td class='list_description'>DRT</td>";
								echo "<td class='list_description'>".$DRT."</td>";
								echo "<td class='list_description'>".$drt."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='DRT'  />
											<input type='hidden' name='varCampo' value='".$drt."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Funcao != $funcao)
							{
								echo "<tr>";
								echo "<td class='list_description'>Função</td>";
								echo "<td class='list_description'>".$Funcao."</td>";
								echo "<td class='list_description'>".$funcao."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Funcao'  />
											<input type='hidden' name='varCampo' value='".$funcao."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Cbo != $cbo)
							{
								echo "<tr>";
								echo "<td class='list_description'>CBO</td>";
								echo "<td class='list_description'>".$Cbo."</td>";
								echo "<td class='list_description'>".$cbo."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Cbo'  />
											<input type='hidden' name='varCampo' value='".$cbo."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Pis != $pis)
							{
								echo "<tr>";
								echo "<td class='list_description'>NIT</td>";
								echo "<td class='list_description'>".$Pis."</td>";
								echo "<td class='list_description'>".$pis."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Pis'  />
											<input type='hidden' name='varCampo' value='".$pis."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($OMB != $omb)
							{
								echo "<tr>";
								echo "<td class='list_description'>OMB</td>";
								echo "<td class='list_description'>".$OMB."</td>";
								echo "<td class='list_description'>".$omb."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='OMB'  />
											<input type='hidden' name='varCampo' value='".$omb."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
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
								echo "<td class='list_description'>".$igNomeBanco['banco']."</td>";
								echo "<td class='list_description'>".$propNomeBanco['banco']."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='codBanco'  />
											<input type='hidden' name='varCampo' value='".$codigoBanco."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Agencia != $agencia)
							{
								echo "<tr>";
								echo "<td class='list_description'>Agência</td>";
								echo "<td class='list_description'>".$Agencia."</td>";
								echo "<td class='list_description'>".$agencia."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Agencia'  />
											<input type='hidden' name='varCampo' value='".$agencia."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($Conta != $conta)
							{
								echo "<tr>";
								echo "<td class='list_description'>Conta</td>";
								echo "<td class='list_description'>".$Conta."</td>";
								echo "<td class='list_description'>".$conta."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='Conta'  />
											<input type='hidden' name='varCampo' value='".$conta."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}
							if($DataAtualizacao != $dataAtualizacao)
							{
								echo "<tr>";
								echo "<td class='list_description'>Última Atualização</td>";
								echo "<td class='list_description'>".exibirDataBr($DataAtualizacao)."</td>";
								echo "<td class='list_description'>".exibirDataBr($dataAtualizacao)."</td>";
								echo "<td>
										<form method='POST' action='".$link."' enctype='multipart/form-data'>
											<input type='hidden' name='campo' value='DataAtualizacao'  />
											<input type='hidden' name='varCampo' value='".$dataAtualizacao."'  />
											<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
										</form>
									</td>";
								echo "</tr>";
							}							
						?>
						</tbody>
					</table>
				</div>	
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-6">
					<form method='POST' action='".$link."' enctype='multipart/form-data'>
						<input type='submit' name='' class='btn btn-theme btn-lg btn-block' value='Voltar'>
					</form>				
				</div>
				<div class="col-md-6">
					<form method='POST' action='<?php echo $link ?>' enctype='multipart/form-data'>
						<input type='submit' name='' class='btn btn-theme btn-lg btn-block' value='Avançar'>
					</form><br/>
				</div>
			</div>
		</section>
	<?php	
	}

	//Se existir no IGSIS e não no MACAPAC
	If($query1 == '' && $query2 == '')
	{
		echo "<br><br><br>";
		echo "Ir para o formulário do primeiro cadastro no IGSIS.";
		$ultimo = cadastroPessoa($_SESSION['idEvento'],$CPF,'1'); 
		$campo = recuperaDados("sis_pessoa_fisica",$ultimo,"Id_PessoaFisica");
	?>
		<section id="contact" class="home-section bg-white">
			<div class="container">
				<div class="form-group">
					<h3>CADASTRO DE PESSOA FÍSICA</h3>
					<p> O CPF <?php echo $cpf; ?> não está cadastrado no nosso sistema. <br />Por favor, insira as informações da Pessoa Física a ser contratada. </p>
					<p><a href="?perfil=contratados&p=fisica"> Pesquisar outro CPF</a> </p>
				</div>
				<div class="row">
					<div class="col-md-offset-1 col-md-10">
						<form class="form-horizontal" role="form" action="?perfil=contratados&p=lista" method="post">
							<div class="form-group">
								<div class="col-md-offset-2 col-md-8"><strong>Nome *:</strong><br/>
									<input type="text" class="form-control" id="Nome" name="Nome" placeholder="Nome" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-2 col-md-8"><strong>Nome Artístico:</strong><br/>
									<input type="text" class="form-control" id="NomeArtistico" name="NomeArtistico" placeholder="Nome Artístico" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-2 col-md-6"><strong>Tipo de documento *:</strong><br/>
									<select class="form-control" id="tipoDocumento" name="tipoDocumento" >
										<?php geraOpcao("igsis_tipo_documento","",""); ?>  
									</select>
								</div>				  
								<div class=" col-md-6"><strong>Documento *:</strong><br/>
									<input type="text" class="form-control" id="RG" name="RG" placeholder="Documento" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-2 col-md-6"><strong>CPF *:</strong><br/>
									<input type="text" class="form-control" id="cpf" name="CPF" placeholder="CPF" value="<?php echo $cpf; ?> ">
								</div>				  
								<div class=" col-md-6"><strong>CCM *:</strong><br/>
									<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-2 col-md-6"><strong>Estado civil:</strong><br/>
									<select class="form-control" id="IdEstadoCivil" name="IdEstadoCivil" >
										<?php geraOpcao("sis_estado_civil","",""); ?>  
									</select>
								</div>				  
								<div class=" col-md-6"><strong>Data de nascimento:</strong><br/>
									<input type="text" class="form-control" id="datepicker01" name="DataNascimento" placeholder="Data de Nascimento" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-2 col-md-6"><strong>Nacionalidade:</strong><br/>
									<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade">
								</div>				  
								<div class=" col-md-6"><strong>CEP:</strong><br/>
									<input type="text" class="form-control" id="CEP" name="CEP" placeholder="CEP">
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
								<div class=" col-md-6"><strong>Bairro:</strong><br/>
									<input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
								</div>
							</div>
							<div class="form-group">     
								<div class="col-md-offset-2 col-md-8"><strong>Complemento *:</strong><br/>
									<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento">
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
								<div class="col-md-offset-2 col-md-6"><strong>DRT:</strong><br/>
									<input type="text" class="form-control" id="DRT" name="DRT" placeholder="DRT" >
								</div>				  
								<div class=" col-md-6"><strong>Função:</strong><br/>
									<input type="text" class="form-control" id="Funcao" name="Funcao" placeholder="Função">
								</div>
							</div>  
							<div class="form-group">
								<div class="col-md-offset-2 col-md-6"><strong>Inscrição do INSS ou PIS/PASEP:</strong><br/>
									<input type="text" class="form-control" id="InscricaoINSS" name="InscricaoINSS" placeholder="Inscrição no INSS ou PIS/PASEP" >
								</div>				  
								<div class=" col-md-6"><strong>OMB:</strong><br/>
									<input type="text" class="form-control" id="OMB" name="OMB" placeholder="OMB" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
									<textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-2 col-md-8">
									<input type="hidden" name="cadastrarFisica" value="1" />
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

}
else
{
	// Se não existe pedido de busca, exibe campo de pesquisa.
?>    
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h2>Contratados - Pessoa Física</h2>
						<p>Você está inserindo pessoas físicas para serem contratadas para o evento <strong><?php  echo $nomeEvento['nomeEvento']; ?></strong></p>
						<p></p>
					</div>
				</div>
			</div>	  
			<div class="row">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<form method="POST" action="?perfil=contratados&p=fisica" class="form-horizontal" role="form">
							<label>Insira o CPF</label>
							<input type="text" name="busca" class="form-control" id="cpf" >
							<br />             
							<div class="form-group">
								<div class="col-md-offset-2 col-md-8">
									<input type="hidden" name="pesquisar" value="1" />
									<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
								</div>
							</div>	
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
}
?>