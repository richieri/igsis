<?php

$id_ped = $_GET['id_ped'];
$server = "http://".$_SERVER['SERVER_NAME']."/igsis/";
$http = $server."/pdf/";
$link2=$http."rlt_recibo_ne_vocacional_pf.php";

$_SESSION['idPedido'] = $_GET['id_ped'];
$pedido = recuperaDados("igsis_pedido_contratacao",$_GET['id_ped'],"idPedidoContratacao");



	$con = bancoMysqli();
if(isset($_POST['atualizar'])){ // atualiza o pedido
	$ped = $_GET['id_ped'];
	$numeroNE=$_POST['NumeroNotaEmpenho'];
	$emissaoNE= $_POST['DataEmissaoNotaEmpenho'];
	$entregaNE= $_POST['DataEntregaNotaEmpenho'];

	$sql_atualiza_pedido = "UPDATE igsis_pedido_contratacao SET 
			NumeroNotaEmpenho = '$numeroNE',
			DataEmissaoNotaEmpenho = '$emissaoNE',
			DataEntregaNotaEmpenho = '$entregaNE',
			estado = 10
			WHERE idPedidoContratacao = '$id_ped' ";
	if(mysqli_query($con,$sql_atualiza_pedido)){
			$mensagem = "<h5>Deseja gerar o recibo?</h5>
			
			<div class='row'>
				<div class='col-md-offset-1 col-md-10'>	
					<div class='form-group'>
						<div class='col-md-offset-2 col-md-8'>
							<a href='$link2?id=$id_ped' class='btn btn-theme btn-lg btn-block' target='_blank'>Recibo Formação</a>
						</div>
					</div>
				</div>
		    </div><br /></center>";	
		}else{
			$mensagem = "Erro ao atualizar! Tente novamente.";
		}
		
	}

	$pedido = recuperaDados("igsis_pedido_contratacao",$_GET['id_ped'],"idPedidoContratacao");
?>	


<?php include 'includes/menu.php';?>
		
	  
	 <!-- Contact -->
	  <section id="contact" class="home-section bg-white">
	  	<div class="container">
			  <div class="form-group">
					<div class="sub-title"><h2>CADASTRO DE NOTA DE EMPENHO</h2></div>
					<div><?php if(isset($mensagem)){ echo $mensagem; } ?></div>
			  </div>

	  		<div class="row">
	  			<div class="col-md-offset-1 col-md-10">

				<form class="form-horizontal" role="form" action="?perfil=formacao&p=frm_cadastra_notaempenho_pf&id_ped=<?php echo $id_ped; ?>" method="post">
				  
				  <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Código do Pedido de Contratação:</strong>
					  <input type="text" class="form-control" id="Id_PedidoContratacao"  name="Id_PedidoContratacao" <?php echo " value='$id_ped' ";?>>
					</div>
				  </div>
				 
                  <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Número da Nota de Empenho: *</strong>
					  <input type="text" class="form-control" id="NumeroNotaEmpenho" name="NumeroNotaEmpenho" placeholder="Número da Nota de Empenho" value="<?php echo $pedido['NumeroNotaEmpenho']; ?>">
					</div>
				  </div>
                  
                   <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Data de Emissão da Nota de Empenho: *</strong>
					  <input type="date" class="form-control" id="DataEmissaoNotaEmpenho" name="DataEmissaoNotaEmpenho" placeholder="Data de Emissao da Nota de Empenho" value="<?php echo $pedido['DataEmissaoNotaEmpenho']; ?>">
					</div>
				  </div>
                  
                  <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Data de Entrega da Nota de Empenho: *</strong>
					  <input type="date" class="form-control" id="DataEntregaNotaEmpenho" name="DataEntregaNotaEmpenho" placeholder="Data de Entrega da Nota de Empenho" value="<?php echo $pedido['DataEntregaNotaEmpenho']; ?>">
					</div>
				  </div>
					
				  <div class="form-group">
					<div class="col-md-offset-2 col-md-8">
					 <input type="submit" name="atualizar" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
					</div>
				  </div>
                  
				</form>
	
	  			</div>
			
				
	  		</div>
			

	  	</div>
	  </section>  
