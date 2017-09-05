<?php

$cnpj = "00.000.000/000-00";

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

// Endereço da página
$link = "?perfil=compara_pj";

// Localiza no IGSIS
$sql1 = $con1->query("SELECT * FROM sis_pessoa_juridica where CNPJ = '$cnpj'");
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
$IdRepresentanteLegal1 = $query2["IdRepresentanteLegal1"];
$IdRepresentanteLegal2 = $query2["IdRepresentanteLegal2"];
$DataAtualizacao = $query1["DataAtualizacao"];
$codBanco = $query1["codBanco"];
$Agencia = $query1["agencia"];
$Conta = $query1["conta"];


//Localiza no proponente
$sql2 = $con2->query("SELECT * FROM usuario_pj where cnpj = '$cnpj'");
$query2 = $sql2->fetch_array(MYSQLI_ASSOC);

$razaoSocial = $query2["razaoSocial"];
$cnpj = $query2["cnpj"];
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
	$sql_update_razao = "UPDATE sis_pessoa_juridica SET ".$campo." = '$varCampo' WHERE CNPJ = '$cnpj'";
	if(mysqli_query($con1,$sql_update_razao))
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
	$sql_insere_pj = "INSERT INTO sis_pessoa_juridica (`RazaoSocial`, `CNPJ`, `CCM`, `CEP`,	`Numero`, `Complemento`, `Telefone1`, `Telefone2`, `Telefone3`, `Email`, `IdRepresentanteLegal1`,`IdRepresentanteLegal2`, `DataAtualizacao`,`codBanco`, `agencia`, `conta`) VALUES ('$razaoSocial', '$cnpj', '$ccm', '$cep', '$numero', '$complemento', '$telefone1', '$telefone2', '$telefone3', '$email', '$idRepresentanteLegal1', '$idRepresentanteLegal2', '$dataAtualizacao', '$codigoBanco', '$agencia', '$conta')";
	 
	if(mysqli_query($con1,$sql_insere_pj))
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
}

//Se existir no MACAPAC e não no IGSIS
If($query1 == '' && $query2 != '')
{
	?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<h6>Não foi encontrado registro com esse CNPJ no IGSIS. Deseja importar do Cadastro de Proponente?</h6>
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
						<strong>Razão Social:</strong> <?php echo $razaoSocial ?><br/>
						<strong>CNPJ:</strong> <?php echo $cnpj ?> | 
						<strong>CCM:</strong> <?php echo $ccm ?><br/>
						<strong>CEP:</strong> <?php echo $cep ?><br/>
						<strong>Número:</strong> <?php echo $numero ?> | 
						<strong>Complemento:</strong> <?php echo $complemento ?><br/>
						<strong>Telefone #1:</strong> <?php echo $telefone1 ?> / <?php echo $telefone2 ?> / <?php echo $telefone3 ?><br/>
						<strong>E-mail:</strong> <?php echo $email ?><br/>
						<strong>Representante Legal 1:</strong> <?php echo $idRepresentanteLegal1 ?><br/>
						<strong>Representante Legal 2:</strong> <?php echo $idRepresentanteLegal2 ?><br/>
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
							echo "<td class='list_description'>Razão Social</td>";
							echo "<td class='list_description'>".$RazaoSocial."</td>";
							echo "<td class='list_description'>".$razaoSocial."</td>";
							echo "<td>
									<form method='POST' action='".$link."' enctype='multipart/form-data'>
										<input type='hidden' name='campo' value='Razão Social'  />
										<input type='hidden' name='varCampo' value='".$razaoSocial."'  />
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
						if($IdRepresentanteLegal1 != $idRepresentanteLegal1)
						{
							echo "<tr>";
							echo "<td class='list_description'>Representante Legal 1</td>";
							echo "<td class='list_description'>".$IdRepresentanteLegal1."</td>";
							echo "<td class='list_description'>".$idRepresentanteLegal1."</td>";
							echo "<td>
									<form method='POST' action='".$link."' enctype='multipart/form-data'>
										<input type='hidden' name='campo' value='IdRepresentanteLegal1'  />
										<input type='hidden' name='varCampo' value='".$idRepresentanteLegal1."'  />
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-lg btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
							echo "</tr>";
						}if($IdRepresentanteLegal2 != $idRepresentanteLegal2)
						{
							echo "<tr>";
							echo "<td class='list_description'>Representante Legal 2</td>";
							echo "<td class='list_description'>".$IdRepresentanteLegal2."</td>";
							echo "<td class='list_description'>".$idRepresentanteLegal2."</td>";
							echo "<td>
									<form method='POST' action='".$link."' enctype='multipart/form-data'>
										<input type='hidden' name='campo' value='IdRepresentanteLegal2'  />
										<input type='hidden' name='varCampo' value='".$idRepresentanteLegal2."'  />
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
}

echo "<br><br><br>";

?>