<?php
$con = bancoMysqliProponente();

$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
$sql_lista = "SELECT eve.id, idTipoEvento, nomeEvento, razaoSocial, cnpj, dataCadastro, publicado
				FROM evento AS eve
				INNER JOIN pessoa_juridica AS pj ON pj.id = eve.idPj
				WHERE idTipoPessoa = '2' OR idTipoPessoa = '5' AND publicado = '2' ORDER BY nomeEvento";
$query_lista = mysqli_query($con, $sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);

//seta a quantidade de itens por página
$registros = 100;

//calcula o número de páginas arredondando o resultado para cima
$numPaginas = ceil($total/$registros);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($registros*$pagina)-$registros;

//seleciona os itens por página
$sql_lista = "SELECT eve.id, idTipoEvento, nomeEvento, razaoSocial, cnpj, dataCadastro, publicado
				FROM evento AS eve
				INNER JOIN pessoa_juridica AS pj ON pj.id = eve.idPj
				WHERE idTipoPessoa = '2' OR idTipoPessoa = '5' AND publicado = '2' ORDER BY nomeEvento limit $inicio,$registros ";
$query_lista = mysqli_query($con,$sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);

$i = 0;
while($resultado = mysqli_fetch_array($query_lista))
{
	$x[$i]['id']= $resultado['id'];
	$x[$i]['tipoEvento']=retornaTipo($resultado['idTipoEvento']);
	$x[$i]['nomeEvento']= $resultado['nomeEvento'];
	$x[$i]['razaoSocial']= $resultado['razaoSocial'];
	$x[$i]['cnpj']= $resultado['cnpj'];
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
			<h5><a href="?perfil=capac_busca">Fazer uma busca</a></h5>
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
								<td>Razão Social</td>
								<td>CNPJ</td>
								<td>Data do Cadastro</td>
								<td width="10%"></td>
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
								echo '<td class="list_description">'.$x[$h]['razaoSocial'].'</td>';
								echo '<td class="list_description">'.$x[$h]['cnpj'].'</td>';
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
										echo "<a href='?perfil=capac_lista_pj&pagina=$i'> [".$i."]</a> ";
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