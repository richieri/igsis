<?php
include 'includes/menu.php';	

$con = bancoMysqli();
$link="?perfil=contratos_lite&p=frm_edita_propostapj&id_ped=";
?>

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
			$registros = 150;
			   
			//calcula o número de páginas arredondando o resultado para cima
			$numPaginas = ceil($total/$registros);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($registros*$pagina)-$registros;	
			
			//seleciona os itens por página
			$sql_lista = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
					WHERE eve.publicado=1 AND ped.publicado=1 AND eve.projetoEspecial IN (41) 
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
						<td>Proposta</td>
						<td>FACC</td>
					</tr>
				</thead>
				<tbody>
			<?php
				/*
				$sql_enviados = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, ped.idContratos
					FROM ig_evento AS eve
					INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
					INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
					WHERE eve.publicado=1 AND ped.publicado=1 AND ped.idContratos = '' AND eve.projetoEspecial IN (41) 
					ORDER BY idPedidoContratacao";
				$query_enviados = mysqli_query($con,$sql_enviados);
				*/
				while($pedido = mysqli_fetch_array($query_lista))
				{
					
					$server = "http://".$_SERVER['SERVER_NAME']."/igsis"; //mudar para pasta do igsis
					$http = $server."/pdf/";
					
					$ped = siscontrat($pedido['idPedidoContratacao']);
					$link5 = $http."rlt_fac_pj.php?id=";
					$link39 = $http."rlt_proposta_virada.php?penal=30&id=";
					echo '<tr><td class="list_description">'.$ped['idEvento'].'</td>' ;
					echo '<td class="list_description">'.$pedido['idPedidoContratacao'].'</td>' ;				
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
