<?php

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

// GETs
$cnpj_busca = $_GET['busca'];
$id_ped = $_GET['id_ped'];

// Endereço da página
$link = "?perfil=contratos&p=frm_compara_pj&id_ped=".$id_ped."";

// Localiza no IGSIS
$sql1 = $con1->query("SELECT * FROM sis_pessoa_juridica where CNPJ = '$cnpj_busca'");
$query1 = $sql1->fetch_array(MYSQLI_ASSOC);

$id_pj = $query1["Id_PessoaJuridica"];
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


//Se existir no CAPAC e também no IGSIS
If($query1 != '' && $query2 != '')
{		
	?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
		<p>O CNPJ nº <strong><?php echo $cnpj_busca ?></strong> possui cadastro no sistema de proponente e no IGSIS. Abaixo está a lista com as divergências apontadas o cadastro.</p>
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
						if($IdRepresentanteLegal1 != $idRepresentanteLegal1)
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
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
									</form>
								</td>";
							echo "</tr>";
						}
						if($IdRepresentanteLegal2 != $idRepresentanteLegal2)
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
										<input type='submit' name='atualizaIgsis' class='btn btn-theme btn-md btn-block' value='Atualizar IGSIS'>
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
										<input type='hidden' name='busca' value='".$cnpj."'  />
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
							<a href="../include/arquivos_pessoa_capac.php?idPessoa=<?php echo $idPessoaMac ?>&tipo=2" class="btn btn-theme btn-md btn-block" target="_blank">Baixar todos os arquivos</a>
						</div>
					</div>
				</div>
			</div>			
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-8">
				<form method='POST' action='?perfil=contratos&p=frm_edita_pj&id_pj=<?php echo $id_pj ?>&id_ped=<?php echo $id_ped ?>'>
					<input type='submit' class='btn btn-theme btn-lg btn-block' value='voltar para o cadastro de pessoa jurídica'>
				</form>				
			</div>
		</div>
	</section>
<?php	
}
?>