<?php
	include 'includes/menu.php';
	$con = bancoMysqli();
	$ano = date('Y');
	$pasta = "?perfil=emia&p=frm_lista_pedidocontratacao_pf&enviados=1&pag=";
	if(isset($_GET['pag']))
	{
		$p = $_GET['pag'];
	}
	else
	{
		$p = $ano;	
	}	
	$link="?perfil=emia&&p=frm_cadastra_pedidocontratacao_pf&id_ped=";
	switch($_GET['enviados'])
	{
		case 1:
?>
<section id="list_items">
	<div class="container">
		<div class="sub-title">
			<br/><br/>
			<h4>Escolha o ano<br/>
                | <a href="<?php echo $pasta?>2017">2017</a>
                | <a href="<?php echo $pasta?>2018">2018</a>
                | <a href="<?php echo $pasta?>2019">2019</a> |
			</h4>
		</div>
	</div>
</section>
<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6>PEDIDOS DE CONTRATAÇÃO DE PESSOA FÍSICA ENVIADOS</h6></div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Periodo</td>
						<td>Status</td>
					</tr>
				</thead>
				<tbody>
            <?php
            $sql_enviados = "SELECT ped.idPedidoContratacao,idPessoa, Ano FROM igsis_pedido_contratacao AS ped INNER JOIN sis_emia ON sis_emia.idPedidoContratacao = ped.idPedidoContratacao WHERE estado IS NOT NULL AND tipoPessoa = '5' AND ped.publicado = '1' AND Ano = $p ORDER BY idPedidoContratacao DESC";
			$data=date('Y');
			$query_enviados = mysqli_query($con,$sql_enviados);
			while($pedido = mysqli_fetch_array($query_enviados))
			{
				$pf = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
				$ped = siscontrat($pedido['idPedidoContratacao']);	 
				echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
				echo '<td class="list_description">'.$pf['Nome'].					'</td> ';
				echo '<td class="list_description">'.$ped['Objeto'].						'</td> ';
				echo '<td class="list_description">'.$ped['Local'].				'</td> ';
				echo '<td class="list_description">'.$ped['Periodo'].						'</td> ';
				echo '<td class="list_description">'.retornaEstado($ped['Status']).						'</td> </tr>';
			}
					?>
				</tbody>	
			</table>
		</div>
	</div>
</section>
	<?php 
		break;
		case 0:
	?>
<br /><br /><br />
 <!-- inicio_list -->
<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6>PEDIDOS DE CONTRATAÇÃO DE PESSOA FÍSICA NÃO ENVIADOS</h6></div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Periodo</td>
					</tr>
				</thead>
				<tbody>
		<?php
			$data=date('Y');
			$sql_n_enviados = "SELECT ped.idPedidoContratacao,idPessoa,idEmia FROM igsis_pedido_contratacao AS ped INNER JOIN  sis_emia AS emi ON emi.idPedidoContratacao = ped.idPedidoContratacao WHERE estado IS NULL AND tipoPessoa = '5' AND ped.publicado = '1' ORDER BY ped.idPedidoContratacao DESC";
			$query_n_enviados = mysqli_query($con,$sql_n_enviados);
			while($pedido = mysqli_fetch_array($query_n_enviados))
			{
				$pf = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
				$ped = siscontrat($pedido['idPedidoContratacao']);	 
				echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
				echo '<td class="list_description">'.$pf['Nome'].'</td> ';
				echo '<td class="list_description">'.$ped['Objeto'].'</td> ';
				echo '<td class="list_description">'.$ped['Local'].'</td> ';
				echo '<td class="list_description">'.$ped['Periodo'].'</td> </tr>';
			}
?>
				</tbody>
			</table>
		</div>
	</div>
</section>
	<?php 
		break;
	}
	?>    
<!--fim_list-->