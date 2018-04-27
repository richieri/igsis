<?php
	include 'includes/menu.php';
	$con = bancoMysqli();
	$ano = date('Y');
	$pasta = "?perfil=formacao&p=frm_lista_pedidocontratacao_pf&enviados=1&pag=";

	if(isset($_POST['apagarPedido']))
			{
				$idPedidoContratacao = $_POST['idPedidoContratacao'];
				$sql_apagar_pedido = "UPDATE igsis_pedido_contratacao SET publicado = '0' WHERE idPedidoContratacao = '$idPedidoContratacao'";
				$query_apagar_pedido = mysqli_query($con,$sql_apagar_pedido);
				if($query_apagar_pedido)
				{
					gravarLog($sql_apagar_pedido);
					$mensagem = "Pedido apagado com sucesso.";
				}
				else
				{
					$mensagem = "Erro ao apagar o pedido! Tente novamente.";
				}
			}

	if(isset($_GET['pag']))
	{
		$p = $_GET['pag'];
	}
	else
	{
		$p = $ano;	
	}	
	$link="?perfil=formacao&&p=frm_cadastra_pedidocontratacao_pf&id_ped=";
	switch($_GET['enviados'])
	{
		case 1:
?>
<section id="list_items">
	<div class="container">
		<div class="sub-title">
			<br/><br/>
			<h4>Escolha o ano<br/> 
			| <a href="<?php echo $pasta?><?php echo $ano;?>"><?php echo $ano; ?></a> 
			| <a href="<?php echo $pasta?><?php echo $ano - 1; ?>"><?php echo $ano - 1; ?></a> |
			</h4>
		</div>
	</div>
</section>
<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6>PEDIDOS ENVIADOS DE CONTRATAÇÃO DE PESSOA FÍSICA</h6></div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Código do Pedido</td>
						<td>Processo</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Período</td>
						<td>Status</td>
					</tr>
				</thead>
				<tbody>
					<?php
			switch($p)
			{
				case $ano:
					$sql_enviados = "SELECT ped.idPedidoContratacao,idPessoa, Ano FROM igsis_pedido_contratacao AS ped INNER JOIN sis_formacao ON sis_formacao.idPedidoContratacao = ped.idPedidoContratacao WHERE estado IS NOT NULL AND tipoPessoa = '4' AND ped.publicado = '1' AND Ano = $ano ORDER BY idPedidoContratacao DESC";
				break;
				case $ano - 1:
					$sql_enviados = "SELECT ped.idPedidoContratacao,idPessoa, Ano FROM igsis_pedido_contratacao AS ped INNER JOIN sis_formacao ON sis_formacao.idPedidoContratacao = ped.idPedidoContratacao WHERE estado IS NOT NULL AND tipoPessoa = '4' AND ped.publicado = '1' AND Ano = $ano - 1 ORDER BY idPedidoContratacao DESC";
				break; 
			} 	
			$data=date('Y');
			$query_enviados = mysqli_query($con,$sql_enviados);
			while($pedido = mysqli_fetch_array($query_enviados))
			{
				$linha_tabela_pedido_contratacaopf = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
				$ped = siscontrat($pedido['idPedidoContratacao']);
				echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
				echo '<td class="list_description">'.$ped['NumeroProcesso'].						'</td> ';
				echo '<td class="list_description">'.$linha_tabela_pedido_contratacaopf['Nome'].					'</td> ';
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
		<div class="sub-title"><h6>PEDIDOS NÃO ENVIADOS DE CONTRATAÇÃO DE PESSOA FÍSICA</h6></div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
					<td>Código do Pedido</td>
					<td>Proponente</td>
					<td>Objeto</td>
					<td>Local</td>
					<td>Período</td>
					<td></td>
					</tr>
				</thead>
				<tbody>
		<?php
			$data=date('Y');
			$sql_n_enviados = "SELECT idPedidoContratacao,idPessoa FROM igsis_pedido_contratacao WHERE estado IS NULL AND tipoPessoa = '4' AND publicado = '1' ORDER BY idPedidoContratacao DESC";
			$query_n_enviados = mysqli_query($con,$sql_n_enviados);
			while($pedido = mysqli_fetch_array($query_n_enviados))
			{
				$linha_tabela_pedido_contratacaopf = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
				$ped = siscontrat($pedido['idPedidoContratacao']);	 
				echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
				echo '<td class="list_description">'.$linha_tabela_pedido_contratacaopf['Nome'].					'</td> ';
				echo '<td class="list_description">'.$ped['Objeto'].						'</td> ';
				echo '<td class="list_description">'.$ped['Local'].				'</td> ';
				echo '<td class="list_description">'.$ped['Periodo'].						'</td> ';
				echo "
						<td class='list_description'>
						<form method='POST' action='?perfil=formacao&p=frm_lista_pedidocontratacao_pf&enviados=0'>
						<input type='hidden' name=apagarPedido value='1'>
						<input type='hidden' name='idPedidoContratacao' value='".$pedido['idPedidoContratacao']."'>
						<input type ='submit' class='btn btn-theme btn-sm btn-block'";
					echo " value='apagar'></td></form>"	; //botão de apagar
					echo "</tr>";
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