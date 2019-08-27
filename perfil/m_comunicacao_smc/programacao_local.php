<?php
include "include/menu.php";
	
if(isset($_POST['inicio']) AND $_POST['inicio'] != "")
{
	if($_POST['final'] == "")
	{
		$mensagem = "É preciso informar a data final do filtro";	
	}
	else
	{
		$inicio = exibirDataMysql($_POST['inicio']);
		$final = exibirDataMysql($_POST['final']);
		if($_POST['inicio'] > $_POST['final'])
		{
			$mensagem = "A data final do filtro deve ser maior que a data inicio";		
		}
		else
		{
			$data_inicio = exibirDataMysql($_POST['inicio']);
			$data_final = exibirDataMysql($_POST['final']);
			$mensagem = "Filtro aplicado: eventos entre ".$_POST['inicio']." e ".$_POST['final'];
		}
		
	}
}
else
{
	$mes = date("m");      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
	$ano = date("Y"); // Ano atual
	$dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
	$data_inicio = "$ano-$mes-01";
	$data_final = "$ano-$mes-$dia";
	$nome_mes = retornaMes($mes);	
	$mensagem = "Filtro aplicado: eventos de $nome_mes de $ano.";
}	

?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h3>Comunicação - Programação Local</h3>
					<p>&nbsp;</p>
					<h6><?php if(isset($mensagem)){echo $mensagem;} ?></h6>
				</div>
			</div>
		</div>  
		<form method="POST" action="?perfil=comunicacao&p=programacao_local" class="form-horizontal" role="form">
			<div class="form-group">
				<div class="col-md-offset-2 col-md-6">
					<label>Data início *</label>
					<input type="text" name="inicio" class="form-control" id="datepicker01" placeholder="">
				</div>
				<div class=" col-md-6">
					<label>Data encerramento *</label>
					<input type="text" name="final" class="form-control" id="datepicker02"  placeholder="">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<br />
					<input type="submit" class="btn btn-theme btn-lg btn-block" value="Filtrar">
					<br >
				</div>
			</div>					
		</form>
		<div class="table-responsive list_info">
			<strong>Legenda status:</strong> | <font color='blue'>[ E ] Editado</font> | <font color='#32CD32'>[ R ] Revisado</font> | <font color='red'>[ S ] Site</font> | <font color='orange'>[ I ] Impresso</font> | <font color='#DA70D6'>[ F ] Foto</font> | 
			<p>&nbsp;</p>
			<table class='table table-condensed'>
				<thead>
					<tr class='list_menu'>
						<td width='10%'>Numero IG</td>
						<td>Nome de Evento</td>
						<td>Enviador por</td>
						<td>Data/Início</td>
						<td>Status</td>
					</tr>
				</thead>
				<tbody>
			<?php					
				$con = bancoMysqli();
				$sql_busca_dic = "SELECT DISTINCT idEvento FROM igsis_agenda AS age INNER JOIN ig_evento AS eve ON age.idEvento = eve.idEvento WHERE eve.publicado = 1 AND data <= '$data_final' AND data >= '$data_inicio' ORDER BY data";
				$query_busca_dic = mysqli_query($con,$sql_busca_dic);
				while($evento = mysqli_fetch_array($query_busca_dic))
				{
					$comunicacao = recuperaDados("ig_comunicacao",$evento['idEvento'],"ig_evento_idEvento");
					$event = recuperaDados("ig_evento",$evento['idEvento'],"idEvento");
					$nome = recuperaUsuario($event['idUsuario']);
					$chamado = recuperaAlteracoesEvento($evento['idEvento']);						
					if($event['dataEnvio'] != NULL AND $event['idInstituicao'] == $_SESSION['idInstituicao'])
					{
						// só as enviadas
			?>			
					<tr>					
						<td><?php echo retornaProtoEvento($evento['idEvento']) ?></td>
						<td><a href="?perfil=comunicacao&p=editar&idCom=<?php echo $comunicacao['idCom']  ?>"><?php echo $event['nomeEvento'] ?></a>  [<?php
						if($chamado['numero'] == '0')
						{
							echo "0";
						}
						else
						{
							echo "<a href='?perfil=chamado&p=evento&id=".$evento['idEvento']."' target='_blank'>".$chamado['numero']."</a>";	
						}
						?>]
						</td>
						<td><?php echo $nome['nomeCompleto'] ?></td>
						<td><?php echo retornaPeriodo($event['idEvento']) ?></td>
						<td><?php 
							if ($comunicacao['editado'] == 1) 
							{ 
								echo "<font color='blue'>[ E ]</font> ";  
							} 
							if ($comunicacao['revisado'] == 1) 
							{
								echo "<font color='#32CD32'>[ R ]</font> ";
							}
							if ($comunicacao['site'] == 1) 
							{
								echo "<font color='red'>[ S ]</font> ";
							}	
							if ($comunicacao['publicacao'] == 1) 
							{
								echo "<font color='orange'>[ I ]</font> ";
							}
							if ($comunicacao['foto'] == 1) 
							{
								echo "<font color='#DA70D6'>[ F ]</font>";
							}
						?></td>
					</tr>
				<?php
					}
				}
				?>							
				</tbody>
			</table>		   
		</div>
	</div>
</section>