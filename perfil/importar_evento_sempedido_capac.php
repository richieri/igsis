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


//Recupera produtor
$sqlProdutor = $con2->query("SELECT * FROM produtor where id = '$idProdutor'");
$produtorC = $sqlProdutor->fetch_array(MYSQLI_ASSOC);

$nomeProdutor = addslashes($produtorC['nome']);
$emailProdutor = $produtorC['email'];
$telefone1Produtor = $produtorC['telefone1'];
$telefone2Produtor = $produtorC['telefone2'];


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
			$sql_insere_igsis = "INSERT INTO `igsis_capac`(`idEventoCapac`, `idEventoIgsis`) VALUES ('$idCapac', '$idEventoIg')";
			if(mysqli_query($con1,$sql_insere_igsis))
			{
				echo "<meta HTTP-EQUIV='refresh' CONTENT='1.5;URL=?perfil=evento&p=carregar'>";
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