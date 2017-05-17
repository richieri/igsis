<?php
include 'includes/menu.php';	
$con = bancoMysqli();
$link="?perfil=contratos&p=frm_edita_propostapj&id_ped=";
switch($_GET['atribuido'])
{
	case 0:
?>
<br /><br /><br />

<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6>PEDIDO DE CONTRATAÇÃO DE PESSOA JURÍDICA</h6>
		</div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>	
		<div class="table-responsive list_info">
		<?php 		
			//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
			$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
			$idInsituicao = $_SESSION['idInstituicao'];
			$sql_lista = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
					WHERE eve.publicado=1 AND ped.publicado=1 AND ped.idContratos = '' AND eve.projetoEspecial IN (41) 
					ORDER BY idPedidoContratacao DESC";
			$query_lista = mysqli_query($con,$sql_lista);
			
			//conta o total de itens
			$total = mysqli_num_rows($query_lista);
			
			//seta a quantidade de itens por página
			$registros = 250;
			   
			//calcula o número de páginas arredondando o resultado para cima
			$numPaginas = ceil($total/$registros);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($registros*$pagina)-$registros;	
			
			//seleciona os itens por página
			$sql_lista = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
					WHERE eve.publicado=1 AND ped.publicado=1 AND ped.NumeroProcesso IN (NULL, '') AND eve.projetoEspecial IN (41) 
					ORDER BY idPedidoContratacao DESC limit $inicio,$registros ";
			$query_lista = mysqli_query($con,$sql_lista);
			//conta o total de itens
			$total = mysqli_num_rows($query_lista);
			
		?>
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Número do Processo</td>
						<td>CNPJ</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Local</td>
						<td>Periodo</td>
						<td>Operador</td>
						<td>Status</td>
						<td width="7%"></td>
					</tr>
				</thead>
				<tbody>
			<?php
				while($pedido = mysqli_fetch_array($query_lista))
				{
					$pj = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$operador = recuperaUsuario($pedido['idContratos']);
					echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
					echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td> 
					<td class="list_description">'.$pj['CNPJ'].'</td>
					<td class="list_description">'.$pj['RazaoSocial'].'</td> 
					<td class="list_description">'.$ped['Objeto'].'</td> 
					<td class="list_description">'.$ped['Local'].'</td> 
					<td class="list_description">'.$ped['Periodo'].'</td> 
					<td class="list_description">'.$operador['nomeCompleto'].'</td> 
					<td class="list_description">'.retornaEstado($ped['Status']).'</td>';
					echo "<td class='list_description'>
						<form method='POST' action='?perfil=evento&p=basica' target='_blank'>
						<input type='hidden' name='carregar' value='".$pedido['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='Evento'></td></form>"	;
					echo "</tr>";
				}
			?>	
					<tr>
						<td colspan="10" bgcolor="#DEDEDE">
						<?php
							//exibe a paginação
							echo "<strong>Páginas</strong>";
							for($i = 1; $i < $numPaginas + 1; $i++) 
							{
								echo "<a href='?perfil=contratos_lite&p=frm_lista_projeto&atribuido=0&pagina=$i'> [".$i."]</a> ";
							}
						?>
						</td>
					</tr>	
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
		<div class="sub-title"><h6>PEDIDO DE CONTRATAÇÃO DE PESSOA JURÍDICA</h6>
		</div>
		<p><?php if(isset($mensagem)){ echo $mensagem; }?></p>	
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
						<td>Periodo</td>
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
					WHERE eve.publicado=1 AND ped.publicado=1 AND ped.NumeroProcesso IS NOT NULL AND eve.projetoEspecial IN (41) 
					ORDER BY idPedidoContratacao DESC";
				$query_enviados = mysqli_query($con,$sql_enviados);
				while($pedido = mysqli_fetch_array($query_enviados))
				{
					$pj = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$operador = recuperaUsuario($pedido['idContratos']);
					echo "<tr><td class='lista'> <a href='".$link.$pedido['idPedidoContratacao']."'>".$pedido['idPedidoContratacao']."</a></td>";
					echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td>
					<td class="list_description">'.$pj['CNPJ'].'</td> 
					<td class="list_description">'.$pj['RazaoSocial'].'</td> 
					<td class="list_description">'.$ped['Objeto'].'</td> 
					<td class="list_description">'.$ped['Local'].'</td> 
					<td class="list_description">'.$ped['Periodo'].'</td> 
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