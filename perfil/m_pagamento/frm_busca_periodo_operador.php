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

if(isset($_POST['periodo']))
{
	$inicio = exibirDataMysql($_POST['inicio']);
	$final = exibirDataMysql($_POST['final']);
	$idPagamentos = $_POST['operador'];

	if($idPagamentos == 0)
	{
		$operador = " ";
	}
	else
	{
		$operador = "AND ped.idPagamentos = '$idPagamentos'";
	}

	$con = bancoMysqli();
	$sql_pedido = "SELECT idPedidoContratacao FROM igsis_pedido_contratacao AS ped WHERE dataKitPagamento BETWEEN '$inicio' AND '$final' $operador AND ped.estado IN (1,2,3,4,5,6,7,8,9,10,13,14,16,17,18) ORDER BY dataKitPagamento ASC ";
	$query_pedido = mysqli_query($con,$sql_pedido);
	$num = mysqli_num_rows($query_pedido);
	$i = 0;
	while($ped = mysqli_fetch_array($query_pedido))
	{
		$pedido = recuperaDados("igsis_pedido_contratacao",$ped['idPedidoContratacao'],"idPedidoContratacao");
		$evento = recuperaDados("ig_evento",$pedido['idEvento'],"idEvento"); //$tabela,$idEvento,$campo
		$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
		$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
		$local = listaLocais($pedido['idEvento']);
		$periodo = retornaPeriodo($pedido['idEvento']);
		$duracao = retornaDuracao($pedido['idEvento']);
		$pessoa = recuperaPessoa($pedido['idPessoa'],$pedido['tipoPessoa']);
		$fiscal = recuperaUsuario($evento['idResponsavel']);
		$suplente = recuperaUsuario($evento['suplente']);
		$protocolo = ""; //recuperaDados("sis_protocolo",$pedido['idEvento'],"idEvento");
		$operador = recuperaUsuario($pedido['idPagamentos']);
		if($pedido['parcelas'] > 1)
		{
			$valorTotal = somaParcela($pedido['idPedidoContratacao'],$pedido['parcelas']);
			$formaPagamento = txtParcelas($pedido['idPedidoContratacao'],$pedido['parcelas']);	
		}
		else
		{
			$valorTotal = $pedido['valor'];
			$formaPagamento = $pedido['formaPagamento'];
		}
		if($pedido['publicado'] == 1)
		{
			$x[$i]['id']= $pedido['idPedidoContratacao'];
			$x[$i]['NumeroProcesso']= $pedido['NumeroProcesso'];
			$x[$i]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
			if($pedido['tipoPessoa'] == 1)
			{
				$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
				$x[$i]['proponente'] = $pessoa['Nome'];
				$x[$i]['tipo'] = "Física";
			}
			else
			{
				$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
				$x[$i]['proponente'] = $pessoa['RazaoSocial'];
				$x[$i]['tipo'] = "Jurídica";
			}
			$x[$i]['local'] = substr($local,1);
			$x[$i]['instituicao'] = $instituicao['sigla'];
			$x[$i]['periodo'] = $periodo;
			$x[$i]['status'] = $pedido['estado'];
			$x[$i]['valor'] = $pedido['valor'];
			$x[$i]['dataKitPagamento'] = exibirDataBr($pedido['dataKitPagamento']);
			$x[$i]['operador'] = $operador['nomeCompleto'];
			$i++;
		}
	}
	$x['num'] = $i;
	if($num > 0)
	{
		$server = "http://".$_SERVER['SERVER_NAME']."/igsis"; //mudar para pasta do igsis
		$http = $server."/pdf/";
		$link0 = $http."rlt_pedido_contratacao_pf.php";
		$link1 = $http."rlt_pedido_contratacao_pj.php";
?>

	<br />
	<br />
	<section id="list_items">
		<div class="container">
			<h3>Resultado da busca</3>
            <h5>Foram encontrados <?php echo $x['num']; ?> pedidos de contratação.</h5>
            <h5><a href="?perfil=pagamento&p=frm_busca_periodo_operador">Fazer outra busca</a></h5>
			<div class="table-responsive list_info">
				<table class="table table-condensed">
					<thead>
						<tr class="list_menu">
							<td>Processo</td>
							<td>Codigo do Pedido</td>
							<td>Proponente</td>
							<td>Tipo</td>
							<td>Objeto</td>
							<td>Período</td>
							<td>Valor</td>
							<td>Status</td>
							<td>Operador</td>
							<td>Kit Pagto</td>
							<td colspan="7">GERAR</td>
						</tr>
					</thead>
					<tbody>

					<?php
						$data=date('Y');
						for($h = 0; $h < $x['num']; $h++)
						{
							$status = recuperaDados("sis_estado",$x[$h]['status'],"idEstado");
							echo '<tr><td class="list_description">'.$x[$h]['NumeroProcesso'].			'</td>';
							if($x[$h]['tipo'] == 'Física' OR $x[$h]['tipo'] == 'Formação' )
							{
								echo '<td class="list_description"><a target="_blank" href="'.$link0.'?id='.$x[$h]['id'].'">'.substr($x[$h]['id'],6,11).'</a></td>';
							}
							else
							{
								echo '<td class="list_description"><a target="_blank" href="'.$link1.'?id='.$x[$h]['id'].'">'.substr($x[$h]['id'],6,11).'</a></td>';
							}
							echo '<td class="list_description">'.$x[$h]['proponente'].					'</td>';
							echo '<td class="list_description">'.substr($x[$h]['tipo'],0,1).						'</td>';
							echo '<td class="list_description">'.$x[$h]['objeto'].						'</td>';
							echo '<td class="list_description">'.$x[$h]['periodo'].						'</td>';
							echo '<td class="list_description">'.$x[$h]['valor'].'</td>';
							echo '<td class="list_description">'.$status['estado'].						'</td>';
							echo '<td class="list_description">'.$x[$h]['operador'].'</td>';
							echo '<td class="list_description">'.$x[$h]['dataKitPagamento'].'</td>';
							if($x[$h]['tipo'] == 'Física' OR $x[$h]['tipo'] == 'Formação' )
							{
								echo "<td><a href='?perfil=pagamento&p=frm_cadastra_notaempenho_pf&id_ped=".$x[$h]['id']."'>N.E.</a><td>";
								echo "<td><a href='?perfil=pagamento&p=frm_cadastra_pagamento_pf&id_ped=".$x[$h]['id']."'>PAGTO</a><td>";
								echo "<td><a href='?perfil=pagamento&p=frm_cadastra_liquidacao&id_ped=".$x[$h]['id']."'>LIQUID.</a><td>";
							}
							else
							{
								echo "<td><a href='?perfil=pagamento&p=frm_cadastra_notaempenho_pj&id_ped=".$x[$h]['id']."'>N.E.</a><td>";
								echo "<td><a href='?perfil=pagamento&p=frm_cadastra_pagamento_pj&id_ped=".$x[$h]['id']."'>PAGTO</a><td>";
								echo "<td><a href='?perfil=pagamento&p=frm_cadastra_liquidacao&id_ped=".$x[$h]['id']."'>LIQUID.</a><td>";
							}
							echo "</tr>";
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
						<h5>Busca por período </h5>
						<div class="section-heading">
							<h2>Busca por período / operador</h2>
							<p><?php if(isset($mensagem)){ echo $num; }?></p>
							<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>
						</div>
					</div>
				</div>
				<div class="row">
				<form method="POST" action="?perfil=pagamento&p=frm_busca_periodo_operador" class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6">
							<label>Data início *</label>
								<input type="text" name="inicio" class="form-control" id="datepicker01" placeholder="">
						</div>
						<div class=" col-md-6">
							<label>Data final *</label>
								<input type="text" name="final" class="form-control" id="datepicker02"  placeholder="">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Operador do Pagamento</label>
							<select class="form-control" name="operador" id="inputSubject" >
								<option value='0'></option>
								<?php
								$con = bancoMysqli();
                                $sql_operador = "SELECT * FROM ig_usuario WHERE idUsuario IN (270, 274, 275, 393, 424, 445, 655, 993, 1010, 1135, 1170, 1256, 1257) ORDER BY nomeCompleto";
								$query_operador = mysqli_query($con,$sql_operador);
								while($user = mysqli_fetch_array($query_operador))
								{
									echo "<option value='".$user['idUsuario']."'>".$user['nomeCompleto']."</option>";
								}
								?>
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
				<h5> Busca por período </h5>
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h2>Busca por período / operador</h2>
						<p><?php if(isset($mensagem)){ echo $num; }?></p>
					</div>
				</div>
			</div>
			<div class="row">
			<form method="POST" action="?perfil=pagamento&p=frm_busca_periodo_operador" class="form-horizontal" role="form">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<label>Data início *</label>
							<input type="text" name="inicio" class="form-control" id="datepicker01" placeholder="">
					</div>
					<div class=" col-md-6">
						<label>Data final *</label>
							<input type="text" name="final" class="form-control" id="datepicker02"  placeholder="">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<label>Operador do Pagamento</label>
						<select class="form-control" name="operador" id="inputSubject" >
							<option value='0'></option>
							<?php
							$con = bancoMysqli();
                            $sql_operador = "SELECT * FROM ig_usuario WHERE idUsuario IN (270, 274, 275, 393, 424, 445, 655, 993, 1010, 1135, 1170, 1256, 1257) ORDER BY nomeCompleto";
							$query_operador = mysqli_query($con,$sql_operador);
							while($user = mysqli_fetch_array($query_operador))
							{
								echo "<option value='".$user['idUsuario']."'>".$user['nomeCompleto']."</option>";
							}
							?>
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