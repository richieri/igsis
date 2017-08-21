<?php

$cpf = "000.000.000-00";

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

// Endereço da página
$link = "?perfil=compara_pf";

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


echo "<br><br><br>";

//Se existir no IGSIS e não no MACAPAC
If($query1 != '' && $query2 == '')
{
	echo "Carregar o resultado do IGSIS.";
}

//Se existir no MACAPAC e não no IGSIS
If($query1 == '' && $query2 != '')
{
	echo "Não foi encontrado registro com esse CPF no IGSIS. Deseja importar do MACAPAC?";
}

//Se existir no MACAPAC e também no IGSIS
If($query1 != '' && $query2 != '')
{
	echo "Comparar os campos para atulizar o cadastro do IGSIS, com um botão de atualizar na frente de cada campo com divergência.";
	?>
	<section id="list_items" class="home-section bg-white">
		<div class="form-group">
			<div class="col-md-offset-2 col-md-8">
				<div class="table-responsive list_info"><h6>Divergências</h6><h5><?php if(isset($mensagem)){echo $mensagem;}; ?></h5>
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
		</div>
	</section>
<?php	
}

//Se existir no IGSIS e não no MACAPAC
If($query1 == '' && $query2 == '')
{
	echo "Ir para o formulário do primeiro cadastro no IGSIS.";
}

echo "<br><br><br>";

?>