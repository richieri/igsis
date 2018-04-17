<?php include 'includes/menu.php';

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

/*POST*/
if(isset($_POST['periodo']))
{
	$processo = $_POST['processo'];
	$idContratos = $_POST['operador'];

	if($idContratos == 0)
	{
		$operador = " ";
	}
	else
	{
		$operador = "AND ped.idContratos = '$idContratos'";
	}

	if($processo == 0)
	{
		$filtro_processo = "AND (ped.NumeroProcesso IS NULL OR ped.NumeroProcesso = '')";
	}
	else
	{
		$filtro_processo = "AND ped.NumeroProcesso IS NOT NULL";
	}

	$con = bancoMysqli();
	$sql_evento =
	  	"SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
		FROM ig_evento AS eve
		INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
		INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
		WHERE eve.publicado=1 AND ped.publicado=1 AND eve.projetoEspecial IN (54) $operador $filtro_processo";

	   $query_evento = mysqli_query($con,$sql_evento);
	   $num = mysqli_num_rows($query_evento);
	   $i = 0;

	   while($evento = mysqli_fetch_array($query_evento))
	   {
		$idEvento = $evento['idEvento'];
		$pedido = recuperaDados("igsis_pedido_contratacao",$evento['idPedidoContratacao'],"idPedidoContratacao");
		$event = recuperaDados("ig_evento",$evento['idEvento'],"idEvento");
		$usuario = recuperaDados("ig_usuario",$event['idUsuario'],"idUsuario");
		$instituicao = recuperaDados("ig_instituicao",$event['idInstituicao'],"idInstituicao");
		$local = listaLocais($pedido['idEvento']);
		$periodo = retornaPeriodo($pedido['idEvento']);
		$operador = recuperaUsuario($pedido['idContratos']);

		$x[$i]['id']= $pedido['idPedidoContratacao'];
		$x[$i]['NumeroProcesso']= $pedido['NumeroProcesso'];
		$x[$i]['objeto'] = retornaTipo($event['ig_tipo_evento_idTipoEvento'])." - ".$event['nomeEvento'];
		if($pedido['tipoPessoa'] == 1)
		{
			$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
			$x[$i]['proponente'] = $pessoa['Nome'];
			$x[$i]['documento'] = $pessoa['CPF'];
			$x[$i]['tipo'] = "Física";
		}
		else
		{
			$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
			$x[$i]['proponente'] = $pessoa['RazaoSocial'];
			$x[$i]['documento'] = $pessoa['CNPJ'];
			$x[$i]['tipo'] = "Jurídica";
		}
		$x[$i]['local'] = substr($local,1);
		$x[$i]['periodo'] = $periodo;
		$x[$i]['valor']= $pedido['valor'];
		$x[$i]['pendencia'] = $pedido['pendenciaDocumento'];
		$x[$i]['status'] = $pedido['estado'];
		$x[$i]['operador'] = $operador['nomeCompleto'];
		$i++;
	}
	$x['num'] = $i;
	if($num > 0)
	{
?>

	<br />
	<br />
	<section id="list_items">
		<div class="container">
			<h3>Resultado da busca</3>
            <h5>Foram encontrados <?php echo $x['num']; ?> pedidos de contratação.</h5>
            <h5><a href="?perfil=contratos&p=frm_busca_especial_operador">Fazer outra busca</a></h5>
			<div class="table-responsive list_info">
				<table class="table table-condensed">
					<thead>
						<tr class="list_menu">
							<td>Codigo do Pedido</td>
							<td>Número do Processo</td>
							<td>CNPJ</td>
							<td>Proponente</td>
							<td>Objeto</td>
							<td>Local</td>
							<td>Período</td>
							<td>Pendências</td>
							<td>Operador</td>
							<td>Status</td>
						</tr>
					</thead>
					<tbody>

					<?php
						$data=date('Y');
						for($h = 0; $h < $x['num']; $h++)
						{
							 $status = recuperaDados("sis_estado",$x[$h]['status'],"idEstado");
							if($x[$h]['tipo'] == 'Física')
							{
								echo "<tr><td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapf&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
							}
							else
							{
								echo "<tr><td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapj&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
							}
							echo '<td class="list_description">'.$x[$h]['NumeroProcesso'].'</td> ';
							echo '<td class="list_description">'.$x[$h]['proponente'].'</td> ';
							echo '<td class="list_description">'.$x[$h]['objeto'].'</td> ';
							echo '<td class="list_description">'.$x[$h]['local'].'</td> ';
							echo '<td class="list_description">'.$x[$h]['periodo'].'</td> ';
							echo '<td class="list_description">'.$x[$h]['valor'].'</td> ';
							echo '<td class="list_description">'.$x[$h]['pendencia'].'</td> ';
							echo '<td class="list_description">'.$x[$h]['operador'].'</td> ';
							echo '<td class="list_description">'.$status['estado'].'</td> </tr>';
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</section>

<?php
	}
	else
	{
?>
		<section id="services" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h4>Busca Virada Cultural</h4>
							<p><?php if(isset($mensagem)){ echo $num; }?></p>
							<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<form method="POST" action="?perfil=contratos&p=frm_busca_especial_operador" class="form-horizontal" role="form">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6">
								<label>Número de processo:</label>
								<select class="form-control" name="processo" id="inputSubject" >
									<option value='0'>Sem número</option>
									<option value='1'>Com número</option>
								</select>
							</div>
							<div class=" col-md-6">
								<label>Operador do Contrato</label>
								<select class="form-control" name="operador" id="inputSubject" >
									<option value='0'></option>
									<?php  geraOpcaoContrato(""); ?>
								</select>
							</div>
						</div>
						<br />
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="periodo" value="1" />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>
<?php
	}
}
else
{
?>
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h4>Busca Virada Cultural</h4>
						<p><?php if(isset($mensagem)){ echo $num; }?></p>
					</div>
				</div>
			</div>
			<div class="row">
			<form method="POST" action="?perfil=contratos&p=frm_busca_especial_operador" class="form-horizontal" role="form">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<label>Número de processo:</label>
						<select class="form-control" name="processo" id="inputSubject" >
							<option value='0'>Sem número</option>
							<option value='1'>Com número</option>
						</select>
					</div>
					<div class=" col-md-6">
						<label>Operador do Contrato</label>
						<select class="form-control" name="operador" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcaoContrato(""); ?>
						</select>
					</div>
				</div>
				<br />
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="periodo" value="1" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
					</div>
				</div>
			</form>
			</div>
		</div>
	</section>
<?php
}
 /* =========== INICIAL ===========*/ break;

} //fim da switch
?>