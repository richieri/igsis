<?php
$con = bancoMysqliProponente();

if(isset($_POST['pesquisar']))
{
	$id = trim($_POST['id']);
	$nomeEvento = addslashes($_POST['nomeEvento']);
	$nomeGrupo = addslashes($_POST['nomeGrupo']);
	$tipoEvento = $_POST['tipoEvento'];


	if($id != '')
	{
		$filtro_id = " AND id  = '$id'";
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

	$sql_existe = "SELECT `id`, `idTipoEvento`, `nomeEvento`, `nomeGrupo`, `dataCadastro`, `publicado` FROM `evento` WHERE publicado = '1' $filtro_id $filtro_nomeEvento $filtro_nomeGrupo $filtro_tipoEvento ORDER BY nomeEvento";
	$query_existe = mysqli_query($con, $sql_existe);
	$num_registro = mysqli_num_rows($query_existe);
	$i = 0;
	while($resultado = mysqli_fetch_array($query_existe))
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
		$mensagem = "Foi encontrado ".$x['num']." cadastro.<br/>";
	}
	else
	{
		$mensagem = "Foram encontrados ".$x['num']." cadastros.";
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
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php
}
else
{
	include '../include/menuEventoInicial.php';
?>

	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="form-group">
				<h3>BUSCAR EVENTOS NO CAPAC</h3>
				<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>
				<h5><?php if(isset($mensagem)){echo $mensagem;};?></h5>
			</div>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<form method="POST" action="?perfil=capac_busca" class="form-horizontal" role="form">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Código do cadastro no CAPAC</strong><br/>
								<input type="text" name="id" class="form-control" placeholder="Insira o Código do Evento" >
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Nome do Evento</strong><br/>
								<input type="text" name="nomeEvento" class="form-control" placeholder="Insira nome do evento" >
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Nome do Grupo</strong><br/>
								<input type="text" name="nomeGrupo" class="form-control" placeholder="Insira o nome do grupo" >
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Tipo de evento</strong><br/>
								<select class="form-control" name="tipoEvento" id="inputSubject" >
									<option value="0"></option>
									<?php
									$sql = "SELECT * FROM tipo_evento";
									$query = mysqli_query($con,$sql);
									while($option = mysqli_fetch_row($query))
									{
										echo "<option value='".$option[0]."'>".$option[1]."</option>";
									}
									?>
								</select>
							</div>
						</div>
			            <div class="form-group">
				            <div class="col-md-offset-2 col-md-8">
								<input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Pesquisar">
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