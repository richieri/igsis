<?php 
include 'includes/menu.php';

	
$con = bancoMysqli();

$sql = "SELECT DISTINCT ped.idPedidoContratacao FROM  ig_evento AS eve		
		INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento = ped.idEvento
		INNER JOIN  ig_log_reabertura AS reab ON ped.idPedidoContratacao = reab.idPedido
		WHERE eve.dataEnvio IS NULL AND eve.publicado = 1 AND ped.publicado = 1 AND ped.estado IS NULL ORDER BY eve.idEvento DESC";
$query = mysqli_query($con,$sql);

$i = 0;

while($lista = mysqli_fetch_array($query))
{
	$pedido = recuperaDados("igsis_pedido_contratacao",$lista['idPedidoContratacao'],"idPedidoContratacao");
	$evento = recuperaDados("ig_evento",$pedido['idEvento'],"idEvento"); 
	$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
	$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
	$loc = listaLocais($pedido['idEvento']);
	$local = substr($loc,1); //retira a vírgula no começo do texto
	$periodo = retornaPeriodo($pedido['idEvento']);
	$pessoa = recuperaPessoa($pedido['idPessoa'],$pedido['tipoPessoa']);
	
	$recuperaOperador = recuperaUsuario($pedido['idContratos']);
	$operadorNome = explode(" ",$recuperaOperador['nomeCompleto']);
	$operador = $operadorNome[0];
	
	$reabertura = recuperaDados("ig_log_reabertura",$pedido['idPedidoContratacao'],"idPedido");
	$reaberturaUsuario = recuperaUsuario($reabertura['idUsuario']);
	$reabertoNome = explode(" ",$reaberturaUsuario['nomeCompleto']);
	$reabertoPor = $reabertoNome[0];
	
	$dataPrazo = date('d/m/Y', strtotime('-5 days', strtotime(retornaPrazo($pedido['idEvento']))));
	
	$dataInicial = retornaPrazo($pedido['idEvento']);
	$dataFinal = exibirDataMysql($dataPrazo);
	
	$hoje = date('d/m/y');
	$today = exibirDataMysql($hoje);
	
	// Calcula a diferença em segundos entre as datas
	$diferenca = strtotime($dataFinal) - strtotime($today);

	//Calcula a diferença em dias
	$dias = floor($diferenca / (60 * 60 * 24));
	
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
	$x[$i]['dataReabertura'] = exibirDataBr($reabertura['data']);
	$x[$i]['reabertoPor'] = $reabertoPor;
	$x[$i]['instituicao'] = $instituicao['sigla'];
	$x[$i]['periodo'] = $periodo;
	$x[$i]['local'] = $local;
	$x[$i]['operador'] = $operador;
	$x[$i]['dias'] = $dias;
	$i++;	
}
$x['num'] = $i;

$mensagem = "Foram encontradas ".$x['num']." pedido(s) de contratação.";
?>

<section id="list_items">
	<h1>&nbsp;</h1>
	<div class="container">
		<div class="sub-title"><h2>EVENTOS REABERTOS E SEM REENVIO</h2></div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>	
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td width="20%">Local</td>
						<td>Periodo</td>
						<td>Data Reabertura</td>
						<td>Reaberto Por</td>
						<td>Prazo (Dias)</td>
						<td>Operador</td>
						<td></td>
					</tr>
				</thead>
				<tbody>		
				<?php
					
					for($h = 0; $h < $x['num']; $h++)
					{
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
						echo '<td class="list_description">'.$x[$h]['dataReabertura'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['reabertoPor'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['dias'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['operador'].'</td> ';
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</section>