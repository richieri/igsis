<?php
	include "include/menu.php";
	//verifica se o usuário tem acesso a página
	$verifica = verificaAcesso($_SESSION['idUsuario'],$_GET['perfil']); 
	if($verifica == 1)
	{
		$idInstituicao = $_SESSION['idInstituicao'];	
			
		if(isset($_GET['order']))
		{
			$order = $_GET['order'];
		}
		else
		{
			$order = "";
		}
		if(isset($_GET['sentido']))
		{
			$sentido = $_GET['sentido'];
			if($sentido == "ASC")
			{
				$invertido = "DESC";
			}
			else
			{
				$invertido = "ASC";
			}
		}
?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Chamado</h2>
					<h4></h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
                </div>
			</div>
		</div>  	
		<div class="table-responsive list_info">
			<table class='table table-condensed'>
				<thead>
					<tr class='list_menu'>
						<td width='10%'>ID</td>
						<td>Chamado</td>
						<td>Data do envio</td>
						<td>Usuário</td>
					</tr>
				</thead>
				<tbody>
			<?php		
				//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
				$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
				$con = bancoMysqli();
				$idInstituicao = $_SESSION['idInstituicao'];
				$sql_busca = "SELECT * FROM igsis_chamado, ig_evento WHERE igsis_chamado.idEvento = ig_evento.idEvento AND idInstituicao = '$idInstituicao' ORDER BY idChamado DESC";
				$query_busca = mysqli_query($con,$sql_busca);
				
				//conta o total de itens
				$total = mysqli_num_rows($query_busca);
				
				//seta a quantidade de itens por página
				$registros = 100;
				   
				//calcula o número de páginas arredondando o resultado para cima
				$numPaginas = ceil($total/$registros);
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($registros*$pagina)-$registros;
				
				//seleciona os itens por página
				$sql_busca = "SELECT * FROM igsis_chamado, ig_evento WHERE igsis_chamado.idEvento = ig_evento.idEvento AND idInstituicao = '$idInstituicao' ORDER BY idChamado DESC limit $inicio,$registros ";
				$query_busca = mysqli_query($con,$sql_busca);
				 
				 //conta o total de itens
				$total = mysqli_num_rows($query_busca);
				
				while($chamado = mysqli_fetch_array($query_busca))
				{ 
					$tipo = recuperaDados("igsis_tipo_chamado",$chamado['tipo'],"idTipoChamado");
					$usuario = recuperaDados("ig_usuario",$chamado['idUsuario'],"idUsuario");
			?>
					<tr>
						<td><?php echo $chamado['idChamado']; ?></td>
						<td>
							<a href="?perfil=chamado&p=detalhe&id=<?php echo $chamado['idChamado'] ?>" target="_blank" ><?php echo $tipo['chamado']." - ".$chamado['titulo']; ?>
				<?php
					if($chamado['idEvento'] != NULL)
					{
						$evento = recuperaDados("ig_evento",$chamado['idEvento'],"idEvento");
						echo "<br />".$evento['nomeEvento'];
					}
				?>
							</a>
						</td>
						<td><?php echo exibirDataHoraBr($chamado['data']) ?></td>
						<td><?php echo $usuario['nomeCompleto'] ?></td>
					</tr>
			<?php
				}
			?>
					<tr>
						<td colspan="5" bgcolor="#DEDEDE">
						<?php
							//exibe a paginação
							echo "<strong>Páginas</strong>";
							for($i = 1; $i < $numPaginas + 1; $i++) 
							{
								echo "<a href='?perfil=comunicacao&p=chamados&pagina=$i'> [".$i."]</a> ";
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
	}
	else
	{ 
?>
<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
		    <h1>Você não tem acesso. Por favor, contacte o administrador do sistema.</h1>
		</div>
	</div>
</section> 
 <?php
	}
 ?>