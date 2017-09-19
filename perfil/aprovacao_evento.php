<?php
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
		}
		$sql_atualiza_pedido = "UPDATE `igsis`.`igsis_pedido_contratacao` SET 
			`estado` = '1'
			WHERE `igsis_pedido_contratacao`.`idEvento` = '$idEvento' AND `igsis_pedido_contratacao`.`publicado` = '1' ";
		$query_atualiza_pedido = mysqli_query($con,$sql_atualiza_pedido);
		if($query_atualiza_evento)
		{
			gravarLog($sql_atualiza_pedido);
			$i = 0;
			$sql = "SELECT * FROM igsis_pedido_contratacao WHERE `idEvento` = '$idEvento'";
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
				<p>Entre em contato através do email <strong>pedidosdecontratacao@gmail.com</strong> informando o <strong>Nº da IG, Nome do Evento </strong> e <strong>Número(s) do(s) Pedido(s) de Contratação.</strong></p>
				<p></p>
				";
			
		}
		else
		{
			$mensagem = "Erro ao enviar formulário";	
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
					<p>&nbsp;</p>
					<?php
					for($h = 0; $h < $i; $h++)
					{
						echo '<p>';
						echo '<strong>Número do Evento:</strong> '.$x[$h]['idEvento'].'<br/>';
						echo '<strong>Nome do Evento:</strong> '.$x[$h]['nomeEvento'].'<br/>';
						echo '<strong>Número do Pedido:</strong> '.$x[$h]['idPedido'];
						echo'</p>';
					}
					?>
					<p><a href="?perfil=evento">Voltar ao módulo eventos?</a></p>
				</div>
			</div>
		</div>	  
	</div>
</section>