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
					<h2>Comunicação</h2>
					<h4>Eventos editados</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
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
					</tr>
				</thead>
				<tbody>
			<?php
				$con = bancoMysqli();
				$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE editado = '1' AND idInstituicao = '$idInstituicao' ORDER BY idCom DESC";
				$query_busca_dic = mysqli_query($con,$sql_busca_dic);
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
					</tr>					
			<?php
				}
			?>									
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