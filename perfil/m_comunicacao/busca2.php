﻿<?php include 'include/menu.php';

$idInstituicao = $_SESSION['idInstituicao'];

if(isset($_POST['filtrar']))
{
	if(isset($_POST['editado']))
		{
			$editado = 1;
		}
		else
		{
			$editado = "";
		}
		if(isset($_POST['revisado']))
		{
			$revisado = 1;
		}
		else
		{
			$revisado = "";
		}
		if(isset($_POST['site']))
		{
			$site = 1;
		}
		else
		{
			$site = "";
		}
		if(isset($_POST['publicacao']))
		{
			$publicacao = 1;
		}
		else
		{
			$publicacao = "";
		}
		if(isset($_POST['foto']))
		{
			$foto = 1;
		}
		else
		{
			$foto = "";
		}
	if($editado == "" AND $revisado == "" AND $site == "" AND $impresso == "" AND $foto == "")
	{ 
?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h2>Comunicação</h2>
						<h4>Todos os eventos</h4>
					</div>
				</div>
			</div> 		
			<div class="table-responsive list_info">
				<table class='table table-condensed'>
					<thead>
						<tr class='list_menu'>
							<td width='10%'>Numero IG</td>
							<td>Nome de Evento</td>
							<td>Enviador por</td>
							<td>Data/Início</td>
							<td>Status</td>
						</tr>
					</thead>		
					<tbody>
				<?php
					//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
					$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
												
					$con = bancoMysqli();
					$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '$idInstituicao' ORDER BY idCom DESC";
					$query_busca_dic = mysqli_query($con,$sql_busca_dic);
					//conta o total de itens
					$total = mysqli_num_rows($query_busca_dic);
					
					//seta a quantidade de itens por página
					$registros = 100;
				   
					//calcula o número de páginas arredondando o resultado para cima
					$numPaginas = ceil($total/$registros);
					
					//variavel para calcular o início da visualização com base na página atual
					$inicio = ($registros*$pagina)-$registros;
					
					//seleciona os itens por página
					$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '$idInstituicao' ORDER BY idCom DESC limit $inicio,$registros ";
					$query_busca_dic = mysqli_query($con,$sql_busca_dic);
					 //conta o total de itens
					$total = mysqli_num_rows($query_busca_dic);
					
					while($evento = mysqli_fetch_array($query_busca_dic))
					{ 
						$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
						$nome = recuperaUsuario($event['idUsuario']);
						$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);
				?>			
						<tr>
							<td><?php echo $evento['ig_evento_idEvento'] ?></td>
							<td><a href="?perfil=comunicacao&p=editar&idCom=<?php echo $evento['idCom']  ?>"><?php echo $evento['nomeEvento'] ?></a> [<?php 
						if($chamado['numero'] == '0')
						{
							echo "0";
						}
						else
						{
							echo "<a href='?perfil=chamado&p=evento&id=".$evento['ig_evento_idEvento']."' target='_blank'>".$chamado['numero']."</a>";	
						}
							?>]
							</td>
							<td><?php echo $nome['nomeCompleto'] ?></td>
							<td><?php echo retornaPeriodo($evento['ig_evento_idEvento']) ?></td>
							<td><?php 
								if ($evento['editado'] == 1) 
								{ 
									echo "Editado <br/>"; 
								} 
								if ($evento['revisado'] == 1) 
								{
									echo "Revisado <br/>";
								}
								if ($evento['site'] == 1) 
								{
									echo "Site <br/>";
								}	
								if ($evento['publicacao'] == 1) 
								{
									echo "Impresso <br/>";
								}
								if ($evento['foto'] == 1) 
								{
									echo "Foto";
								}
							?></td>
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
									echo "<a href='?perfil=comunicacao&p=todos_eventos&pagina=$i'> [".$i."]</a> ";
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
		//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
		$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
		
		if($editado != 1)
		{
			$filtro_editado = "";	
		}
		else
		{
			$filtro_editado = "AND editado = 1";	
		}
		
		if($revisado != 1)
		{
			$filtro_revisado = "";	
		}
		else
		{
			$filtro_revisado = "AND revisado = 1";	
		}
		
		if($site != 1)
		{
			$filtro_site = "";	
		}
		else
		{
			$filtro_site = "AND site = 1";	
		}

		if($publicacao != 1)
		{
			$filtro_publicacao = "";	
		}
		else
		{
			$filtro_publicacao = "AND publicacao = 1";	
		}
		
		if($foto != 1)
		{
			$filtro_foto = "";	
		}
		else
		{
			$filtro_foto = "AND foto = 1";	
		}
		
		$con = bancoMysqli();
		$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '$idInstituicao' $filtro_editado $filtro_revisado $filtro_site $filtro_publicacao $filtro_foto ORDER BY idCom DESC";
		$query_busca_dic = mysqli_query($con,$sql_busca_dic);
		//conta o total de itens
		$total = mysqli_num_rows($query_busca_dic);

		//seta a quantidade de itens por página
		$registros = 2;
		   
		//calcula o número de páginas arredondando o resultado para cima
		$numPaginas = ceil($total/$registros);
		
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($registros*$pagina)-$registros;		
			
		//seleciona os itens por página
		$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '$idInstituicao' $filtro_editado $filtro_revisado $filtro_site $filtro_publicacao $filtro_foto ORDER BY idCom DESC limit $inicio,$registros ";
		$query_busca_dic = mysqli_query($con,$sql_busca_dic);
		 //conta o total de itens
		$total = mysqli_num_rows($query_busca_dic);
?>

	<br />
	<br />
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h2>Comunicação</h2>								
						<h4>Eventos editados</h4>	
					</div>
				</div>
			</div>	
			<div class="table-responsive list_info">
				<table class='table table-condensed'>
					<thead>
						<tr class='list_menu'>
							<td width='10%'>Numero IG</td>
							<td>Nome de Evento</td>
							<td>Enviador por</td>
							<td>Data/Início</td>
							<td>Status</td>
						</tr>
					</thead>
					<tbody>	
					<?php
						
						while($evento = mysqli_fetch_array($query_busca_dic))
						{ 
							$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
							$nome = recuperaUsuario($event['idUsuario']);
							$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);
					?>	
							<tr>
								<td><?php echo $evento['ig_evento_idEvento'] ?></td>
								<td><a href="?perfil=comunicacao&p=edicao&id=<?php echo $evento['ig_evento_idEvento']  ?>"><?php echo $evento['nomeEvento'] ?></a>  [<?php 
							if($chamado['numero'] == '0')
							{
								echo "0";
							}
							else
							{
								echo "<a href='?perfil=chamado&p=evento&id=".$evento['ig_evento_idEvento']."' target='_blank'>".$chamado['numero']."</a>";	
							}
					?>]						</td>				
								<td><?php echo $nome['nomeCompleto'] ?></td>
								<td><?php echo retornaPeriodo($evento['ig_evento_idEvento']) ?></td>
								<td><?php 
									if ($evento['editado'] == 1) 
									{ 
										echo "Editado <br/>"; 
									} 
									if ($evento['revisado'] == 1) 
									{
										echo "Revisado <br/>";
									}
									if ($evento['site'] == 1) 
									{
										echo "Site <br/>";
									}	
									if ($evento['publicacao'] == 1) 
									{
										echo "Impresso <br/>";
									}
									if ($evento['foto'] == 1) 
									{
										echo "Foto";
									}
								?></td>
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
									echo "<a href='?perfil=comunicacao&p=todos_eventos&pagina=$i'> [".$i."]</a> ";
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
}
else
{
?>
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h3>Filtrar por</h3>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<form method="POST" action="?perfil=comunicacao&p=busca2" class="form-horizontal" role="form">
					<div class="form-group">					
						<div class="col-md-offset-2 col-md-8"> 
							| <input type="checkbox" name="editado" /> <label>Editado</label>  |
							<input type="checkbox" name="revisado" /> <label>Revisado</label> |
							<input type="checkbox" name="site" /> <label>Site</label> | 
							<input type="checkbox" name="publicacao" /> <label>Impresso</label> |
							<input type="checkbox" name="foto" /> <label>Foto</label> |
						</div>
					</div>
					<br />             
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="filtrar" value="1" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Filtrar">                    
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>             
<?php 
} 
 ?>