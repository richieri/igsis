<?php
$con = bancoMysqliProponente();

if(isset($_POST['pesquisar']))
{
	$idCapac = trim($_POST['idCapac']);
	$_SESSION['idCapac'] = $idCapac;

	$nomeEvento = addslashes($_POST['nomeEvento']);
	$_SESSION['nomeEvento'] = $nomeEvento;

	$nomeGrupo = addslashes($_POST['nomeGrupo']);
	$_SESSION['nomeGrupo'] = $nomeGrupo;

	$tipoEvento = $_POST['tipoEvento'];
	$_SESSION['tipoEvento'] = $tipoEvento;
}

$idCapac = $_SESSION['idCapac'];
$nomeEvento = $_SESSION['nomeEvento'];
$nomeGrupo = $_SESSION['nomeGrupo'];
$tipoEvento = $_SESSION['tipoEvento'];

if($idCapac != '')
{
	$filtro_id = " AND id  = '$idCapac'";
}
else
{
	$filtro_id = "";
}

if($nomeEvento != '')
{
	$filtro_nomeEvento = " AND nomeEvento LIKE '%$nomeEvento%'";
}
else
{
	$filtro_nomeEvento = "";
}

if($nomeGrupo != '')
{
	$filtro_nomeGrupo = " AND nomeGrupo LIKE '%$nomeGrupo%'";
}
else
{
	$filtro_nomeGrupo = "";
}

if($tipoEvento != 0)
{
	$filtro_tipoEvento = " AND idTipoEvento = '$tipoEvento'";
}
else
{
	$filtro_tipoEvento = "";
}

$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
$sql_lista = "SELECT `id`, `idTipoEvento`, `nomeEvento`, `nomeGrupo`, `dataCadastro`, `publicado` FROM `evento` WHERE publicado = '2' $filtro_id $filtro_nomeEvento $filtro_nomeGrupo $filtro_tipoEvento ORDER BY nomeEvento";
$query_lista = mysqli_query($con, $sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);

//seta a quantidade de itens por página
$registros = 30;

//calcula o número de páginas arredondando o resultado para cima
$numPaginas = ceil($total/$registros);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($registros*$pagina)-$registros;

//seleciona os itens por página
$sql_lista = "SELECT `id`, `idTipoEvento`, `nomeEvento`, `nomeGrupo`, `dataCadastro`, `publicado` FROM `evento` WHERE publicado = '2' $filtro_id $filtro_nomeEvento $filtro_nomeGrupo $filtro_tipoEvento ORDER BY nomeEvento limit $inicio,$registros ";
$query_lista = mysqli_query($con,$sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);

$i = 0;
while($resultado = mysqli_fetch_array($query_lista))
{
	$x[$i]['id']= $resultado['id'];
	$x[$i]['tipoEvento']=retornaTipo($resultado['idTipoEvento']);
	$x[$i]['nomeEvento']= $resultado['nomeEvento'];
	$x[$i]['nomeGrupo']= $resultado['nomeGrupo'];
	$x[$i]['dataCadastro']= exibirDataHoraBr($resultado['dataCadastro']);
	$i++;
}
$x['num'] = $i;
if($i <= '1')
{
	$mensagem = "Nesta página contém ".$x['num']." resultado.<br/>";
}
else
{
	$mensagem = "Nesta página contém ".$x['num']." resultados.";
}

include '../include/menuEventoInicial.php';
?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>Resultado da busca</h3>
			<h5><?php if(isset($mensagem)){echo $mensagem;};?></h5>
			<h5><a href="?perfil=capac_busca">Fazer outra busca</a></h5>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="table-responsive list_info">
					<table class="table table-condensed">
						<thead>
							<tr class="list_menu">
								<td>Codigo</td>
								<td>Tipo de Evento</td>
								<td>Nome do Evento</td>
								<td>Nome do Grupo</td>
								<td>Data do Cadastro</td>
								<td width="20%"></td>
							</tr>
						</thead>
						<tbody>
						<?php
							for($h = 0; $h < $x['num']; $h++)
							{
								echo '<tr>';
								echo '<td class="list_description">'.$x[$h]['id'].'</td>';
								echo '<td class="list_description">'.$x[$h]['tipoEvento'].'</td>';
								echo '<td class="list_description">'.$x[$h]['nomeEvento'].'</td>';
								echo '<td class="list_description">'.$x[$h]['nomeGrupo'].'</td>';
								echo '<td class="list_description">'.$x[$h]['dataCadastro'].'</td>';
								echo "<td><a class='btn btn-theme btn-md btn-block' target='_blank' href='?perfil=capac_detalhes&id_capac=".$x[$h]['id']."'>CARREGAR</a></td>";
								echo '</tr>';
							}
							?>
							<tr>
								<td colspan="10" bgcolor="#DEDEDE">
								<?php
									//exibe a paginação
									echo "<strong>Páginas</strong>";
									for($i = 1; $i < $numPaginas + 1; $i++)
									{
										echo "<a href='?perfil=capac_busca_resultado&pagina=$i'> [".$i."]</a> ";
									}
								?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>