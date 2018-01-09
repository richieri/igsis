<?php
include 'includes/menu.php';

$link="index.php?perfil=curadoria&p=detalhe_evento&id_eve=";

$con = bancoMysqli();
$usr = recuperaDados('ig_usuario',$_SESSION['idUsuario'],'idUsuario');
$localUsr = $usr['local'];
/*$sql_lista = "
	SELECT DISTINCT idEvento FROM ig_ocorrencia WHERE publicado = 1 AND idEvento IN (SELECT idEvento FROM ig_evento WHERE publicado = 1 AND statusEvento = 'Aguardando' AND dataEnvio IS NULL AND idEvento NOT IN (SELECT DISTINCT idEvento FROM igsis_pedido_contratacao WHERE publicado = 1 AND estado IS NULL) )AND local IN ($localUsr) ORDER BY dataInicio DESC";
*/	
$sql_lista = "SELECT DISTINCT eve.idEvento FROM ig_evento AS eve INNER JOIN ig_ocorrencia AS oco ON oco.idEvento=eve.idEvento WHERE eve.publicado = 1 AND local IN ($localUsr) ORDER BY idEvento DESC";	
$query_lista = mysqli_query($con,$sql_lista);
?>

<section id="list_items">
	<div class="container">
		<div class="sub-title"><br/><br/><h4>Eventos</h4>
		</div>
		<div class="table-responsive list_info">
			<table class="table table-condensed"><script type=text/javascript language=JavaScript src=../js/find2.js> </script>
				<thead>
					<tr class="list_menu">
						<td>Id Evento</td>
						<td width="40%">Nome do evento</td>
						<td>Responsável</td>
						<td width="20%">Local</td>
						<td>Período</td>
						<td width="10%">Valor do Contrato</td>
					</tr>
				</thead>
				<tbody>
			
				<?php
					$total = 0;
					while($evento = mysqli_fetch_array($query_lista))
					{
						$event = recuperaDados("ig_evento",$evento['idEvento'],"idEvento");
						$usuario = recuperaDados("ig_usuario",$event['idResponsavel'],"idUsuario");
						echo "<tr><td class='lista'><a href='".$link.$evento['idEvento']."' >".$evento['idEvento']."</a></td>";
						echo "<td class='lista'>".$event['nomeEvento']."</td>";
						echo "<td class='lista'>".$usuario['nomeCompleto']."</td>";
						echo "<td class='lista'>".substr(listaLocais($evento['idEvento']),1)."</td>";
						echo "<td class='lista'>".retornaPeriodo($evento['idEvento'])."</td>";
						echo "<td class='list_description'>".dinheiroParaBr(infoContrato($evento['idEvento']))."</td>"	;
						echo "</tr>";		
						$val = 	infoContrato($evento['idEvento']);		
						$total = $total + $val;					
					}  
				?>	
				</tbody>
			</table>
			<h5>Total: <?php echo dinheiroParaBr($total); ?></h5>
		</div>
	</div>
</section>