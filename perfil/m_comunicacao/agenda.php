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
					 <h2>Comunicação - Agenda</h2>
					<h4></h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
                 </div>
				  </div>
			  </div>  
            <form method="POST" action="?perfil=comunicacao&p=agenda" class="form-horizontal" role="form">
                <div class="form-group">
                	<div class="col-md-offset-2 col-md-6">
               			 <label>Data início *</label>
                		<input type="text" name="inicio" class="form-control" id="datepicker01" placeholder="">
               		 </div>
                	<div class=" col-md-6">
                		<label>Data encerramento *</label>
                		<input type="text" name="final" class="form-control" id="datepicker02"  placeholder="">
               		</div>
                </div>
                  <div class="form-group">
	            <div class="col-md-offset-2 col-md-8">
                <br />
                	<input type="submit" class="btn btn-theme btn-lg btn-block" value="Filtrar">
                    <br >
					</form>
            	</div>
            </div>
            </form>
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
					/*
					$sql_busca_dic = "SELECT DISTINCT idEvento FROM ig_ocorrencia WHERE 
					(".
					"(dataInicio >= '$data_inicio' AND dataInicio <= '$data_final')". // o evento começa no período
					" OR (dataInicio <= '$data_inicio' AND dataFinal <= '$data_final' AND dataFinal <> '0000-00-00' )". // o evento começa antes e acaba durante o período
					" OR (dataInicio <= '$data_inicio' AND dataFinal >= '$data_final' AND dataFinal <> '0000-00-00')". // o evento começa antes e acaba depois do período

					") AND publicado = '1' ".
					
					"  ORDER BY dataInicio ASC";
					*/
					$loc = recuperaDados("ig_usuario",$_SESSION['idUsuario'],"idUsuario");
					$local = $loc['local'];
					$sql_busca_dic = "SELECT DISTINCT idEvento FROM igsis_agenda WHERE idLocal IN($local) AND data <= '$data_final' AND data >= '$data_inicio' ORDER BY idTipo"; 
					  
					$query_busca_dic = mysqli_query($con,$sql_busca_dic);
						while($evento = mysqli_fetch_array($query_busca_dic)){ 
						$event = recuperaDados("ig_evento",$evento['idEvento'],"idEvento");
						$nome = recuperaUsuario($event['idUsuario']);
						$chamado = recuperaAlteracoesEvento($evento['idEvento']);						
						if($event['dataEnvio'] != NULL AND $event['idInstituicao'] == $_SESSION['idInstituicao']){ // só as enviadas
						?>
						
					<tr>
					<td><?php echo retornaProtoEvento($evento['idEvento']) ?></td>
					<td><a href="?perfil=comunicacao&p=editar&id=<?php echo $evento['idEvento']  ?>"><?php echo $event['nomeEvento'] ?></a>  [<?php 
					if($chamado['numero'] == '0'){
						echo "0";
					}else{
						echo "<a href='?perfil=chamado&p=evento&id=".$evento['idEvento']."' target='_blank'>".$chamado['numero']."</a>";	
					}
					
					?>] </td>
					<td><?php echo $nome['nomeCompleto'] ?></td>
					<td><?php echo retornaPeriodo($event['idEvento']) ?></td>
					</tr>					
					<?php
						}
						}
					?>
					
					
					</tbody>
					</table>
				   
			</div>
		</div>
	</section>