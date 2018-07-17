<?php

$con1 = bancoMysqli();
$con2 = bancoMysqliProponente();

$idInstituicao = $_SESSION['idInstituicao'];
$idUsuario = $_SESSION['idUsuario'];

$idCapac = $_POST['idCapac'];

//Recupera o evento
$sqlEvento = $con2->query("SELECT * FROM evento where id = '$idCapac'");
$eventoC = $sqlEvento->fetch_array(MYSQLI_ASSOC);

$idTipoEvento = $eventoC['idTipoEvento'];
$nomeEvento = addslashes($eventoC['nomeEvento']);
$nomeGrupo = addslashes($eventoC['nomeGrupo']);
$fichaTecnica = addslashes($eventoC['fichaTecnica']);
$idFaixaEtaria = $eventoC['idFaixaEtaria'];
$sinopse = addslashes($eventoC['sinopse']);
$releaseCom = addslashes($eventoC['releaseCom']);
$link = addslashes($eventoC['link']);
$idProdutor = $eventoC['idProdutor'];
$idTipoPessoa = $eventoC['idTipoPessoa'];
$idPj = $eventoC['idPj'];
$idPf = $eventoC['idPf'];


//Recupera produtor
$sqlProdutor = $con2->query("SELECT * FROM produtor where id = '$idProdutor'");
$produtorC = $sqlProdutor->fetch_array(MYSQLI_ASSOC);

$nomeProdutor = addslashes($produtorC['nome']);
$emailProdutor = $produtorC['email'];
$telefone1Produtor = $produtorC['telefone1'];
$telefone2Produtor = $produtorC['telefone2'];

if($eventoC['contratacao'] != 3)
{

	if($idTipoPessoa == 2)
	{
		//Recupera pj
		$sqlPj = $con2->query("SELECT * FROM pessoa_juridica where id = '$idPj'");
		$pj = $sqlPj->fetch_array(MYSQLI_ASSOC);

		$razaoSocial = $pj['razaoSocial'];
		$cnpj = $pj['cnpj'];
		$ccmPj = $pj["ccm"];
		$cepPj = $pj["cep"];
		$numeroPj = $pj["numero"];
		$complementoPj = $pj["complemento"];
		$telefone1Pj = $pj["telefone1"];
		$telefone2Pj = $pj["telefone2"];
		$telefone3Pj = $pj["telefone3"];
		$emailPj = $pj["email"];
		$idRepresentanteLegal1 = $pj["idRepresentanteLegal1"];
		$idRepresentanteLegal2 = $pj["idRepresentanteLegal2"];
		$dataAtualizacaoPj = $pj["dataAtualizacao"];
		$codigoBancoPj = $pj["codigoBanco"];
		$agenciaPj = $pj["agencia"];
		$contaPj = $pj["conta"];

		//Recupera representante 1
		$sqlRep1 = $con2->query("SELECT * FROM representante_legal where id = '$idRepresentanteLegal1'");
		$rep1 = $sqlRep1->fetch_array(MYSQLI_ASSOC);

		$nomeRep1 = addslashes($rep1['nome']);
		$rgRep1 = $rep1['rg'];
		$cpfRep1 = $rep1['cpf'];
		$nacionalidadeRep1 = $rep1['nacionalidade'];
		$idEstadoCivilRep1 = $rep1['idEstadoCivil'];

		//Recupera representante 2
		$sqlRep2 = $con2->query("SELECT * FROM representante_legal where id = '$idRepresentanteLegal2'");
		$rep2 = $sqlRep2->fetch_array(MYSQLI_ASSOC);

		$nomeRep2 = addslashes($rep2['nome']);
		$rgRep2 = $rep2['rg'];
		$cpfRep2 = $rep2['cpf'];
		$nacionalidadeRep2 = $rep2['nacionalidade'];
		$idEstadoCivilRep2 = $rep2['idEstadoCivil'];
	}


	// recupera pf
	$sqlPf = $con2->query("SELECT * FROM pessoa_fisica where id = '$idPf'");
	$pf = $sqlPf->fetch_array(MYSQLI_ASSOC);

	$nomePf = addslashes($pf["nome"]);
	$nomeArtistico = addslashes($pf["nomeArtistico"]);
	$rgPf = $pf["rg"];
	$cpfPf = $pf["cpf"];
	$ccmPf= $pf["ccm"];
	$idEstadoCivilPf = $pf["idEstadoCivil"];
	$dataNascimentoPf = $pf["dataNascimento"];
	$nacionalidadePf = $pf["nacionalidade"];
	$cepPf = $pf["cep"];
	$numeroPf = $pf["numero"];
	$complementoPf = $pf["complemento"];
	$telefone1Pf = $pf["telefone1"];
	$telefone2Pf = $pf["telefone2"];
	$telefone3Pf = $pf["telefone3"];
	$emailPf = $pf["email"];
	$drt = $pf["drt"];
	$pis = $pf["pis"];
	$codigoBancoPf = $pf["codigoBanco"];
	$agenciaPf = $pf["agencia"];
	$contaPf = $pf["conta"];
}


if(isset($_POST['idCapac']))
{
	$sql_insere_produtor = "INSERT INTO `ig_produtor`(`nome`, `email`, `telefone`, `telefone2`) VALUES ('$nomeProdutor', '$emailProdutor', '$telefone1Produtor', '$telefone2Produtor')";
	if(mysqli_query($con1,$sql_insere_produtor))
	{
		$mensagem = "Produtor importado com sucesso!<br/>";
		$sql_ultimo_produtor = "SELECT * FROM ig_produtor ORDER BY idProdutor DESC LIMIT 0,1";
		$query_ultimo_produtor = mysqli_query($con1,$sql_ultimo_produtor);
		$array_produtor = mysqli_fetch_array($query_ultimo_produtor);
		$idProdutorIg = $array_produtor['idProdutor'];

		$sql_insere_evento = "INSERT INTO `ig_evento`(`ig_produtor_idProdutor`, `ig_tipo_evento_idTipoEvento`, `nomeEvento`, `nomeGrupo`, `fichaTecnica`, `faixaEtaria`, `sinopse`, `releaseCom`, `publicado`, `idUsuario`, `linksCom`, `idInstituicao`, `statusEvento`) VALUES ('$idProdutorIg', '$idTipoEvento', '$nomeEvento', '$nomeGrupo', '$fichaTecnica', '$idFaixaEtaria', '$sinopse', '$releaseCom', '1', '$idUsuario', '$link', 'idInstituicao', 'Em elaboração')";
		if(mysqli_query($con1,$sql_insere_evento))
		{
			$mensagem = $mensagem."Evento inserido com sucesso!<br/>";
			$sql_ultimo_evento = "SELECT * FROM ig_evento ORDER BY idEvento DESC LIMIT 0,1";
			$query_ultimo_evento = mysqli_query($con1,$sql_ultimo_evento);
			$array_evento = mysqli_fetch_array($query_ultimo_evento);
			$idEventoIg = $array_evento['idEvento'];
			$_SESSION['idEvento'] = $idEventoIg;
			if($idPf > 0) //Se existir um executante cadastrado no CAPAC
			{
				$_SESSION['edicaoPessoa'] = 2;
			}
			else
			{
				$_SESSION['edicaoPessoa'] = 0;
			}

			$sql_insere_igsis = "INSERT INTO `igsis_capac`(`idEventoCapac`, `idEventoIgsis`) VALUES ('$idCapac', '$idEventoIg')";
			if(mysqli_query($con1,$sql_insere_igsis))
			{
				if($idTipoPessoa == 2)
				{
					if($idPf > 0)
					{
						$_SESSION['cpfPf'] = $cpfPf;
						echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=compara_pj&busca=".$cnpj."'>";
					}
					else
					{
						echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=compara_pj&busca=".$cnpj."'>";
					}
				}
				elseif($idTipoPessoa == 1)
				{
					echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=compara_pf&busca=".$cpfPf."'>";
				}
				else
				{
					echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=evento&p=carregar'>";
				}
			}
			else
			{
				$mensagem = $mensagem."Erro ao gravar equivalência!<br/>";
			}
		}
		else
		{
			$mensagem = $mensagem."Erro ao importar o evento!<br/>";
		}
	}
	else
	{
		$mensagem = $mensagem."Erro ao importar produtor!<br/>";
	}
}
?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<div class="col-md-offset-2 col-md-8">
				<h5><?php if(isset($mensagem)){ echo $mensagem; } ?></h5>
			</div>
		</div>
	</div>
</section>