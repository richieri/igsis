<?php
$con = bancoMysqli();
include 'includes/menu_administrativo.php';

$idPedido = $_GET['id_ped'];

if(isset($_POST['cadastrar']))
{
	$mensagem = "";		
	
	for($i = 1; $i <= 12; $i++)
	{
		$valor = dinheiroDeBr($_POST['parcela'.$i]);
		if($_POST['vigencia_inicio'.$i] == "")
		{
			$vigencia_inicio = NULL;
		}
		else
		{
			$vigencia_inicio = exibirDataMysql($_POST['vigencia_inicio'.$i]);
		}
		
		if($_POST['vigencia_final'.$i] == "")
		{
			$vigencia_final = NULL;	
		}
		else
		{
			$vigencia_final = exibirDataMysql($_POST['vigencia_final'.$i]);
		}
		
		if($_POST['vencimento'.$i] == "")
		{
			$vencimento = NULL;
		}
		else
		{
			$vencimento = exibirDataMysql($_POST['vencimento'.$i]);
		}
		
		$horas = $_POST['cargahoraria'.$i];
		$sql_atualiza_parcela = "UPDATE `igsis_parcelas` SET 
			`valor` = '$valor', 
			`vigencia_inicio` = '$vigencia_inicio', 
			`vigencia_final` = '$vigencia_final', 
			`vencimento` = '$vencimento', 
			`horas` = '$horas' 
			WHERE idPedido = '$idPedido' AND numero = '$i'";
		$query_atualiza_parcela = mysqli_query($con,$sql_atualiza_parcela);
		if($query_atualiza_parcela)
		{
		    $mensagem = $mensagem." Parcela $i atualizada.<br />";
		    
		    $sql_parcelas = "SELECT SUM(valor) AS valorTotal FROM igsis_parcelas WHERE idPedido = '$idPedido'";
			$query_parcelas = mysqli_query($con,$sql_parcelas);
			$parcelas = mysqli_fetch_array($query_parcelas);
		    $valorGlobal = $parcelas['valorTotal'];
		    $sql_pedido = "UPDATE igsis_pedido_contratacao SET valor = '$valorGlobal' WHERE idPedidoContratacao = '$idPedido'";
		    if(mysqli_query($con,$sql_pedido)){
		        $mensagem .= "Valor total do pedido atuallizado.";
            }
		    else{
		        $mensagem .= "Erro ao atualizar pedido";
            }
		}
		else
		{
			$mensagem = $mensagem."Erro ao atualizar parcela $i.<br />";
		}
	}		
}
?>
<!-- Contact -->
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<div class="sub-title">
				<h2>EDIÇÃO DE PARCELAS</h2>
				<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=formacao&p=frm_edicao_parcelas&id_ped=<?php echo $idPedido; ?>" method="post">
								 
					
		<?php 
			for($i = 1; $i <= 12; $i++)
			{
				$sql_rec_parcela = "SELECT * FROM igsis_parcelas WHERE idPedido = '$idPedido' AND numero = '$i'";
				$query_rec_parcela = mysqli_query($con,$sql_rec_parcela);
				$parcela = mysqli_fetch_array($query_rec_parcela);				
		?>
					<div class="form-group">
						<div class="col-md-15 col-sm-1"><strong>Parcela</strong><br/>
							<input type='text' disabled name="Valor" id='valor<?php echo $i ?>' class='form-control' value="<?php echo $i ?>" >
						</div>	
						<div class=" col-sm-2"><strong>Valor</strong><br/>
							<input type='text'  name="parcela<?php echo $i ?>" id='valor' class='form-control valor' value="<?php echo dinheiroParaBr($parcela['valor']) ?>" >
						</div>
						<div class="col-sm-2"><strong>Data inicial:</strong><br/>
							<input type='text' name="vigencia_inicio<?php echo $i ?>" id='' class='form-control datepicker' value="<?php if($parcela['vigencia_inicio'] == NULL OR $parcela['vigencia_inicio'] == '0000-00-00' ){}else{ echo exibirDataBr($parcela['vigencia_inicio']);}?>">
						</div>
						<div class="col-sm-2"><strong>Data final:</strong><br/>
							<input type='text'  name="vigencia_final<?php echo $i ?>" id='' class='form-control datepicker' value="<?php if($parcela['vigencia_final'] == NULL OR $parcela['vigencia_final'] == '0000-00-00' ){}else{ echo exibirDataBr($parcela['vigencia_final']);} ?>">
						</div>
						<div class="col-sm-2"><strong>Pagamento:</strong><br/>
							<input type='text'  name="vencimento<?php echo $i ?>" id='' class='form-control datepicker' value="<?php if($parcela['vencimento'] == NULL OR $parcela['vencimento'] == '0000-00-00' ){}else{ echo exibirDataBr($parcela['vencimento']);} ?>">
						</div>
						<div class="col-sm-2"><strong>Carga Horária:</strong><br/>
							<input type='text'  name="cargahoraria<?php echo $i ?>" id='duracao' class='form-control' value="<?php echo $parcela['horas'] ?>">
						</div>
					</div>
		<?php
			}
		?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="cadastrar" value="<?php echo $vigencia['Id_Vigencia'] ?>" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Gravar">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>