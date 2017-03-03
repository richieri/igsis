<?php 
require_once("../funcoes/funcoesVerifica.php");
require_once("../funcoes/funcoesSiscontrat.php");

include "includes/menu.php"; 

if(isset($_GET['b']))
{
	$b = $_GET['b'];	
}
else
{
	$b = 'inicial';
}

switch($b)
{
case 'inicial':
	
if(isset($_POST['pesquisar']))
{
	$id = trim($_POST['id']);
	$evento = trim($_POST['nomeEvento']);
	$fiscal = $_POST['fiscal'];
	$projeto = $_POST['projeto'];

	if($id == "" AND $evento == "" AND $fiscal == 0 AND $projeto == 0)
	{
?>
		<section id="services" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h2>Busca por Evento</h2>
							<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
							
							<form method="POST" action="?perfil=gestao_eventos&p=frm_busca" class="form-horizontal" role="form">
							<label>Id do Evento</label>
							<input type="text" name="id" class="form-control" id="palavras" placeholder="Insira o Id do Evento" ><br />
							
							<label>Nome do Evento</label>
							<input type="text" name="nomeEvento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />
							
							<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
							<select class="form-control" name="fiscal" id="inputSubject" >
								<option value="0"></option>	
								<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
							</select>
							<br />	
								
							<label>Tipo de Projeto</label>
							<select class="form-control" name="projeto" id="inputSubject" >
								<option value='0'></option>
								<?php  geraOpcaoOrdem("ig_projeto_especial","projetoEspecial"); ?>
							</select>
						</div>
					</div><br />  
				</div>			
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="pesquisar" value="1" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
						</form>
					</div>
				</div>
			</div>
		</section>	
<?php
	}
	else
	{
		$con = bancoMysqli();
		$sql_existe = "SELECT DISTINCT idEvento FROM ig_ocorrencia WHERE publicado = 1 AND idEvento IN (SELECT idEvento FROM ig_evento WHERE publicado = 1 AND statusEvento = 'Aguardando' AND dataEnvio IS NULL AND idEvento NOT IN (SELECT DISTINCT idEvento FROM igsis_pedido_contratacao WHERE publicado = 1) ) ORDER BY dataInicio DESC";
		$query_existe = mysqli_query($con, $sql_existe);
		$num_registro = mysqli_num_rows($query_existe);
		
		if($id != "" AND $num_registro > 0)//Foi inserido número do evento
		{ 	
			$evento = recuperaDados("ig_evento",$id,"idEvento");
			$idEvento = $evento['idEvento'];
			$projeto = recuperaDados("ig_projeto_especial",$evento['idEvento'],"projetoEspecial");
			$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
			$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
			$local = listaLocais($idEvento);
			$periodo = retornaPeriodo($idEvento);
			$fiscal = recuperaUsuario($evento['idResponsavel']);
			
			$x[0]['id']= $evento['idEvento'];			
			$x[0]['local'] = substr($local,1);
			$x[0]['periodo'] = $periodo;
			$x[0]['fiscal'] = $fiscal['nomeCompleto'];
			$x['num'] = 1;
			$x[0]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
		}
		else
		{ //Não foi inserido o número do evento
			if($nomeEvento != '')
			{
				$filtro_nomeEvento = " AND nomeEvento LIKE '%$nomeEvento%' OR autor LIKE '%$nomeEvento%' ";
			}
			else
			{
				$filtro_nomeEvento = "";			
			}		
					
			if($fiscal != 0)
			{
				$filtro_fiscal = " AND (idResponsavel = '$fiscal' OR suplente = '$fiscal' OR idUsuario = '$fiscal' )";	
			}
			else
			{
				$filtro_fiscal = "";	
			}	
			
			if($projeto == 0)
			{
				$filtro_projeto = " ";	
			}
			else
			{
				$filtro_projeto = " AND ig_evento.projetoEspecial = '$projeto'  ";	
			}		
			$usr = recuperaDados('ig_usuario',$_SESSION['idUsuario'],'idUsuario');
			$localUsr = $usr['local'];
			$sql_evento = "
				SELECT DISTINCT idEvento FROM ig_ocorrencia WHERE publicado = 1 AND idEvento IN (SELECT idEvento FROM ig_evento WHERE publicado = 1 AND statusEvento = 'Aguardando' AND dataEnvio IS NULL $filtro_fiscal $filtro_projeto AND idEvento NOT IN (SELECT DISTINCT idEvento FROM igsis_pedido_contratacao WHERE publicado = 1) )AND local IN ($localUsr) $filtro_nomeEvento  ORDER BY dataInicio DESC";
			$query_evento = mysqli_query($con,$sql_evento);
			
			$i = 0;

			while($evento = mysqli_fetch_array($query_evento))
			{
				$idEvento = $evento['idEvento'];	
				$evento = recuperaDados("ig_evento",$idEvento,"idEvento"); 			
				$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
				$local = listaLocais($idEvento);
				$periodo = retornaPeriodo($idEvento);
				$fiscal = recuperaUsuario($evento['idResponsavel']);			
				
				$x[$i]['id']= $evento['idEvento'];
				$x[$i]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
				$x[$i]['local'] = substr($local,1);
				$x[$i]['periodo'] = $periodo;
				$x[$i]['fiscal'] = $fiscal['nomeCompleto'];			
				$i++;			
			
			}
			$x['num'] = $i;
		}				
	}
	$mensagem ="Total de eventos encontrados: ".$x['num'].".";
?>
	<br /><br />
	<section id="list_items">
		<div class="container">
			<h3>Resultado da busca</h3>
			<?php
			if ($x['num'] == 1)
			{
				echo "<h5>Foi encontrado ".$x['num']." evento</h5>";
			}
			else
			{
				echo "<h5>Foram encontrados ".$x['num']." eventos</h5>";
			}
			?>
			<h5><a href="?perfil=gestao_eventos&p=frm_busca">Fazer outra busca</a></h5>
			<div class="table-responsive list_info">
			<?php 
				if($x['num'] == 0)
				{  
				}
				else
				{ 
			?>
					<table class="table table-condensed">
						<thead>
							<tr class="list_menu">
							<td>Id Evento</td>	
							<td>Nome Evento</td>
							<td>Local</td>
							<td>Periodo</td>
							<td>Fiscal</td>
							</tr>
						</thead>
					<tbody>
				<?php
					$link="index.php?perfil=gestao_eventos&p=detalhe_evento&id_eve=";
					$data=date('Y');
					for($h = 0; $h < $x['num']; $h++)
					{		
						echo "<tr><td class='list_description'> <a target=_blank href='".$link.$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
						echo '<td class="list_description">'.$x[$h]['objeto'].'</td>';
						echo '<td class="list_description">'.$x[$h]['local'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['periodo'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['fiscal'].'</td> </tr>';
					}
				?>					
					</tbody>
				</table>
			<?php 
				} 
			?>		
			</div>
		</div>
	</section>

<?php
}
else
{
?>
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h2>Busca por Evento</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
						
						<form method="POST" action="?perfil=gestao_eventos&p=frm_busca" class="form-horizontal" role="form">
						<label>Id do Evento</label>
						<input type="text" name="id" class="form-control" id="palavras" placeholder="Insira o Id do Evento" ><br />
            		
						<?php 
							if($_SESSION['perfil'] == 1)
							{
						?>					
								<label>Nome do Evento</label>
								<input type="text" name="nomeEvento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />
						<?php 
							} 
						?>
						
						<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
						<select class="form-control" name="fiscal" id="inputSubject" >
							<option value="0"></option>	
							<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
						</select><br />
					
						<label>Tipo de Projeto</label>
						<select class="form-control" name="projeto" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcaoOrdem("ig_projeto_especial","projetoEspecial"); ?>
						</select>	
					</div>
				</div><br />             
	            <div class="form-group">
		            <div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="pesquisar" value="1" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
						</form>
        	    	</div>
        	    </div>
            </div>
		</div>
	</section>               

<?php 
} 

 break; 
} // fim da switch ?>