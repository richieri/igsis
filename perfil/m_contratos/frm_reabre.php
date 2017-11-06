<?php
require_once("../funcoes/funcoesVerifica.php");
require_once("../funcoes/funcoesSiscontrat.php");
require_once("../funcoes/funcoesReabertura.php");
include 'includes/menu.php';

$con = bancoMysqli();

if(isset($_POST['idEvento']))
{
	$idEvento = $_POST['idEvento'];
	$evento = recuperaDados("ig_evento",$_POST['idEvento'],"idEvento");
	$voltar = $_POST['voltar'];
}

if(isset($_POST['reabrir']))
{
	$titulo = "Reabertura do evento ".$evento['nomeEvento']." pela área de Contratos";
	$idUsuario = $_SESSION['idUsuario'];
	$tipo = $_POST['tipo'];
	$event = $idEvento;
	$descricao = $titulo;	
	$justificativa = addslashes($_POST['justificativa']);
	$data = date('Y-m-d H:i:s');
	$reabertura = reaberturaEvento($idEvento);
	if($reabertura == 0)
	{
		$sql_inserir_chamado = "INSERT INTO `igsis_chamado` (`idChamado`, `titulo`, `descricao`, `data`, `idUsuario`, `estado`, `tipo`, `idEvento`, `justificativa`) VALUES (NULL, '$titulo', '$descricao', '$data', '$idUsuario', '1', '$tipo', '$event', '$justificativa')";
		$query_inserir_chamado = mysqli_query($con,$sql_inserir_chamado);
		if($query_inserir_chamado)
		{ 
?>   
			<section id="inserir" class="home-section bg-white">
				<div class="container">
					<div class="row">
						<div class="col-md-offset-2 col-md-8">
							<div class="text-hide">
								<h3>O evento <?php echo $evento['nomeEvento']; ?> foi reaberto com sucesso!</h3>
								 <h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
							</div>
						</div>
					</div>	
					<div class="row">
						<div class="col-md-offset-1 col-md-10">
							<div class="form-group">
								<p> Não será mais possível visualizar os pedidos deste evento até que o responsável o reenvie. </p>
							</div>
						</div>
					</div>
				</div>	
			</section>  
<?php
		}
	}
}
else
{
?>
	<script>
	function valida()
	{
		var campo = document.getElementById("justificativa");
		if(campo.value == "")
		{
		   alert("Preencha o campo Justificativa para reabertura!");
		   return false;
		} 
		return true;
	}
</script>
	<section id="inserir" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="text-hide">
						<h3>Reabrir evento e pedido</h3>
						<h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
					</div>
				</div>
			</div>		
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
				<form method="POST" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" onsubmit="return valida()" class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<div class="left">
								<p><b>Evento:</b> <?php echo $evento['nomeEvento']; ?></p>
							<?php 
								$outros = listaPedidoContratacao($idEvento); 
								for($i = 0; $i < count($outros); $i++)
								{
									$dados = siscontrat($outros[$i]);
							?>
								<p align="left"><b>Número do Pedido de Contratação:</b> <?php echo $outros[$i]; ?><br /></p>
							<?php 
								} 
							?>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Tipo de chamado</label>
							<select class="form-control" name="tipo" id="inputSubject" >
								<option value="0"></option>
								<?php echo geraOpcaoTipoChamado("") ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Justificativa para reabertura</label>
							<textarea name="justificativa" id='justificativa' class="form-control" rows="10" placeholder="Informe aqui o que será alterado"></textarea>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="reabrir" value="1" />
							<input type="hidden" name="idEvento" value="<?php echo $_POST['idEvento'] ?>" />
							<input type="hidden" name="voltar" value="<?php echo $_POST['voltar'] ?>" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Reabrir evento">
						</div>
					</div>
				</form>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<a href="<?php echo $voltar; ?>" class="btn btn-theme btn-lg btn-block">Voltar</a>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</section>  
<?php 
} 
?>