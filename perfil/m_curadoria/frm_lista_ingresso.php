<?php
include 'includes/menu.php';
$con = bancoMysqli();
$usr = recuperaDados('ig_usuario',$_SESSION['idUsuario'],'idUsuario');
$localUsr = $usr['local'];

$link="index.php?perfil=curadoria&p=detalhe_evento&id_eve=";

//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
$idInsituicao = $_SESSION['idInstituicao'];
$sql_lista = "
	SELECT eve.idEvento, eve.nomeEvento, eve.releaseCom, age.data, age.hora, loc.sala, oco.valorIngresso
	FROM igsis_agenda AS age
	INNER JOIN ig_evento AS eve ON eve.idEvento = age.idEvento
	INNER JOIN ig_local AS loc ON loc.idLocal = age.idLocal
	INNER JOIN ig_ocorrencia AS oco ON oco.idOcorrencia = age.idOcorrencia
	WHERE eve.publicado=1 AND eve.dataEnvio IS NOT NULL AND oco.local IN ($localUsr)
	ORDER BY eve.idEvento DESC, age.data, age.hora";
$query_lista = mysqli_query($con,$sql_lista);

//conta o total de itens
$total_geral = mysqli_num_rows($query_lista);

//seta a quantidade de itens por página
$registros = 100;

//calcula o número de páginas arredondando o resultado para cima
$numPaginas = ceil($total_geral/$registros);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($registros*$pagina)-$registros;

//seleciona os itens por página
$sql_lista = "
	SELECT eve.idEvento, eve.nomeEvento, eve.releaseCom, age.data, age.hora, loc.sala, oco.valorIngresso
	FROM igsis_agenda AS age
	INNER JOIN ig_evento AS eve ON eve.idEvento = age.idEvento
	INNER JOIN ig_local AS loc ON loc.idLocal = age.idLocal
	INNER JOIN ig_ocorrencia AS oco ON oco.idOcorrencia = age.idOcorrencia
	WHERE eve.publicado=1 AND eve.dataEnvio IS NOT NULL AND oco.local IN ($localUsr)
	ORDER BY eve.idEvento DESC, age.data, age.hora
	LIMIT $inicio,$registros";
$query_lista = mysqli_query($con,$sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);
?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="sub-title"><h4>EVENTOS</h4>
		</div>
		<p><strong>Total de registros:</strong> <?php echo $total_geral;?> | <strong>Registros nesta página:</strong> <?php echo $total;?></p>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do evento</td>
						<td>Nome do evento</td>
						<td>Release</td>
						<td>Data</td>
						<td>Horário inicial</td>
						<td>Local</td>
						<td>Ingresso</td>
					</tr>
				</thead>
				<tbody>
			<?php
				while($campo = mysqli_fetch_array($query_lista))
				{
					echo "<tr>";
					echo "<td class='list_description'><a href='".$link.$campo['idEvento']."' target='_blank'>".$campo['idEvento']."</a></td>";
					echo "<td class='list_description'>".$campo['nomeEvento']."</td>";
					echo "<td class='list_description'>".$campo['releaseCom']."</td>";
					echo "<td class='list_description'>".exibirDataBr($campo['data'])."</td>";
					echo "<td class='list_description'>".$campo['hora']."</td>";
					echo "<td class='list_description'>".$campo['sala']."</td>";
					echo "<td class='list_description'>".dinheiroParaBr($campo['valorIngresso'])."</td>";
					echo "</tr>";
				}
			?>
					<tr>
						<td colspan="10" bgcolor="#DEDEDE">
						<?php
							//exibe a paginação
							echo "<strong>Páginas</strong>";
							for($i = 1; $i < $numPaginas + 1; $i++)
							{
								echo "<a href='?perfil=curadoria&p=frm_lista_ingresso&pagina=$i'> [".$i."]</a> ";
							}
						?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>