<?php 
include 'includes/menu.php';

$id_ped = $_GET['id_ped'];
$pedido = recuperaDados("igsis_pedido_contratacao",$id_ped,"idPedidoContratacao");
?>
	  	  
<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
	                <h4>Escolha um modelo</h4>
                </div>
            </div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
				<?php  
					$con = bancoMysqli();
					$sql = "select * from sis_modelos_juridico";
					$query = mysqli_query($con,$sql);
					while ($modelo = mysqli_fetch_array ($query))
					{
				?>
						<a href="?perfil=juridico&p=frm_cadastra_juridico&id_ped=<?php echo $id_ped ?>&idModelo=<?php echo $modelo['idModelo'] ?>" class="btn btn-theme btn-lg btn-block"><?php echo $modelo['nomeModelo'] ?></a>
				<?php	
					}
				?>				
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8"><p>&nbsp;</p></div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<a href="?perfil=juridico&p=frm_edita_juridico&id_ped=<?php echo $id_ped ?>" class="btn btn-theme btn-lg btn-block">Editar</a>	
				</div>
			</div>
        </div>
    </div>
</section>   