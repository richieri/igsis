<?php
include 'includes/menu.php';
$con = bancoMysqli();
switch($_GET['atribuido'])
{
	case 0:
?>
<br /><br /><br />

<br /><br /><br />
<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6>PEDIDO DE CONTRATAÇÃO VIRADA CULTURAL</h6>
		</div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Número do Processo</td>
						<td>Documento</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Período</td>
						<td>Pendências</td>
						<td>Operador</td>
						<td>Status</td>
						<td width="7%"></td>
					</tr>
				</thead>
				<tbody>
			<?php
				$sql_enviados = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
					WHERE eve.publicado=1 AND eve.dataEnvio IS NOT NULL AND ped.publicado=1 AND (ped.NumeroProcesso = NULL OR ped.NumeroProcesso = '') AND eve.projetoEspecial IN (69)
					ORDER BY idPedidoContratacao DESC";
				$query_enviados = mysqli_query($con,$sql_enviados);
				while($pedido = mysqli_fetch_array($query_enviados))
				{
					$pj = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$operador = recuperaUsuario($pedido['idContratos']);
					if($ped['tipoPessoa'] == 1)
					{
						$link="?perfil=contratos&p=frm_edita_propostapf&id_ped=";
					}
					else
					{
						$link="?perfil=contratos&p=frm_edita_propostapj&id_ped=";
					}
					echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
					echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td>';
					if($ped['tipoPessoa'] == 1)
					{
						$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
						echo '<td class="list_description">'.$pessoa['CPF'].'</td>';
						echo '<td class="list_description">'.$pessoa['Nome'].'</td>';
					}
					else
					{
						$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
						echo '<td class="list_description">'.$pessoa['CNPJ'].'</td>';
						echo '<td class="list_description">'.$pessoa['RazaoSocial'].'</td>';
					}
					echo '
					<td class="list_description">'.$ped['Objeto'].'</td>
					<td class="list_description">'.$ped['Local'].'</td> 
					<td class="list_description">'.$ped['Periodo'].'</td> 
					<td class="list_description">'.$ped['pendenciaDocumento'].'</td> 
					<td class="list_description">'.$operador['nomeCompleto'].'</td> 
					<td class="list_description">'.retornaEstado($ped['Status']).'</td>';
					echo "<td class='list_description'>
						<form method='POST' action='?perfil=evento&p=basica' target='_blank'>
						<input type='hidden' name='carregar' value='".$pedido['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='Carregar'></td></form>"	;
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
case 1:
?>
<br /><br /><br />
<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6>PEDIDO DE CONTRATAÇÃO VIRADA CULTURAL</h6>
		</div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Número do Processo</td>
						<td>Documento</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Periodo</td>
						<td>Pendências</td>
						<td>Valor</td>
						<td>Operador</td>
						<td>Status</td>
						<td width="7%"></td>
					</tr>
				</thead>
				<tbody>
			<?php
				$sql_enviados = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
					WHERE eve.publicado=1 AND eve.dataEnvio IS NOT NULL AND ped.publicado=1 AND (ped.NumeroProcesso != NULL OR ped.NumeroProcesso != '') AND eve.projetoEspecial IN (69)
					ORDER BY idPedidoContratacao DESC";
				$query_enviados = mysqli_query($con,$sql_enviados);
				while($pedido = mysqli_fetch_array($query_enviados))
				{
					$pj = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$operador = recuperaUsuario($pedido['idContratos']);
					if($ped['tipoPessoa'] == 1)
					{
						$link="?perfil=contratos&p=frm_edita_propostapf&id_ped=";
					}
					else
					{
						$link="?perfil=contratos&p=frm_edita_propostapj&id_ped=";
					}
					echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
					echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td>';
					if($ped['tipoPessoa'] == 1)
					{
						$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
						echo '<td class="list_description">'.$pessoa['CPF'].'</td>';
						echo '<td class="list_description">'.$pessoa['Nome'].'</td>';
					}
					else
					{
						$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
						echo '<td class="list_description">'.$pessoa['CNPJ'].'</td>';
						echo '<td class="list_description">'.$pessoa['RazaoSocial'].'</td>';
					}
					echo '
					<td class="list_description">'.$ped['Objeto'].'</td>
					<td class="list_description">'.$ped['Local'].'</td> 
					<td class="list_description">'.$ped['Periodo'].'</td> 
					<td class="list_description">'.$ped['pendenciaDocumento'].'</td> 
					<td class="list_description">'.dinheiroParaBr($ped['ValorGlobal']).'</td> 
					<td class="list_description">'.$operador['nomeCompleto'].'</td> 
					<td class="list_description">'.retornaEstado($ped['Status']).'</td>';
					echo "<td class='list_description'>
						<form method='POST' action='?perfil=evento&p=basica' target='_blank'>
						<input type='hidden' name='carregar' value='".$pedido['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='Carregar'></td></form>"	;
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
case 3:
$server = "http://".$_SERVER['SERVER_NAME']."/igsis/"; //mudar para pasta do igsis
$http = $server."/pdf/";
$link01 = $http."relatorio_virada.php";
?>
<br /><br /><br />
<section id="list_items">
	<div class="container" style="min-width: 1201px; width: 90%;">
		<div class="sub-title"><h6>PEDIDO DE CONTRATAÇÃO VIRADA CULTURAL</h6> <a href="<?php echo $link01 ?>"><strong>Gerar Excel</strong></a>
		</div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>	
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Número do Processo</td>
						<td>Documento</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Periodo</td>
                        <td>Pendências</td>
                        <td>Valor</td>
                        <td>Operador</td>
                        <td>Status</td>
						<td width="7%"></td>
					</tr>
				</thead>
				<tbody>
			<?php
				$sql_enviados = "SELECT DISTINCT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
                    INNER JOIN ig_ocorrencia AS oco ON eve.idEvento = oco.idEvento
					WHERE eve.publicado=1 AND eve.dataEnvio IS NOT NULL AND ped.publicado=1 AND idProjetoEspecial = 69 AND oco.dataInicio LIKE '2019%'
					ORDER BY idPedidoContratacao DESC";
				$query_enviados = mysqli_query($con,$sql_enviados);
				while($pedido = mysqli_fetch_array($query_enviados))
				{
					$pj = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$operador = recuperaUsuario($pedido['idContratos']);
					if($ped['tipoPessoa'] == 1)
					{
						$link="?perfil=contratos&p=frm_edita_propostapf&id_ped=";
					}
					else
					{
						$link="?perfil=contratos&p=frm_edita_propostapj&id_ped=";
					}
					echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
					echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td>';
					if($ped['tipoPessoa'] == 1)
					{
						$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
						echo '<td class="list_description">'.$pessoa['CPF'].'</td>';
						echo '<td class="list_description">'.$pessoa['Nome'].'</td>';
					}
					else
					{
						$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
						echo '<td class="list_description">'.$pessoa['CNPJ'].'</td>';
						echo '<td class="list_description">'.$pessoa['RazaoSocial'].'</td>';
					}
					echo '
					<td class="list_description">'.$ped['Objeto'].'</td>
					<td class="list_description">'.$ped['Local'].'</td> 
					<td class="list_description">'.$ped['Periodo'].'</td>
                    <td class="list_description">'.$ped['pendenciaDocumento'].'</td>
					<td class="list_description">'.dinheiroParaBr($ped['ValorGlobal']).'</td>
					<td class="list_description">'.$operador['nomeCompleto'].'</td>
					<td class="list_description">'.retornaEstado($ped['Status']).'</td>';
					echo "<td class='list_description'>
						<form method='POST' action='?perfil=evento&p=basica' target='_blank'>
						<input type='hidden' name='carregar' value='".$pedido['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='Carregar'></td></form>"	;
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