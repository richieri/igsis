<?php 
include 'includes/menu.php';

	
$con = bancoMysqli();

$sql = "SELECT DISTINCT idPedidoContratacao FROM  ig_evento AS eve
		INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento = ped.idEvento
		WHERE eve.publicado = 1 AND ped.publicado =1 AND ped.estado = 14 ORDER BY idPedidoContratacao DESC";
$query = mysqli_query($con,$sql);

$i = 0;

while($lista = mysqli_fetch_array($query))
{
	$pedido = recuperaDados("igsis_pedido_contratacao",$lista['idPedidoContratacao'],"idPedidoContratacao");
	$evento = recuperaDados("ig_evento",$pedido['idEvento'],"idEvento"); //$tabela,$idEvento,$campo
	$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
	$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
	$loc = listaLocais($pedido['idEvento']);
	$local = substr($loc,1); //retira a vírgula no começo do texto
	$periodo = retornaPeriodo($pedido['idEvento']);
	$pessoa = recuperaPessoa($pedido['idPessoa'],$pedido['tipoPessoa']);
	$operador = recuperaUsuario($pedido['idContratos']);
	
	
	if($pedido['parcelas'] > 1)
	{
		$valorTotal = somaParcela($pedido['idPedidoContratacao'],$pedido['parcelas']);
	}
	else
	{
		$valorTotal = $pedido['valor'];
	}		
	$x[$i]['id']= $pedido['idPedidoContratacao'];
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
	$x[$i]['instituicao'] = $instituicao['sigla'];
	$x[$i]['periodo'] = $periodo;
	$x[$i]['local'] = $local;
	$x[$i]['operador'] = $operador['nomeCompleto'];	
	$x[$i]['status'] = $pedido['estado'];
	$x[$i]['parcelas'] = $pedido['parcelas'];
	$i++;	
}
$x['num'] = $i;

$mensagem = "Foram encontradas ".$x['num']." pedido(s) de contratação.";
?>

<section id="list_items">
	<h1>&nbsp;</h1>
	<div class="container">
		<div class="sub-title"><h2>USO PESSOAL</h2></div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>	
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td width="25%">Local</td>
						<td>Periodo</td>
						<td>Parcelas</td>
						<td>Operador</td>
						<td>Status</td>
						<td></td>
					</tr>
				</thead>
				<tbody>		
				<?php
					
					for($h = 0; $h < $x['num']; $h++)
					{
						$status = recuperaDados("sis_estado",$x[$h]['status'],"idEstado");
						echo '<tr>';
						/* REMOÇÃO DO LINK PARA EDIÇÃO
						if($x[$h]['tipo'] == 'Física')
						{
							echo "<td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapf&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
						}
						else
						{
							echo "<td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapj&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";	
						}
						*/
						echo "<td class='lista'> <a target='_blank' href='?perfil=detalhe_pedido&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
						echo '<td class="list_description">'.$x[$h]['proponente'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['objeto'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['local'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['periodo'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['parcelas'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['operador'].'</td> ';
						echo '<td class="list_description">'.$status['estado'].'</td> ';
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</section>