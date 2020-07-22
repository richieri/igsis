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
							<h2>Busca por Evento - Reabertura</h2>
							<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
							
							<form method="POST" action="?perfil=gestao_eventos&p=frm_reabertura" class="form-horizontal" role="form">
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
		if($id != '')
		{
			$filtro_id = " AND idEvento = '$id' ";
		}
		else
		{
			$filtro_id = "";			
		}
		
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
			SELECT DISTINCT idEvento FROM ig_ocorrencia WHERE publicado = 1 AND idEvento IN (SELECT idEvento FROM ig_evento WHERE publicado = 1 AND dataEnvio IS NOT NULL $filtro_id $filtro_fiscal $filtro_projeto AND idEvento NOT IN (SELECT DISTINCT idEvento FROM igsis_pedido_contratacao WHERE publicado = 1) )  $filtro_nomeEvento  ORDER BY dataInicio DESC";
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
			<h5><a href="?perfil=gestao_eventos&p=frm_reabertura">Fazer outra busca</a></h5>
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
							<td></td>
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
						echo '<td class="list_description">'.$x[$h]['fiscal'].'</td> ';
						echo "<td class='list_description'>
						<form method='POST' action='?perfil=gestao_eventos&p=frm_reabertura'>
						<input type='hidden' name='reabertura' value='".$x[$h]['id']."' >	
						<input type ='submit' class='btn btn-theme  btn-block' value='reabrir'></td></form></tr>";
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
	//AQUI FAZ A REABERTURA
	if(isset($_POST['reabertura']))
	{
		$con = BancoMysqli();
		$id = $_POST['reabertura'];
		$mensagem = "";
		$sql_reabrir = "UPDATE ig_evento SET dataEnvio = NULL, statusEvento = 'Em elaboração' WHERE idEvento = '$id'";
		$query_reabrir = mysqli_query($con,$sql_reabrir);
		if($query_reabrir)
		{
		    gravarLog($sql_reabrir);
			$evento = recuperaDados("ig_evento",$id,"idEvento");
			$mensagem = $mensagem."O evento ".$evento['nomeEvento']." foi reaberto.<br /><br/>";
		}
		else
		{
			$mensagem = $mensagem."Erro ao reabrir evento.<br/><br/>";
		}	
	}
?>
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<h2>Busca por Evento - Reabertura</h2>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
						
						<form method="POST" action="?perfil=gestao_eventos&p=frm_reabertura" class="form-horizontal" role="form">
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