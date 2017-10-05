<?php
	if(isset($_POST['finalizar']))
	{
		$con = bancoMysqli();
		$datetime = date("Y-m-d H:i:s");
		$instituicao = $_SESSION['idInstituicao'];
		$idEvento = $_SESSION['idEvento'];
		$sql_atualiza_evento = "UPDATE ig_evento SET dataEnvio = '$datetime', statusEvento = 'Enviado' WHERE idEvento = '$idEvento'";
		$query_atualiza_evento = mysqli_query($con,$sql_atualiza_evento);
		if($query_atualiza_evento)
		{
			gravarLog($sql_atualiza_evento);
			atualizarAgenda($idEvento);	
			$sql_data_envio = "INSERT INTO `ig_data_envio`(`idEvento`, `dataEnvio`) VALUES ('$idEvento', '$datetime')";
			$query_data_envio = mysqli_query($con,$sql_data_envio);
		
			if($query_data_envio)
			{
				$sql_atualiza_pedido = "UPDATE `igsis`.`igsis_pedido_contratacao` SET 
				`estado` = '2'
				WHERE `igsis_pedido_contratacao`.`idEvento` = '$idEvento' AND `igsis_pedido_contratacao`.`publicado` = '1' ";
				$query_atualiza_pedido = mysqli_query($con,$sql_atualiza_pedido);
			
				if($query_atualiza_evento)
				{
					gravarLog($sql_atualiza_pedido);						
					$mensagem = "O formulário de evento foi enviado com sucesso!<br/><h5>O número IG é ".$idEvento."</h5>
						Refira-se a este número ao entrar em contato com as áreas de Comunicação e Produção.<br /><br /><br />";
					$sql_recupera_pedidos = "SELECT * FROM igsis_pedido_contratacao WHERE idEvento = '$idEvento' AND publicado = '1'";
					$query_recupera_pedidos = mysqli_query($con,$sql_recupera_pedidos);
					$num_pedidos = mysqli_num_rows($query_recupera_pedidos);
					if($num_pedidos > 0)
					{						
						while($pedido = mysqli_fetch_array($query_recupera_pedidos))
						{
							$idPedido = $pedido['idPedidoContratacao'];
							$i = 0;
							if($sql_atualiza_pedido)
							{								
								$pedidos[$i] = $idPedido;
								$mensagem = $mensagem."Foi gerado um <strong>pedido de contratação</strong> com número <h5>".$pedidos[$i]."</h5>
								Este número é a referência para as áreas de Contratos, Jurídico, Finanças, Contabilidade entre outros.<br />
								<strong><a target='_blank' href='?perfil=detalhe_pedido&id_ped=".$pedidos[$i]."'>Clique aqui caso queira visualizar os detalhes desta contratação.</a></strong>
								<br /><br />
								<a href='http://smcsistemas.prefeitura.sp.gpv.br/igsis/manual/index.php/introducao-ao-sistema-igsis/numero-igpedido-de-contratacao/' target='_blank'>Saiba mais sobre os números gerados no nosso <i>Manual do Sistema</i></a>.<br /><br /><br />
								";
								$i++;
							}
						}
					}
				}
				else
				{
					$mensagem = "Erro ao enviar o pedido de contratação. Contacte o administrador do sistema.";
				}
			}
			else
			{
				$mensagem = "Erro ao registrar data de envio. Contacte o administrador do sistema.";
			}
		}
		else
		{
			$mensagem = "Erro ao enviar formulário";	
		}
		// Gera um registro em ig_comunicacao
		$sql_pesquisar = "SELECT * FROM ig_evento WHERE idEvento = '$idEvento'";
		$query = mysqli_query($con,$sql_pesquisar);
		while($importa = mysqli_fetch_array($query))
		{
			$sql_importar = "INSERT INTO `igsis`.`ig_comunicacao` (`sinopse`, `fichaTecnica`, `autor`, `projeto`, `releaseCom`, `ig_evento_idEvento`, `nomeEvento`, `ig_tipo_evento_idTipoEvento`, `ig_programa_idPrograma`, `idInstituicao`) 
				SELECT `sinopse`, `fichaTecnica`, `autor`, `projeto`,`releaseCom`, `idEvento`, `nomeEvento`, `ig_tipo_evento_idTipoEvento`, `ig_programa_idPrograma`, `idInstituicao` FROM `ig_evento` WHERE `idEvento` = '$idEvento'";
			$query_importar = mysqli_query($con,$sql_importar);
			if($query_importar)
			{
				$mensagem_com = "Registro na Divisão de Comunicação e Informação efetuado com sucesso.";
			}
			else
			{
				$mensagem_com = "Erro ao registrar evento na Divisão de Comunicação e Informação.";
			}
		}	
		
		$_SESSION['idEvento'] = NULL;
	}
?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h3>Envio confirmado!</h3>
					<p><?php //var_dump($_SESSION); ?></p>
					<p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
					<p><?php if(isset($mensagem_com)){echo $mensagem_com;} ?></p>
					<p><?php if(isset($mensagem_email)){echo $mensagem_email;} ?></p>
					<p><a href="?p=inicio">Voltar ao início?</a></p>
				</div>
			</div>
		</div>
	</div>
</section>