<?php
	require_once("../funcoes/funcoesVerifica.php");
	require_once("../funcoes/funcoesSiscontrat.php");
	include "../include/menuBusca.php";
	$ano=date('Y');
	$id_ped = $_GET['id_ped'];	
	$pedido = siscontrat($id_ped);
	$ped = recuperaDados('igsis_pedido_contratacao',$id_ped,'idPedidoContratacao');
	$idEvento = $pedido['idEvento'];
	$pessoa = siscontratDocs($pedido['IdProponente'],$pedido['TipoPessoa']);
	$evento = recuperaDados('ig_evento',$idEvento,'idEvento');
	$chamado = recuperaAlteracoesEvento($idEvento);	
	
?>
<!-- Contact -->
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="form-group"><h3><?php echo $evento['nomeEvento'] ?></h3>
			<h5><?php if(isset($mensagem)){ echo $mensagem; } ?></h5>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">            
				<div class="left">
<?php
	if($pedido['TipoPessoa'] == '4')
	{
?>
					<h3>CONTRATAÇÃO DA SMC-DIVISÃO DE FORMAÇÃO</h3>
<?php
	}
	else
	{
?>
					<p align="justify"><?php descricaoEvento($idEvento); ?></p>
					<br/>
					<h5>Ocorrências</h5>
					<p align="justify"><?php echo resumoOcorrencias($idEvento); ?></p>
					<p align="justify"><?php listaOcorrenciasTexto($idEvento); ?></p>				
					<br />
					<h5>Especificidades</h5>
					<p align="justify"><?php descricaoEspecificidades($idEvento,$evento['ig_tipo_evento_idTipoEvento']); ?></p>
					<br/>
					<h5>Sub-eventos</h5>
	<?php
		if($evento['subEvento'] == '1')
		{
	?>
					<p align="justify"><?php listaSubEventosCom($idEvento); ?></p>
	<?php
		}
		else
		{
	?>
					<p>Não há sub-eventos cadastrados.</p>
	<?php
		}
		if($evento['ig_tipo_evento_idTipoEvento'] == '1')
		{
	?>
					<br />
					<h5>Grade de filmes</h5>
					<p align="justify"><?php gradeFilmes($idEvento) ?></p>
	<?php
		}
	?>
					<br />
					<h5>Serviços externos</h5>
					<p align="justify"><?php listaServicosExternos($idEvento); ?></p>
					<br />
					<h5>Serviços internos</h5>
					<p align="justify"><?php listaServicosInternos($idEvento) ?></p>
<?php
	}
?>
					<br />	
					<h5>Arquivos Comunicação/Produção anexos</h5>
					<p align="left"><?php listaArquivosDetalhe($_GET['id_ped']) ?></p>
					<br />				
					<h5>Pedidos de contratação</h5>
<?php
	if($pedido != NULL)
	{
?>
					<p align="justify"><strong>Código do pedido de contratação:</strong> <?php echo $id_ped; ?></p>
					<p align="justify"><strong>Número do processo:</strong> <?php echo $pedido['NumeroProcesso'];?></p>
					<p align="justify"><strong>Setor:</strong> <?php echo $pedido['Setor'];?></p>
					<p align="justify"><strong>Tipo de pessoa:</strong> <?php echo retornaTipoPessoa($pedido['TipoPessoa']);?></p>
					<p align="justify"><strong>Proponente:</strong> <?php echo $pessoa['Nome'];?></p>
					<p align="justify"><strong>Objeto:</strong> <?php echo $pedido['Objeto'];?></p>
					<p align="justify"><strong>Local:</strong> <?php echo $pedido['Local'];?></p>
	<?php
		if($pedido['TipoPessoa'] == '4')
		{
	?>
					<p align="justify"><strong>Carga horária:</strong> <?php echo $pedido['CargaHoraria'];?></p>
	<?php
		}
	?>
					<p align="justify"><strong>Verba:</strong> <?php echo retornaVerba($pedido['Verba']);?></p>
					<p align="justify"><strong>Valor:</strong> R$ <?php echo dinheiroParaBr($pedido["ValorGlobal"]);?></p>
					<p align="justify"><strong>Forma de Pagamento:</strong> <?php echo addslashes($pedido['FormaPagamento']);?></p>
					<p align="justify"><strong>Data/Período:</strong> <?php echo $pedido['Periodo'];?></p>
					<p align="justify"><strong>Justificativa:</strong> <?php echo $pedido['Justificativa']; ?></p>
					<p align="justify"><strong>Parecer:</strong> <?php echo $pedido['ParecerTecnico'];?></p>
					<p align="justify"><strong>Nota de Empenho:</strong> <?php echo $pedido['NotaEmpenho'];?></p>
					<p align="justify"><strong>Data de Emissão da N.E.:</strong> <?php 
						if ($pedido['EmissaoNE'] == '0000-00-00' OR $pedido['EmissaoNE'] == NULL)
						{
							echo "Não há registro.";
						}
						else
						{	
							echo exibirDataBr($pedido['EmissaoNE']);
						}?>
					</p>
					<p align="justify"><strong>Data de Entrega de N.E.:</strong> <?php 
						if ($pedido['EntregaNE'] == '0000-00-00' OR $pedido['EntregaNE'] == NULL)
						{
							echo "Não há registro.";
						}
						else
						{	
							echo exibirDataBr($pedido['EntregaNE']);
						}?>
					</p>
					<p align="justify"><strong>Dotação Orçamentária:</strong> <?php echo $pedido['ComplementoDotacao'];?></p>
					<p align="justify"><strong>Observação:</strong> <?php echo $pedido['Observacao'];?></p>
					<?php $status = recuperaDados("sis_estado",$pedido['Status'],"idEstado"); ?>
					<p align="justify"><strong>Último Status:</strong> <?php echo $status['estado'];?></p>
					<br/>
					<h5>Data de recebimento pelos setores</h5>
					<div class="table-responsive list_info">
						<table class='table table-condensed'>
							<thead>
								<tr class='list_menu'>
									<td>Contratos</td>
									<td>Jurídico</td>
									<td>Publicação</td>
									<td>Contabilidade</td>
									<td>Pagamento</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php 
									if ($ped['DataContrato'] == '0000-00-00 00:00:00')
										{
											echo "Não há registro.";
										}
										else
										{
											echo exibirDataHoraBr($ped['DataContrato']);
										}	?>
									</td>
									<td><?php 
									if ($ped['DataJuridico'] == '0000-00-00 00:00:00')
										{
											echo "Não há registro.";
										}
										else
										{ 
											echo exibirDataHoraBr($ped['DataJuridico']);
										}	?>
									</td>
									<td><?php 
									if ($ped['DataPublicacao'] == '0000-00-00 00:00:00')
										{
											echo "Não há registro.";
										}
										else
										{ 
											echo exibirDataHoraBr($ped['DataPublicacao']);
										}	?>
									</td>
									<td><?php 
									if ($ped['DataContabilidade'] == '0000-00-00 00:00:00')
										{
											echo "Não há registro.";
										}
										else
										{
											echo exibirDataHoraBr($ped['DataContabilidade']);
										}	?>
									</td>
									<td><?php 
										if ($ped['DataPagamento'] == '0000-00-00 00:00:00')
										{
											echo "Não há registro.";
										}
										else
										{
											echo exibirDataHoraBr($ped['DataPagamento']);
										}	?>
									</td>
								</tr>
						</table>
					</div>		
	<?php
		}
		else
		{
	?>
					<h5> Não há pedidos de contratação. </h5>
<?php
	}
?>
					<br />
					<h5>Chamados</h5>
<?php 
	if($chamado['numero'] == '0')
	{
		echo "Não há registro de chamado para este evento.<br/><p>&nbsp</p>";
	}
	else
	{	
?>
					<div class="table-responsive list_info">
						<table class='table table-condensed'>
							<thead>
								<tr class='list_menu'>
									<td width='5%'>ID</td>
									<td>Chamado</td>
									<td>Descrição</td>
									<td>Data do envio</td>
									<td>Status</td>
								</tr>
							</thead>
							<tbody>
					<?php
						$idUsuario = $_SESSION['idUsuario'];
						$con = bancoMysqli();
						$sql_busca = "SELECT * FROM igsis_chamado WHERE idEvento = '$idEvento' ORDER BY idChamado DESC";
						$query_busca = mysqli_query($con,$sql_busca);
						while($chamado = mysqli_fetch_array($query_busca))
						{ 
							$tipo = recuperaDados("igsis_tipo_chamado",$chamado['tipo'],"idTipoChamado");
					?>	
								<tr>
									<td><?php echo $chamado['idChamado']; ?></td>
									<td><a href="?perfil=chamado&p=detalhe&id=<?php echo $chamado['idChamado'] ?>" ><?php echo $tipo['chamado']; ?></a></td>
									<td><?php echo nl2br($chamado['descricao']) ?></td>
									<td><?php echo exibirDataHoraBr($chamado['data']) ?></td>
									<td><?php
										if ($chamado['estado'] == 1)
										{
											echo "Aberto";
										}
										else
										{
											echo "Fechado";
										}?>		
									</td>
								</tr>					
					<?php
						}
					?>
							</tbody>
						</table>	   
					</div>
<?php 
	} 
?>
					<br />
					<h5>Histórico de reabertura</h5>
					<?php
						$con = bancoMysqli();									
						$sql_reabertura = "SELECT * FROM `ig_log_reabertura` WHERE idEveForm = '$idEvento' AND idPedido != '0' ORDER BY data DESC";
						$query_reabertura = mysqli_query($con,$sql_reabertura);	
						$row_cnt = mysqli_num_rows($query_reabertura);
						
						if($row_cnt > 0)
						{
					?>							
							<table class='table table-condensed'>
								<?php							
								while($reabertura = mysqli_fetch_array($query_reabertura))
								{ 									
								?>	
										<tr>
											<td><?php echo $reabertura['descricao']; ?>
											<td><?php  echo exibirDataHoraBr($reabertura['data']); ?></td>
										</tr>
								<?php
								}
								?>
							</table>
					<?php
						}
						else
						{
							echo "Não há registro para este evento.<p>&nbsp;</p>";
						}	
					?>
					
					
					<br />
					<h5>Histórico de envio</h5>
					<?php
						$con = bancoMysqli();
						$dataHistorico = recuperaDados('ig_data_envio',$idEvento,'idEvento');
						$sql_data_envio = "SELECT * FROM `ig_data_envio` WHERE idEvento = '$idEvento' ORDER BY dataEnvio DESC";
						$query_data_envio = mysqli_query($con,$sql_data_envio);	
						
						if($dataHistorico != '')
						{
					?>							
							<table class='table table-condensed'>
								<?php									
								while($dataEnvio = mysqli_fetch_array($query_data_envio))
								{ 									
								?>	
										<tr>
											<td><?php  echo exibirDataHoraBr($dataEnvio['dataEnvio']); ?></td>
										</tr>
								<?php
								}
								?>
							</table>
					<?php
						}
						else
						{
							echo "Não há registro para este evento.<p>&nbsp;</p>";
						}	
					?>
					
					<br/>
					<?php 
						$con = bancoMysqli();
						$sql_nao_aprovado = "SELECT * FROM `igsis_argumento` WHERE `idEvento` = '$idEvento' ORDER BY data DESC";
						$query_nao_aprovado = mysqli_query($con,$sql_nao_aprovado);
						$i = 0;
						while($lista = mysqli_fetch_array($query_nao_aprovado))
						{			
							$operador = recuperaUsuario($lista['idContratos']);						
							$x[$i]['argumento']= $lista['argumento'];
							$x[$i]['idContratos'] = $operador['nomeCompleto'];
							$x[$i]['data'] = exibirDataHoraBr($lista['data']);
							$i++;				
						}
						
						if($i > 0)
						{
						?>	
							<h5>Argumento da Não Aprovação por Contratos</h5>
							<div class="table-responsive list_info">
								<table class="table table-condensed">
									<thead>
										<tr class="list_menu">								
											<td width="65%">Argumento</td>
											<td>Operador de Contratos</td>
											<td>Data</td>								
										</tr>
									</thead>
									<tbody>
									<?php
									for($h = 0; $h < $i; $h++)
									{
										echo '<tr>';
										echo '<td class="list_description">'.$x[$h]['argumento'].'</td>';
										echo '<td class="list_description">'.$x[$h]['idContratos'].'</td>';
										echo '<td class="list_description">'.$x[$h]['data'].'</td>';
										echo'</tr>';
									}
									?>
									</tbody>
								</table>
							</div>
					<?php	
						}
					?>
				</div>
			</div>
		</div>
	</div>
</section>         