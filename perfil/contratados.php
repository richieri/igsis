<?php
	/*
	Para fazer
	+ funcao que retornam os locais
	+ funcao que retornam os periodos
	*/
	$con = bancoMysqli();
	$conn = bancoPDO();
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];
	}
	else
	{
		$p = 'lista';	
	}
	$nomeEvento = recuperaEvento($_SESSION['idEvento']);
	include "../include/menuContratatados.php";
	switch($p)
	{
		case 'lista': 
			unset($_SESSION['edicaoPessoa']);
			if($_SESSION['idPedido'])
			{
				// fecha a session idPedido
				unset($_SESSION['idPedido']);
			}
			if(isset($_SESSION['idPessoaJuridica']))
			{
				unset($_SESSION['idPessoaJuridica']);
			}
			if(isset($_POST['inserirRepresentante']))
			{
				//insere represenante existente
			}
			if(isset($_POST['cadastrarFisica']))
			{
				//cadastra e insere pessoa física
				$cpf = $_POST['CPF'];
				$verificaCPF = verificaExiste("sis_pessoa_fisica","CPF",$cpf,"");
				if($verificaCPF['numero'] > 0)  
					{
					//verifica se o cpf já existe
 					$mensagem = "O CPF já consta no sistema. Faça uma busca e insira diretamente";
					}
				else
 				{
					$Nome = addslashes($_POST['Nome']);
					$NomeArtistico = addslashes($_POST['NomeArtistico']);
					$RG = $_POST['RG'];
					$CPF = $_POST['CPF'];
					$CCM = $_POST['CCM'];
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
					$Funcao = $_POST['Funcao'];
					$InscricaoINSS = $_POST['InscricaoINSS'];
					$OMB = $_POST['OMB'];
					$Observacao = $_POST['Observacao'];
					$Pis = 0;
					$data = date('Y-m-d');
					$idUsuario = $_SESSION['idUsuario'];
					if($DataNascimento == '31/12/1969')
					{
						$mensagem = "Por favor, preencha o campo DATA DE NASCIMENTO!";
					}
					else
					{
						$sql_insert_pf = "INSERT INTO `sis_pessoa_fisica` 
							(`Id_PessoaFisica`, 
							`Foto`, 
							`Nome`, 
							`NomeArtistico`, 
							`RG`, 
							`CPF`, 
							`CCM`, 
							`DataNascimento`, 
							`LocalNascimento`, 
							`Nacionalidade`, 
							`CEP`, 
							`Numero`, 
							`Complemento`, 
							`Telefone1`, 
							`Telefone2`, 
							`Telefone3`, 
							`Email`, 
							`DRT`, 
							`Funcao`, 
							`InscricaoINSS`, 
							`Pis`, 
							`OMB`, 
							`DataAtualizacao`, 
							`Observacao`, 
							`IdUsuario`) 
							VALUES (NULL, 
							NULL, 
							'$Nome', 
							'$NomeArtistico', 
							'$RG', 
							'$CPF', 
							'$CCM', 
							'$DataNascimento', 
							NULL, 
							'$Nacionalidade', 
							'$CEP', 
							'$Numero', 
							'$Complemento', 
							'$Telefone1', 
							'$Telefone2', 
							'$Telefone3', 
							'$Email', 
							'$DRT', 
							'$Funcao', 
							'$InscricaoINSS', 
							'$Pis', 
							'$OMB', 
							'$data', 
							'$Observacao', 
							'$idUsuario');";
						$query_insert_pf = mysqli_query($con,$sql_insert_pf);
						if($query_insert_pf)
						{
							gravarLog($sql_insert_pf);
							$sql_ultimo = "SELECT * FROM sis_pessoa_fisica ORDER BY Id_PessoaFisica DESC LIMIT 0,1"; //recupera ultimo id
							$id_evento = mysqli_query($con,$sql_ultimo);
							$id = mysqli_fetch_array($id_evento);
							$idFisica = $id['Id_PessoaFisica'];
							$idEvento = $_SESSION['idEvento'];
							$sql_anterior = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataFinal ASC LIMIT 0,1"; //a data final 
							$query_anterior = mysqli_query($con,$sql_anterior);
							$data = mysqli_fetch_array($query_anterior);
							$data_final = $data['dataFinal'];
							if ($data_final != '0000-00-00')
							{
							$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_final)));
							}else{
							$sql_unica = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataInicio ASC LIMIT 0,1"; //a data inicio 
							$query_unica = mysqli_query($con,$sql_unica);
							$data = mysqli_fetch_array($query_unica);
							$data_inicio = $data['dataInicio'];	
							$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_inicio)));
							}
							$sql_insert_pedido = "INSERT INTO `igsis_pedido_contratacao` 
								(`idEvento`, `tipoPessoa`, `idPessoa`, `publicado`, `dataKitPagamento`) VALUES 
								('$idEvento', '1', '$idFisica', '1', '$dataKitPagamento')";
							$query_insert_pedido = mysqli_query($con,$sql_insert_pedido);
							if($query_insert_pedido)
							{
								gravarLog($sql_insert_pedido);
								echo "<h2>Inserido com sucesso!</h2>"; 
							}
							else
							{
								echo "<h2>Erro ao inserir![1]</h2>";
							}
						}
						else
						{
							echo "<h5>Erro ao inserir![2]</h2>";
						}
					}
				}
			}
			if(isset($_POST['insereFisica']))
			{
				//insere pessoa física
				$idInstituicao = $_SESSION['idInstituicao'];
				$idPessoa = $_POST['Id_PessoaFisica'];
				$idEvento = $_SESSION['idEvento'];
				$sql_verifica_cpf = "SELECT * 
					FROM igsis_pedido_contratacao 
					WHERE idPessoa = '$idPessoa' 
					AND tipoPessoa = '1' 
					AND publicado = '1' 
					AND idEvento = '$idEvento' ";
				$query_verifica_cpf = mysqli_query($con,$sql_verifica_cpf);
				$num_rows = mysqli_num_rows($query_verifica_cpf);
				if($num_rows > 0)
				{
					$mensagem = "A pessoa física já está na lista de pedido de contratação.";
				}
				else
				{
					$idEvento = $_SESSION['idEvento'];
					$con2 = bancoMysqliProponente();
					//retorna uma array com os dados de qualquer tabela do CAPAC. Serve apenas para 1 registro.
					function recuperaDadosProp($tabela,$campo,$variavelCampo)
					{
						$con2 = bancoMysqliProponente();
						$sql = "SELECT * FROM $tabela WHERE ".$campo." = '$variavelCampo' LIMIT 0,1";
						$query = mysqli_query($con2,$sql);
						$campo = mysqli_fetch_array($query);
						return $campo;
					}
					$sql_evento = "SELECT * FROM igsis_capac WHERE idEventoIgsis = '$idEvento'";
					$query_evento = mysqli_query($con,$sql_evento);
					$array_evento = mysqli_fetch_array($query_evento);

					$idEventoCapac = $array_evento['idEventoCapac'];
					$eventoCapac = recuperaDadosProp("evento","id",$idEventoCapac);
					$integrantes = $eventoCapac['integrantes'];
					
					$sql_anterior = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataFinal ASC LIMIT 0,1"; //a data final 
					$query_anterior = mysqli_query($con,$sql_anterior);
					$data = mysqli_fetch_array($query_anterior);
					$data_final = $data['dataFinal'];
						if ($data_final != '0000-00-00')
						{
						$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_final)));
						}else{
						$sql_unica = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataInicio ASC LIMIT 0,1"; //a data inicio 
						$query_unica = mysqli_query($con,$sql_unica);
						$data = mysqli_fetch_array($query_unica);
						$data_inicio = $data['dataInicio'];	
						$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_inicio)));
						}
						$sql_insere_pf = "INSERT INTO igsis_pedido_contratacao 
						(idPessoa,
						tipoPessoa,
						integrantes,
						publicado,
						idEvento,
						instituicao,
						dataKitPagamento)
						VALUES ('$idPessoa',
						'1',
						'$integrantes',
						'1',
						'$idEvento',
						'$idInstituicao',
						'$dataKitPagamento')";
					$query_insere_pf = mysqli_query($con,$sql_insere_pf);
					if($query_insere_pf)
					{
						gravarLog($query_insere_pf);
						$mensagem = "Pedido inserido com sucesso!";
					}
					else
					{
						$mensagem = "Erro ao criar pedido. Tente novamente.";
					}
				}
			}
			if(isset($_POST['cadastrarJuridica']))
			{
				//cadastra e insere pessoa jurídica
				$verificaCNPJ = verificaExiste("sis_pessoa_juridica","CNPJ",$_POST['CNPJ'],"");
				if($verificaCNPJ['numero'] > 0)
				{
					//verifica se o cpf já existe
					$mensagem = "O CNPJ já consta no sistema. Faça uma busca e insira diretamente";
				}
				else
				{
					// o CNPJ não existe, inserir.
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
					$Observacao = $_POST['Observacao'];
					$data = date("Y-m-d");
					$idUsuario = $_SESSION['idUsuario'];
					$sql_inserir_pj = "INSERT INTO `sis_pessoa_juridica` (`Id_PessoaJuridica` , `RazaoSocial` ,`CNPJ` ,`CCM` ,`CEP` ,`Numero` ,`Complemento` ,`Telefone1` ,`Telefone2` ,`Telefone3` ,`Email` , `DataAtualizacao` ,`Observacao` ,`IdUsuario`) VALUES ( NULL ,  '$RazaoSocial',  '$CNPJ', '$CCM' , '$CEP' , '$Numero' , '$Complemento' ,  '$Telefone1', '$Telefone2' , '$Telefone3' , '$Email' , '$data', '$Observacao' ,  '$idUsuario')";
					$query_inserir_pj = mysqli_query($con,$sql_inserir_pj);
					if($query_inserir_pj)
					{
						gravarLog($sql_inserir_pj);
						$sql_ultimo = "SELECT * FROM sis_pessoa_juridica ORDER BY Id_PessoaJuridica DESC LIMIT 0,1"; //recupera ultimo id
						$id_evento = mysqli_query($con,$sql_ultimo);
						$id = mysqli_fetch_array($id_evento);
						$idJuridica = $id['Id_PessoaJuridica'];
						$idEvento = $_SESSION['idEvento'];
						$sql_anterior = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY datFinal ASC LIMIT 0,1"; //a data final mais antecedente
						$query_anterior = mysqli_query($con,$sql_anterior);
						$data = mysqli_fetch_array($query_anterior);
						$data_final = $data['dataFinal'];
						if ($data_final != '0000-00-00')
						{
						$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_final)));
						}else{
						$sql_unica = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataInicio ASC LIMIT 0,1"; //a data inicio 
						$query_unica = mysqli_query($con,$sql_unica);
						$data = mysqli_fetch_array($query_unica);
						$data_inicio = $data['dataInicio'];	
						$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_inicio)));
						}
						$sql_insert_pedido = "INSERT INTO `igsis_pedido_contratacao` 
							(`idEvento`,
							`tipoPessoa`,
							`idPessoa`,
							`dataKitPagamento`,
							`publicado`)
							VALUES (
							'$idEvento',
							'2',
							'$idJuridica',
							'$dataKitPagamento'
							'1')";
						$query_insert_pedido = mysqli_query($con,$sql_insert_pedido);
						if($query_insert_pedido)
						{
							gravarLog($sql_insert_pedido);
							echo "<h1>Inserido com sucesso!</h1>";
						}
						else
						{
							echo "<h1>Erro ao inserir o pedido(1)!</h1>";
						}
					}
					else
					{
						echo "<h1>Erro ao inserir(2)!</h1>";
					}
				}
			}
			if(isset($_POST['insereJuridica']))
			{
				//insere pessoa jurídica
				$idInstituicao = $_SESSION['idInstituicao'];
				$idPessoa = $_POST['Id_PessoaJuridica'];
				$idEvento = $_SESSION['idEvento'];
				$sql_verifica_cnpj = "SELECT * 
					FROM igsis_pedido_contratacao 
					WHERE idPessoa = '$idPessoa' 
					AND tipoPessoa = '2' 
					AND publicado = '1' 
					AND idEvento = '$idEvento' ";
				$query_verifica_cnpj = mysqli_query($con,$sql_verifica_cnpj);
				$sql_anterior = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataFinal ASC LIMIT 0,1"; //a data inicial mais antecedente
				$query_anterior = mysqli_query($con,$sql_anterior);
				$data = mysqli_fetch_array($query_anterior);
				$data_final = $data['dataFinal'];
				if ($data_final != '0000-00-00')
				{
				$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_final)));
				}else{
				$sql_unica = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataInicio ASC LIMIT 0,1"; //a data inicio 
				$query_unica = mysqli_query($con,$sql_unica);
				$data = mysqli_fetch_array($query_unica);
				$data_inicio = $data['dataInicio'];	
				$dataKitPagamento = date('Y/m/d', strtotime("+1 day",strtotime($data_inicio)));
				}				
				$sql_insere_cnpj = "INSERT INTO igsis_pedido_contratacao
					(idPessoa,
					tipoPessoa,
					publicado,
					idEvento,
					instituicao,
					dataKitPagamento)
					VALUES ('$idPessoa',
					'2',
					'1',
					'$idEvento',
					'$idInstituicao',
					'$dataKitPagamento')";
				$query_insere_cnpj = mysqli_query($con,$sql_insere_cnpj);
				if($query_insere_cnpj)
				{
			 		gravarLog($sql_insere_cnpj);
					$mensagem = "Pedido inserido com sucesso!";
				}
				else
				{
					$mensagem = "Erro ao criar pedido. Tente novamente.";
				}
			}
			if(isset($_POST['novoJuridica']))
			{
				//cadastra e insere pessoa jurídica
				$verificaCNPJ = verificaExiste("sis_pessoa_juridica","CNPJ",$_POST['CNPJ'],"");
				if($verificaCNPJ['numero'] > 0)
				{
					//verifica se o cpf já existe
					$mensagem = "O CNPJ já consta no sistema. Faça uma busca e insira diretamente";
				}
				else
				{
					// o CNPJ não existe, inserir.
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
					$Observacao = $_POST['Observacao'];
					$data = date("Y-m-d");
					$idUsuario = $_SESSION['idUsuario'];
					$sql_inserir_pj = "INSERT INTO `sis_pessoa_juridica` (`Id_PessoaJuridica` , `RazaoSocial` ,`CNPJ` ,`CCM` ,`CEP` ,`Numero` ,`Complemento` ,`Telefone1` ,`Telefone2` ,`Telefone3` ,`Email` , `DataAtualizacao` ,`Observacao` ,`IdUsuario`) VALUES ( NULL ,  '$RazaoSocial',  '$CNPJ', '$CCM' , '$CEP' , '$Numero' , '$Complemento' ,  '$Telefone1', '$Telefone2' , '$Telefone3' , '$Email' , '$data', '$Observacao' ,  '$idUsuario')";
					$query_inserir_pj = mysqli_query($con,$sql_inserir_pj);
					if($query_inserir_pj)
					{
						gravarLog($sql_inserir_pj);
						$sql_ultimo = "SELECT * FROM sis_pessoa_juridica ORDER BY Id_PessoaJuridica DESC LIMIT 0,1"; //recupera ultimo id
						$query_ultimo = mysqli_query($con,$sql_ultimo);
						if($query_ultimo)
						{
							$id = mysqli_fetch_array($query_ultimo);
							$idJuridica = $id['Id_PessoaJuridica'];
							//insere pessoa jurídica
							//$idPessoaJuridica = $descricao['Id_PessoaJuridica'];
							$id_ped = $_GET['id_ped'];
							$sql_atualiza_cnpj = "UPDATE `igsis_pedido_contratacao` 
								SET `idPessoa` = '$idJuridica'
								WHERE `idPedidoContratacao` = '$id_ped'";
							$query_atualiza_cnpj = mysqli_query($con,$sql_atualiza_cnpj);
							if($query_atualiza_cnpj)
							{
								echo "ok";
								echo $idJuridica;
								echo $id_ped;
							}
							else
							{
								echo "erro";
							}
						}
						else
						{
							echo "erro";
						}
					}
					else
					{
						echo "<h1>Erro ao inserir(2)!</h1>";
					}
				}
			}
			if(isset($_POST['apagarPedido']))
			{
				$idPedidoContratacao = $_POST['idPedidoContratacao'];
				$sql_apagar_pedido = "UPDATE igsis_pedido_contratacao SET publicado = '0' WHERE idPedidoContratacao = '$idPedidoContratacao'";
				$query_apagar_pedido = mysqli_query($con,$sql_apagar_pedido);
				if($query_apagar_pedido)
				{
					gravarLog($sql_apagar_pedido);
					$mensagem = "Pedido apagado com sucesso.";
				}
				else
				{
					$mensagem = "Erro ao apagar o pedido. Tente novamente.";
				}
			}
?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Contratados</h2>
                    <p>Você está inserindo pessoas físicas ou jurídicas para serem contratadas para o evento <strong><?php  echo $nomeEvento['nomeEvento']; ?></strong></p>
                    <p><?php if(isset($mensagem)){ echo $mensagem; } ?></p>
					<p></p>
				</div>
			</div>
		</div>
		<div class="table-responsive list_info">
		<?php  
			$idEvento = $_SESSION['idEvento'];
			$sql_busca = "SELECT * 
				FROM igsis_pedido_contratacao 
				WHERE idEvento = '$idEvento' 
				AND publicado = '1'";
			$query_busca = mysqli_query($con,$sql_busca);
			$num_reg = mysqli_num_rows($query_busca);		   
			if($num_reg > 0)
			{
		?> 
			<table class="table table-condensed">
				<thead>
					<tr class='list_menu'>
						<td>Razão Social / Nome</td>
						<td>Tipo de Pessoa</td>
						<td>CPF/CNPJ</td>
   						<td>Valor</td>
						<td width="10%"></td>
  						<td width="10%"></td>
						<td width="10%"></td>
					</tr>
				</thead>
        <?php
				while($descricao = mysqli_fetch_array($query_busca))
				{
					$recuperaPessoa = recuperaPessoa($descricao['idPessoa'],$descricao['tipoPessoa']);
					echo "<tr>";
					echo "<td class='list_description'><b>".$recuperaPessoa['nome']."</b></td>";
					echo "<td class='list_description'>".$recuperaPessoa['tipo']."</td>";
					echo "<td class='list_description'>".$recuperaPessoa['numero']."</td>";
					echo "<td class='list_description'>".dinheiroParaBr($descricao['valor'])."</td>";
					echo "
						<td class='list_description'>
						<form method='POST' action='?perfil=contratados&p=edicaoPessoa'>
						<input type='hidden' name='idPedidoContratacao' value='".$descricao['idPedidoContratacao']."'>
						<input type ='submit' class='btn btn-theme btn-sm btn-block' value='editar pessoa'></td></form>"	; //botão de edição
					echo "
						<td class='list_description'>
						<form method='POST' action='?perfil=contratados&p=edicaoPedido'>
						<input type='hidden' name='idPedidoContratacao' value='".$descricao['idPedidoContratacao']."'>
						<input type ='submit' class='btn btn-theme btn-sm btn-block' value='editar pedido'";
					if($descricao['tipoPessoa'] == 3){ echo "disabled"; } //não permite que Representante legal faça pedido.
					echo " ></td></form>"	; //botão de edição
					echo "
						<td class='list_description'>
						<form method='POST' action='?perfil=contratados&p=lista'>
						<input type='hidden' name=apagarPedido value='1'>
						<input type='hidden' name='idPedidoContratacao' value='".$descricao['idPedidoContratacao']."'>
						<input type ='submit' class='btn btn-theme btn-sm btn-block'";
					apagarRepresentante($descricao['idPessoa'],$descricao['tipoPessoa'],$_SESSION['idEvento']);
					echo " value='apagar pedido'></td></form>"	; //botão de apagar
					echo "</tr>";
				}
		?>
				</tbody>
			</table>
		<?php
			}
			else
			{
		?>
			<h5> Não há nenhum pedido de contratação cadastrado. </h5>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<a href="?perfil=contratados&p=fisica" class="btn btn-theme btn-lg btn-block">Inserir um pedido Pessoa Física</a>
					<a href="?perfil=contratados&p=juridica" class="btn btn-theme btn-lg btn-block">Inserir um pedido Pessoa Jurídica</a>
				</div>
			</div>
        <?php
			}
		?>
		</div>
    </div>
</section>
	<?php
		break; 
		case 'juridica':
		unset($_SESSION['edicaoPessoa']);
?>   
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Contratados - Pessoa Jurídica</h2>
                    <p>Você está inserindo pessoas jurídicas para serem contratadas para o evento <strong><?php  echo $nomeEvento['nomeEvento']; ?></strong></p>
					<p></p>
				</div>
			</div>
		</div>	  
	    <div class="row">
            <div class="form-group">
            	<div class="col-md-offset-2 col-md-8">  
                    <form method="POST" action="?perfil=compara_pj" class="form-horizontal" role="form">
						<label>Insira o CNPJ</label>
						<input type="text" name="busca" class="form-control" id="CNPJ"><br />
						<br />             
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type='hidden' name='edicaoPessoa' value='0'>
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
			
		break;
		case 'fisica':
		unset($_SESSION['edicaoPessoa']);
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
						<form method="POST" action="?perfil=compara_pf" class="form-horizontal" role="form">
							<label>Insira o CPF</label>
							<input type="text" name="busca" class="form-control" id="cpf" >
							<br />             
							<div class="form-group">
								<div class="col-md-offset-2 col-md-8">
									<input type='hidden' name='edicaoPessoa' value='0'>
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
			
		break;
		case 'representante':
			if(isset($_POST['numero']))
			{
				$_SESSION['numero'] = $_POST['numero'];	
			}
			include "../funcoes/funcoesSiscontrat.php";
			if(isset($_POST['idPessoaJuridica']))
			{
				$pessoa = recuperaPessoa($_POST['idPessoaJuridica'],2);	
			}
			else
			{
				$pessoa = recuperaPessoa($_SESSION['idPessoaJuridica'],2);	
			}
			if(isset($_GET['action']))
			{
				$action = $_GET['action'];
			}
			else
			{
				$action = "edita";
			}
			switch($action)
			{
				case "edita":
					if($_POST['idPessoa'] == 0)
					{
						//mostra busca ?>		
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>CADASTRO DE REPRESENTANTE LEGAL</h2>
					<p>A pessoa jurídica para quem você está cadastrando representantes legais é <strong><?php echo $pessoa['nome'];  ?></strong></p>  
					<p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
				</div>
			</div>
		</div>	  
	    <div class="row">
            <div class="form-group">
            	<div class="col-md-offset-2 col-md-8">
					<form method="POST" action="?perfil=contratados&p=representante&action=busca" class="form-horizontal" role="form">
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
					else
					{
						//mostra formulário de edição
						//Carrega edição
						//carrega os posts
						if(isset($_POST['atualizar']))
						{	
							$idRepresentante = $_POST['atualizar'];
							$representante = addslashes($_POST['RepresentanteLegal']);
							$rg = $_POST['RG'];
							$nacionalidade = $_POST['Nacionalidade'];
							$sql_atualiza_dados = "UPDATE `igsis`.
								`sis_representante_legal` 
								SET `RepresentanteLegal` = '$representante',
								`RG` = '$rg', 
								`Nacionalidade` = '$nacionalidade' 
								WHERE `sis_representante_legal`.
								`Id_RepresentanteLegal` = '$idRepresentante'";
							$query_atualiza_dados = mysqli_query($con,$sql_atualiza_dados);
							if($query_atualiza_dados)
							{
								$mensagem = "Dados atualizados!";	
								gravarLog($sql_atualiza_dados);
							}
							else
							{
								$mensagem = "Erro ao atualizar dados.";
							}
						}
						$pessoa = siscontratDocs($_POST['idPessoa'],3);
						$k = "?perfil=contratados&p=representante";
						$empresa = siscontratDocs($_SESSION['idJuridico'],2);
				?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
            <h2>CADASTRO DE REPRESENTANTE LEGAL</h2>
            <p>A pessoa jurídica para quem você está cadastrando representante legal é <strong><?php echo $empresa['Nome']; ?></strong></p>
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=representante&action=edita" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="text" class="form-control" id="RepresentanteLegal" name="RepresentanteLegal" value="<?php echo $pessoa['Nome'] ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6">
							<input type="text" class="form-control" id="RG" name="RG" placeholder="RG" value="<?php echo $pessoa['RG'] ?>">
						</div>
						<div class="col-md-6">
							<input type="text" readonly class="form-control" id="cpf" name="CPF" placeholder="CPF" value="<?php echo $pessoa['CPF'] ?>">
						</div>
					</div>  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade" value="<?php echo $pessoa['Nacionalidade'] ?>">
						</div>
					</div>
					<!-- Botão Gravar -->	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="idPessoa" value="<?php echo $_POST['idPessoa'] ?>" />
							<input type="hidden" name="atualizar" value="<?php echo $_POST['idPessoa'] ?>" />                    
							<input type="hidden" name="numero" value="<?php echo $_SESSION['numero'] ?>" />
							<input type="submit" name="enviar" value="atualizar" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoPessoa" method="post">                  
							<input type="hidden" name="numero" value="<?php echo $_SESSION['numero'] ?>" />
							<input type="submit" name="enviar" value="Voltar" class="btn btn-theme btn-block">
						</form>
					</div>
					<div class="col-md-6">
						<form class="form-horizontal" role="form" action="?perfil=contratados&p=representante&action=edita" method="post">
							<input type="hidden" name="idPessoa" value="0" />
							<input type="submit" name="enviar" value="Inserir outro representante" class="btn btn-theme btn-block">
						</form>
					</div>
				</div>    
	  		</div>
	  	</div>
	</div>
</section>  
					<?php
						//var_dump($pessoa);
					}
				break;
				case "busca":
					//validação
					$validacao = validaCPF($_POST['busca']);
					if($validacao == false)
					{
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?perfil=contratados&p=erro_representante'>";
						$mensagem = "CPF Inválido!";
					}
					else
					{
						$busca = $_POST['busca'];
						$sql_busca = "SELECT * FROM sis_representante_legal WHERE CPF LIKE '%$busca%' ORDER BY RepresentanteLegal";
						$query_busca = mysqli_query($con,$sql_busca); 
						$num_busca = mysqli_num_rows($query_busca);
						if($num_busca > 0)
						{
							// Se exisitr, lista a resposta.
					?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>CADASTRO DE REPRESENTANTE LEGAL</h2>
					<p>O sistema encontrou informações sobre representantes legais com referência a <br /><strong><?php echo $_POST['busca'] ?>. </strong><br /> </p>
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
						<td width="20%"></td>
						<td width="20%"></td>
					</tr>
				</thead>
				<tbody>
                    <?php
						while($descricao = mysqli_fetch_array($query_busca))
						{			
							echo "<tr>";
							echo "<td class='list_description'><b>".$descricao['RepresentanteLegal']."</b></td>";
							echo "<td class='list_description'>".$descricao['CPF']."</td>";
							echo "
								<td class='list_description'>
								<form method='POST' action='?'>
								<input type='hidden' name='idPessoa' value='1'>
								<input type ='submit' class='btn btn-theme btn-md btn-block' value='detalhe'></td></form>"	;
							echo "
								<td class='list_description'>
								<form method='POST' action='?perfil=contratados&p=edicaoPessoa'>
								<input type='hidden' name='insereRepresentante' value='".$descricao['Id_RepresentanteLegal']."'>
								<input type='hidden' name='idPessoa' value='".$descricao['Id_RepresentanteLegal']."'>
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
						else
						{
					?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE REPRESENTANTE LEGAL</h3>
			<p>Não foi encontrado nenhum registro com o seguinte CPF <?php echo $_POST['busca']; ?></p>
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoPessoa" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="text" class="form-control" id="RepresentanteLegal" name="RepresentanteLegal" placeholder="Representante Legal">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6">
							<input type="text" class="form-control" id="RG" name="RG" placeholder="RG">
						</div>
						<div class="col-md-6">
							<input type="text" class="form-control" id="cpf" name="CPF" placeholder="CPF" readonly value="<?php echo $_POST['busca']; ?>" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade">
						</div>
					</div>
					<!-- Botão Gravar -->	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="cadastraRepresentante" value="1" />
							<input type="hidden" name="idPessoajuridica" value="1" />
							<input type="submit" name="enviar" value="CADASTRAR" class="btn btn-theme btn-lg btn-block">
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
				break;	
			} //fecha a action
		break;
		case "edicaoPedido":
		
		?>
<script>
	function valida()
	{
		var campo = document.getElementById("qtdApresentacoes");
		if(campo.value == "")
		{
		   alert("Preencha o campo Quantidade de Apresentações!");
		   return false;
		} 
		return true;
	}
</script>
		<?php
			if(isset($_POST['novoJuridica']))
			{
				//cadastra e insere pessoa jurídica
				$verificaCNPJ = verificaExiste("sis_pessoa_juridica","CNPJ",$_POST['CNPJ'],"");
				if($verificaCNPJ['numero'] > 0)
				{
					//verifica se o cpf já existe
					$mensagem = "O CNPJ já consta no sistema. Faça uma busca e insira diretamente";
				}
				else
				{
					// o CNPJ não existe, inserir.
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
					$Observacao = $_POST['Observacao'];
					$data = date("Y-m-d");
					$idUsuario = $_SESSION['idUsuario'];
					$sql_inserir_pj = "INSERT INTO `sis_pessoa_juridica` (`Id_PessoaJuridica` , `RazaoSocial` ,`CNPJ` ,`CCM` ,`CEP` ,`Numero` ,`Complemento` ,`Telefone1` ,`Telefone2` ,`Telefone3` ,`Email` , `DataAtualizacao` ,`Observacao` ,`IdUsuario`) VALUES ( NULL ,  '$RazaoSocial',  '$CNPJ', '$CCM' , '$CEP' , '$Numero' , '$Complemento' ,  '$Telefone1', '$Telefone2' , '$Telefone3' , '$Email' , '$data', '$Observacao' ,  '$idUsuario')";
					$query_inserir_pj = mysqli_query($con,$sql_inserir_pj);
					if($query_inserir_pj)
					{
						gravarLog($sql_inserir_pj);
						$sql_ultimo = "SELECT * FROM sis_pessoa_juridica ORDER BY Id_PessoaJuridica DESC LIMIT 0,1"; //recupera ultimo id
						$query_ultimo = mysqli_query($con,$sql_ultimo);
						if($query_ultimo)
						{
							$id = mysqli_fetch_array($query_ultimo);
							$idJuridica = $id['Id_PessoaJuridica'];
							//insere pessoa jurídica
							//$idPessoaJuridica = $descricao['Id_PessoaJuridica'];
							$id_ped = $_GET['id_ped'];
							$sql_atualiza_cnpj = "UPDATE `igsis_pedido_contratacao` 
								SET `idPessoa` = '$idJuridica'
								WHERE `idPedidoContratacao` = '$id_ped'";
							$query_atualiza_cnpj = mysqli_query($con,$sql_atualiza_cnpj);
							if($query_atualiza_cnpj)
							{
								echo "Pessoa Jurídica Atualizada Com Sucesso!";
							}
							else
							{
								echo "erro";
							}
						}
						else
						{
							echo "erro";
						}
					}
					else
					{
						echo "<h1>Erro ao inserir(2)!</h1>";
					}
				}
					
			}
			if(isset($_POST['atualizaJuridica']))
			{
				//insere pessoa jurídica
				//$idPessoaJuridica = $descricao['Id_PessoaJuridica'];
				$idPedido = $_SESSION['idPedido'];
				$idPessoa = $_POST['atualizaJuridica'];
				$sql_atualiza_cnpj = "UPDATE `igsis_pedido_contratacao` 
					SET `idPessoa` = '$idPessoa'
					WHERE `idPedidoContratacao` = '$idPedido'";
				$query_atualiza_cnpj = mysqli_query($con,$sql_atualiza_cnpj);
					
			}
			if(isset($_POST['idPedidoContratacao']))
			{
				$_SESSION['idPedido'] = $_POST['idPedidoContratacao'];
			}
			if(isset($_SESSION['numero']))
			{
				unset($_SESSION['numero']);
			}
			if(isset($_POST['insereExecutante']))
			{
				//insere IdExecutante
				$id_executante = $_POST['insereExecutante'];
				$_SESSION['idPedido'] = $_POST['idPedido'];
				$idPedido = $_SESSION['idPedido'];
				$sql_atualiza_executante = "UPDATE `igsis_pedido_contratacao` SET `IdExecutante` = '$id_executante' WHERE `idPedidoContratacao` = '$idPedido';";
				$query_atualiza_executante = mysqli_query($con,$sql_atualiza_executante);	
				if($query_atualiza_executante)
				{
		 			gravarLog($sql_atualiza_executante);
					$mensagem = "Líder do Grupo inserido com sucesso!";	
					
					$pf = recuperaDados("sis_pessoa_fisica",$id_executante,"Id_PessoaFisica");
					$nome = addslashes($pf['Nome']);
					$rg = $pf['RG'];
					$cpf = $pf['CPF'];
					$sql_inserir = "INSERT INTO `igsis_grupos` 
						(`idGrupos`, 
						`idPedido`, 
						`nomeCompleto`, 
						`rg`, 
						`cpf`, 
						`publicado`) 
						VALUES (NULL, 
						'$idPedido', 
						'$nome', 
						'$rg', 
						'$cpf', 
						'1')";
					$query_inserir = mysqli_query($con,$sql_inserir);
					if($query_inserir)
					{	
				 		gravarLog($sql_inserir);
						$mensagem = "Integrante inserido com sucesso!";	
					}
					else
					{
						$mensagem = "Erro ao inserir integrante. Tente novamente.";	
					}
				}
				else
				{
					$mensagem = "Erro ao inserir Líder do Grupo. Tente novamente!";
				}
			}
			if(isset($_POST['atualizar']))
			{
				$integrantes = addslashes($_POST['integrantes']);
				$Observacao = addslashes($_POST['Observacao']);
				$parcelas = $_POST['parcelas'];
				$Verba = $_POST['verba'];
				$parecer = addslashes($_POST['parecerArtistico']);
				$justificativa = addslashes($_POST['justificativa']);
				$qtdApresentacoes = addslashes($_POST['qtdApresentacoes']);
				$dataKitPagamento = exibirDataMysql($_POST['dataKitPagamento']);
				$idPedidoContratacao = $_POST['idPedidoContratacao'];
				$formaPagamento = $_POST['formaPagamento'];
				if($_POST['atualizar'] >= '2')
				{
					$sql_atualizar_pedido = "UPDATE  `igsis_pedido_contratacao` SET
						`integrantes` = '$integrantes',
						`observacao` =  '$Observacao',
						`parcelas` =  '$parcelas',
						`parecerArtistico` =  '$parecer',
						`justificativa` =  '$justificativa',
						`qtdApresentacoes` =  '$qtdApresentacoes',
						`dataKitPagamento` = '$dataKitPagamento',
						`idVerba` =  '$Verba'
						WHERE  `idPedidoContratacao` = '$idPedidoContratacao';";
				}
				else
				{
					$Valor = dinheiroDeBr($_POST['Valor']);
					$sql_atualizar_pedido = "UPDATE  	`igsis_pedido_contratacao` 
						SET `valor` =  '$Valor',
						`formaPagamento` =  '$formaPagamento',
						`observacao` =  '$Observacao',
						`parcelas` =  '$parcelas',
						`parecerArtistico` =  '$parecer',
						`integrantes` = '$integrantes',
						`justificativa` =  '$justificativa',
						`qtdApresentacoes` =  '$qtdApresentacoes',
						`dataKitPagamento` = '$dataKitPagamento',
						`idVerba` =  '$Verba'
						WHERE  `idPedidoContratacao` = '$idPedidoContratacao';";
				}
				$query_atualizar_pedido = mysqli_query($con,$sql_atualizar_pedido);
				if($query_atualizar_pedido)
				{
					gravarLog($sql_atualizar_pedido);
					$mensagem = "Atualizado com sucesso";
				}
				else
				{
					$mensagem = "Erro ao atualizar(5).".$sql_atualizar_pedido	;
				}
			}
			include "../funcoes/funcoesSiscontrat.php";
			$pedido = recuperaDados("igsis_pedido_contratacao",$_SESSION['idPedido'],"idPedidoContratacao");
			$executante = siscontratDocs($pedido['IdExecutante'],1);
		?>
<!-- Contact -->
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h2>PEDIDO DE CONTRATAÇÃO <?php if($pedido['tipoPessoa'] == 1){echo "PESSOA FÍSICA";}else{echo "PESSOA JURÍDICA";} ?> </h2>
            <p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<p class="left">
							<?php $evento = recuperaEvento($_SESSION['idEvento']); ?>
							<strong>Setor:</strong> <?php echo $_SESSION['instituicao']; ?> - 
							<strong>Categoria de contratação:</strong> <?php recuperaModalidade($evento['ig_modalidade_IdModalidade']); ?> <br />
							<?php
							$fisica = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
							$juridica = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
							if($pedido['tipoPessoa'] == 1)
							{
								echo "<strong>Proponente:</strong> ".$fisica['Nome']."<br />";
							}
							else
							{
								echo "<strong>Proponente:</strong> ".$juridica['RazaoSocial']."<br />";
							}
							?>
							<strong>Objeto:</strong> <?php echo retornaTipo($evento['ig_tipo_evento_idTipoEvento']) ?> -  <?php echo $evento['nomeEvento']; ?> <br />
							<strong>Local:</strong> <?php echo listaLocais($_SESSION['idEvento']); ?><br />
							<strong>Período:</strong> <?php echo retornaPeriodo($_SESSION['idEvento']); ?><br /> 
							<?php
								$fiscal = recuperaUsuario($evento['idResponsavel']);
								$suplente = recuperaUsuario($evento['suplente']);
								$representante01 = siscontratDocs($pedido['idRepresentante01'],3);
							?>
							<strong>Fiscal:</strong>  <?php echo $fiscal['nomeCompleto']; ?> - <strong>Suplente:</strong>  <?php echo $suplente['nomeCompleto']; ?>
						</p>
					</div>
                </div>
        <?php
			if($pedido['tipoPessoa'] == 2)
			{
		?>
                <!-- Executante -->
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br/>
					</div>
                </div>
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoProponente&id_ped=<?php echo $pedido['idPedidoContratacao']?>"  method="post">
					<div class="col-md-offset-2 col-md-8"><strong>Proponente:</strong><br/>
						<input type='text' readonly class='form-control' name='proponente' id='proponente' value="<?php echo $juridica['RazaoSocial'];?>">
						<input type="submit" class="btn btn-theme btn-med btn-block" value="Mudar Proponente">
					</div>
				</form>
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoExecutante&id_pf="  method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Líder do Grupo:</strong><br/>
							<input type='text' readonly class='form-control' name='Executante' id='Executante' value="<?php echo $executante['Nome']; ?>">
						</div>	
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="idPedido" value="<?php echo $_SESSION['idPedido']; ?>" />
			<?php
				if($pedido['IdExecutante'] == NULL OR $pedido['IdExecutante'] == "")
				{
			?>
									<input type="submit" class="btn btn-theme btn-med btn-block" value="Inserir Líder do Grupo">
            <?php
				}
				else
				{
			?>
									<input type="submit" class="btn btn-theme btn-med btn-block" value="Mudar Líder do Grupo">
            <?php
				}
			?>
						</div>
					</div>
				</form>
				<!-- /Executante -->
		<?php
			}
		?>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
				<!-- /Grupo -->
				<form class="form-horizontal" role="form" onsubmit="return valida()" action="?perfil=contratados&p=edicaoPedido" method="post">
		<?php
			$multiplo = recuperaDados("sis_verba",$pedido['idVerba'],"Id_Verba");
			if($pedido['parcelas'] > 1 OR $multiplo['multiplo'] == '1' )
			{
		?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Valor:</strong><br/>
							<input type='text' disabled name="valor_parcela" id='valor' class='form-control' value="<?php echo dinheiroParaBr($pedido['valor']) ?>" >
						</div>					
		<?php
			}
			else
			{
		?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Valor:</strong><br/>
								<input type='text' name="Valor" id='valor' class='form-control' value="<?php echo dinheiroParaBr($pedido['valor']) ?>" >
							</div>					
		<?php
			}
		?>
						</div>
		<?php
			if($pedido['parcelas'] > 0)
			{
                if ($nomeEvento['ig_tipo_evento_idTipoEvento'] != 4)
                {
		?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Forma de Pagamento / Valor da Prestação de Serviço:</strong><br/>
								<textarea  disabled name="formaPagamento" class="form-control" cols="40" rows="5"><?php echo txtParcelas($_SESSION['idPedido'],$pedido['parcelas']); ?>
								</textarea>
								<p></p>
							</div>
						</div>
		<?php
                }
                else
                {
        ?>
                    <!--TODO: Adaptar texto das parcelas para Oficinas-->
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Forma de Pagamento / Valor da Prestação de Serviço:</strong><br/>
                                <textarea  disabled name="formaPagamento" class="form-control" cols="40" rows="5"><?php echo txtParcelas($_SESSION['idPedido'],$pedido['parcelas']); ?>
                                    </textarea>
                                <p></p>
                            </div>
                        </div>
        <?php
                }
			}
			else
			{
		?>				
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Forma de Pagamento / Valor da Prestação de Serviço:</strong><br/>
								<textarea name="formaPagamento" class="form-control" cols="40" rows="5"><?php echo $pedido['formaPagamento'] ?></textarea>
							</div>
						</div>
		<?php
			}
		?>   				  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Parcelas (antes de editar as parcelas, é preciso salvar o pedido)</strong><br/>
                                <select class="form-control" id="parcelas" name="parcelas">
                                <?php if ($nomeEvento['ig_tipo_evento_idTipoEvento'] == 4) { /*Caso seja evento tipo OFICINA*/?>
									<option value="0" <?php if($pedido['parcelas'] == '0'){ echo "selected"; } ?> >Outros</option>
									<option value="1" <?php if($pedido['parcelas'] == '1'){ echo "selected"; } ?> >Parcela única Oficinas de Curta Duração (1 mês)</option>
									<option value="2" <?php if($pedido['parcelas'] == '2'){ echo "selected"; } ?> >2 parcelas Oficinas de Média Duração I (3 meses)</option>
									<option value="2" <?php if($pedido['parcelas'] == '2'){ echo "selected"; } ?> >2 parcelas Oficinas de Média Duração II (4 meses) </option>
									<option value="3" <?php if($pedido['parcelas'] == '3'){ echo "selected"; } ?> >3 parcelas Oficina Estendida I (6 meses)</option>
									<option value="5" <?php if($pedido['parcelas'] == '5'){ echo "selected"; } ?> >5 parcelas Oficina Estendida II  (10 meses)</option>
                                <?php } else { ?>
                                    <option value="0" <?php if($pedido['parcelas'] == '0'){ echo "selected"; } ?> >Outros</option>
                                    <option value="1" <?php if($pedido['parcelas'] == '1'){ echo "selected"; } ?> >Parcela única</option>
                                    <option value="2" <?php if($pedido['parcelas'] == '2'){ echo "selected"; } ?> >2 parcelas</option>
                                    <option value="3" <?php if($pedido['parcelas'] == '3'){ echo "selected"; } ?> >3 parcelas</option>
                                    <option value="4" <?php if($pedido['parcelas'] == '4'){ echo "selected"; } ?> >4 parcelas</option>
                                    <option value="5" <?php if($pedido['parcelas'] == '5'){ echo "selected"; } ?> >5 parcelas</option>
                                    <option value="6" <?php if($pedido['parcelas'] == '6'){ echo "selected"; } ?> >6 parcelas</option>
                                    <option value="7" <?php if($pedido['parcelas'] == '7'){ echo "selected"; } ?> >7 parcelas</option>
                                    <option value="8" <?php if($pedido['parcelas'] == '8'){ echo "selected"; } ?> >8 parcelas</option>
                                    <option value="9" <?php if($pedido['parcelas'] == '9'){ echo "selected"; } ?> >9 parcelas</option>
                                    <option value="10" <?php if($pedido['parcelas'] == '10'){ echo "selected"; } ?> >10 parcelas</option>
                                    <option value="11" <?php if($pedido['parcelas'] == '11'){ echo "selected"; } ?> >11 parcelas</option>
                                    <option value="12" <?php if($pedido['parcelas'] == '12'){ echo "selected"; } ?> >12 parcelas</option>
                                <?php } ?>
								</select>
							</div>	
						</div>
        <?php
			if($pedido['parcelas'] > 1)
			{ //libera a edição de parcelas
                $mostraAlerta = isset($_POST['alertaParcela']) ? $_POST['alertaParcela'] : null;
                if($mostraAlerta == null){
                    ?>
                    <script>
                        alert("Lembre-se de editar as parcelas!");
                    </script>
                    <?php
                }
		?>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<a href="?perfil=contratados&p=edicaoParcelas" class="btn btn-theme btn-block">Editar parcelas</a>
							</div>
						</div>
		<?php
			}
		?>
						<div class="form-group">
		<?php 
			$idverba = $pedido['idVerba'];
			$recupera_verba = recuperaDados("sis_verba",$pedido['idVerba'],"Id_Verba");
			$campo_verba = $recupera_verba['Verba'];
		?>
							<div class="col-md-offset-2 col-md-8">
								<strong>Verba:</strong> <?php echo $campo_verba; ?>  <br/>
								<select class="form-control" id="verba" name="verba" >
									<option value="0"></option>
									<?php geraVerbaUsuario($_SESSION['idUsuario'],$pedido['idVerba']); ?>  
								</select>
							</div>
						</div>
        <?php
			$verbas = recuperaDados("sis_verba",$pedido['idVerba'],"Id_Verba");
			if($verbas['multiplo'] == 1)
			{
				//libera a edição de parcelas
		?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<a href="?perfil=contratados&p=edicaoVerbas" class="btn btn-theme btn-block">Editar verbas múltiplas</a>
							</div>
				
						</div>
		<?php
			}
		?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<p><?php echo comparaValores($_SESSION['idPedido']); ?></p>
							</div>
						</div>

						<!-- Grupo -->
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><br/>
							</div>
		                </div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Integrantes do grupo:</strong><br/>
								<label>Esse campo deve conter a listagem de pessoas envolvidas no espetáculo <font color='#FF0000'>incluindo o líder do grupo</font>.<br/>Apenas o <font color='#FF0000'>nome civil, RG e CPF</font> de quem irá se apresentar, excluindo técnicos.</i></strong></label>
								<p align="justify"><font color="gray"><strong><i>Elenco de exemplo:</strong><br/>Ana Cañas RG 00000000-0 CPF 000.000.000-00<br/>Lúcio Maia RG 00000000-0 CPF 000.000.000-00<br/>Fabá Jimenez RG 00000000-0 CPF 000.000.000-00</br>Fabio Sá RG 00000000-0 CPF 000.000.000-00</br>Marco da Costa RG 00000000-0 CPF 000.000.000-00</font></i></p>
								<textarea name="integrantes" class='form-control' cols="40" rows="5"><?php echo $pedido['integrantes'] ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Justificativa*</label>
								<textarea name="justificativa" required class="form-control" rows="10" placeholder="Texto usado fins jurídicos e confecção de contratos."><?php echo $pedido["justificativa"] ?></textarea>
							</div> 
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Parecer artístico</label>
								<textarea name="parecerArtistico" readonly class="form-control" rows="10" placeholder="Texto usado fins jurídicos e confecção de contratos."><?php $pedido_parecer_artistico = str_replace('<br />',"\n\n", $pedido["parecerArtistico"]);echo $pedido_parecer_artistico;?></textarea>
							</div> 
						
							<div class='col-md-offset-2 col-md-6'>
								<a href='?perfil=contratados&p=edicaoParecer&artista=Local' class='btn btn-theme btn-block'>Editar parecer artista local</a>
							</div>
							<div class='col-md-6'>
								<a href='?perfil=contratados&p=edicaoParecer&artista=Consagrado' class='btn btn-theme btn-block'>Editar parecer artista consagrado</a>
							</div>
						</div>			
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
								<textarea name="Observacao" class='form-control' cols="40" rows="5"><?php echo $pedido['observacao'] ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Quantidade de Apresentações:</strong><br/>
								<input type='text' name="qtdApresentacoes" id='qtdApresentacoes' class='form-control' value="<?php echo $pedido['qtdApresentacoes'] ?>" >
							</div>
							<div class="col-md-6"><strong>Data do Kit de Pagamento:</strong><br/>
								<input type='text' name="dataKitPagamento" id="datepicker01" class='form-control' value="<?php echo exibirDataBr($pedido['dataKitPagamento']) ?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="atualizar" value="<?php echo $pedido['parcelas']; ?>" />
								<input type="hidden" name="idPedidoContratacao" value="<?php echo $_SESSION['idPedido']; ?>" />
								<input type="submit" name="GRAVAR" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
							</div>
						</div>
					</div>
				</form>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<form method='POST' action='?perfil=contratados&p=arqped'>
							<input type="hidden" name="idPedido" value="<?php echo $_SESSION['idPedido']; ?>" >
							<input type="submit" name="" value="ANEXAR ARQUIVOS" class="btn btn-theme btn-lg btn-block">
						</form>
					</div>
					<div class="col-md-6">
						<a href="?perfil=contratados" value="VOLTAR" class="btn btn-theme btn-lg btn-block">VOLTAR para contratados</a>
					</div>
				</div>
			</div>
		</div>
	</div>	
</section>  
	<?php 
		break;
		case "edicaoProponente":
			if(isset($_POST['pesquisar']))
			{
				$id_ped = $_GET['id_ped'];
				//validação
				$validacao = validaCNPJ($_POST['busca']);
				if($validacao == false)
				{
					echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?perfil=contratados&p=erro_edicao_pj&id_ped=".$id_ped."'>";
				}
				else
				{					
				
					// inicia a busca por Razao Social ou CNPJ
					$busca = $_POST['busca'];
					$sql_busca = "SELECT * 
						FROM sis_pessoa_juridica 
						WHERE RazaoSocial LIKE '%$busca%' 
						OR CNPJ LIKE '%$busca%' 
						ORDER BY RazaoSocial";
					$query_busca = mysqli_query($con,$sql_busca); 
					$num_busca = mysqli_num_rows($query_busca);
					if($num_busca > 0)
					{
		// Se exisitr, lista a resposta.
	?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Razão Social</td>
						<td>CNPJ</td>
						<td width="25%"></td>
						<td width="5%"></td>
					</tr>
				</thead>
				<tbody>
				<?php
					while($descricao = mysqli_fetch_array($query_busca))
					{			
						echo "<tr>";
						echo "<td class='list_description'><b>".$descricao['RazaoSocial']."</b></td>";
						echo "<td class='list_description'>".$descricao['CNPJ']."</td>";
						echo "
							<td class='list_description'>
							<form method='POST' action='?perfil=contratados&p=edicaoPedido'>
							<input type='hidden' name='atualizaJuridica' value='".$descricao['Id_PessoaJuridica']."'>
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
					else
					{
						// Se não existe, exibe um formulario para insercao.
				?>
		<!-- Contact -->
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA JURÍDICA</h3>
			<p>Não foram encontradas nenhuma pessoa jurídica com referência <strong><?php echo $_POST['busca'] ?></strong></p> 
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
			<?php
			$id_ped = $_GET['id_ped'];
			if($id_ped == "")
			{
				$form = "<form class='form-horizontal' role='form' action='?perfil=contratados&p=lista' method='post'>";
			}
			else
			{
				$form = "<form class='form-horizontal' role='form' action='?perfil=contratados&p=edicaoPedido&id_ped=".$id_ped."' method='post'>";
			}
			echo $form;
			?>
				
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Razão Social:</strong><br/>
							<input type="text" class="form-control" id="RazaoSocial" name="RazaoSocial" placeholder="RazaoSocial" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>CNPJ:</strong><br/>
							<input type="text" readonly class="form-control" id="CNPJ" name="CNPJ" placeholder="CNPJ" value=<?php echo $_POST['busca'] ?> >
						</div>
						<div class="col-md-6"><strong>CCM:</strong><br/>
							<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" >
						</div>
					</div>  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>CEP *:</strong><br/>
							<input type="text" class="form-control" id="CEP" name="CEP" placeholder="XXXXX-XXX">
						</div>				  
						<div class=" col-md-6"><strong>Estado *:</strong><br/>
							<input type="text" class="form-control" id="Estado" name="Estado" placeholder="Estado">
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
						<div class=" col-md-6"><strong>Complemento:</strong><br/>
							<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento">
						</div>
					</div>  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Bairro *:</strong><br/>
							<input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
						</div>				  
						<div class=" col-md-6"><strong>Cidade *:</strong><br/>
							<input type="text" class="form-control" id="Cidade" name="Cidade" placeholder="Cidade">
						</div>
					</div>  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone1" placeholder="Exemplo: (11) 98765-4321">
						</div>				  
						<div class=" col-md-6"><strong>Telefone:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone2" placeholder="Exemplo: (11) 98765-4321" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone3" placeholder="Exemplo: (11) 98765-4321">
						</div>				  
						<div class=" col-md-6"><strong>E-mail:</strong><br/>
							<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Observações:</strong><br/>
							<textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
						</div>
					</div>
					<!-- Botão Gravar -->	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
						<?php
						if($id_ped == "")
						{
							$lista = "?perfil=contratados&p=lista";
			
						?>
						
							<input type="hidden" name="cadastrarJuridica" value="1" />
							<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
							</form>
							<?php
						}
						else
						{
							$lista = "?perfil=contratados&p=edicaoPedido&id_ped=";
							?>
							<input type="hidden" name="novoJuridica" value="1" />
							<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
							
						<?php
						}
							?>
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
			}
			else
			{
				// Se não existe pedido de busca, exibe campo de pesquisa.
				$pedido = recuperaDados("igsis_pedido_contratacao",$_SESSION['idPedido'],"idPedidoContratacao");
			?>    
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Contratados - Pessoa Jurídica</h2>
                    <p>Você está inserindo pessoas físicas para serem contratadas para o evento <strong><?php  echo $nomeEvento['nomeEvento']; ?></strong></p>
					<p></p>
				</div>
			</div>
		</div>	  
	    <div class="row">
            <div class="form-group">
            	<div class="col-md-offset-2 col-md-8">  
                    <form method="POST" action="?perfil=contratados&p=edicaoProponente&id_ped=<?php echo $pedido['idPedidoContratacao']?>" class="form-horizontal" role="form">
						<label>Insira o CNPJ</label>
						<input type="text" name="busca" class="form-control" id="CNPJ" placeholder="" ><br />
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
		break;
		case "edicaoExecutante":
			$con = bancoMysqli(); // conecta no banco
			$ultimo = $_GET['id_pf']; //recupera o id da pessoa
			if(isset($_POST['idPedido']))
			{
				$id_pedido = $_POST['idPedido']; //recupera o id do pedido
				$mensagem = $id_pedido;
			}
			if($_GET['id_pf'] == "" OR $_GET['id_pf'] == NULL)
			{
				$pagina = "busca";	
				if(isset($_POST['pesquisar']))
				{
					$pagina = "pesquisar";	
				}
			}
			else
			{
				$pagina = "editar";
			}
			switch($pagina)
			{
				case "busca":
	?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Líder do Grupo - Pessoa Física</h2>
                    <p>Você está inserindo pessoas físicas para serem contratadas para o evento <strong><?php  //echo $nomeEvento['nomeEvento']; ?></strong></p>
					<p></p>
				</div>
			</div>
		</div>  
	    <div class="row">
            <div class="form-group">
            	<div class="col-md-offset-2 col-md-8">    
					<form method="POST" action="?perfil=contratados&p=edicaoExecutante&id_pf=" class="form-horizontal" role="form">
						<label>Insira o CPF</label>
						<input type="text" name="busca" class="form-control" id="cpf" >
						<br />             
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="pesquisar" value="1" />
								<input type="hidden" name="idPedido" value="<?php echo $_POST['idPedido']; ?>" />
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
				break;
				case "pesquisar":
					$idPedido = $_POST['idPedido'];
					//validação
					$validacao = validaCPF($_POST['busca']);
					if($validacao == false)
					{
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?perfil=contratados&p=erro_executante'>";
						$mensagem = "CPF Inválido!";
					}
					else
					{					
						$busca = $_POST['busca'];
						$sql_busca = "SELECT * FROM sis_pessoa_fisica WHERE CPF = '$busca' ORDER BY Nome";
						$query_busca = mysqli_query($con,$sql_busca); 
						$num_busca = mysqli_num_rows($query_busca);
						if($num_busca > 0)
						{
							// Se exisitr, lista a resposta.
			?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Executante - Pessoa Física</h2>
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
						<td width="15%"></td>    
					</tr>
				</thead>
				<tbody>
					<?php
						while($descricao = mysqli_fetch_array($query_busca))
						{			
							echo "<tr>";
							echo "<td class='list_description'><b>".$descricao['Nome']."</b></td>";
							echo "<td class='list_description'>".$descricao['CPF']."</td>";
							echo "
								<td class='list_description'>
								<form method='POST' action='?perfil=contratados&p=edicaoPedido' >
								<input type='hidden' name='idPedido' value='".$_POST['idPedido']."'>
								<input type='hidden' name='insereExecutante' value='".$descricao['Id_PessoaFisica']."'>
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
						else
						{
							// se não existir o cpf, imprime um formulário.
				?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA FÍSICA</h3>
            <p> O CPF <?php echo $busca; ?> não está cadastrado no nosso sistema. <br />Por favor, insira as informações da Pessoa Física a ser contratada. </p>
            <p><a href="?perfil=contratados&p=edicaoExecutante&id_pf="> Pesquisar outro CPF</a> </p>
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_edita_propostapj&id_ped=<?php echo $_SESSION['idPedido'] ?>" method="post">
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
							<input type="text" class="form-control" id="cpf" name="CPF" readonly placeholder="CPF" value="<?php echo $busca; ?> ">
						</div>				  
						<div class=" col-md-6"><strong>CCM *:</strong><br/>
							<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" >
						</div>
					</div>
					<div class="form-group">			  
						<div class="col-md-offset-2 col-md-6"><strong>Data de nascimento:</strong><br/>
							<input type="text" class="form-control" id="datepicker01" name="DataNascimento" placeholder="Data de Nascimento" >
						</div>
						<div class="col-md-6"><strong>Nacionalidade:</strong><br/>
							<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade">
						</div>
					</div>
					<div class="form-group">				  
						<div class="col-md-offset-2 col-md-8"><strong>CEP:</strong><br/>
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
							<input type="hidden" name="cadastraExecutante" value="1" />
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
				break;
				case "editar":
					if(isset($_POST['cadastrarFisica']))
					{
						$idPessoaFisica = $_POST['cadastrarFisica'];
						$Nome = addslashes($_POST['Nome']);
						$NomeArtistico = addslashes($_POST['NomeArtistico']);
						$RG = $_POST['RG'];
						$CPF = $_POST['CPF'];
						$Telefone1 = $_POST['Telefone1'];
						$Telefone2 = $_POST['Telefone2'];
						$Telefone3 = $_POST['Telefone3'];
						$Email = $_POST['Email'];
						$DRT = $_POST['DRT'];
						$Observacao = addslashes($_POST['Observacao']);
						$tipoDocumento = $_POST['tipoDocumento'];
						$Pis = 0;
						$data = date('Y-m-d');
						$idUsuario = $_SESSION['idUsuario'];
						$sql_atualizar_pessoa = "UPDATE sis_pessoa_fisica 
							SET `Nome` = '$Nome',
							`NomeArtistico` = '$NomeArtistico',
							`RG` = '$RG', 
							`CPF` = '$CPF', 
							`Telefone1` = '$Telefone1', 
							`Telefone2` = '$Telefone2',  
							`Telefone3` = '$Telefone3', 
							`Email` = '$Email', 
							`DRT` = '$DRT', 
							`DataAtualizacao` = '$data', 
							`Observacao` = '$Observacao', 
							`IdUsuario` = '$idUsuario', 
							`tipoDocumento` = '$tipoDocumento' 
							WHERE `Id_PessoaFisica` = '$idPessoaFisica'";		
						if(mysqli_query($con,$sql_atualizar_pessoa))
						{
					 		gravarLog($sql_atualizar_pessoa);
							$mensagem = "Atualizado com sucesso!";	
						}
						else
						{
							$mensagem = "Erro ao atualizar! Tente novamente.";
						}
					}
					$fisica = recuperaDados("sis_pessoa_fisica",$ultimo,"Id_PessoaFisica");
				?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE LÍDER DO GRUPO (PESSOA FÍSICA)</h3>
			<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
        </div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoExecutante&id_pf=<?php echo $ultimo ?>" method="post">
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
						<div class="col-md-6"><strong>E-mail *:</strong><br/>
							<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail" value="<?php echo $fisica['Email']; ?>" >
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Celular *:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone1" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $fisica['Telefone1']; ?>">
						</div>
						<div class="col-md-6"><strong>Telefone #2:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone2" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $fisica['Telefone2']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone #3:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone3" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $fisica['Telefone3']; ?>" >
						</div>
							<div class="col-md-6"><strong>DRT:</strong><br/>
							<input type="text" class="form-control" id="DRT" name="DRT" placeholder="DRT" value="<?php echo $fisica['DRT']; ?>">
						</div>	
					</div>	  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
							<textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
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
                <div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<form class="form-horizontal" role="form" action="?perfil=contratados&p=arqexec" method="post">
							<input type="hidden" name="cadastrarFisica" value="<?php echo $fisica['Id_PessoaFisica'] ?>" />
							<input type="hidden" name="idPedido" value="<?php echo $id_pedido ?>" />
							<input type="submit" value="Anexos" class="btn btn-theme btn-block">
						</form>					
					</div>
					<!-- Botão para verificar 					da pessoa -->
					<div class="col-md-6">
						<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoPedido" method="post">
							<input type="hidden" name="idPedido" value="<?php echo $_SESSION['idPedido']; ?>">
							<input type="submit" value="Voltar ao Pedido" class="btn btn-theme btn-block"></a> 
						</form>
					</div>
				</div> 
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br />
					</div>
				</div>
				<?php
					if(isset($id_pedido))
					{
				?>      
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<a href="?perfil=contratados&p=edicaoExecutante&id_pf="><input type="submit" value="Mudar o líder do grupo" class="btn btn-theme btn-block"></a>
					</div>
				</div>
				<?php
					}
				?>
	  		</div>
	  	</div>	
	</div>
</section>  
			<?php
				break;
			} // fecha a switch
		break;
		case "arqexec":
			$con = bancoMysqli();
			$idPessoa = $_POST["cadastrarFisica"];
			$idPedido = $_POST["idPedido"];
			if(isset($_POST["enviar"]))
			{
				$sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '1'";
				$query_arquivos = mysqli_query($con,$sql_arquivos);
				while($arq = mysqli_fetch_array($query_arquivos))
				{ 
					$y = $arq['idTipoDoc'];
					$x = $arq['sigla'];
					$nome_arquivo = $_FILES['arquivo']['name'][$x];
					if($nome_arquivo != "")
					{
						$nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
						//$ext = strtolower(substr($nome_arquivo[$i],-4)); //Pegando extensão do arquivo
						$new_name = date("YmdHis")."_".semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
						$hoje = date("Y-m-d H:i:s");
						$dir = '../uploadsdocs/'; //Diretório para uploads	  
						if(move_uploaded_file($nome_temporario, $dir.$new_name))
						{
							$sql_insere_arquivo = "INSERT INTO `igsis_arquivos_pessoa` 
								(`idArquivosPessoa`, 
								`idTipoPessoa`, 
								`idPessoa`, 
								`arquivo`, 
								`dataEnvio`, 
								`publicado`, 
								`tipo`) 
								VALUES (NULL, 
								'1', 
								'$idPessoa', 
								'$new_name', 
								'$hoje', 
								'1', 
								'$y'); ";
							$query = mysqli_query($con,$sql_insere_arquivo);
							if($query)
							{
						 		gravarLog($sql_insere_arquivo);
								$mensagem = "Arquivo recebido com sucesso";
							}
							else
							{
								$mensagem = "Erro ao gravar no banco";
							}
						}
						else
						{
						 $mensagem = "Erro no upload"; 
						}
					}
				}
			}
			if(isset($_POST['apagar']))
			{
				$idArquivo = $_POST['apagar'];
				$sql_apagar_arquivo = "UPDATE igsis_arquivos_pessoa SET publicado = 0 WHERE idArquivosPessoa = '$idArquivo'";
				if(mysqli_query($con,$sql_apagar_arquivo))
				{
					$arq = recuperaDados("igsis_arquivos_pessoa",$idArquivo,"idArquivosPessoa");
					$mensagem =	"Arquivo ".$arq['arquivo']."apagado com sucesso!";
					gravarLog($sql_apagar_arquivo);
				}
				else
				{
					$mensagem = "Erro ao apagar o arquivo. Tente novamente!";
				}
			}
			$campo = recuperaPessoa($idPessoa,1);
			?>
<section id="enviar" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
                    <h2><?php echo $campo["nome"] ?>  </h2>
					<p><?php echo $campo["tipo"] ?></p>
					<h3>Envio de Arquivos</h3>
                    <p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
					<p>Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 60MB.</p>
					<br />
					<div class = "center">
						<form method="POST" action="?<?php echo $_SERVER['QUERY_STRING'] ?>" enctype="multipart/form-data">
							<table>
								<tr>
									<td width="50%"><td>
								</tr>
		<?php 
			$sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '1' ";
			$query_arquivos = mysqli_query($con,$sql_arquivos);
			while($arq = mysqli_fetch_array($query_arquivos))
			{
		?>
								<tr>	
									<td><label><?php echo $arq['documento']?></label></td><td><input type='file' name='arquivo[<?php echo $arq['sigla']; ?>]'></td>
								</tr>
		<?php
			}
		?>
							</table>
							<br>
							<input type="hidden" name="cadastrarFisica" value="<?php echo $idPessoa; ?>"  />
							<input type="hidden" name="idPedido" value="<?php echo $_SESSION['idPedido']; ?>"  />
							<input type="hidden" name="tipoPessoa" value="1"  />
							<input type="hidden" name="enviar" value="1"  />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value='Enviar'>
						</form>
					</div>
					<br />
					<br />
				</div>
			</div>
		</div>	  
	</div>
</section>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Arquivos anexados</h2>
					<h5>Se na lista abaixo, o seu arquivo começar com "http://", por favor, clique, grave em seu computador, faça o upload novamente e apague a ocorrência citada.</h5>
				</div>
				<div class="table-responsive list_info">
		<?php
			include "../funcoes/funcoesSiscontrat.php";
			$pag = "contratos";
			listaArquivosPessoaSiscontrat($idPessoa,1,$_SESSION['idPedido'],$p,$pag);
		?>             
				</div>
			</div>
		</div>  
	</div>
</section>
	<?php 
		break;
		case "edicaoGrupo":
			if(isset($_GET['action']))
			{
				$action = $_GET['action'];	
			}
			else
			{
				$action = "listar";
			}
			switch($action)
			{
				case "listar":
					$con = bancoMysqli();
					$idPedido = $_POST['idPedido'];
					if(isset($_POST['inserir']))
					{
						$nome = addslashes($_POST['nome']);
						$rg = trim($_POST['rg']);
						$cpf = $_POST['cpf'];
						$sql_inserir = "INSERT INTO `igsis_grupos` 
							(`idGrupos`, 
							`idPedido`, 
							`nomeCompleto`, 
							`rg`, 
							`cpf`, 
							`publicado`) 
							VALUES (NULL, 
							'$idPedido', 
							'$nome', 
							'$rg', 
							'$cpf', 
							'1')";
						$query_inserir = mysqli_query($con,$sql_inserir);
						if($query_inserir)
						{	
					 		gravarLog($sql_inserir);
							$mensagem = "Integrante inserido com sucesso!";	
						}
						else
						{
							$mensagem = "Erro ao inserir integrante. Tente novamente.";	
						}	
					}
					if(isset($_POST['apagar']))
					{
						$id = $_POST['apagar'];
						$sql_apagar = "UPDATE igsis_grupos SET publicado = '0' WHERE idGrupos = '$id'";
						$query_apagar = mysqli_query($con,$sql_apagar);
						if($query_apagar)
						{	
					 		gravarLog($sql_apagar);
							$mensagem = "Integrante apagado com sucesso!";	
						}
						else
						{
							$mensagem = "Erro ao apagar integrante. Tente novamente.";	
						}
					}
					$sql_grupos = "SELECT * 
						FROM igsis_grupos 
						WHERE idPedido = '$idPedido' 
						AND publicado = '1'";
					$query_grupos = mysqli_query($con,$sql_grupos);
					$num = mysqli_num_rows($query_grupos);
	?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Grupos</h2>
					<h4>Integrantes de grupos</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div>
				<?php
					if($num > 0)
					{ 
				?>
		<div class="table-responsive list_info">
            <table class='table table-condensed'>
				<thead>
					<tr class='list_menu'>
						<td width='40%'>Nome Completo</td>
						<td>RG</td>
						<td>CPF</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
					<?php
						while($grupo = mysqli_fetch_array($query_grupos))
						{ 
					?>	
					<tr>
						<td><?php echo $grupo['nomeCompleto'] ?></td>
						<td><?php echo $grupo['rg'] ?></td>
						<td><?php echo $grupo['cpf'] ?></td>
						<td class='list_description'>
							<form method='POST' action='?perfil=contratados&p=edicaoGrupo'>
								<input type="hidden" name="apagar" value="<?php echo $grupo['idGrupos'] ?>" />
								<input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>" >	
								<input type ='submit' class='btn btn-theme btn-block' value='apagar'>
							</form>
						</td>
					</tr>					
					<?php
						}
					?>
				</tbody>
			</table>
				<?php 
					}
					else
					{
				?>				
            <div class="col-md-offset-2 col-md-8">
            	<h3>Não há integrantes de grupos inseridos. <br />
            </div> 
				<?php 
					}
				?>
			<div class="col-md-offset-2 col-md-8"><br/>
			</div>
            <div class="col-md-offset-2 col-md-6">
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoGrupo&action=inserir"  method="post">
					<input type="hidden" name="idPedidoContratacao" value="<?php echo $idPedido; ?>" >
					<input type ='submit' class='btn btn-theme btn-block' value='Inserir novo integrante'></td>
				</form>	
			</div>
				<?php
					$pedido = recuperaDados("igsis_pedido_contratacao",$idPedido,"idPedidoContratacao");
				?>
			<div class="col-md-4">
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoPedido"  method="post">
					<input type="hidden" name="idPedidoContratacao" value="<?php echo $idPedido; ?>" >
					<input type ='submit' class='btn btn-theme btn-block' value='Voltar ao Pedido de Contratação'></td>
				</form>
	        </div>
		</div>		   
	</div>
</section>
			<?php
				break;
				case "inserir";
			?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
            <h3>CADASTRO DE INTEGRANTE DE GRUPO</h3>
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoGrupo&action=listar" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome completo: *</strong><br/>
							<input type="text" class="form-control" id="RepresentanteLegal" name="nome" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>RG: *</strong><br/>
							<input type="text" class="form-control" id="RG" name="rg" placeholder="RG">
						</div>
						<div class="col-md-6"><strong>CPF: *</strong><br/>
							<input type="text" class="form-control" id="cpf" name="cpf"  placeholder="CPF">
						</div>
					</div>
					<!-- Botão Gravar -->	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="idPedido" value="<?php echo $_POST['idPedidoContratacao']; ?>" >					
							<input type="submit" name="inserir" value="CADASTRAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
	  		</div>
		</div>
	</div>
</section>
		<?php
			} //fecha a action
		break;
		case "edicaoParcelas":
			include "../funcoes/funcoesSiscontrat.php";
			$pedido = recuperaDados("igsis_pedido_contratacao",$_SESSION['idPedido'],"idPedidoContratacao");
			//verifica se há dados na tabela igsis_parcelas
			$idPedido = $_SESSION['idPedido'];
			$sql_verifica_parcela = "SELECT * FROM igsis_parcelas WHERE idPedido = '$idPedido'";
			$query_verifica_parcela = mysqli_query($con,$sql_verifica_parcela);
			$num_parcelas = mysqli_num_rows($query_verifica_parcela);
			if($num_parcelas == 0)
			{
				for($i = 1; $i <= 12; $i++)
				{
					// se não há, insere 12 parcelas vazias.
					$insert_parcela = "INSERT INTO `igsis_parcelas` 
						(`idParcela`, 
						`idPedido`, 
						`numero`, 
						`valor`, 
						`vencimento`, 
						`publicado`, 
						`descricao`) 
						VALUES (NULL, 
						'$idPedido', 
						'$i', 
						'', 
						NULL, 
						'0', 
						'')";
					mysqli_query($con,$insert_parcela);
				}
			}
			if(isset($_POST['atualizar']))
			{
				for($i = 1; $i <= $pedido['parcelas']; $i++)
				{
					$valor = dinheiroDeBr($_POST['valor'.$i]);
					$data = exibirDataMysql($_POST['vencimento'.$i]);
					$descricao = $_POST['descricao'.$i];
					$mensagem = "";
					$horas = $_POST['horas'.$i];
					$vigencia_inicio = exibirDataMysql($_POST['vigencia_inicio'.$i]);
					$vigencia_final = exibirDataMysql($_POST['vigencia_final'.$i]);
					
					$sql_atualiza_parcela = "UPDATE igsis_parcelas 
						SET valor = '$valor', 
						vencimento = '$data', 
						descricao = '$descricao',
						horas = '$horas',
						vigencia_inicio =  '$vigencia_inicio',
						vigencia_final = '$vigencia_final'
						WHERE idPedido = '$idPedido' 
						AND numero = '$i'";	
					$query_atualiza_parcela = mysqli_query($con,$sql_atualiza_parcela);
					if($query_atualiza_parcela)
					{
						gravarLog($sql_atualiza_parcela);
						$mensagem = $mensagem." Parcela $i atualizada.<br />";
						$sql_recuperaParcela = "SELECT vencimento FROM igsis_parcelas WHERE numero = '1' AND idPedido = '$idPedido'";
						$query_recuperaParcela = mysqli_query($con,$sql_recuperaParcela);
						$recuperaParcela = mysqli_fetch_array($query_recuperaParcela);
						$dataParcela = $recuperaParcela['vencimento'];
						$soma = somaParcela($idPedido,$pedido['parcelas']);
						$sql_atualiza_valor = "UPDATE igsis_pedido_contratacao SET valor = '$soma', dataKitPagamento = '$dataParcela' WHERE idPedidoContratacao = '$idPedido'";
						$query_atualiza_valor = mysqli_query($con,$sql_atualiza_valor);
						if($query_atualiza_valor)
						{
							gravarLog($sql_atualiza_valor);
							$mensagem = $mensagem." Valor total atualizado. ";
						}
					}
					else
					{
						$mensagem = $mensagem."Erro ao atualizar parcela $i.<br />";
					}
				}
			}
		?>
<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
			<h2>PEDIDO DE CONTRATAÇÃO <?php if($pedido['tipoPessoa'] == 1){echo "PESSOA FÍSICA";}else{echo "PESSOA JURÍDICA";} ?> </h2>
			<h5>Edição de parcelas</h5>
			<p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<p class="left">
							<?php $evento = recuperaEvento($_SESSION['idEvento']); ?>
						</p>
					</div>
                </div>
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoParcelas" method="post">
		<?php
			$soma = 0;
			for($i = 1; $i <= $pedido['parcelas']; $i++)
			{
				$sql_rec_parcela = "SELECT * 
					FROM igsis_parcelas 
					WHERE idPedido = '$idPedido' 
					AND numero = '$i'";
				$query_rec_parcela = mysqli_query($con,$sql_rec_parcela);
				$parcela = mysqli_fetch_array($query_rec_parcela);
		?>
		
		<?php
			if ($evento['ig_tipo_evento_idTipoEvento'] == 4) {
		?>
					<div class="form-group">
						<div class="col-xs-6 col-sm-1"><strong>Parcela</strong><br/>
							<input type='text' disabled name="Valor" id='valor' class='form-control' value="<?php echo $i; ?>" >
						</div>					
						<div class="col-xs-6 col-sm-2"><strong>Valor</strong><br/>
							<input type='text'  name="valor<?php echo $i; ?>" id='valor' class='form-control valor' value="<?php echo dinheiroParaBr($parcela['valor']); ?>">
						</div>
						<div class="col-xs-6 col-sm-2"><strong>Data Inicial</strong><br/>
							<input type='text' name="vigencia_inicio<?php echo $i; ?>" id='datepicker1<?php echo $i; ?>' class='form-control datepicker' value="<?php 	echo exibirDataBr($parcela['vigencia_inicio']); ?>">
						</div>
						<div class="col-xs-6 col-sm-2"><strong>Data Final</strong>
							<input type='text' name="vigencia_final<?php echo $i; ?>" id='datepicker2<?php echo $i; ?>' class='form-control datepicker' value="<?php 	echo exibirDataBr($parcela['vigencia_final']); ?>">
						</div>
						<div class="col-xs-6 col-sm-2"><strong>Data Pagamento</strong>
							<input type='text' name="vencimento<?php echo $i; ?>" id='datepicker3<?php echo $i; ?>' class='form-control datepicker' value="<?php 	echo exibirDataBr($parcela['vencimento']); ?>">
						</div>
						<div class="col-xs-6 col-sm-2"><strong>Descrição</strong>
							<input type='text'  name="descricao<?php echo $i; ?>" id='' class='form-control' value="<?php echo $parcela['descricao']; ?>">
						</div>
						<div class="col-xs-6 col-sm-1"><strong>Horas</strong>
							<input type='text'  name="horas<?php echo $i; ?>" id='horas' class='form-control' value="<?php echo $parcela['horas']; ?>">
						</div>
					</div>	
		<?php
			} else{
		?>
					<div class="form-group">
						<div class="col-xs-6 col-sm-1"><strong>Parcela</strong><br/>
							<input type='text' disabled name="Valor" id='valor' class='form-control' value="<?php echo $i; ?>" >
						</div>					
						<div class="col-xs-6 col-sm-3"><strong>Valor</strong><br/>
							<input type='text'  name="valor<?php echo $i; ?>" id='valor' class='form-control valor' value="<?php echo dinheiroParaBr($parcela['valor']); ?>">
						</div>
						<div class="col-xs-6 col-sm-3"><strong>Data do Kit de Pagamento</strong><br/>
							<input type='text' name="vencimento<?php echo $i; ?>" id='datepicker1<?php echo $i; ?>' class='form-control datepicker' value="<?php 	echo exibirDataBr($parcela['vencimento']); ?>">
						</div>
						<div class="col-xs-6 col-sm-3"><strong>Descrição</strong><br/>
							<input type='text'  name="descricao<?php echo $i; ?>" id='' class='form-control' value="<?php echo $parcela['descricao']; ?>">
						</div>
					</div>
			<?php
			}
			?>						
						
            <?php 
				$soma = $soma + $parcela['valor'];
			}
			?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<p><?php echo "A soma das parcelas é: ".dinheiroParaBr($soma); ?></p>
							<p><?php echo "O valor total do contrato é: ".dinheiroParaBr($pedido['valor']); ?></p>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="atualizar" value="1" />
							<input type="hidden" name="idPedidoContratacao" value="<?php echo $_SESSION['idPedido']; ?>" />
							<input type="submit" alt="" name="GRAVAR" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
                        <form method="POST" action="?perfil=contratados&p=edicaoPedido">
                            <input type="hidden" name="alertaParcela" value="7777">
                            <input type='submit' class='btn btn-theme btn-lg btn-block' value='VOLTAR para área de pedidos de contratação'>
                            <!--<a href="?perfil=contratados&p=edicaoPedido" value="VOLTAR" class="btn btn-theme btn-lg btn-block">VOLTAR para área de pedidos de contratação</a>-->

                        </form>
					</div>
				</div>	
	  		</div>
	  	</div>	
	</div>
</section>
	<?php 
		break;
		case "edicaoVerbas":
			include "../funcoes/funcoesSiscontrat.php";
			$pedido = recuperaDados("igsis_pedido_contratacao",$_SESSION['idPedido'],"idPedidoContratacao");
			//verifica se há dados na tabela igsis_parcelas
			$idPedido = $_SESSION['idPedido'];
			$pedido = recuperaDados("igsis_pedido_contratacao",$idPedido,"idPedidoContratacao");
			$idVerba = $pedido['idVerba'];
			$sql_verifica_parcela = "SELECT * FROM sis_verbas_multiplas WHERE idPedidoContratacao = '$idPedido'";
			$query_verifica_parcela = mysqli_query($con,$sql_verifica_parcela);
			$num_parcelas = mysqli_num_rows($query_verifica_parcela);
			if($num_parcelas == 0)
			{
				$idInstituicao = $_SESSION['idInstituicao'];
				$sql_verbas = "SELECT * 
					FROM sis_verba 
					WHERE Idinstituicao = '$idInstituicao' 
					AND pai IS NOT NULL 
					AND multiplo IS NULL";
				$query_verbas = mysqli_query($con,$sql_verbas);
				while($campo = mysqli_fetch_array($query_verbas))
				{
					$verba = $campo['Id_Verba']; 
					$insert_parcela = "INSERT INTO `sis_verbas_multiplas` 
						(`idMultiplas`, 
						`idPedidoContratacao`, 
						`idVerba`, 
						`valor`) 
						VALUES (NULL, 
						'$idPedido', 
						'$verba', 
						'');";
					mysqli_query($con,$insert_parcela);
				}
			}
			if(isset($_POST['atualizar']))
			{
				$idPedido = $_SESSION['idPedido'];
				$sql_verbas = "SELECT * FROM sis_verbas_multiplas WHERE idPedidoContratacao = '$idPedido'";
				$query_verbas = mysqli_query($con,$sql_verbas);
				while($campo = mysqli_fetch_array($query_verbas))
				{
					$id = $campo['idMultiplas'];
					$valor = dinheiroDeBr($_POST[$id]);
					$sql_atualiza_verba = "UPDATE sis_verbas_multiplas SET valor = '$valor' WHERE idMultiplas = '$id'";
					$query_atualiza_verba = mysqli_query($con,$sql_atualiza_verba);
					if($query_atualiza_verba)
					{
						$soma = somaVerbas($idPedido);
						gravarLog($sql_atualiza_verba);
						$sql_atualiza_valor = "UPDATE igsis_pedido_contratacao SET valor = '$soma' WHERE idPedidoContratacao = '$idPedido'";
						$query_atualiza_valor = mysqli_query($con,$sql_atualiza_valor);
						$mensagem = "Valores atualizados";	
					}
					else
					{
						$mensagem = "Erro ao atualizar valores"; 	
					}		
				}
			}
	?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h5>Edição de verbas</h5>
			<p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<p class="left">
							<?php $evento = recuperaEvento($_SESSION['idEvento']); ?>
						</p>
					</div>
                </div>
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoVerbas" method="post">
		<?php
			$soma = 0;
			$idPedido = $_SESSION['idPedido'];
			$sql_recupera_verbas = "SELECT * FROM sis_verbas_multiplas WHERE idPedidoContratacao = '$idPedido'";
			$query_recupera_verbas = mysqli_query($con,$sql_recupera_verbas);
			while($campo_verba = mysqli_fetch_array($query_recupera_verbas))
			{
				$nome_verba = recuperaDados("sis_verba",$campo_verba['idVerba'],"Id_Verba");
		?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Verba</strong><br/>
							<p><?php echo $nome_verba['Verba']; ?></p>
						</div>					
						<div class="col-md-3"><strong>Valor</strong><br/>
							<input type='text'  name="<?php echo $campo_verba['idMultiplas'];?>" id='valor' class='form-control valor' value="<?php echo dinheiroParaBr($campo_verba['valor']);?>">
						</div>
					</div>
		<?php 
			}
		?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<p><?php echo "O valor total do contrato é: ".dinheiroParaBr(somaVerbas($idPedido)); ?></p>
						</div>
					</div>	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="atualizar" value="1" />
							<input type="hidden" name="idPedidoContratacao" value="<?php echo $_SESSION['idPedido']; ?>" />
							<input type="submit" name="GRAVAR" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<a href="?perfil=contratados&p=edicaoPedido" value="VOLTAR" class="btn btn-theme btn-lg btn-block">VOLTAR para area de pedidos de contratação</a>
					</div>
				</div>
	  		</div>
	  	</div>
	</div>
</section>
	<?php 
		break;
		case "edicaoPessoa":
	?>
<script>
	function valida()
	{
		var campo = document.getElementById("RazaoSocial");
		if(campo.value == "")
		{
		   alert("Preencha os campos obrigatórios!");
		   return false;
		} 
		return true;
	}
</script>
		<?php
			if(isset($_POST['cadastraRepresentante']))
			{
				$n = $_SESSION['numero'];
				if($n == 1)
				{
					$campoRepresentante = "IdRepresentanteLegal1";
				}
				else
				{
					$campoRepresentante = "IdRepresentanteLegal2";
				}
				$representante = addslashes($_POST['RepresentanteLegal']);
				$rg = $_POST['RG'];
				$cpf = $_POST['CPF'];
				$nacionalidade = $_POST['Nacionalidade'];
				$idPessoaJuridica = $_SESSION['idPessoaJuridica'];
				$sql_insere_representante =  "INSERT INTO `sis_representante_legal` 
					(`Id_RepresentanteLegal`, 
					`RepresentanteLegal`, 
					`RG`, 
					`CPF`, 
					`Nacionalidade`, 
					`idEvento`) 
					VALUES (NULL, 
					'$representante', 
					'$rg', 
					'$cpf', 
					'$nacionalidade', 
					NULL)";
				$con = bancoMysqli();
				$query_insere_representante = mysqli_query($con,$sql_insere_representante);
				if($query_insere_representante)
				{
					$ultimo = recuperaUltimo("sis_representante_legal");
					$sql_atualiza_representante = "UPDATE sis_pessoa_juridica SET $campoRepresentante = '$ultimo' WHERE Id_PessoaJuridica = '$idPessoaJuridica'";
					$query_atualiza_representante = mysqli_query($con,$sql_atualiza_representante);
					if($query_atualiza_representante)
					{
						gravarLog($sql_atualiza_representante);
						$mensagem = "Represenante legal 0".$n." atualizado com sucesso!";
					}
					else
					{
						$mensagem = "Erro(1)";
					}
				}
				else
				{
					$mensagem = "Erro(2)";		
				}
			}
			if(isset($_POST['insereRepresentante']))
			{
				$n = $_SESSION['numero'];
				$idRepresentante = $_POST['idPessoa'];
				$idPessoaJuridica = $_SESSION['idPessoaJuridica'];
				if($n == 1)
				{
					$campoRepresentante = "IdRepresentanteLegal1";
				}
				else
				{
					$campoRepresentante = "IdRepresentanteLegal2";
				}
				$sql_atualiza_representante = "UPDATE sis_pessoa_juridica SET $campoRepresentante = '$idRepresentante' WHERE Id_PessoaJuridica = '$idPessoaJuridica' "; 	
				$query_atualiza_representante = mysqli_query($con,$sql_atualiza_representante);
				if($query_atualiza_representante)
				{
					gravarLog($sql_atualiza_representante);
					$mensagem = "Representante legal inserido.";
				}
				else
				{
					$mensagem = "Erro ao inserir representante legal.";	
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
				$DataNascimento = exibirDataMysql($_POST['DataNascimento']);
				$Nacionalidade = $_POST['Nacionalidade'];
				$CEP = $_POST['CEP'];
				//$Endereco = $_POST['Endereco'];
				$Numero = $_POST['Numero'];
				$Complemento = $_POST['Complemento'];
				$Bairro = $_POST['Bairro'];
				$Cidade = $_POST['Cidade'];
				$Telefone1 = $_POST['Telefone1'];
				$Telefone2 = $_POST['Telefone2'];
				$Telefone3 = $_POST['Telefone3'];
				$Email = $_POST['Email'];
				$DRT = $_POST['DRT'];
				$Funcao = $_POST['Funcao'];
				$InscricaoINSS = $_POST['InscricaoINSS'];
				$OMB = $_POST['OMB'];
				$Observacao = addslashes($_POST['Observacao']);
				$tipoDocumento = $_POST['tipoDocumento'];
				$Pis = 0;
				$data = date('Y-m-d');
				$idUsuario = $_SESSION['idUsuario'];
				$codBanco = $_POST['codBanco'];
				$agencia = $_POST['agencia'];
				$conta = $_POST['conta'];
				if($DataNascimento == '31/12/1969')
				{
					$mensagem = "Por favor, preencha o campo DATA DE NASCIMENTO!";
				}
				else
				{
					$sql_atualizar_pessoa = "UPDATE sis_pessoa_fisica 
						SET `Nome` = '$Nome',
						`NomeArtistico` = '$NomeArtistico',
						`RG` = '$RG', 
						`CPF` = '$CPF', 
						`CCM` = '$CCM', 
						`DataNascimento` = '$DataNascimento', 
						`Nacionalidade` = '$Nacionalidade', 
						`CEP` = '$CEP', 
						`codBanco` = '$codBanco', 
						`agencia` = '$agencia', 
						`conta` = '$conta', 
						`Numero` = '$Numero', 
						`Complemento` = '$Complemento', 
						`Telefone1` = '$Telefone1', 
						`Telefone2` = '$Telefone2',  
						`Telefone3` = '$Telefone3', 
						`Email` = '$Email', 
						`DRT` = '$DRT', 
						`Funcao` = '$Funcao', 
						`InscricaoINSS` = '$InscricaoINSS', 
						`Pis` = '$Pis', 
						`OMB` = '$OMB', 
						`DataAtualizacao` = '$data', 
						`Observacao` = '$Observacao', 
						`IdUsuario` = '$idUsuario', 
						`tipoDocumento` = '$tipoDocumento' 
						WHERE `Id_PessoaFisica` = '$idPessoaFisica'";	
					if(mysqli_query($con,$sql_atualizar_pessoa))
					{
						gravarLog($sql_atualizar_pessoa);
						$mensagem = "Atualizado com sucesso!";	
					}
					else
					{
						$mensagem = "Erro ao atualizar! Tente novamente.";
					}
				}	
			}
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
				//$IdRepresentanteLegal1 = $_POST['IdRepresentanteLegal1'];
				//$IdRepresentanteLegal2 = $_POST['IdRepresentanteLegal2'];
				$Observacao = $_POST['Observacao'];
				$data = date("Y-m-d");
				$idUsuario = $_SESSION['idUsuario'];
				$codBanco = $_POST['codBanco'];
				$agencia = $_POST['agencia'];
				$conta = $_POST['conta'];
				$sql_atualizar_juridica = "UPDATE `sis_pessoa_juridica` 
					SET `RazaoSocial` = '$RazaoSocial', 
					`CNPJ` = '$CNPJ', 
					`CCM` = '$CCM', 
					`CEP` = '$CEP', 
					`Numero` = '$Numero', 
					`Complemento` = '$Complemento', 
					`Telefone1` = '$Telefone1', 
					`Telefone2` = '$Telefone2', 
					`Telefone3` = '$Telefone3', 
					`Email` = '$Email', 
					`DataAtualizacao` = '$data', 
					`Observacao` = '$Observacao', 
					`codBanco` = '$codBanco', 
					`agencia` = '$agencia', 
					`conta` = '$conta'  
					WHERE `sis_pessoa_juridica`.
					`Id_PessoaJuridica` = '$idJuridica'";
				if(mysqli_query($con,$sql_atualizar_juridica))
				{
					$mensagem = "Atualizado com sucesso!";	
					gravarLog($sql_atualizar_juridica);
				}
				else
				{
					$mensagem = "Erro ao atualizar! Tente novamente.";
				}
			}
			if($_SESSION['idPessoaJuridica'] != NULL)
			{
				$pedido['tipoPessoa'] = 2;
				$pedido['idPessoa'] = $_SESSION['idPessoaJuridica'];	
			}
			else
			{
				$idPedidoContratacao = $_POST['idPedidoContratacao'];
				$pedido = recuperaDados("igsis_pedido_contratacao",$idPedidoContratacao,"idPedidoContratacao");
			}
			switch($pedido['tipoPessoa'])
			{
				case 1:
					$fisica = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
		?>
<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA FÍSICA</h3>
			<?php 
				$cpf_busca = $fisica['CPF'];
				//Localiza no proponente
				$con2 = bancoMysqliProponente();
				$sql2 = $con2->query("SELECT * FROM pessoa_fisica where cpf = '$cpf_busca'");
				$query2 = $sql2->fetch_array(MYSQLI_ASSOC);
				
				If($query2 != '')
				{
				?>
					<div class="col-md-offset-1 col-md-10">
						<div class="col-md-offset-2 col-md-8">
							<form method='POST' action='?perfil=compara_pf&busca=<?php echo $fisica['CPF']; ?>'>
								<input type='hidden' name='edicaoPessoa' value='1'>
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
				<form class="form-horizontal" role="form" onsubmit="return valida()" action="?perfil=contratados&p=edicaoPessoa" method="post">
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
						<div class="col-md-offset-2  col-md-6"><strong>Data de nascimento:</strong><br/>
							<input type="text" class="form-control" id="datepicker01" name="DataNascimento" placeholder="Data de Nascimento" value="<?php echo exibirDataBr($fisica['DataNascimento']); ?>">
						</div>
						<div class="col-md-6"><strong>Nacionalidade:</strong><br/>
							<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade" value="<?php echo $fisica['Nacionalidade']; ?>">
						</div>	
					</div>  
					<div class="form-group">			  
						<div class="col-md-offset-2  col-md-8"><strong>CEP:</strong><br/>
							<input type="text" class="form-control" id="CEP" name="CEP" placeholder="CEP" value="<?php echo $fisica['CEP']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Endereço *:</strong><br/>
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
						<div class="col-md-offset-2 col-md-8"><strong>Complemento *:</strong><br/>
							<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento" value="<?php echo $fisica['Complemento']; ?>">
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
							<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail" value="<?php echo $fisica['Email']; ?>" >
						</div>				  
						<div class=" col-md-6"><strong>Telefone #1 *:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone1" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $fisica['Telefone1']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone #2:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone2" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $fisica['Telefone2']; ?>">
						</div>				  
						<div class="col-md-6"><strong>Telefone #3:</strong><br/>
							<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone3" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $fisica['Telefone3']; ?>" >
						</div>
					</div>	  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>DRT:</strong><br/>
							<input type="text" class="form-control" id="DRT" name="DRT" placeholder="DRT" value="<?php echo $fisica['DRT']; ?>">
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
					<!-- Dados Bancários -->
					<div class="form-group">
					<font color="#FF0000"><strong>Pagamentos parcelados ou de valores acima de R$ 5.000,00 *SOMENTE COM CONTA CORRENTE NO BANCO DO BRASIL*.<br /></strong></font>
					<br />
						<div class="col-md-offset-2 col-md-8"><strong>Banco:</strong><br/>
							<select class="form-control" name="codBanco" id="codBanco">
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
							<textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
						</div>
					</div>  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="cadastrarFisica" value="<?php echo $fisica['Id_PessoaFisica'] ?>" />
							<input type="hidden" name="idPedidoContratacao" value="<?php echo $_POST['idPedidoContratacao'] ?>" />
							<input type="hidden" name="Sucesso" id="Sucesso" />
							<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
 						<form method='POST' action='?perfil=contratados&p=arquivos'>
							<input type='hidden' name='idPessoa' value='<?php echo $fisica['Id_PessoaFisica'] ?>'>
							<input type='hidden' name='tipoPessoa' value='1'>
							<input type="submit" value="Anexar arquivos" class="btn btn-theme btn-lg btn-block">
						</form>
					</div>
				</div>
	  		</div>
	  	</div>	
	</div>
</section>
			<?php
				break;
				case 2:
					$_SESSION['idPessoaJuridica'] = $pedido['idPessoa'];
					$juridica = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
			?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA JURÍDICA</h3>
			<?php 
				$cnpj_busca = $juridica['CNPJ'];
				//Localiza no proponente
				$con2 = bancoMysqliProponente();
				$sql2 = $con2->query("SELECT * FROM pessoa_juridica where cnpj = '$cnpj_busca'");
				$query2 = $sql2->fetch_array(MYSQLI_ASSOC);
				
				If($query2 != '')
				{
				?>
					<div class="col-md-offset-1 col-md-10">
						<div class="col-md-offset-2 col-md-8">
							<form method='POST' action='?perfil=compara_pj&busca=<?php echo $juridica['CNPJ']; ?>'>
								<input type='hidden' name='edicaoPessoa' value='1'>
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
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Representante legal #01:</strong><br/>
						<form class="form-horizontal" role="form"  method="post" action="?perfil=contratados&p=representante&action=edita">
							<input type='text' readonly class='form-control' name='representante01' id='Executante' value="<?php $nome1 = recuperaPessoa($juridica['IdRepresentanteLegal1'],3); echo $nome1['nome']; ?>">  
							<input type="hidden" name="numero" value="1" />
							<input type="hidden" name="idPessoa" value="<?php echo $juridica['IdRepresentanteLegal1'] ?>" /> 
							<input type="hidden" name="idPessoaJuridica" value="<?php echo $juridica['Id_PessoaJuridica'] ?>" />                     
							<input type="submit" class="btn btn-theme btn-med btn-block" value="Abrir Representante legal #01">
						</form>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<br />
					</div>
				</div>
				<div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Representante legal #02:</strong><br/>
					</div>
				</div>  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<form class="form-horizontal" role="form"  method="post" action="?perfil=contratados&p=representante&action=edita">
							<input type="hidden" name="numero" value="2" />
							<input type="hidden" name="idPessoa" value="<?php echo $juridica['IdRepresentanteLegal2'] ?>" />
							<input type="hidden" name="idPessoaJuridica" value="<?php echo $juridica['Id_PessoaJuridica'] ?>" />
							<input type='text' readonly class='form-control' name='representante02' id='Executante' value="<?php $nome2 = recuperaPessoa($juridica['IdRepresentanteLegal2'],3); echo $nome2['nome']; ?>">              
							<input type="submit" class="btn btn-theme btn-med btn-block" value="Abrir Representante legal #02">
						</form>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<br />
							<br />	
						</div>
					</div>
					<form class="form-horizontal" role="form" onsubmit="return valida()" action="?perfil=contratados&p=edicaoPessoa" method="post">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Razão Social:</strong><br/>
								<input type="text" class="form-control" id="RazaoSocial" name="RazaoSocial" placeholder="RazaoSocial" value="<?php echo $juridica['RazaoSocial']; ?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>CNPJ:</strong><br/>
								<input type="text" class="form-control" id="CNPJ" name="CNPJ" readonly placeholder="CNPJ" value="<?php echo $juridica['CNPJ']; ?>" >
							</div>
							<div class="col-md-6"><strong>CCM:</strong><br/>
								<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM" value="<?php echo $juridica['CCM']; ?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>CEP *:</strong><br/>
								<input type="text" class="form-control" id="CEP" name="CEP" placeholder="CEP" value="<?php echo $juridica['CEP']; ?>">
							</div>				  
							<div class=" col-md-6"><strong>Estado *:</strong><br/>
								<input type="text" class="form-control" id="Estado" name="Estado" placeholder="Estado">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Endereço *:</strong><br/>
								<input type="text" class="form-control" id="Endereco" name="Endereco" placeholder="Endereço">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Número *:</strong><br/>
								<input type="text" class="form-control" id="Numero" name="Numero" placeholder="Numero" value="<?php echo $juridica['Numero']; ?>">
							</div>				  
							<div class=" col-md-6"><strong>Complemento:</strong><br/>
								<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento" value="<?php echo $juridica['Complemento']; ?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Bairro *:</strong><br/>
								<input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
							</div>				  
							<div class=" col-md-6"><strong>Cidade *:</strong><br/>
								<input type="text" class="form-control" id="Cidade" name="Cidade" placeholder="Cidade">
							</div>
						</div>  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Telefone:</strong><br/>
								<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone1" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $juridica['Telefone1']; ?>">
							</div>				  
							<div class=" col-md-6"><strong>Telefone:</strong><br/>
								<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone2" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $juridica['Telefone2']; ?>" >
							</div>
						</div>  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Telefone:</strong><br/>
								<input type="text" class="form-control" id="telefone" onkeyup="mascara( this, mtel );" maxlength="15" name="Telefone3" placeholder="Exemplo: (11) 98765-4321" value="<?php echo $juridica['Telefone3']; ?>">
							</div>				  
							<div class=" col-md-6"><strong>E-mail:</strong><br/>
								<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail" value="<?php echo $juridica['Email']; ?>">
							</div>
						</div>  
						<!-- Dados Bancários -->
						<div class="form-group">
						<font color="#FF0000"><strong>Pagamentos parcelados ou de valores acima de R$ 5.000,00 *SOMENTE COM CONTA CORRENTE NO BANCO DO BRASIL*.</strong></font><br />
						<br />
							<div class="col-md-offset-2 col-md-8"><strong>Banco:</strong><br/>
								<select class="form-control" name="codBanco" id="codBanco">
									<option value='32'>Banco do Brasil S.A.</option>
									<?php geraOpcao("igsis_bancos",$juridica['codBanco'],""); ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6"><strong>Agência</strong><br/>
								<input type="text" class="form-control" id="agencia" name="agencia" placeholder="" value="<?php echo $juridica['agencia']; ?>">
							</div>				  
							<div class=" col-md-6"><strong>Conta:</strong><br/>
								<input type="text" class="form-control" id="conta" name="conta" placeholder="" value="<?php echo $juridica['conta']; ?>">
							</div>
						</div>                   
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<br />
							</div>
							<br />
						</div>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Observações:</strong><br/>
								<textarea name="Observacao" class="form-control" rows="10" placeholder=""><?php echo $juridica['Observacao']; ?></textarea>
							</div>
						</div>	  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<br />
							</div>
							<br />
						</div>
						<!-- Botão Gravar -->	
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="editaJuridica" value="<?php echo $juridica['Id_PessoaJuridica'] ?>" />
								<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
							</div>
						</div>
					</form>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<br />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<form method='POST' action='?perfil=contratados&p=arquivos'>
								<input type='hidden' name='idPessoa' value='<?php echo $juridica['Id_PessoaJuridica'] ?>'>
								<input type='hidden' name='tipoPessoa' value='2'>
								<input type="submit" value="Anexar arquivos" class="btn btn-theme btn-lg btn-block">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
			<?php
				break;
				case 3: ?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE REPRESENTANTE LEGAL</h3>
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoPessoa" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="text" class="form-control" id="RepresentanteLegal" name="RepresentanteLegal" placeholder="Representante Legal">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6">
							<input type="text" class="form-control" id="RG" name="RG" placeholder="RG">
						</div>
						<div class="col-md-6">
							<input type="text" class="form-control" id="cpf" name="CPF" readonly placeholder="CPF">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade">
						</div>
					</div>
					<!-- Botão Gravar -->	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="cadastrarRepresentante" value="1" />
							<input type="submit" name="enviar" value="CADASTRAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
	  		</div>
	  	</div>	
	</div>
</section>
			<?php
				break;
			}
		break;
		case "arquivos":
			$idPessoa = $_REQUEST['idPessoa'];
			$tipoPessoa = $_REQUEST['tipoPessoa'];
			$mensagem = $idPessoa." - ".$tipoPessoa;
			if(isset($_POST["enviar"]))
			{
				$sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '$tipoPessoa' AND publicado = '1'";
				$query_arquivos = mysqli_query($con,$sql_arquivos);
				while($arq = mysqli_fetch_array($query_arquivos))
				{ 
					$y = $arq['idTipoDoc'];
					$x = $arq['sigla'];
					$nome_arquivo = $_FILES['arquivo']['name'][$x];
					if($nome_arquivo != "")
					{
						$nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
						//$ext = strtolower(substr($nome_arquivo[$i],-4)); //Pegando extensão do arquivo
						$new_name = date("YmdHis")."_".semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
						$hoje = date("Y-m-d H:i:s");
						$dir = '../uploadsdocs/'; //Diretório para uploads
						if(move_uploaded_file($nome_temporario, $dir.$new_name))
						{
							$sql_insere_arquivo = "INSERT INTO `igsis_arquivos_pessoa` 
								(`idArquivosPessoa`, 
								`idTipoPessoa`, 
								`idPessoa`, 
								`arquivo`, 
								`dataEnvio`, 
								`publicado`, 
								`tipo`) 
								VALUES (NULL, 
								'$tipoPessoa', 
								'$idPessoa', 
								'$new_name', 
								'$hoje', 
								'1', 
								'$y'); ";
							$query = mysqli_query($con,$sql_insere_arquivo);
							if($query)
							{
						 		gravarLog($sql_insere_arquivo);
								$mensagem = "Arquivo recebido com sucesso";
							}
							else
							{
								$mensagem = "Erro ao gravar no banco";
							}
						}
						else
						{
							$mensagem = "Erro no upload";   
						}
					}	
				}
			}
			if(isset($_POST['apagar']))
			{
				$idArquivo = $_POST['apagar'];
				$sql_apagar_arquivo = "UPDATE igsis_arquivos_pessoa SET publicado = 0 WHERE idArquivosPessoa = '$idArquivo'";
				if(mysqli_query($con,$sql_apagar_arquivo))
				{
					$arq = recuperaDados("igsis_arquivos_pessoa",$idArquivo,"idArquivosPessoa");
					$mensagem =	"Arquivo ".$arq['arquivo']."apagado com sucesso!";
					gravarLog($sql_apagar_arquivo);
				}
				else
				{
					$mensagem = "Erro ao apagar o arquivo. Tente novamente!";
				}
			}
			$campo = recuperaPessoa($_REQUEST['idPessoa'],$_REQUEST['tipoPessoa']); 
			?>

<section id="list_items" class="home-section bg-white">
	<div class="container">
      	<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Arquivos anexados</h2>
					<h5>Se na lista abaixo, o seu arquivo começar com "http://", por favor, clique, grave em seu computador, faça o upload novamente e apague a ocorrência citada.</h5>
				</div>
				<div class="table-responsive list_info">
					<?php listaArquivosPessoa($_POST['idPessoa'],$_POST['tipoPessoa']); ?>
				</div>
			</div>
		</div>  
	</div>
</section>

<section id="enviar" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2><?php echo $campo["nome"] ?>  </h2>
					<p><?php echo $campo["tipo"] ?></p>
					<h3>Envio de Arquivos</h3>
                    <p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
					<p>Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 60MB.</p>
					<br />
					<div class = "center">
						<form method="POST" action="?perfil=contratados&p=arquivos&idPessoa=<?php echo $_REQUEST['idPessoa']; ?>&tipoPessoa=<?php echo $_REQUEST['tipoPessoa']; ?>" enctype="multipart/form-data">
							<table>
								<tr>
									<td width="50%"><td>
									<?php $evento = recuperaEvento($_SESSION['idEvento']); ?>
								</tr>
		
		<?php
			if ($evento['ig_tipo_evento_idTipoEvento'] == 4 OR $evento['ig_tipo_evento_idTipoEvento'] == 5 OR $evento['ig_tipo_evento_idTipoEvento'] == 28 OR $evento['ig_tipo_evento_idTipoEvento'] == 34) {
		?>
		
		<?php 
			$sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '$tipoPessoa' AND publicado = '1' AND oficina = '1'";
			$query_arquivos = mysqli_query($con,$sql_arquivos);
			while($arq = mysqli_fetch_array($query_arquivos))
			{
		?>
								<tr>
									<td><label><?php echo $arq['documento']?></label></td><td><input type='file' name='arquivo[<?php echo $arq['sigla']; ?>]'></td>
								</tr>
		<?php
			}
		?>
		
		<?php
			} else {
		?>	
		
		<?php 
			$sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '$tipoPessoa' AND publicado = '1' AND teatro = '1' AND musica = '1' ";
			$query_arquivos = mysqli_query($con,$sql_arquivos);
			while($arq = mysqli_fetch_array($query_arquivos))
			{
		?>
								<tr>
									<td><label><?php echo $arq['documento']?></label></td><td><input type='file' name='arquivo[<?php echo $arq['sigla']; ?>]'></td>
								</tr>
		<?php
			}
		?>
		
		<?php
			}
		?>	
			</table>
							<br>
							<input type="hidden" name="idPessoa" value="<?php echo $_REQUEST['idPessoa']; ?>"  />
							<input type="hidden" name="tipoPessoa" value="<?php echo $_REQUEST['tipoPessoa']; ?>"  />
							<input type="hidden" name="enviar" value="1"  />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value='Enviar'>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
	<?php
		break;
		case "arqped":
			$idPedido = $_REQUEST['idPedido'];
			include "../funcoes/funcoesSiscontrat.php";
			$pedido = siscontrat($idPedido);
			if(isset($_POST["enviar"]))
			{
				$sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '3' AND publicado = '1'";
				$query_arquivos = mysqli_query($con,$sql_arquivos);
				while($arq = mysqli_fetch_array($query_arquivos))
				{ 
					$y = $arq['idTipoDoc'];
					$x = $arq['sigla'];
					$nome_arquivo = $_FILES['arquivo']['name'][$x];
					if($nome_arquivo != "")
					{
						$nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
						//$ext = strtolower(substr($nome_arquivo[$i],-4)); //Pegando extensão do arquivo
						$new_name = date("YmdHis")."_".semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
						$hoje = date("Y-m-d H:i:s");
						$dir = '../uploadsdocs/'; //Diretório para uploads  
						if(move_uploaded_file($nome_temporario, $dir.$new_name))
						{	  
							$sql_insere_arquivo = "INSERT INTO `igsis_arquivos_pedidos` 
								(`idArquivosPedidos`, 
								`idPedido`, 
								`arquivo`, 
								`data`, 
								`publicado`, 
								`tipo`) 
								VALUES (NULL, 
								'$idPedido', 
								'$new_name', 
								'$hoje', 
								'1', 
								'$y')";
							$query = mysqli_query($con,$sql_insere_arquivo);
							if($query)
							{
 								gravarLog($sql_insere_arquivo);
								$mensagem = "Arquivo recebido com sucesso";
							}
							else
							{
								$mensagem = "Erro ao gravar no banco";
							}
						}
						else
						{
							$mensagem = "Erro no upload";
						}
					}
				}
			}
			if(isset($_POST['apagar']))
			{
				$idArquivo = $_POST['apagar'];
				$sql_apagar_arquivo = "UPDATE igsis_arquivos_pedidos SET publicado = 0 WHERE idArquivosPedidos = '$idArquivo'";
				if(mysqli_query($con,$sql_apagar_arquivo))
				{
					$arq = recuperaDados("igsis_arquivos_pedidos",$idArquivo,"idArquivosPedidos");
					$mensagem =	"Arquivo ".$arq['arquivo']."apagado com sucesso!";
					gravarLog($sql_apagar_arquivo);
				}
				else
				{
					$mensagem = "Erro ao apagar o arquivo. Tente novamente!";
				}
			}
	?>
	
	<section id="list_items" class="home-section bg-white">
	<div class="container">
      	<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Arquivos anexados</h2>
					<h5>Se na lista abaixo, o seu arquivo começar com "http://", por favor, clique, grave em seu computador, faça o upload novamente e apague a ocorrência citada.</h5>
				</div>
				<div class="table-responsive list_info">
					<?php listaArquivosPedidoEvento($idPedido); ?>
				</div>
			</div>
		</div>  
	</div>
</section>
<section id="enviar" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2><?php echo $pedido["Objeto"] ?>  </h2>
					<h3>Envio de Arquivos</h3>
                    <p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
					<p>Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 60MB.</p>
					<br />
					<div class = "center">
						<form method="POST" action="?perfil=contratados&p=arqped" enctype="multipart/form-data">
							<table>
								<tr>
									<td width="50%"><td>
								</tr>
		<?php 
			$sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '3' AND publicado = '1'";
			$query_arquivos = mysqli_query($con,$sql_arquivos);
			while($arq = mysqli_fetch_array($query_arquivos))
			{
		?>
								<tr>
									<td><label><?php echo $arq['documento']?></label></td><td><input type='file' name='arquivo[<?php echo $arq['sigla']; ?>]'></td>
								</tr>
		<?php
			}
		?>
							</table>
							<br>
							<input type="hidden" name="idPedido" value="<?php echo $idPedido ?>"  />
							<input type="hidden" name="enviar" value="1"  />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value='Enviar'>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php 
break;
case "edicaoParecer":

include "../funcoes/funcoesSiscontrat.php";

$id_ped = $_SESSION['idPedido'];
$artista = $_GET['artista'];

if(isset($_POST['insereParecer']))
{
	$id_ped = $_SESSION['idPedido'];				
	$topico1 = $_POST['topico1'];
	$topico2 = $_POST['topico2'];
	$topico3 = $_POST['topico3'];
	$topico4 = $_POST['topico4'];
			
	$sql_insere_parecer = "INSERT INTO igsis_parecer_artistico(idPedidoContratacao, topico1, topico2, topico3, topico4) VALUES (:id_ped, :topico1, :topico2, :topico3, :topico4)";

	$stmt = $conn->prepare($sql_insere_parecer);

	$stmt->bindParam(':topico1', $topico1);
	$stmt->bindParam(':topico2', $topico2);
	$stmt->bindParam(':topico3', $topico3);
	$stmt->bindParam(':topico4', $topico4);
 	$stmt->bindParam(':id_ped', $id_ped);

	if ($stmt->execute()) 
	{
		echo 'Inserido com sucesso!';
		gravarLog($sql_insere_parecer);
		$mensagem = "Atualizado com sucesso!";
		$parecer = recuperaDados("igsis_parecer_artistico",$id_ped,"idPedidoContratacao");
		$topicos = $parecer['topico1']."<br />".$parecer['topico2']."<br />".$parecer['topico3']."<br />".$parecer['topico4'];

		$sql_pedido = "UPDATE igsis_pedido_contratacao 
		SET parecerArtistico = :topicos WHERE idPedidoContratacao = :id_ped";

		$stmt = $conn->prepare($sql_pedido);

		$stmt->bindParam(':topicos', $topicos);
		$stmt->bindParam(':id_ped', $id_ped);

		if ($stmt->execute())
		{
			echo $id_ped;
			$mensagem = "Atualizado com sucesso!";
			gravarLog($sql_pedido);
		}
		else
		{
			$mensagem = "Erro ao atualizar!";
		}
	}
	else
	{
		$mensagem = "Erro ao atualizar! Tente novamente.";
	}
}
if(isset($_POST['editaParecer']))
{
	$id_ped = $_SESSION['idPedido'];				
	$topico1 = $_POST['topico1'];
	$topico2 = $_POST['topico2'];
	$topico3 = $_POST['topico3'];
	$topico4 = $_POST['topico4'];
				
	$sql_edita_parecer = "UPDATE igsis_parecer_artistico SET topico1 = :topico1, topico2 = :topico2, topico3 = :topico3, topico4 = :topico4 WHERE idPedidoContratacao = :id_ped";
	
	$stmt = $conn->prepare($sql_edita_parecer);
	$stmt->bindParam(":topico1", $topico1);
	$stmt->bindParam(":topico2", $topico2);
	$stmt->bindParam(":topico3", $topico3);
	$stmt->bindParam(":topico4", $topico4);
 	$stmt->bindParam(":id_ped", $id_ped);

 	if ($stmt->execute()) 
 	{
 		gravarLog($sql_edita_parecer);
		$mensagem = "Atualizado com sucesso!";
		$parecer = recuperaDados("igsis_parecer_artistico",$id_ped,"idPedidoContratacao");
		$topicos = $parecer['topico1']."<br />".$parecer['topico2']."<br />".$parecer['topico3']."<br />".$parecer['topico4'];

		$sql_pedido = "UPDATE igsis_pedido_contratacao SET parecerArtistico = :topicos WHERE idPedidoContratacao = :id_ped";

		$stmt = $conn->prepare($sql_pedido);
		$stmt->bindParam(':topicos', $topicos);
		$stmt->bindParam(':id_ped', $id_ped);

 		if ($stmt->execute()) 
 		{
	 		gravarLog($sql_pedido);
			$mensagem = "Atualizado com sucesso!";
			echo $id_ped;
		}
		else
		{
			$mensagem = "Erro ao atualizar!";
		}	
 	}
	else
	{
		$mensagem = "Erro ao atualizar! Tente novamente.";
	}
}
$parecer = recuperaDados("igsis_parecer_artistico",$id_ped,"idPedidoContratacao");
			
$pedido = siscontrat($id_ped);
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];

if ($pedido['TipoPessoa'] == 2)
{
	$pj = siscontratDocs($pedido['IdProponente'],2);
	$ex = siscontratDocs($pedido['IdExecutante'],1);
	$rep01 = siscontratDocs($pj['Representante01'],3);
	$rep02 = siscontratDocs($pj['Representante02'],3);
	$t1 = "Esta comissão ratifica o pedido de contratação de ".$ex['Nome']." por intermédio da ".$pj['Nome'].", para apresentação artística no evento “".$Objeto."”, que ocorrerá ".$Periodo." no valor de R$ ".$pedido['ValorGlobal']." (".valorPorExtenso($pedido['ValorGlobal']).").";
}
else
{
	$pf = siscontratDocs($pedido['IdProponente'],1);
	$t1 = "Esta comissão ratifica o pedido de contratação de ".$pf['Nome'].", para apresentação artística no evento “".$Objeto."”, que ocorrerá ".$Periodo." no valor de R$ ".$pedido['ValorGlobal']." (".valorPorExtenso($pedido['ValorGlobal']).").";
}

?>
<script>
function mostrarResultado(box,num_max,campospan){
	var contagem_carac = box.length;
	if (contagem_carac != 0){
		document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
		if (contagem_carac == 1){
			document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
		}
		if (contagem_carac < num_max){
			document.getElementById(campospan).innerHTML = "<font color='red'>Você não inseriu a quantidade mínima de caracteres!</font>";
		}
	}else{
		document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado...";
	}
}
function contarCaracteres(box,valor,campospan){
	var conta = valor - box.length;
	document.getElementById(campospan).innerHTML = "Faltam " + conta + " caracteres";
	if(box.length >= valor){
		document.getElementById(campospan).innerHTML = "Quantidade mínima de caracteres atingida!";
	}	
}
function mostrarResultado3(box,num_max,campospan){
	var contagem_carac = box.length;
	if (contagem_carac != 0){
		document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
		if (contagem_carac == 1){
			document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
		}
		if (contagem_carac < num_max){
			document.getElementById(campospan).innerHTML = "<font color='red'>Você não inseriu a quantidade mínima de caracteres!</font>";
		}
	}else{
		document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado...";
	}
}
function contarCaracteres3(box,valor,campospan){
	var conta = valor - box.length;
	document.getElementById(campospan).innerHTML = "Faltam " + conta + " caracteres";
	if(box.length >= valor){
		document.getElementById(campospan).innerHTML = "Quantidade mínima de caracteres atingida!";
	}	
}
</script>


 	
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h2>Parecer de Pessoa 
			<?php 
				if ($pedido['TipoPessoa'] == 2)
				{
					echo "Jurídica";
				} 
				else 
				{
					echo "Física";
				} ?> 
			- Artista <?php echo $artista; ?></h2>
            <p><?php if(isset($mensagem)){echo $mensagem;} ?></p>			
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">    
                <div class="form-group">
				<form name="form" class="form-horizontal" role="form" onsubmit="return valida()" action="#" method="post">
					<hr/>
					<h6>1º Tópico</h6>					
					<label>Neste tópico deve conter o posicionamento da comissão e as informações gerais do evento (nome do artista, evento, datas, valor, tempo, etc).</label>
					<p align="justify"><font color="gray"><strong><i>Texto de exemplo:</strong><br/>Esta comissão ratifica o pedido de contratação de Nome do artista ou grupo (nome artístico) por intermédio da Nome da empresa representante, para apresentação artística no evento “Nome do evento ou atividade especial”, que ocorrerá no dia datas ou período quando for temporada no valor de R$ XXX (valor por extenso).</font></i></p>					
					<?php 
						if($parecer['topico1'] == NULL)
						{
					?>
							<textarea name="topico1" id="topico1" class="form-control" rows="3"><?php echo $t1; ?></textarea>
					<?php					
						}
						else
						{
					?>		
							<textarea name="topico1" id="topico1" class="form-control" rows="3"><?php echo $parecer["topico1"]; ?></textarea>
					<?php 		
						}
					?>
					
					<hr/>
					
					<h6>2º Tópico (mínimo de 500 caracteres)</h6> 
					<label>Neste tópico deve-se falar sobre o evento ou atividade especial da qual o artista/grupo irá participar. Se for programação geral do equipamento, sem estar vinculada a nenhum evento ou projeto específico, falar sobre o equipamento, histórico, tipo de atividades desenvolvidas, etc, demonstrando a importância desse tipo de programação dentro do equipamento.</label>
					<p align="justify"><font color="gray"><strong><i>Texto de exemplo:</strong><br/>Em sua nona edição, o projeto Virada Cultural, da Secretaria Municipal de Cultura, consolida a Cidade de São Paulo como o principal pólo gerador de arte e cultura do País proporcionando, não só aos munícipes como também aos visitantes de outros Estados e de outras nacionalidades, o acesso gratuito ao que há de melhor na produção cultural atual existente no Brasil e no exterior. A Virada Cultural da Cidade de São Paulo, através de apresentações artísticas em logradouros públicos e equipamentos oficiais dentre outros espaços culturais conquistou, nesses nove anos de existência, o reconhecimento da mídia e do público, solidificando-se como um dos eventos nacionais mais conhecidos e divulgados do Brasil, assim como no exterior.</font></i></p>					
					<textarea cols="45" name="topico2" id="topico2" rows="10" class="form-control" onkeyup="mostrarResultado(this.value,500,'spcontando');contarCaracteres(this.value,500,'sprestante')"><?php echo $parecer["topico2"]; ?></textarea>
					<span id="spcontando" style="font-family:Georgia;">Comece a digitar para ativar a contagem de caracteres.</span><br />
					<span id="sprestante" style="font-family:Georgia;"></span>
								
					<hr/>
					
					<h6>3º Tópico (mínimo de 700 caracteres)</h6> 
					<?php
						if ($pedido['TipoPessoa'] == 2)
						{
					?>
							<label>Neste tópico deve-se falar sobre o currículo/biografia do artista ou grupo (na 3ª pessoa), escrever um breve release. Deve ficar claro que o artista contribuirá positivamente para a programação e porque essa é a melhor escolha de artista para o evento.</label>
					<?php		
						}
						else
						{
					?>
							<label>Neste tópico deve-se falar sobre o currículo/biografia do artista ou grupo (na 3ª pessoa), escrever um breve release. Deve ficar claro que o artista contribuirá positivamente para a programação e porque essa é a melhor escolha de artista para o evento.</label>
					<?php
						}		
					?>
					
					<textarea cols="45" name="topico3" id="topico3" rows="10" class="form-control" onkeyup="mostrarResultado3(this.value,700,'spcontando3');contarCaracteres3(this.value,700,'sprestante3')"><?php echo $parecer["topico3"]; ?></textarea>
					<span id="spcontando3" style="font-family:Georgia;">Comece a digitar para ativar a contagem de caracteres.</span><br />
					<span id="sprestante3" style="font-family:Georgia;"></span>
					
					<hr/>
					
					<h6>4º Tópico</h6> 
					<?php
						if ($artista == "Local")
						{
					?>
							<label>Neste tópico deve-se falar que o contratado tem o necessário para a contratação e que as exigências legais foram observadas, apresentando a comprovação documental (mínimo três comprovações diferentes) do valor proposto para o cachê. Encerrar com a manifestação favorável da comissão quanto à contratação.</label>
							<p align="justify"><font color="gray"><strong><i>Texto de exemplo:</strong><br/>Os artistas reúnem as condições necessárias para integrar a programação Secretaria Municipal de Cultura, possuem consagração, reconhecimento e aceitação do público, conforme documentos juntados ao presente, SEI ( link do clipping, curriculo e release ). Ainda, avaliamos que o cachê proposto encontra-se compatível com os valores praticados no mercado e pagos por esta Secretaria, conforme pode ser comprovado pelos processos/notas fiscais  ( link de 3 números de processos SEI que constem notas fiscais ), em cumprimento ao Acórdão TC 2.393/15-37.
							<br/>
							Sendo os serviços indubitavelmente de natureza artística, manifestamo-nos favoravelmente à contratação, endossando a proposta inicial.</font></i></p>
					<?php		
						}
						else
						{
					?>
							<label>Neste tópico deve-se falar que o contratado tem o necessário para a contratação e que as exigências legais foram observadas, apresentando a comprovação documental (mínimo três notas fiscais de eventos que não foram contratados pela prefeitura) do valor proposto para o cachê. Encerrar com a manifestação favorável da comissão quanto à contratação.</label>
							<p align="justify"><font color="gray"><strong><i>Texto de exemplo:</strong><br/>O espetáculo é composto por profissionais consagrados pelo público e pela crítica especializada, estando o cachê proposto de acordo com os valores praticados no mercado, conforme pode ser comprovado pelos documentos x, y e z, em cumprimento ao Acórdão TCM 2.393/15-37.
							<br/>
							Sendo os serviços indubitavelmente de natureza artística, manifestamo-nos favoravelmente à contratação, endossando a proposta inicial.</font></i></p>
					<?php
						}		
					?>					
					<textarea name="topico4" class="form-control" rows="10"><?php echo $parecer["topico4"]; ?></textarea>
											
				</div>
			<?php
				if($parecer == NULL)
				{
			?>	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="insereParecer" value="<?php echo $parecer['idParecer'] ?>" />
						<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
					</div>
				</div>
			<?php	
				}
				else
				{
			?>		
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="editaParecer" value="<?php echo $id_ped ?>" />
						<input type="submit" value="EDITAR" class="btn btn-theme btn-lg btn-block">
					</div>
				</div>
			<?php			
				}	
			?>
				</form>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">&nbsp;</div>
				</div>	
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<form class="form-horizontal" role="form" action="?perfil=contratados&p=edicaoPedido" method="post">
						<input type="hidden" name="numero" value="<?php echo $_SESSION['numero'] ?>" />
						<input type="submit" value="Voltar à edição do Pedido" class="btn btn-theme btn-lg btn-block">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
	<?php 
		break;
		case 'erro_pf':
	?>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h4><font color='red'>CPF inválido! por favor, insira o número correto!</font></h4> 
							<h4><font color='red'>Redirecionando...</font></h4>
							<p></p>
						</div>
					</div>
				</div>			
			</div>
		</section>
	<?php 
		echo "<meta HTTP-EQUIV='refresh' CONTENT='3.5;URL=?perfil=contratados&p=fisica'>"; 
		break;
		case 'erro_pj':
	?>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h4><font color='red'>CNPJ inválido! por favor, insira o número correto!</font></h4> 
							<h4><font color='red'>Redirecionando...</font></h4>
							<p></p>
						</div>
					</div>
				</div>			
			</div>
		</section>
	<?php 
		echo "<meta HTTP-EQUIV='refresh' CONTENT='3.5;URL=?perfil=contratados&p=juridica'>"; 
		break;
		case 'erro_representante':
	?>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h4><font color='red'>CPF inválido! por favor, insira o número correto!</font></h4> 
							<h4><font color='red'>Redirecionando...</font></h4>
							<p></p>
						</div>
					</div>
				</div>			
			</div>
		</section>
	<?php 
		echo "<meta HTTP-EQUIV='refresh' CONTENT='3.5;URL=?perfil=contratados&p=representante&action=edita'>"; 
		break;
		case 'erro_executante':
	?>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h4><font color='red'>CPF inválido! por favor, insira o número correto!</font></h4> 
							<h4><font color='red'>Redirecionando...</font></h4>
							<p></p>
						</div>
					</div>
				</div>			
			</div>
		</section>
	<?php 
		echo "<meta HTTP-EQUIV='refresh' CONTENT='3.5;URL=?perfil=contratados&p=edicaoExecutante&id_pf='>"; 
		break;
		case 'erro_edicao_pj':
		$id_ped = $_GET['id_ped'];
	?>
		<section id="list_items" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h4><font color='red'>CNPJ inválido! por favor, insira o número correto!</font></h4> 
							<h4><font color='red'>Redirecionando...</font></h4>
							<p></p>
						</div>
					</div>
				</div>			
			</div>
		</section>
	<?php 
		echo "<meta HTTP-EQUIV='refresh' CONTENT='3.5;URL=?perfil=contratados&p=edicaoProponente&id_ped=".$id_ped."'>"; 
		break;
	} //fim da switch
	?>