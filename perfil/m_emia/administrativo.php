<?php
include "includes/menu_administrativo.php";

$con = bancoMysqli();
if(isset($_GET['pag']))
{
	$p = $_GET['pag'];
}
else
{
	$p = 'inicial';	
}

switch($p)
{
/* =========== INICIAL ===========*/
case 'inicial':
?>

<p>&nbsp;</p>
<p>&nbsp;</p>
<h3> Acesso Administrativo</h3>
<p>Aqui você acessa a parte administrativa do módulo Formação.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<?php /* =========== INICIAL ===========*/ break; ?>

<?php
/* =========== INÍCIO CADASTRA CARGO ===========*/
case 'add_cargo':

if(isset($_POST['add_cargo']))
{
	$idCargo = $_POST['add_cargo'];		   
	$cargo = $_POST['Cargo'];
	$justificativa = $_POST['justificativa'];
	$sql_atualiza_cargo = "INSERT INTO sis_emia_cargo 
	(Cargo, justificativa) VALUES ('$cargo', '$justificativa')";
	$con = bancoMysqli();
	$query_atualiza_cargo = mysqli_query($con,$sql_atualiza_cargo);
	if($query_atualiza_cargo)
	{
		$mensagem = "Cargo ".$cargo." cadastrado com sucesso!";
		gravarLog($sql_atualiza_cargo);
	}
	else
	{
		$mensagem = "Erro ao cadastrar.";	
	}		   
}
?>   
 
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<div class="sub-title">
            	<h2>CADASTRO DE FUNÇÃO</h2>
                <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="#" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Função: *</strong>
							<input type="text" class="form-control" id="Cargo" name="Cargo" placeholder="Exemplo: Oficinas de Artes Plásticas"> 
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Justificativa*:</strong><br/>
							<textarea name="justificativa" class="form-control" rows="5"></textarea>
						</div>
					</div>
						
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="add_cargo" value="1"/>
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Gravar">
						</div>
					</div>
				</form>
			</div>		
		</div>
	</div>
</section> 

<?php 
break;
case 'list_cargo':

	if(isset($_POST['atualizar']))
	{
		$idCargo = $_POST['atualizar'];		   
		$cargo = $_POST['cargo'];	
		$justificativa = $_POST['justificativa'];
		$sql_atualiza_cargo = "UPDATE sis_emia_cargo SET
		Cargo = '$cargo',
		justificativa = '$justificativa'
		WHERE Id_Cargo = '$idCargo'";
		$con = bancoMysqli();
		$query_atualiza_cargo = mysqli_query($con,$sql_atualiza_cargo);
		if($query_atualiza_cargo){
			$mensagem = "Atualizado com sucesso!";
			gravarLog($sql_atualiza_cargo);
		}else{
			$mensagem = "Erro ao atualizar.";	
		}		   
	}
?> 

<section id="list_items">
	<div class="container">
		<div class="col-md-offset-2 col-md-8">
			<br />
			<h2>FUNÇÃO</h2>
			<p><?php if(isset($mensagem)){ echo $mensagem; } ?></p>
			<br/>
		</div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Id</td>
						<td>Função</td>
						<td>Justificativa</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "SELECT * FROM sis_emia_cargo" ;
					$query = mysqli_query($con,$sql);
					while($cargo = mysqli_fetch_array($query))
					{
				?>
						<tr>
						<form action="?perfil=emia&p=administrativo&pag=list_cargo" method="post">
							<td><?php echo $cargo['Id_Cargo']; ?></td>
							<td><input type="text" name="cargo" class="form-control" value="<?php echo $cargo['Cargo']; ?>"/></td>							
							<td><textarea name="justificativa" class="form-control" rows="8" required><?php echo $cargo['justificativa']; ?></textarea></td>	<td>
								<input type="hidden" name="atualizar" value="<?php echo $cargo['Id_Cargo']; ?>" />
								<input type ='submit' class='btn btn-theme  btn-block' value='atualizar'>
							</td>
						</form>
						</tr>
				<?php 
					} 
				?>	
				</tbody>
			</table>
		</div>
	</div>           		
</section>

<?php /* =========== FIM CARGO ===========*/ break;
 

 } //fim da switch ?>