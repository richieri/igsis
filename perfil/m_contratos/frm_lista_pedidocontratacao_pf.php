<?php
include 'includes/menu.php';	

$con = bancoMysqli();
$link="?perfil=contratos&p=frm_edita_proposta_formacao&id_ped=";
$ano=date('Y');

if(isset($_POST['reabrir']))
{
	$idPedido = $_POST['reabrir'];
	$sql_pedido = "UPDATE igsis_pedido_contratacao SET estado = NULL WHERE idPedidoContratacao = '$idPedido'";
	$query_pedido = mysqli_query($con,$sql_pedido);
	if($query_pedido)
	{
		$sql_formacao = "UPDATE sis_formacao SET idPedidoContratacao = NULL WHERE idPedidoContratacao = '$idPedido'";
		$query_formacao = mysqli_query($con,$sql_formacao);
		if($query_formacao)
		{
			$mensagem = "Reabertura do Pedido $idPedido realizado com sucesso.";
		}
		else
		{
			$mensagem = "Erro(1).";	
		}
	}
	else
	{
		$mensagem = "Erro(2).";	
	}	
}

switch($_GET['enviados'])
{
	case 1:

?>
<br /><br /><br />

<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6>PEDIDO ENVIADOS DE CONTRATAÇÃO DE PESSOA FÍSICA FORMAÇÃO - <?php echo $ano ?></h6>
		</div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>	
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Periodo</td>
						<td>Pendências</td>
						<td>Operador</td>
						<td>Status</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
			<?php
				$sql_enviados = "SELECT igsis_pedido_contratacao.idPedidoContratacao,idPessoa, idContratos FROM igsis_pedido_contratacao 
					INNER JOIN sis_formacao ON igsis_pedido_contratacao.idPedidoContratacao=sis_formacao.idPedidoContratacao
					WHERE estado IS NOT NULL AND tipoPessoa = '4' AND igsis_pedido_contratacao.publicado = '1' AND sis_formacao.Ano = '".$ano."' 
					ORDER BY idPedidoContratacao DESC";
				$data=date('Y');
				$query_enviados = mysqli_query($con,$sql_enviados);
				while($pedido = mysqli_fetch_array($query_enviados))
				{
					$linha_tabela_pedido_contratacaopf = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$operador = recuperaUsuario($pedido['idContratos']);
					echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
					echo '<td class="list_description">'.$linha_tabela_pedido_contratacaopf['Nome'].'</td> 
					<td class="list_description">'.$ped['Objeto'].'</td> 
					<td class="list_description">'.$ped['Local'].'</td> 
					<td class="list_description">'.$ped['Periodo'].'</td> 
					<td class="list_description">'.$ped['pendenciaDocumento'].'</td> 
					<td class="list_description">'.$operador['nomeCompleto'].'</td> 
					<td class="list_description">'.retornaEstado($ped['Status']).'</td>'; 
					echo "
					<td class='list_description'>
					<form method='POST' action='?perfil=contratos&p=frm_lista_pedidocontratacao_pf&enviados=1'>
					<input type='hidden' name='reabrir' value='".$pedido['idPedidoContratacao']."' />
					<input type ='submit' class='btn btn-theme btn-block' value='reabrir'></td></form>"	;
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
case 2:

?>
<br /><br /><br />
	 <!-- inicio_list -->
	<section id="list_items">
		<div class="container">
			<div class="sub-title"><h6>PEDIDOS NÃO ENVIADOS DE CONTRATAÇÃO DE PESSOA FÍSICA</h6>
			</div>
			<div class="table-responsive list_info">
				<table class="table table-condensed">
					<thead>
						<tr class="list_menu">
							<td>Codigo do Pedido</td>
							<td>Proponente</td>
							<td>Objeto</td>
							<td>Local</td>
							<td>Periodo</td>
							<td>Pendências</td>
							<td>Operador</td>
							<td>Status</td>
						</tr>
					</thead>
					<tbody>
				<?php
					$data=date('Y');
					$sql_n_enviados = "SELECT igsis_pedido_contratacao.idPedidoContratacao,idPessoa FROM igsis_pedido_contratacao 
						INNER JOIN sis_formacao ON igsis_pedido_contratacao.idPedidoContratacao=sis_formacao.idPedidoContratacao
						WHERE estado IS NULL AND tipoPessoa = '4' AND igsis_pedido_contratacao.publicado = '1' AND sis_formacao.Ano = '".$ano."' 
						ORDER BY idPedidoContratacao DESC";
					$query_n_enviados = mysqli_query($con,$sql_n_enviados);					
					while($pedido = mysqli_fetch_array($query_n_enviados))
					{
						$linha_tabela_pedido_contratacaopf = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
						$ped = siscontrat($pedido['idPedidoContratacao']);	 
						echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
						echo '<td class="list_description">'.$linha_tabela_pedido_contratacaopf['Nome'].'</td> 
						<td class="list_description">'.$ped['Objeto'].'</td> 
						<td class="list_description">'.$ped['Local'].'</td> 
						<td class="list_description">'.$ped['Periodo'].'</td> 
						<td class="list_description">'.$ped['pendenciaDocumento'].'</td> 
						<td class="list_description">'.retornaEstado($ped['Status']).'</td>'; 
						echo "
						<td class='list_description'>
						<form method='POST' action='?perfil=contratos&p=frm_lista_pedidocontratacao_pf&enviados=1'>
						<input type='hidden' name='reabrir' value='".$pedido['idPedidoContratacao']."' />
						<input type ='submit' class='btn btn-theme btn-block' value='reabrir'></td></form>"	;
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