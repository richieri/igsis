<h1>Migração da base Google Forms para Virada</h1>

<?php 
echo "<p>carregando as funcoes...</p>";
include "../funcoes/funcoesConecta.php";
include "../funcoes/funcoesGerais.php";
$con = bancoMysqli();


// Criando a tabela igsis_virada para guardar os eventos migrados
echo "<p>verificando se existe a tabela 'igis_virada'...</p>";
$table = 'igsis_virada';
$result = mysqli_query($con,"SHOW TABLES LIKE '$table'");
$tableExists = mysqli_num_rows($result);
if($tableExists == 0){
	echo "<p>criando a tabela 'igsis_virada'...</p>";
	$sql = "CREATE TABLE IF NOT EXISTS igsis_virada (
			id INT(5) AUTO_INCREMENT PRIMARY KEY,
			data VARCHAR(20) NOT NULL,
			idEvento INT(11) NOT NULL
			)ENGINE=MyISAM";
	$query = mysqli_query($con,$sql);
	if($query){
		echo "<p>Tabela igsis_virada criada</p>";	
	}else{
		echo "<p>Erro ao criar a tabela igsis_virada</p>";	
		
	}

}else{
	echo "<p>A tabela igsis_virada já existe</p>";	

}

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
	$sql_pk = "SELECT * FROM googleforms_evento WHERE dataHora NOT IN(SELECT data FROM igsis_virada)"; //seleciona todos pks que não existem na tabela igsis_virada
	$query_pk = mysqli_query($con,$sql_pk);
	$n = mysqli_num_rows($query_pk);
	echo "<p>Foram encontrados $n registros para serem importados</p>";
	
	if($n > 0){ // se existirem registros não importados
		echo "<p>Importando os registros</p>";
		while($x = mysqli_fetch_array($query_pk)){
			$dataHora = $x['dataHora'];	
			$sql_insere_pk = "INSERT INTO `igsis_virada` (`id`, `data`, `idEvento`) VALUES (NULL, '$dataHora', '')";
			$query_insere_pk = mysqli_query($con,$sql_insere_pk);
			if($query_insere_pk){
				echo "Chave $dataHora inserida - ";
				// criar evento
				$sql_insere_evento = "INSERT INTO `ig_evento` (idEvento) VALUES (NULL)";
				$query_insere_evento = mysqli_query($con,$sql_insere_evento);
				if($query_insere_evento){
					$id = mysqli_insert_id($con);
					echo "Evento $id criado - ";
					// atualiza igsis_virada
					$sql_update_pk = "UPDATE igsis_virada SET idEvento = '$id' WHERE data = '$dataHora'";
					$query_update_pk = mysqli_query($con,$sql_update_pk);
					if($query_update_pk){
						echo " relacionamento criado. <br />";
						// criar pedido de contratação
						$sql_insert_pedido = "INSERT INTO `igsis_pedido_contratacao` (`idPedidoContratacao`, `idEvento`) VALUES (NULL, '$id')";
						$query_insert_pedido = mysqli_query($con,$sql_insert_pedido);
						if($query_insert_pedido){
							echo "pedido com evento $id criado<br />";
							
							// Blocão da importação
							$responsavelContratos = $x['responsavelContratos'];
							$fiscal = $x['fiscal'];
							$suplente = $x['suplente'];
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
							$classificacao = $x['classificacao'];
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
							$estadocivil2 = $x['estadocivil2'];
							$rg2 = $x['rg2'];
							$cpf2 = $x['cpf2'];
							$fichaEquipe = $x['fichaEquipe'];
							$banco = $x['banco'];
							$agencia = $x['agencia'];
							$conta = $x['conta'];

							// Fim do blocão da importação
							
						}else{
							echo "erro ao inserir pedido<br />";
							
						}
						
						
						
						
						
						
						
						
						
						
						
						
						
					}else{
						echo " erro ao criar relacionamento. <br />";
					}
				}else{
					echo " erro ao criar evento<br />";
				}		
			}else{
				echo "Erro ao gerar nova chave.<br />";
				
			}
		}
	}
	
	
	
	
	
	
}// if da tabela googleform

?>
