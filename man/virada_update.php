<h1>Migração da base Google Forms para Virada</h1>
<p>As datas de nascimento estão entrando sem padronização, não dá para importar.</p>
<?php 
$hoje = date('Y-m-d');
$antes = strtotime(date('Y-m-d H:i:s')); // note que usei hífen
echo "<p>carregando as funções...</p>";
include "../funcoes/funcoesConecta.php";
include "../funcoes/funcoesGerais.php";
$con = bancoMysqli();

$idVerba = '92';
$justificativa = "Promovida desde 2005 pela Prefeitura de São Paulo, por meio da Secretaria Municipal de Cultura, a Virada Cultural tornou-se ao longo de sua existência um dos maiores eventos culturais oferecidos aos cidadãos paulistanos e aos turistas que para cá convergem por ocasião da realização deste evento. Tradicionalmente o evento oferece, todos os anos, 24 horas de programação contínua integrando os diversos equipamentos da SMC, bem como a ocupação de espaços públicos das diferentes regiões da cidade de São Paulo. O objeto deste processo é a contratação dos artistas para realizar a atração durante este evento que em 2017 ocorrerá ao longo dos dias 20 e 21 de maio A Prefeitura de São Paulo, através de uma política cultural diversificada, proporciona assim, a todos os munícipes e visitantes, o acesso gratuito ao que há de melhor na produção cultural atual existente no País.";
$formaPagto = "O pagamento se dará em até 45 dias úteis, após a data de realização do evento, mediante a entrega dentro do prazo solicitado de toda documentação correta relativa ao pagamento.";


echo "<p>Os valores fixos são: <br />
	<strong>idVerba =</strong> $idVerba <br />
	<strong>justificativa =</strong> $justificativa <br />
	<strong>forma de pagamento =</strong> $formaPagto<br />
</p>";

// funções para esta migração


function recUser($string){
	$con = bancoMysqli();
	$sql = "SELECT idUsuario FROM ig_usuario WHERE nomeUsuario LIKE '$string' LIMIT 0,1";
	$query = mysqli_query($con, $sql);
	$x = mysqli_fetch_array($query);
	return $x['idUsuario'];	
}

function idEstadoCivil($string){
	$con = bancoMysqli();
	$sql = "SELECT Id_EstadoCivil FROM sis_estado_civil WHERE EstadoCivil LIKE '%$string%' LIMIT 0,1";
	$query = mysqli_query($con, $sql);
	$x = mysqli_fetch_array($query);
	return $x['Id_EstadoCivil'];	
		
}

// Fim das funções




// Verifica se existe a tabela googleform
echo "<p>verificando se a tabela `googleforms_evento` existe</p>";
$table = 'googleforms_evento';
$result = mysqli_query($con,"SHOW TABLES LIKE '$table'");
$tableExists = mysqli_num_rows($result);
if($tableExists == 0){
	echo "<p>A tabela googleforms_evento não existe. Por favor, faça o upload. </p>";
}else{
	echo "<p>A tabela googleforms_evento existe</p>";	
	echo "<p>Importando as PK da tabela googleforms_evento (dataHora)</p>";
	$sql_pk = "SELECT * FROM googleforms_evento WHERE dataHora NOT IN(SELECT data FROM igsis_virada) AND dataHora <> '' AND fiscal <> '' AND suplente <> ''"; //seleciona todos pks que não existem na tabela igsis_virada
	$query_pk = mysqli_query($con,$sql_pk);
	$n = mysqli_num_rows($query_pk);
	echo "<p>Foram encontrados $n registros para serem importados</p>";
	
	if($n > 0)
	{ // se existirem registros não importados
		echo "<p>Importando os registros</p>";
		while($x = mysqli_fetch_array($query_pk))
		{
			$dataHora = $x['dataHora'];	
			$sql_insere_pk = "INSERT INTO `igsis_virada` (`id`, `data`, `idEvento`) VALUES (NULL, '$dataHora', '')";
			$query_insere_pk = mysqli_query($con,$sql_insere_pk);
			if($query_insere_pk)
			{
				echo "Chave $dataHora inserida - ";
				// criar evento
				$sql_insere_evento = "INSERT INTO `ig_evento` (idEvento) VALUES (NULL)";
				$query_insere_evento = mysqli_query($con,$sql_insere_evento);
				if($query_insere_evento)
				{
					$id = mysqli_insert_id($con);
					echo "Evento $id criado - ";
					// atualiza igsis_virada
					$sql_update_pk = "UPDATE igsis_virada SET idEvento = '$id' WHERE data = '$dataHora'";
					$query_update_pk = mysqli_query($con,$sql_update_pk);
					if($query_update_pk)
					{
						echo " relacionamento criado. <br />";
						// criar pedido de contratação
						$sql_insert_pedido = "INSERT INTO `igsis_pedido_contratacao` (`idPedidoContratacao`, `idEvento`) VALUES (NULL, '$id')";
						$query_insert_pedido = mysqli_query($con,$sql_insert_pedido);
						if($query_insert_pedido)
						{
							$idPedido = mysqli_insert_id($con);
							echo "pedido $idPedido com evento $id criado<br />";
							// Blocão da importação
							// carrega as variáveis
							$responsavelContratos = $x['responsavelContratos'];
							$fiscal = recUser($x['fiscal']);
							$suplente = recUser($x['suplente']);
							$enviadoSei = $x['enviadoSei'];
							$valor = $x['valor'];
							/*
							$data = 
							$x['horario'];
							$x['local = 
							$dataHora'];
							*/
							$nomeEspetaculo = $x['nomeEspetaculo'];
							$nomeGrupo = $x['nomeGrupo'];
							$classificacao = faixaEt($x['classificacao']);
							$duracao = $x['duracao'];
							$sinopse = $x['sinopse'];
							$releaseApresentacao = $x['releaseApresentacao'];
							$releaseCompleto = $x['releaseCompleto'];
							$links = $x['links'];
							$fichaTecnica = $x['fichaTecnica'];
							$nomeProdutor = $x['nomeProdutor'];
							$emailProdutor = $x['emailProdutor'];
							$emailOpicionalProdutor = $x['emailOpicionalProdutor'];
							$telefoneFixoProdutor = $x['telefoneFixoProdutor'];
							$celular1Produtor = $x['celular1Produtor'];
							$celular2Produtor = $x['celular2Produtor'];
							$nomeExecutante = $x['nomeExecutante'];
							$dataNascimento = $x['dataNascimento'];
							$estadoCivil = $x['estadoCivil'];
							$rg = $x['rg'];
							$estadoCivil = $x['estadoCivil'];
							$cpf = $x['cpf'];
							$cep = $x['cep'];
							$numero = $x['numero'];
							$complemento = $x['complemento'];
							$cnpj = $x['cnpj'];
							$ccm = $x['ccm'];
							$razaoSocial = $x['razaoSocial'];
							$enderecoPJ = $x['enderecoPJ'];
							$cepPJ = $x['cepPJ'];
							$numeroPJ = $x['numeroPJ'];
							$complementoPJ = $x['complementoPJ'];
							$representante1 = $x['representante1'];
							$dataNascimento1 = $x['dataNascimento1'];
							$estadoCivil1 = $x['estadoCivil1'];
							$rg1 = $x['rg1'];
							$cpf1 = $x['cpf1'];
							$representante2 = $x['representante2'];
							$dataNascimento2 = $x['dataNascimento2'];
							$estadoCivil2 = $x['estadocivil2'];
							$rg2 = $x['rg2'];
							$cpf2 = $x['cpf2'];
							$fichaEquipe = $x['fichaEquipe'];
							$banco = $x['banco'];
							$codBanco = '1';
							$agencia = $x['agencia'];
							$conta = $x['conta'];
							// insere produtor
							$email_produtor = $emailProdutor." / ".$emailOpicionalProdutor;
							$telefone_produtor = $celular1Produtor." / ".$celular2Produtor;
							$sql_insere_produtor = "INSERT INTO `ig_produtor` 
							(`idProdutor`, `nome`, `email`, `telefone`, `telefone2`, `idSpCultura`) 
							VALUES (NULL, '$nomeProdutor', '$email_produtor', '$telefoneFixoProdutor' , '$telefone_produtor','')";
							$query_insere_produtor = mysqli_query($con,$sql_insere_produtor);
							if($query_insere_produtor)
							{
								$idProdutor = mysqli_insert_id($con);	
							}
							else
							{
								$idProdutor = "";
							}
							$releaseCom = "Release Apresentacao: ".$releaseApresentacao.'\n'.'\n'."Release Completo: ".$releaseCompleto;
							// atualiza o evento
							$sql_update_evento = "UPDATE ig_evento SET
								`ig_produtor_idProdutor` = '$idProdutor',
								`projetoEspecial` = '$idVirada', 
								`nomeEvento` = '$nomeEspetaculo', 
								`idResponsavel` = '$fiscal', 
								`suplente` = '$suplente', 
								`autor` = '$nomeGrupo', 
								`nomeGrupo` = '$nomeGrupo', 
								`fichaTecnica` = '$fichaTecnica', 
								`faixaEtaria` = '$classificacao', 
								`sinopse` = '$sinopse', 
								`releaseCom` = '$releaseCom', 
								`publicado` = '1', 
								`idUsuario` = '$idUsuarioAdmin', 
								`ig_modalidade_IdModalidade` = '5', 
								`linksCom` = '$links', 
								`idInstituicao` = '4' 
								WHERE idEvento = '$id'";
							$query_update_evento = mysqli_query($con,$sql_update_evento);
							if($query_update_evento)
							{
							    gravarLog($sql_update_evento);
								echo "<p>Evento $nomeEspetaculo inserido corretamente</p>";
								// insere ocorrencia
								$sql_insere_ocorrencia = "INSERT INTO ig_ocorrencia (idEvento, idTipoOcorrencia, sabado, domingo, dataInicio, dataFinal, timezone, duracao, publicado, virada) VALUES ('$id', '4', '1', '1', '2017-05-20', '2017-05-21', '-3', '$duracao', '1', '1')";
								$query_insere_ocorrencia = mysqli_query($con,$sql_insere_ocorrencia);
								if($query_insere_ocorrencia)
								{
									echo "<p>Ocorrência inserida corretamente</p>";
									// atualiza o pedido de contratação								
									// verifica se o cnpj está em branco
									if($cnpj == "" OR $cnpj == NULL)
									{
										// insere pessoa fisica
									}
									else
									{
										//insere pessoa juridica
										$obs_pedido = "";
										// verifica se o cnpj existe na base
										$sql_ver_cnpj = "SELECT Id_PessoaJuridica FROM sis_pessoa_juridica WHERE CNPJ LIKE '%$cnpj%' ORDER BY Id_PessoaJuridica DESC LIMIT 0,1";
										$query_ver_cnpj = mysqli_query($con,$sql_ver_cnpj);
										$n_cnpj = mysqli_num_rows($query_ver_cnpj);
										if($n_cnpj > 0)
										{ // o cnpj existe
											echo "<p> O CNPJ contratante já existe no sistema</p>";
											$y = mysqli_fetch_array($query_ver_cnpj);
											$idPessoa = $y['Id_PessoaJuridica'];
											$obs_pedido .= "Dados importados automaticamente para Virada Cultura 2017. Por favor, conferir. ".'\n'.'\n'."Razão Social: $razaoSocial / CCM: $ccm / CEP: $cepPJ / Número: $numeroPJ / Complemento: $complementoPJ / Código do Banco: $codBanco / Agência: $agencia / CC: $conta";
										}
										else
										{ // o cnpj não existe
											$sql_insere_pj = "INSERT INTO `sis_pessoa_juridica` 
											(`RazaoSocial`, `CNPJ`, `CCM`, `CEP`, `Numero`, `Complemento`, `DataAtualizacao`, `codBanco`, `agencia`, `conta`) 
											VALUES ('$razaoSocial','$cnpj', '$ccm', '$cepPJ', '$numeroPJ', '$complementoPJ', '$hoje', '$codBanco', '$agencia', '$conta')";
											$query_insere_pj = mysqli_query($con,$sql_insere_pj);
											if($query_insere_pj)
											{
												$idPessoa = mysqli_insert_id($con);
												$obs_pedido .= "";	
											}
											else
											{
												echo "<p>Erro ao inserir Pessoa Jurídica</p>";	
											}										
										}
										// verfica se o representante existe na base
										$sql_ver_rep01 = "SELECT Id_RepresentanteLegal FROM sis_representante_legal WHERE CPF LIKE '%$cpf1%' ORDER BY Id_RepresentanteLegal DESC LIMIT 0,1";
										$query_ver_rep01 = mysqli_query($con,$sql_ver_rep01);
										$n_rep01 = mysqli_num_rows($query_ver_rep01);
										if($n_rep01 > 0)
										{ // o cpf existe
											echo "<p> O representante legal já existe no sistema</p>";
											$y = mysqli_fetch_array($query_ver_rep01);
											$idRep01= $y['Id_RepresentanteLegal'];
											$obs_pedido .= '\n'.'\n'."Nome RL01: $representante1 / data de nascimento: $dataNascimento1 / Estado Civil: $estadoCivil1 / RG: $rg1 / CPF: = $cpf1";
										}
										else
										{ // o cpf não existe
											$estCivil1 = idEstadoCivil($estadoCivil1);
											$sql_insere_rep01 = "INSERT INTO `sis_representante_legal` (`RepresentanteLegal`, `RG`, `CPF`, `Nacionalidade`, `IdEstadoCivil`)
											 VALUES ('$representante1', '$rg1', '$cpf1', 'Brasileiro(a)', '$estCivil1');";
											$query_insere_rep01 = mysqli_query($con,$sql_insere_rep01);
											if($query_insere_rep01)
											{
												$idRep01 = mysqli_insert_id($con);
												$obs_pedido .= "";	
											}
											else
											{
												echo "<p>Erro ao inserir representante legal</p>";
											}										
										}
										$sql_ver_rep02 = "SELECT Id_RepresentanteLegal FROM sis_representante_legal WHERE CPF LIKE '%$cpf2%' ORDER BY Id_RepresentanteLegal DESC LIMIT 0,1";
										$query_ver_rep02 = mysqli_query($con,$sql_ver_rep02);
										$n_rep02 = mysqli_num_rows($query_ver_rep02);
										if($n_rep02 > 0)
										{ // o cpf existe
											echo "<p> O representante legal já existe no sistema</p>";
											$y = mysqli_fetch_array($query_ver_rep02);
											$idRep02= $y['Id_RepresentanteLegal'];
											$obs_pedido .= '\n'.'\n'."Nome RL02: $representante2 / data de nascimento: $dataNascimento2 / Estado Civil: $estadoCivil2 / RG: $rg2 / CPF: = $cpf2";
										}
										else
										{ // o cpf não existe
											$estCivil2 = idEstadoCivil($estadoCivil2);
											$sql_insere_rep02 = "INSERT INTO `sis_representante_legal` (`RepresentanteLegal`, `RG`, `CPF`, `Nacionalidade`, `IdEstadoCivil`)
											 VALUES ('$representante2', '$rg2', '$cpf2', 'Brasileiro(a)', '$estCivil2');";
											$query_insere_rep02 = mysqli_query($con,$sql_insere_rep02);
											if($query_insere_rep02)
											{	
												$idRep02 = mysqli_insert_id($con);
												$obs_pedido .= "";	
											}
											else
											{
												echo "<p>Erro ao inserir representante legal</p>";	
											}										
										}
										// verifica se o executante existe na base
										$sql_ver_exec = "SELECT Id_PessoaFisica FROM sis_pessoa_fisica WHERE CPF LIKE '%$cpf%' ORDER BY Id_PessoaFisica DESC LIMIT 0,1";
										$query_ver_exec = mysqli_query($con,$sql_ver_exec);
										$n_exec = mysqli_num_rows($query_ver_exec);
										if($n_exec > 0)
										{ // o exec existe
											echo "<p> O executante contratante já existe no sistema</p>";
											$estCivilExec = idEstadoCivil($estadoCivil);
											echo "estado civil: ".$estadoCivil."<br />";
											$y = mysqli_fetch_array($query_ver_exec);
											$idExec = $y['Id_PessoaFisica'];
											$obs_pedido .= '\n'.'\n'."Executante: $nomeExecutante / Data de nascimento: $dataNascimento / Estado civil: $estCivilExec / RG: $rg / CPF: $cpf".'\n'.'\n'."CEP: $cep / Número: $numero / Complemento: $complemento";
										}
										else
										{ // o exec não existe
											$estCivilExec = idEstadoCivil($estadoCivil);
											//$dataNasc = exibirDataMysql($dataNascimento);
											$sql_insere_exec = "INSERT INTO `sis_pessoa_fisica` 
											(`Nome`, `RG`, `CPF`, `CCM`, `IdEstadoCivil`,  `CEP`, `Numero`, `Complemento`, `DataAtualizacao`, `codBanco`, `agencia`, `conta`) 
											VALUES ('$nomeExecutante', '$rg', '$cpf', '$ccm','$estCivilExec',  '$cep', '$numero', '$complemento', '$hoje', '1', '$agencia', '$conta' )";
											//echo $sql_insere_exec;
											$query_insere_exec = mysqli_query($con,$sql_insere_exec);
											if($query_insere_exec)
											{
												$idExec = mysqli_insert_id($con);
												$obs_pedido .= "";	
											}
											else
											{
												echo "<p>Erro ao inserir executante </p>";
											}										
										}
										if(isset($idPessoa))
										{ // se foi encontrado ou inserido o PJ, atualiza o pedido de Contratação
											$sql_update_pedido = "UPDATE igsis_pedido_contratacao SET
												`tipoPessoa` = '2',
												`idRepresentante01` = '$idRep01',
												`idRepresentante02` = '$idRep02',
												`idPessoa` = '$idPessoa',
												`valor` = '$valor',
												`idVerba` = '$idVerba',
												`observacao` = '$obs_pedido' ,
												`publicado` = '1',
												`instituicao` = '4',
												`IdExecutante` = '$idExec',
												`justificativa` = '$justificativa',
												`formaPagamento` = '$formaPagto',
												`idContratos` = ''
												WHERE `idPedidoContratacao` = '$idPedido'";
											//echo $sql_update_pedido;
											$query_update_pedido = mysqli_query($con,$sql_update_pedido);
											echo "<p>Pedido atualizado.</p>";
										}
										else
										{
											echo "<p>Erro ao atualizar pedido.</p>";
										}	
									}
								}
								else
								{
									echo "erro ao inserir ocorrencia (error07)<br />";							
								}
							}
							else
							{
								echo "<p>Erro ao inserir o evento $nomeEspetaculo (error02). $sql_update_evento</p>";
							}
						// Fim do blocão da importação	
						}
						else
						{
							echo "erro ao inserir pedido (error03)<br />";
						}
					}
					else
					{
						echo " erro ao criar relacionamento. (error04)<br />";
					}
				}
				else
				{
					echo " erro ao criar evento (error05)<br />";
				}		
			}
			else
			{
				echo "Erro ao gerar nova chave. (error06)<br />";	
			}
		}
	}	
}// if da tabela googleform
$depois = strtotime(date('Y-m-d H:i:s'));
$tempo = $depois - $antes;
echo "<br /><br /> Importação executada em $tempo segundos";
			}
			else
			{
				$mensagem .= "erro ao inserir. <br />";
			}
		}
		else
		{
			// Não foi possível fazer o upload, provavelmente a pasta está incorreta
			$mensagem =  "Não foi possível enviar o arquivo, tente novamente";
		}	
	}
?>