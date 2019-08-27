<?php include 'include/menu.php';

$idInstituicao = $_SESSION['idInstituicao'];

//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

if(isset($_POST['filtrar']))
{
	if(isset($_POST['editado_c']))
	{
		$_SESSION['editado'] = '1';
	}
	else
	{
		if(isset($_POST['editado_p']))
		{
			$_SESSION['editado'] = '2';
		}
		else
		{
			$_SESSION['editado'] = '0';
		}	
	}
		
	
	if(isset($_POST['revisado_c']))
	{
		$_SESSION['revisado'] = '1';
	}
	else
	{
		if(isset($_POST['revisado_p']))
		{
			$_SESSION['revisado'] = '2';
		}
		else
		{
			$_SESSION['revisado'] = '0';
		}	
	}
	
	if(isset($_POST['site_c']))
	{
		$_SESSION['site'] = '1';
	}
	else
	{
		if(isset($_POST['site_p']))
		{
		$_SESSION['site'] = '2';
		}
		else
		{
		$_SESSION['site'] = '0';
		}
	}
	
	if(isset($_POST['publicacao_c']))
	{
		$_SESSION['publicacao'] = '1';
	}
	else
	{
		if(isset($_POST['publicacao_p']))
		{
			$_SESSION['publicacao'] = '2';
		}
		else
		{
			$_SESSION['publicacao'] = '0';
		}	
	}
	
	if(isset($_POST['foto_c']))
	{
		$_SESSION['foto'] = '1';
	}
	else
	{
		if(isset($_POST['foto_p']))
		{
			$_SESSION['foto'] = '2';
		}
		else
		{	
			$_SESSION['foto'] = '0';
		}	
	}

}
$editado = $_SESSION['editado'];
$revisado = $_SESSION['revisado'];
$site = $_SESSION['site'];
$publicacao = $_SESSION['publicacao'];
$foto = $_SESSION['foto'];

			
if ($editado == 1)//confirmado
{
	$filtro_editado = "AND ig_comunicacao.editado = 1";	
}
elseif ($editado == 2)//pendente
{
	$filtro_editado = "AND ig_comunicacao.editado != 1";	
}
else
{
	$filtro_editado = ""; //tanto faz
}	

if($revisado == 1)//confirmado
{
	$filtro_revisado = "AND ig_comunicacao.revisado = 1";	
}
elseif($revisado == 2)
{
	$filtro_revisado = "AND ig_comunicacao.revisado != 1";	
}
else
{
	$filtro_revisado = "";
}	

if($site == 1)
{
	$filtro_site = "AND ig_comunicacao.site = 1";	
}
elseif($site == 2)
{
	$filtro_site = "AND ig_comunicacao.site != 1";	
}
else
{
	$filtro_site = "";
}	

if($publicacao == 1)
{
	$filtro_publicacao = "AND ig_comunicacao.publicacao = 1";
}
elseif($publicacao == 2)
{
	$filtro_publicacao = "AND ig_comunicacao.publicacao != 1";	
}
else
{
	$filtro_publicacao = "";
}

if($foto == 1)
{
	$filtro_foto = "AND ig_comunicacao.foto = 1";
}
elseif($foto == 2)
{
	$filtro_foto = "AND ig_comunicacao.foto != 1";	
}
else
{
	$filtro_foto = "";
}

$con = bancoMysqli();

$sql_busca_dic = "SELECT * FROM ig_comunicacao, ig_evento WHERE ig_comunicacao.idInstituicao = '$idInstituicao' AND ig_evento.publicado = '1' AND ig_comunicacao.ig_evento_idEvento = ig_evento.idEvento $filtro_editado $filtro_revisado $filtro_site $filtro_publicacao $filtro_foto ORDER BY ig_evento.dataEnvio DESC";

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

$sql_busca_dic = "SELECT * FROM ig_comunicacao, ig_evento WHERE ig_comunicacao.idInstituicao = '$idInstituicao' AND ig_evento.publicado = '1' AND ig_comunicacao.ig_evento_idEvento = ig_evento.idEvento $filtro_editado $filtro_revisado $filtro_site $filtro_publicacao $filtro_foto ORDER BY ig_evento.dataEnvio DESC limit $inicio,$registros ";
$query_busca_dic = mysqli_query($con,$sql_busca_dic);
 //conta o total de itens
$total = mysqli_num_rows($query_busca_dic);

?>

	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h3>Comunicação</h3>								
						<h6><a href="?perfil=comunicacao&p=filtro">Efetuar outro filtro</a></h6>	
					</div>
				</div>
			</div>	
			<div class="table-responsive list_info">
				<strong>Legenda status:</strong> | <font color='blue'>[ E ] Editado</font> | <font color='#32CD32'>[ R ] Revisado</font> | <font color='red'>[ S ] Site</font> | <font color='orange'>[ I ] Impresso</font> | <font color='#DA70D6'>[ F ] Foto</font> | 
				<p>&nbsp;</p>
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
								<td><a href="?perfil=comunicacao&p=editar&idCom=<?php echo $evento['idCom'] ?>"><?php echo $evento['nomeEvento'] ?></a>  [<?php 
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
										echo "<font color='blue'>[ E ]</font> "; 
									} 
									if ($evento['revisado'] == 1) 
									{
										echo "<font color='#32CD32'>[ R ]</font> ";
									}
									if ($evento['site'] == 1) 
									{
										echo "<font color='red'>[ S ]</font> ";
									}	
									if ($evento['publicacao'] == 1) 
									{
										echo "<font color='orange'>[ I ]</font> ";
									}
									if ($evento['foto'] == 1) 
									{
										echo "<font color='#DA70D6'>[ F ]</font>";
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
									echo "<a href='?perfil=comunicacao&p=filtro_resultado&pagina=$i'> [".$i."]</a> ";
								}
							?>
							</td>
						</tr>				
					</tbody>
				</table>
			</div>
		</div>
	</section>