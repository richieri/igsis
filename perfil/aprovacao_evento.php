﻿<?php
	if(isset($_POST['aprovacao_evento']))
	{
		$con = bancoMysqli();
		$datetime = date("Y-m-d H:i:s");
		$instituicao = $_SESSION['idInstituicao'];
		$idEvento = $_SESSION['idEvento'];
		$sql_atualiza_evento = "UPDATE ig_evento SET statusEvento = 'Aguardando' WHERE idEvento = '$idEvento'";
		$query_atualiza_evento = mysqli_query($con,$sql_atualiza_evento);
		if($query_atualiza_evento)
		{
			gravarLog($sql_atualiza_evento);
			atualizarAgenda($idEvento);
			$evento = recuperaDados("ig_evento",$idEvento,"idEvento");

			$titulo = "Evento ".$evento['nomeEvento']." enviado fora do prazo.";
			$idUsuario = $_SESSION['idUsuario'];
			$event = $idEvento;
			$descricao = "Evento fora do prazo!";
			$justificativa = "Solicitação de aprovação de evento fora do prazo.";
			$data = date('Y-m-d H:i:s');

			$sql_inserir_chamado = "INSERT INTO `igsis_chamado` (`idChamado`, `titulo`, `descricao`, `data`, `idUsuario`, `estado`, `tipo`, `idEvento`, `justificativa`) VALUES (NULL, '$titulo', '$descricao', '$data', '$idUsuario', '1', '18', '$event', '$justificativa')";
			$query_inserir_chamado = mysqli_query($con,$sql_inserir_chamado);

		}
		/*
		$sql_atualiza_pedido = "UPDATE `igsis`.`igsis_pedido_contratacao` SET
			`estado` = '1'
			WHERE `igsis_pedido_contratacao`.`idEvento` = '$idEvento' AND `igsis_pedido_contratacao`.`publicado` = '1' ";
		$query_atualiza_pedido = mysqli_query($con,$sql_atualiza_pedido);
		*/
		if($query_atualiza_evento)
		{
			$i = 0;
			$sql = "SELECT * FROM igsis_pedido_contratacao WHERE `idEvento` = '$idEvento' AND `publicado` = 1";
			$query = mysqli_query($con,$sql);
			while($ped = mysqli_fetch_array($query))
			{
				$pedido = recuperaDados("igsis_pedido_contratacao",$ped['idPedidoContratacao'],"idPedidoContratacao");
				$evento = recuperaDados("ig_evento",$pedido['idEvento'],"idEvento"); //$tabela,$idEvento,$campo
				$x[$i]['idEvento'] = $evento['idEvento'];
				$x[$i]['idPedido']= $pedido['idPedidoContratacao'];
				$x[$i]['nomeEvento'] = $evento['nomeEvento'];
				$i++;
			}
			$mensagem = "<h3>Solicitação enviada!</h3>
				<h5>Acompanhe o andamento de sua solicitação de envio através do módulo de Evento -> menu 'Carregar um evento gravado' -> coluna 'Status do evento'.</h5>
				<h5>Após seu pedido ser aprovado pelo setor de Contratos Artísticos, acompanhe seu evento através do módulo de Evento -> menu 'Acompanhar andamento de pedidos enviados'.</h5>
				<p>&nbsp;</p>
				<hr/>
				<p align='justify'>O seguinte evento foi para Gestão de Prazos:</p>";
		}
		else
		{
			$mensagem = "Erro ao enviar formulário! Os seguintes pedidos não foram enviados:";
		}
		$_SESSION['idEvento'] = NULL;
	}
?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h3></h3>
					<p><?php //var_dump($_SESSION); ?></p>
					<?php if(isset($mensagem)){echo $mensagem;} ?>
					<div align="justify">
						<strong>Número do Evento:</strong> <?php echo $evento['idEvento'] ?><br/>
						<strong>Nome do Evento:</strong> <?php echo $evento['nomeEvento'] ?><br/>
						<?php
						for($h = 0; $h < $i; $h++)
						{
							echo '<strong>Número do Pedido:</strong> '.$x[$h]['idPedido'];
							echo'<br/>';
						}
						?>
					</div>
					<hr/>
					<p><a href="?perfil=evento">Voltar ao módulo eventos?</a></p>
				</div>
			</div>
		</div>
	</div>
</section>