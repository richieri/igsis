<!-- BUSCA POR PEDIDO -->
<?php  
 require_once("../funcoes/funcoesVerifica.php");
 require_once("../funcoes/funcoesSiscontrat.php");
 include "includes/menu.php"; 
?>
 
<?php
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
		$nomeEvento = trim($_POST['nomeEvento']);
		$fiscal = $_POST['fiscal'];

		if($id == "" AND $nomeEvento == "" AND $fiscal == 0 AND $juridico == 0 AND $projeto == 0)
		{ ?>
			<section id="services" class="home-section bg-white">
				<div class="container">
					<div class="row">
						<div class="col-md-offset-2 col-md-8">
							<div class="section-heading">
								<h2>Busca por evento</h2>
								<p>É preciso ao menos um critério de busca ou você pesquisou por um evento inexistente. Tente novamente.</p>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
								<form method="POST" action="?perfil=gestao_prazos&p=frm_busca" class="form-horizontal" role="form">				
									<label>Id do Evento</label>
									<input type="text" name="id" class="form-control" id="palavras" placeholder="Insira o Id do Evento" ><br />		
							
									<?php if($_SESSION['perfil'] == 1){?>
									<label>Objeto/Evento</label>
									<input type="text" name="evento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />					
									<?php } ?>
						
									<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
									<select class="form-control" name="fiscal" id="inputSubject" >
										<option value="0"></option>	
										<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
									</select>
							</div>
						</div> 
						<br />  
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
		else
		{					
			if($id != "")
			{ // Foi inserido o número do pedido
				
				$con = bancoMysqli();
				$sql_existe = "SELECT * FROM igsis_pedido_contratacao AS ped
				INNER JOIN ig_evento AS eve ON ped.idEvento = eve.idEvento
				WHERE ped.idEvento = '$id'
				AND ped.publicado = 1 
				AND statusEvento = 'Aguardando' 
				AND eve.publicado = 1 
				AND eve.dataEnvio IS NULL
				ORDER BY eve.idEvento DESC";
				$query_existe = mysqli_query($con, $sql_existe);
				$num_registro = mysqli_num_rows($query_existe);
		
				if($num_registro == 0)
				{
					$x['num'] = 0;
				}
				else
				{
					$i = 0;
					while($lista = mysqli_fetch_array($query_existe))
					{
						$pedido = recuperaDados("igsis_pedido_contratacao",$lista['idEvento'],"idEvento");				
						$evento = recuperaDados("ig_evento",$lista['idEvento'],"idEvento"); //$tabela,$idEvento,$campo
						$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
						$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
						$local = listaLocais($pedido['idEvento']);
						$periodo = retornaPeriodo($pedido['idEvento']);
						$pessoa = recuperaPessoa($pedido['idPessoa'],$pedido['tipoPessoa']);
						$fiscal = recuperaUsuario($evento['idResponsavel']);
						$x[0]['id']= $evento['idEvento'];
						$x[0]['id_ped']= $pedido['idPedidoContratacao'];
						$x[0]['local'] = substr($local,1);
						$x[0]['periodo'] = $periodo;
						$x[0]['fiscal'] = $fiscal['nomeCompleto'];
						$x[0]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
						
						if($pedido['tipoPessoa'] == 1)
						{
							$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
							$x[0]['proponente'] = $pessoa['Nome'];
							$x[0]['tipo'] = "Física";
						}
						else
						{
							$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
							$x[0]['proponente'] = $pessoa['RazaoSocial'];
							$x[0]['tipo'] = "Jurídica";
						}
						$i++;
					}	
					$x['num'] = $i;
				}
			}
			else
			{ //Não foi inserido o número do pedido
				if($nomeEvento != '')
				{
					$filtro_evento = " AND nomeEvento LIKE '%$nomeEvento%' OR autor LIKE '%$nomeEvento%'";
				}
				else
				{
					$filtro_evento = "";			
				}
		
				if($fiscal != 0)
				{
					$filtro_fiscal = " AND (idResponsavel = '$fiscal' OR suplente = '$fiscal' OR idUsuario = '$fiscal' )";	
				}
				else
				{
					$filtro_fiscal = "";	
				}		
				
				$con = bancoMysqli();
				$sql_evento = "SELECT * FROM igsis_pedido_contratacao AS ped
					INNER JOIN ig_evento AS eve ON ped.idEvento = eve.idEvento
					WHERE ped.publicado = 1 
					AND statusEvento = 'Aguardando' 
					AND (eve.publicado = 1 
					AND eve.dataEnvio IS NULL
					$filtro_evento) 
					$filtro_fiscal
					ORDER BY eve.idEvento DESC";
				$query_evento = mysqli_query($con,$sql_evento);
				$num_registro = mysqli_num_rows($query_evento);
				
				if($num_registro == 0)
				{
					$x['num'] = 0;
				}
				else
				{			
					$i = 0;
			
					while($lista2 = mysqli_fetch_array($query_evento))
					{
						$pedido = recuperaDados("igsis_pedido_contratacao",$lista2['idEvento'],"idEvento");				
						$evento = recuperaDados("ig_evento",$lista2['idEvento'],"idEvento"); //$tabela,$idEvento,$campo
						$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
						$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
						$local = listaLocais($pedido['idEvento']);
						$periodo = retornaPeriodo($pedido['idEvento']);
						$pessoa = recuperaPessoa($pedido['idPessoa'],$pedido['tipoPessoa']);
						$fiscal = recuperaUsuario($evento['idResponsavel']);
						$x[$i]['id']= $evento['idEvento'];
						$x[$i]['id_ped']= $pedido['idPedidoContratacao'];
						$x[$i]['local'] = substr($local,1);
						$x[$i]['periodo'] = $periodo;
						$x[$i]['fiscal'] = $fiscal['nomeCompleto'];
						$x[$i]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
						
						if($pedido['tipoPessoa'] == 1)
						{
							$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
							$x[$i]['proponente'] = $pessoa['Nome'];
							$x[$i]['tipo'] = "Física";
						}
						else
						{
							$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
							$x[$i]['proponente'] = $pessoa['RazaoSocial'];
							$x[$i]['tipo'] = "Jurídica";
						}
						$i++;			
					}
					$x['num'] = $i;	
				}		
			}
		} 
		$mensagem = "Foram encontradas ".$x['num']." pedido(s) de contratação.";
?>
	<section id="list_items">
		<div class="container">
			<h3><br/></h3>
            <h5>Foram encontrados <?php echo $x['num'] ?> eventos.</h5>
            <h5><a href="?perfil=gestao_prazos&p=frm_busca">Fazer outra busca</a></h5>
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
							<td>Objeto</td>
							<td>Local</td>
							<td>Periodo</td>
							<td>Fiscal</td>
							</tr>
						</thead>
						<tbody>		
						<?php
							$link="index.php?perfil=gestao_prazos&p=detalhe_evento&id_eve=";
							$data=date('Y');
							
							for($h = 0; $h < $x['num']; $h++)
							{		
								echo "<tr><td class='list_description'> <a href='".$link.$x[$h]['id']."'>".$x[$h]['id']."</a></td>";	
								echo '<td class="list_description">'.$x[$h]['objeto'].				'</td> ';
								echo '<td class="list_description">'.$x[$h]['local'].				'</td> ';
								echo '<td class="list_description">'.$x[$h]['periodo'].				'</td> ';
								echo '<td class="list_description">'.$x[$h]['fiscal'].				'</td> </tr>';
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
						<h4><font color="red">* Em Fase de Testes *</font></h4><br/>
						<h3>Busca por evento</h1>
					</div>
				</div>
			</div>
			  
			<div class="row">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
						<form method="POST" action="?perfil=gestao_prazos&p=frm_busca" class="form-horizontal" role="form">
							<label>Id do Evento</label>
							<input type="text" name="id" class="form-control" placeholder="Insira o Id do Evento" ><br />
							
							<label>Objeto/Evento</label>
							<input type="text" name="nomeEvento" class="form-control" placeholder="Insira o objeto" ><br />
										  
							<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
							<select class="form-control" name="fiscal" id="inputSubject" >
								<option value="0"></option>	
								<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
							</select>
					</div>
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
	</section>               

	<?php 
	} 
	break;	
} // fim da switch
?>