<?php
include 'includes/menu.php';

	$con = bancoMysqli();
	$id_ped = $_GET['id_ped'];
	$server = "http://".$_SERVER['SERVER_NAME']."/igsis/";
	$http = $server."/pdf/";
	$link1=$http."rlt_publicacao_pj.php";
	$link2=$http."rlt_ordemservico_pj_assinatura_word.php";
	$link3=$http."rlt_termo_doacao_pj_assinatura_word.php";
	$data = date('Y-m-d H:i:s');
	$ano = date('Y');
	$pedido = siscontrat($id_ped);
	$pj = siscontratDocs($pedido['IdProponente'],2);

	if(isset($_POST['atualizar']))
	{ // atualiza o pedido
		$ped = $_GET['id_ped'];
		$sql_atualiza_pedido = "UPDATE igsis_pedido_contratacao SET
		estado = 8,
		DataPublicacao = '$data'
		WHERE idPedidoContratacao = '$id_ped'";
		if(mysqli_query($con,$sql_atualiza_pedido))
		{
			$mensagem = "
				<div class='form-group'>
					<div class='col-md-offset-2 col-md-8'>
						<a href='$link1?id=$id_ped' class='btn btn-theme btn-lg btn-block' target='_blank'>Gerar Word</a></br/>
					</div>
				</div>
				<div class='form-group'>
					<div class='col-md-offset-2 col-md-6'>
						<a href='$link2?id=$id_ped' class='btn btn-theme btn-lg btn-block' target='_blank'>Ordem de Serviço</a>
					</div>
					<div class='col-md-6'>
						<a href='$link3?id=$id_ped' class='btn btn-theme btn-lg btn-block' target='_blank'>Termo de doação</a><br/><br/>
					</div>
				</div>			
		";	
		}
			else
			{
				$mensagem = "Erro ao atualizar! Tente novamente.";
			}		
	}
?>

	<section id="contact" class="home-section bg-white">
		<div class="container">
			<div class="form-group"><h2>CONTRATAÇÃO DE PESSOA JURÍDICA</h2>
				<h4><?php if(isset($mensagem)){ echo $mensagem; } ?></h4>
			</div>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<div class="col-md-offset-2 col-md-8">
						<div class="left">
							<p align="justify"><strong>Código do pedido de contratação:</strong> <?php echo $ano."-".$id_ped; ?></p>
							<p align="justify"><strong>Número do Processo:</strong> <?php echo $pedido['NumeroProcesso'];?></p>
							<p align="justify"><strong>Setor:</strong> <?php echo $pedido['Setor'];?></p>	
							<p align="justify"><strong>Proponente:</strong> <?php echo $pj['Nome'];?></p>
							<p align="justify"><strong>Objeto:</strong> <?php echo $pedido['Objeto'];?></p>
							<p align="justify"><strong>Local:</strong> <?php echo $pedido['Local'];?></p>
							<p align="justify"><strong>Valor:</strong> R$ <?php echo dinheiroParaBr($pedido["ValorGlobal"]);?></p>
							<p align="justify"><strong>Data/Período:</strong> <?php echo $pedido['Periodo'];?></p>
							<p align="justify"><strong>Duração:</strong> <?php echo $pedido['Duracao'];?>utos</p>
							<p align="justify"><strong>Carga Horária:</strong> <?php echo $pedido['CargaHoraria'];?></p>
							<p align="justify"><strong>Justificativa:</strong> <?php echo $pedido['Justificativa']; ?></p>
							<p align="justify"><strong>Fiscal:</strong> <?php echo $pedido['Fiscal'];?></p>
							<p align="justify"><strong>Suplente:</strong> <?php echo $pedido['Suplente'];?></p>
							<p align="justify"><strong>Dotação Orçamentária:</strong> <?php echo $pedido['ComplementoDotacao'];?></p>
							<p align="justify"><strong>Observação:</strong> <?php echo $pedido['Observacao'];?></p>
							<p align="justify"><strong>Data do Cadastro:</strong> <?php echo exibirDataBr($pedido['DataCadastro']);?></p>               
						</div>
					</div>
					<form class="form-horizontal" role="form" action="?perfil=publicacao&p=frm_cadastra_publicacaopj&id_ped=<?php echo $id_ped; ?>" method="post">
						<div class="col-md-offset-2 col-md-8">
							<input type="submit" name="atualizar" class="btn btn-theme btn-lg btn-block" value="Confirmar">
						</div>
					</form> 
				</div>
			</div>
		</div>
	</section>         