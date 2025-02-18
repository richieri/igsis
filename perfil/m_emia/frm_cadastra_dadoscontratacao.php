<?php
include 'includes/menu.php';

$con = bancoMysqli();
$id_pf = $_GET['id_pf'];
$idUsuario = $_SESSION["idUsuario"];

if(isset($_POST['novo']))
{
	$sql_novo = "INSERT INTO `sis_emia` (`IdPessoaFisica`, `publicado` ) VALUES ('$id_pf','0')";
	$query_novo = mysqli_query($con,$sql_novo);
	if($query_novo)
	{
		$ultimo = mysqli_insert_id($con);
		$mensagem = "Gerado novo registro"; 
	}
	else
	{
		$mensagem = "Erro ao gerar novo registro";
	}
}

if(isset($_GET['id']))
{
	$id = $_GET['id'];
}
else
{
	$id = $ultimo;	
}	

if(isset($_POST['atualizar']))
{	
	$id = $_POST['atualizar'];
	$ano = $_POST['ano'];
	$status  = $_POST['status'];	
	$cargo  = $_POST['cargo'];
	$cronograma = addslashes($_POST['cronograma']);
	$obs = addslashes($_POST['obs']);	
	$vigencia = $_POST['vigencia'];
	$sql_atualiza_emia = "UPDATE sis_emia SET
	`Ano` = '$ano',
	`IdLocal` = '523',
	`IdCargo` = '$cargo',
	`Status` = '$status', 
	`publicado` = '1',
	`IdVigencia` = '$vigencia',
	`cronograma` = '$cronograma',
	`Observacao` = '$obs',
	`IdUsuario` = $idUsuario
	WHERE idEmia = '$id'";
	$query_atualiza_emia = mysqli_query($con,$sql_atualiza_emia);
	
	if($query_atualiza_emia)
	{
		$mensagem = "Atualizado com sucesso!";	
	}
	else
	{
		$mensagem = "Erro ao atualizar. Tente novamente.";		
	}
}
$emia = recuperaDados("sis_emia",$id,"idEmia");
$pessoa = recuperaDados("sis_pessoa_fisica",$emia['IdPessoaFisica'],"Id_PessoaFisica");
$id_pf = $emia['IdPessoaFisica'];

$_SESSION['id_pf']= $emia['IdPessoaFisica'];
$_SESSION['id']= $id;
?>

		
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<div class="sub-title">
				<h3>DADOS PARA CONTRATAÇÃO</h3>
				<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Proponente:</strong><br/>
						<input type='text'  readonly class='form-control' name='nome' id='nome' value="<?php echo $pessoa['Nome']; ?>">
					</div>
				</div>  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<form class="form-horizontal" role="form" action="?perfil=emia&p=frm_edita_pf&id_pf=<?php echo $id_pf; ?>&id=<?php echo $id; ?>" method="post">
							<input type="submit" class="btn btn-theme btn-med btn-block" value="Abrir proponente">
						</form>
					</div>
				</div>
	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br/></div>
				</div>
				
				<form class="form-horizontal" role="form" action="" method="post">	  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Ano:</strong>
						<select class="form-control" name="ano" id="Status">
                            <option value='2020'<?php if($emia['Ano'] == 2020){echo " selected ";} ?>>2020</option>
                            <option value='2019'<?php if($emia['Ano'] == 2019){echo " selected ";} ?>>2019</option>
							<option value='2018'<?php if($emia['Ano'] == 2018){echo " selected ";} ?>>2018</option>
						</select>
					</div>			
					<div class="col-md-6"><strong>Status:</strong><br/>
						<select class="form-control" name="status" id="Status">
							<option value='1'<?php if($emia['Status'] == 1){echo " selected ";} ?>>Ativo</option>
							<option value='0'<?php if($emia['Status'] == 0){echo " selected ";} ?>>Inativo</option>
						</select>
					</div>
				</div>
                  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Função *:</strong>
						<select class="form-control" name="cargo" id="Cargo">
							<?php geraOpcao("sis_emia_cargo",$emia['IdCargo'],"") ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">					
					<div class="col-md-offset-2 col-md-8"><strong>Local:</strong><br/>
						<input type='text' readonly class='form-control' value="EMIA" />
					</div>
				</div>
				
				<div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Vigência:</strong><br/>
						<select class="form-control" name="vigencia" id="local3" >
						<?php  
							$sql_vigencia = "SELECT * FROM sis_emia_vigencia WHERE publicado = '1'";
							$query_vigencia = mysqli_query($con,$sql_vigencia);
							while($vigencia = mysqli_fetch_array($query_vigencia))
							{
								if($vigencia['Id_Vigencia'] == $emia['IdVigencia'])
								{
							?>
									<option value="<?php echo $vigencia['Id_Vigencia']; ?>" selected><?php echo $vigencia['descricao'] ?></option>
							<?php 
								}
								else
								{
							?>
									<option value="<?php echo $vigencia['Id_Vigencia']; ?>" ><?php echo $vigencia['descricao'] ?></option>
							<?php 
								}
							} 
							?>
						</select>
					</div>
				</div>  
								
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Cronograma:</strong><br/>
						<textarea name="cronograma" class="form-control" cols="40" rows="5"><?php echo $emia['cronograma']; ?></textarea>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
						<textarea name="obs" class="form-control" cols="40" rows="5"><?php echo $emia['Observacao']; ?></textarea>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br/></div>
				</div>
							
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="atualizar" value="<?php echo $id ?>" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Gravar">
					</div>
				</div>
				</form>
	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br/></div>
				</div>
	
			<?php 					
				if($emia['idPedidoContratacao'] == NULL)
				{
			?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<form class="form-horizontal" role="form" action="?perfil=emia&p=frm_cadastra_pedidocontratacao_pf" method="post">
								<input type="hidden" name="action" value="novo"  />
								<input type="hidden" name="idEmia" value="<?php echo $emia['idEmia']; ?>"  />
								<input type="submit" class="btn btn-theme btn-med btn-block" value="Criar pedido de contratação">
							</form>
						</div>
					</div>
			<?php 
				} 
			?>
			</div>
		</div>
	</div>	
</section>  