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
		$evento = trim($_POST['nomeEvento']);
		$fiscal = $_POST['fiscal'];
		$juridico = $_POST['juridico'];
		//$processo = $_POST['NumeroProcesso'];
		$projeto = $_POST['projeto'];

		if($id == "" AND $evento == "" AND $fiscal == 0 AND $juridico == 0 AND $projeto == 0)
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
										
<?php if($_SESSION['perfil'] == 1)
			{
?>
					<label>Objeto/Evento</label>
            		<input type="text" name="evento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />
          
<?php 		} 
?>
					
					<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
						<select class="form-control" name="fiscal" id="inputSubject" >
						<option value="0"></option>	
						
<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
						</select><br />	
                    
						<label>Tipo de Relação Jurídica</label>
						<select class="form-control" name="juridico" id="inputSubject" >
						<option value='0'></option>
						
<?php  geraOpcaoOrdem("ig_modalidade","modalidade"); ?>
						</select><br/>
					
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
		$x = recuperaDados("ig_usuario",$_SESSION['idUsuario'],"idUsuario");
		$local = $x['local'];
		$sql_existe = "SELECT DISTINCT idEvento FROM ig_ocorrencia WHERE publicado = 1 AND idEvento IN (SELECT idEvento FROM ig_evento WHERE publicado = 1 AND dataEnvio IS NULL )
		AND local IN ($local) ORDER BY idEvento DESC";
		$query_existe = mysqli_query($con, $sql_existe);
		$num_registro = mysqli_num_rows($query_existe);
		
		if($id != "" AND $num_registro > 0)
		{ 
				
			$evento = recuperaDados("ig_evento",$evento['idEvento'],"idEvento"); 
			$projeto = recuperaDados("ig_projeto_especial",$evento['idEvento'],"projetoEspecial");
			$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
			$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
			$local = listaLocais($evento['idEvento']);
			$local_juridico = listaLocaisJuridico($evento['idEvento']);
			$periodo = retornaPeriodo($evento['idEvento']);
			$duracao = retornaDuracao($evento['idEvento']);
			$pessoa = recuperaPessoa($evento['idPessoa'],$evento['tipoPessoa']);
			$fiscal = recuperaUsuario($evento['idResponsavel']);				
			//$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
			//$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
			$x['num'] = 1;
			$x['num'] = 0;


		}
			else
			{ //Não foi inserido o número do pedido
				if($evento != '')
				{
					$filtro_evento = " AND nomeEvento LIKE '%$evento%' OR autor LIKE '%$evento%' ";
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
								if($juridico == 0)
								{
									$filtro_juridico = " ";	
								}
									else
									{
										$filtro_juridico = " AND ig_evento.ig_modalidade_IdModalidade = '$juridico'  ";	
									}	
										if($projeto == 0)
										{
											$filtro_projeto = " ";	
										}
											else
											{
												$filtro_projeto = " AND ig_evento.projetoEspecial = '$projeto'  ";	
											}		
												$sql_evento = "SELECT * FROM ig_evento WHERE publicado = '1' AND dataEnvio IS NULL AND idEvento NOT IN (SELECT DISTINCT idEvento FROM igsis_pedido_contratacao WHERE publicado = '1') $filtro_evento $filtro_fiscal $filtro_juridico $filtro_projeto ORDER BY idEvento DESC";
												$query_evento = mysqli_query($con,$sql_evento);
												$i = 0;
		
				while($evento = mysqli_fetch_array($query_evento))
				{
					$idEvento = $evento['idEvento'];	
					$sql_existe = "SELECT * FROM ig_evento WHERE publicado = '1' AND dataEnvio IS NULL AND idEvento NOT IN (SELECT DISTINCT idEvento FROM igsis_pedido_contratacao WHERE publicado = '1') $filtro_evento $filtro_fiscal $filtro_juridico $filtro_projeto ORDER BY idEvento DESC";
					$query_existe = mysqli_query($con, $sql_existe);
	
					if(mysqli_num_rows($query_existe) > 0)
					{
						while($ped = mysqli_fetch_array($query_existe))
						{	
							$pedido = recuperaDados("ig_evento",$ped['idEvento'],"idEvento");
							$evento = recuperaDados("ig_evento",$pedido['idEvento'],"nomeEvento"); 
							$projeto = recuperaDados("ig_projeto_especial",$pedido['idEvento'],"projetoEspecial");
							$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
							$local = listaLocais($pedido['idEvento']);
							$periodo = retornaPeriodo($pedido['idEvento']);
							$fiscal = recuperaUsuario($evento['idResponsavel']);
			
							if($pedido['publicado'] == 1)
							{		
								$evento['nomeEvento'];
								$i++;
							}
						}
					}
				}
				$x['num'] = $i;		
			}
	} 	
		$mensagem = "Foram encontradas ".$x['num']." pedido(s) de contratação.";
?>
<br /><br />
	<section id="list_items">
		 <div class="container">
			 <h3>Resultado da busca</h3>
             <h5>Foram encontrados <?php echo $x['num']; ?> pedidos de contratação.</h5>
             <h5><a href="?perfil=gestao_eventos&p=frm_busca">Fazer outra busca</a></h5>
		 <div class="table-responsive list_info">
		
<?php if($x['num'] == 0)
	{ 
?>
			
<?php 
	}
		else
		{ 
?>
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
					<td>Id Evento</td>	
					<td>Nome Evento</td>						
					<td>Proponente</td>
					<td>Objeto</td>
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
				echo "<tr><td class='list_description'> <a href='".$link.$x[$h]['idEvento']."'>".$x[$h]['idEvento']."</a></td>";
				echo '<td class="list_description">'.$x[$h]['nomeEvento'].		'</td>';
				echo '<td class="list_description">'.$x[$h]['proponente'].			'</td> ';
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
            		
<?php if($_SESSION['perfil'] == 1)
			{
?>					
					<label>Objeto/Evento</label>
            		<input type="text" name="evento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />
				
<?php 
			} 
?>
            			          
					<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
					<select class="form-control" name="fiscal" id="inputSubject" >
					<option value="0"></option>	
		
<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
					</select><br />
                    	                   
					<label>Tipo de Relação Jurídica</label>
					<select class="form-control" name="juridico" id="inputSubject" >
					<option value='0'></option>

<?php  geraOpcaoOrdem("ig_modalidade","modalidade"); ?>
					</select><br/>
					
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

<?php } ?>

<?php break; ?>

<?php 
} // fim da switch ?>