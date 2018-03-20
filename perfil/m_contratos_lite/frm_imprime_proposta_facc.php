<?php
include 'includes/menu.php';	

$con = bancoMysqli();
$link="?perfil=contratos_lite&p=frm_edita_propostapj&id_ped=";
?>

<section id="list_items">
	<div class="container">
		<div class="sub-title"><h6><br/>PEDIDO DE CONTRATAÇÃO VIRADA CULTURAL</h6>
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
					WHERE eve.publicado=1 AND ped.publicado=1 AND ped.idContratos = '' AND eve.projetoEspecial IN (54) 
					ORDER BY idPedidoContratacao DESC";
			$query_lista = mysqli_query($con,$sql_lista);
			
			//conta o total de itens
			$total = mysqli_num_rows($query_lista);
			
			//seta a quantidade de itens por página
			$registros = 100;
			   
			//calcula o número de páginas arredondando o resultado para cima
			$numPaginas = ceil($total/$registros);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($registros*$pagina)-$registros;	
			
			//seleciona os itens por página
			$sql_lista = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
					WHERE eve.publicado=1 AND ped.publicado=1 AND ped.idPessoa IS NOT NULL AND eve.projetoEspecial IN (54) 
					ORDER BY idPedidoContratacao DESC limit $inicio,$registros ";
			$query_lista = mysqli_query($con,$sql_lista);
			//conta o total de itens
			$total = mysqli_num_rows($query_lista);
			
		?>
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Evento</td>
						<td>Codigo do Pedido</td>
						<td>Processo</td>
						<td>Proposta</td>
						<td>FACC</td>
					</tr>
				</thead>
				<tbody>
			<?php
				while($pedido = mysqli_fetch_array($query_lista))
				{
					
					$server = "http://".$_SERVER['SERVER_NAME']."/igsis"; //mudar para pasta do igsis
					$http = $server."/pdf/";
					
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$link5 = $http."rlt_fac_pj.php?id=";
					$link39 = $http."rlt_proposta_virada.php?penal=30&id=";
					echo '<tr><td class="list_description">'.$ped['idEvento'].'</td>' ;
					echo '<td class="list_description">'.$pedido['idPedidoContratacao'].'</td>' ;
					echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td>' ;					
					echo "<td class='lista'> <a href='".$link39.$pedido['idPedidoContratacao']."'>Donwload Proposta</a></td>";
					echo "<td class='lista'> <a href='".$link5.$pedido['idPedidoContratacao']."'>Donwload FACC</a></td>";					
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
								echo "<a href='?perfil=contratos_lite&p=frm_imprime_proposta_facc&pagina=$i'> [".$i."]</a> ";
							}
						?>
						</td>
					</tr>	
				</tbody>
			</table>
		</div>
	</div>
</section>
