﻿<?php 
include 'includes/menu.php';

if(isset($_GET['action']))
{
	$action = $_GET['action'];	
}
else
{
	$action = "listar";		
}


switch($action)
{
case "listar":

$con = bancoMysqli();
$idPedido = $_SESSION['idPedido'];

if(isset($_POST['inserir']))
	{
		$nome = addslashes($_POST['nome']);
		$rg = trim($_POST['rg']);
		$cpf = $_POST['cpf'];
		$sql_inserir = "INSERT INTO `igsis_grupos` 
			(`idGrupos`, 
			`idPedido`, 
			`nomeCompleto`, 
			`rg`, 
			`cpf`, 
			`publicado`) 
			VALUES (NULL, 
			'$idPedido', 
			'$nome', 
			'$rg', 
			'$cpf', 
			'1')";
		$query_inserir = mysqli_query($con,$sql_inserir);
		if($query_inserir)
		{
			$mensagem = "Integrante inserido com sucesso!";	
		}
		else
		{
			$mensagem = "Erro ao inserir integrante. Tente novamente.";	
		}	
	}
	
	if(isset($_POST['apagar']))
	{
		$id = $_POST['apagar'];
		$sql_apagar = "UPDATE igsis_grupos SET publicado = '0' WHERE idGrupos = '$id'";
		$query_apagar = mysqli_query($con,$sql_apagar);
		if($query_apagar)
		{
			$mensagem = "Integrante apagado com sucesso!";	
		}
		else
		{
			$mensagem = "Erro ao apagar integrante. Tente novamente.";	
		}
	}
	$sql_grupos = "SELECT * 
		FROM igsis_grupos 
		WHERE idPedido = '$idPedido' 
		AND publicado = '1'";
	$query_grupos = mysqli_query($con,$sql_grupos);
	$num = mysqli_num_rows($query_grupos);


	if(isset($_POST['atualizarIntegrante']))
	{
		$id = $_POST['atualizarIntegrante'];
		$nomeCompleto = addslashes($_POST['nomeCompleto']);
		$rg = trim($_POST['rg']);
		$cpf = $_POST['cpf'];
		$sql_atualizar = "UPDATE igsis_grupos SET
			nomeCompleto = '$nomeCompleto',
			rg = '$rg',
			cpf = '$cpf'
			WHERE idGrupos = '$id'";
		$query_atualizar = mysqli_query($con,$sql_atualizar);
		if($query_atualizar)
		{
			$mensagem = "Integrante editado com sucesso!";
			echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?perfil=contratos&p=frm_grupos&idGrupo=$id'>";
		}
		else
		{
			$mensagem = "Erro ao editar integrante. Tente novamente.";
		}
	}

?>

<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Grupos</h2>
					<h4>Integrantes de grupos</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
                </div>
			</div>
		</div>  
	<?php
		if($num > 0)
		{ 
	 ?>
			<div class="table-responsive list_info">
                <table class='table table-condensed'>
					<thead>
						<tr class='list_menu'>
							<td width='40%'>Nome Completo</td>
							<td>RG</td>
							<td>CPF</td>
							<td></td>
							<td></td>
						</tr>
					</thead>
					<tbody>
				<?php
					while($grupo = mysqli_fetch_array($query_grupos))
					{ 
				?>	
						<tr>
							<td><?php echo $grupo['nomeCompleto'] ?></td>
							<td><?php echo $grupo['rg'] ?></td>
							<td><?php echo $grupo['cpf'] ?></td>
							<td class='list_description'>
							<form method='POST' action='?perfil=contratos&p=frm_grupos&action=editar&idGrupo=<?php echo $grupo['idGrupos'] ?>'>
								<input type="hidden" name="editar" value="<?php echo $grupo['idGrupos'] ?>" />
								<input type ='submit' class='btn btn-theme btn-block' value='editar'>
								</form>
							</td>
							<td class='list_description'>
							<form method='POST' action='?perfil=contratos&p=frm_grupos'>
								<input type="hidden" name="apagar" value="<?php echo $grupo['idGrupos'] ?>" />
								<input type ='submit' class='btn btn-theme btn-block' value='apagar'>
							</form>
							</td>
						</tr>					
				<?php
					}
				?>
					</tbody>
				</table>
			</div>
	<?php 
		}
		else
		{
	?>
            <div class="col-md-offset-2 col-md-8">
            	<h3>Não há integrantes de grupos inseridos. <br />
                <a href="?perfil=contratos&p=frm_grupos&action=inserir">Clique aqui para inserir</a>.</h3>				
            </div> 
	<?php 
		}
	?>
            <div class="col-md-offset-2 col-md-8">
				<a href="?perfil=contratos&p=frm_grupos&action=inserir" class="btn btn-theme btn-md btn-block">Inserir outro integrante</a>
            	<?php 
					$pedido = recuperaDados("igsis_pedido_contratacao",$_SESSION['idPedido'],"idPedidoContratacao");
					if($pedido['tipoPessoa'] == 2)
					{
				?>        
						<a href="?perfil=contratos&p=frm_edita_propostapj&id_ped=<?php echo $_SESSION['idPedido'] ?>" class="btn btn-theme btn-md btn-block">Voltar ao pedido</a>
				<?php 
					}
					else 
					{ 
				?>
						<a href="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $_SESSION['idPedido'] ?>" class="btn btn-theme btn-md btn-block">Voltar ao pedido</a>
				<?php 
					} 
				?>
	        </div>        	   
	</div>
</section>

<?php 

break;
case "inserir";

?>
<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
        	<h3>CADASTRO DE INTEGRANTE DE GRUPO</h3>
		</div>
  		<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
			<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_grupos&action=listar" method="post">
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Nome completo: *</strong><br/>
						<input type="text" class="form-control" id="RepresentanteLegal" name="nome" >
					</div>
				</div>
                  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>RG: *</strong><br/>
						<input type="text" class="form-control" id="RG" name="rg" placeholder="RG">
					</div>
					<div class="col-md-6"><strong>CPF: *</strong><br/>
						<input type="text" class="form-control" id="cpf" name="cpf"  placeholder="CPF">
					</div>
				</div>
                                
                <!-- Botão Gravar -->	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="submit" name="inserir" value="CADASTRAR" class="btn btn-theme btn-lg btn-block">
					</div>
				</div>
			</form>
			</div>
	</div>
</section>

<?php 

break;
case "editar";

$con = bancoMysqli();
$idGrupo = $_GET['idGrupo'];
$integrateGrupo = recuperaDados("igsis_grupos",$idGrupo,"idGrupos");

?>

<section id="contact" class="home-section bg-white">
 	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE INTEGRANTE DE GRUPO</h3>
		</div>
		<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
			<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_grupos&idGrupo=<?php echo $idGrupo ?>" method="post">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Nome completo: *</strong><br/>
						<input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" value="<?php echo $integrateGrupo['nomeCompleto'] ?>" >
					</div>
				</div>
                  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>RG: *</strong><br/>
						<input type="text" class="form-control" id="rg" name="rg" placeholder="RG" value="<?php echo $integrateGrupo['rg'] ?>">
					</div>
					<div class="col-md-6"><strong>CPF: *</strong><br/>
						<input type="text" class="form-control" id="cpf" name="cpf"  placeholder="CPF" value="<?php echo $integrateGrupo['cpf'] ?>">
					</div>
				</div>
                  
                <!-- Botão Gravar -->	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="atualizarIntegrante" value="<?php echo $idGrupo ?>" />
						<input type="submit" name="enviar" value="CADASTRAR" class="btn btn-theme btn-lg btn-block">
					</div>
                </div>
			</form>
			</div>
	  	</div>
	</div>
</section>  	  

<?php 
} 
?>