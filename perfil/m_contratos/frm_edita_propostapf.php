<?php
include 'includes/menu.php';

$con = bancoMysqli();
$_SESSION['idPedido'] = $_GET['id_ped'];
$id_ped = $_GET['id_ped'];
$ano=date('Y');

$link1="index.php?perfil=contratos&p=impressao_contratos_pf&id_ped=".$id_ped;

if(isset($_POST['idContrato']))
{
	$con = bancoMysqli();
	$idContratos = $_POST['contratos'];
	$idPedido = $_POST['idContrato'];
	$sql_atualiza_contratos = "UPDATE igsis_pedido_contratacao SET idContratos = '$idContratos' WHERE idPedidoContratacao = '$idPedido'";
	$query_atualiza_contratos = mysqli_query($con,$sql_atualiza_contratos);
	if($query_atualiza_contratos)
	{
		$mensagem = "Responsável por contrato atualizado.";
	}
	else
	{
		$mensagem = "Erro.";
	}
}

if(isset($_POST['atualizaGrupo']))
{ // atualiza o grupo
	$con = bancoMysqli();
	$idEvento = $_GET['idEvento'];
	$nomeGrupo = $_POST['nomeGrupo'];
	$sql_atualiza_grupo = "UPDATE ig_evento SET
		nomeGrupo = '$nomeGrupo'
		WHERE idEvento = '$idEvento'";
		$query_atualiza_grupo = mysqli_query($con,$sql_atualiza_grupo);
	if($query_atualiza_grupo)
	{
		$mensagem = "Grupo atualizado com sucesso. <br/> <br>";	 
	}
	else
	{
		$mensagem = "Erro(1) ao atualizar grupo.";	
	}
}

if(isset($_POST['atualizar']))
{ // atualiza o pedido
	$con = bancoMysqli();
	$ped = $_GET['id_ped'];
	$integrantes= addslashes($_POST['integrantes']);
	$verba = $_POST['Verba'];
	$justificativa = addslashes($_POST['Justificativa']);
	$fiscal = $_POST['Fiscal'];
	$suplente  = $_POST['Suplente'];
	$parecer = addslashes($_POST['ParecerTecnico']);
	$observacao = addslashes($_POST['Observacao']);
	$pendenciaDocumento = addslashes($_POST['pendenciaDocumento']);
    $recupera = recuperaDados("igsis_pedido_contratacao",$ped,"idPedidoContratacao");
    $idEvento = $recupera['idEvento'];
    $evento = recuperaDados('ig_evento', $idEvento, 'idEvento');
    $forma_pagamento = $_POST['FormaPagamento'];
    $processoMae = $_POST['processoMae'] ?? NULL;

	if ($_POST['parcelas'] <= 12)
    {
        $parcelas = $_POST['parcelas'];
        $tipoParcela = "tipoParcela = NULL";
    }
    else
    {
        $parcelas = substr($_POST['parcelas'], 0, 1);
        $tipoParcela = "tipoParcela = " . substr($_POST['parcelas'], 1, 1);
    }

	$processo = $_POST['NumeroProcesso'];
	$dataAgora = date('Y-m-d H:i:s');

	if($_POST['atualizar'] > '1')
	{
        if ($evento['ig_tipo_evento_idTipoEvento'] == 4)
        {
            $sql_atualiza_pedido = "UPDATE igsis_pedido_contratacao SET
            `formaPagamento` = '$forma_pagamento',
			`integrantes` = '$integrantes',
			`parcelas` =  '$parcelas',
            $tipoParcela, 
            idVerba = '$verba',
			justificativa = '$justificativa',
			observacao = '$observacao',
			pendenciaDocumento = '$pendenciaDocumento',
			parecerArtistico = '$parecer',
			DataContrato = '$dataAgora',
			NumeroProcesso = '$processo',
            processoMae = '$processoMae'
			WHERE idPedidoContratacao = '$ped'";
        }
        else
        {
            $sql_atualiza_pedido = "UPDATE igsis_pedido_contratacao SET
                `integrantes` = '$integrantes',
                `parcelas` =  '$parcelas',
                $tipoParcela,
                idVerba = '$verba',
                justificativa = '$justificativa',
                observacao = '$observacao',
                pendenciaDocumento = '$pendenciaDocumento',
                parecerArtistico = '$parecer',
                DataContrato = '$dataAgora',
                NumeroProcesso = '$processo',
                processoMae = '$processoMae'
                WHERE idPedidoContratacao = '$ped'";
        }
		$query_atualiza_pedido = mysqli_query($con,$sql_atualiza_pedido);
		if($query_atualiza_pedido)
		{
			$recupera = recuperaDados("igsis_pedido_contratacao",$ped,"idPedidoContratacao");
			$idEvento = $recupera['idEvento'];
			$sql_atualiza_evento = "UPDATE ig_evento SET
				idResponsavel = '$fiscal',
				suplente = '$suplente'
				WHERE idEvento = '$idEvento'";
			$query_atualiza_evento = mysqli_query($con,$sql_atualiza_evento);
			if($query_atualiza_evento)
			{
				$mensagem = "Pedido atualizado com sucesso. <br/> <br>
					<div class='row'>
						<div class='col-md-offset-1 col-md-10'>	
							<div class='form-group'>
								<div class='col-md-offset-2 col-md-8'>
									<a href='$link1' class='btn btn-theme btn-lg btn-block' target='_blank'>Ir para a área de impressão</a>
								</div>
							</div>
						</div>
					</div>		
					<br /></center>";
	 
			}
			else
			{
				$mensagem = "Erro(1) ao atualizar pedido.";	
			}
		}
		else
		{
			$mensagem = "Erro(2) ao atualizar pedido.";
		}
	}
	else
	{
		$valor = dinheiroDeBr($_POST['Valor']); 
		$forma_pagamento = $_POST['FormaPagamento'];
		$sql_atualiza_pedido = "UPDATE igsis_pedido_contratacao SET
			`integrantes` = '$integrantes',
			valor = '$valor',
			formaPagamento = '$forma_pagamento',
			`parcelas` =  '$parcelas',
			$tipoParcela,
            idVerba = '$verba',
			justificativa = '$justificativa',
			observacao = '$observacao',
			pendenciaDocumento = '$pendenciaDocumento',
			parecerArtistico = '$parecer',
			DataContrato = '$dataAgora',
			NumeroProcesso = '$processo',
            processoMae = '$processoMae'
			WHERE idPedidoContratacao = '$ped'";
		$query_atualiza_pedido = mysqli_query($con,$sql_atualiza_pedido);
		if($query_atualiza_pedido)
		{
			$recupera = recuperaDados("igsis_pedido_contratacao",$ped,"idPedidoContratacao");
			$idEvento = $recupera['idEvento'];
			$sql_atualiza_evento = "UPDATE ig_evento SET
				idResponsavel = '$fiscal',
				suplente = '$suplente'
				WHERE idEvento = '$idEvento'";
			$query_atualiza_evento = mysqli_query($con,$sql_atualiza_evento);
			if($query_atualiza_evento)
			{
				$mensagem = "Pedido atualizado com sucesso. <br/> <br><br>
					<div class='row'>
						<div class='col-md-offset-1 col-md-10'>	
							<div class='form-group'>
								<div class='col-md-offset-2 col-md-8'>
									<a href='$link1' class='btn btn-theme btn-lg btn-block' target='_blank'>Ir para a área de impressão</a>
								</div>
							</div>
						</div>
					</div>		
					<br /></center>";
			}
			else
			{
				$mensagem = "Erro(1) ao atualizar pedido.";	
			}	
		}
		else
		{
			$mensagem = "Erro(2) ao atualizar pedido.";
		}
	}
}

if(isset($_POST['idEstado']))
{
	$con = bancoMysqli();
	$ped = $_GET['id_ped'];
	$estado = $_POST['estado'];
    $pedRelacionados = $_POST['relacionados'];
    if (count($pedRelacionados) > 1) {
        $sql_atualiza_estado = "UPDATE igsis_pedido_contratacao SET estado = '$estado' WHERE idPedidoContratacao IN (".implode(", ", $pedRelacionados).")";
    } else {
        $sql_atualiza_estado = "UPDATE igsis_pedido_contratacao SET estado = '$estado' WHERE idPedidoContratacao = '$ped'";
    }
	$query_atualiza_estado = mysqli_query($con,$sql_atualiza_estado);
	if($query_atualiza_estado)
	{
		$mensagem = "Status atualizado com sucesso.";
	}
	else 
	{
		$mensagem = "Erro ao atualizar status.";
	}
}	

$ano=date('Y');
$id_ped = $_GET['id_ped'];	
$linha_tabelas = siscontrat($id_ped);
$ped = recuperaDados("igsis_pedido_contratacao",$id_ped,"idPedidoContratacao");
$fisico = siscontratDocs($linha_tabelas['IdProponente'],1);		
$evento = recuperaDados("ig_evento",$linha_tabelas['idEvento'],"idEvento");
$pedido = recuperaDados("igsis_pedido_contratacao",$_GET['id_ped'],"idPedidoContratacao");

$idVerba = $linha_tabelas['Verba'];
$sql_verifica_verba = "SELECT * FROM sis_verba WHERE publicado = 0 AND Id_Verba = '$idVerba'";
$query_verifica_verba = mysqli_query($con,$sql_verifica_verba);
$verba = mysqli_fetch_array($query_verifica_verba);
?>

<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h2>PEDIDO DE CONTRATAÇÃO DE PESSOA FÍSICA</h2>
            <h4><?php if(isset($mensagem)){ echo $mensagem; } ?></h4>
        </div>

		<div class="form-group">                  
			<div class="col-md-offset-2 col-md-8"><hr/></div>
		</div> 

		<div class="form-group">
            <div class="col-md-offset-2 col-md-8">
				<h5>Pedidos Relacionados</h5>
				<?php 
					$outros = listaPedidoContratacao($pedido['idEvento']);
                    $relacionados = [];
					for($i = 0; $i < count($outros); $i++)
					{
                        array_push($relacionados, $outros[$i]);
						$dados = siscontrat($outros[$i]);
						if($dados['TipoPessoa'] == 1)
						{
				?>
							<p align="left">Número do Pedido de Contratação:<b> <a href="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $outros[$i]; ?>"></b><?php echo $outros[$i]; ?></a><br /></p>
				<?php 
						}
						if($dados['TipoPessoa'] == 2)
						{
				?>
							<p align="left">Número do Pedido de Contratação:<b> <a href="?perfil=contratos&p=frm_edita_propostapj&id_ped=<?php echo $outros[$i]; ?>"></b><?php echo $outros[$i]; ?></a><br /></p>
				<?php 
						}
					}		
				?>
            	<br />
			</div>
		</div>

		<div class="form-group">                  
			<div class="col-md-offset-2 col-md-8"><hr/></div>
		</div> 

	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
			<!-- Coordenador de Contratos -->
			<?php 
				$coord = recuperaDados("ig_usuario",$_SESSION['idUsuario'],"idUsuario");
				if($coord['contratos'] ==  3)
				{
			?>	
					<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $id_ped; ?>" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-5"><strong>Responsável no Setor de Contratos Artísticos:</strong><br/>
							<select class="form-control" name="contratos" id="">
								<option value='0'></option>
								<?php  geraOpcaoContrato($ped['idContratos']); ?>
							</select>
						</div>
						<div class="col-md-3"><br/>
							<input type="hidden" name="idContrato" value="<?php echo $id_ped; ?>" />
							<input type="submit" class="btn btn-theme  btn-block" value="Atualizar responsável">
						</div>
					</div>
					</form>
				
					<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $id_ped; ?>" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-5"><strong>Status:</strong><br/>
							<select class="form-control" name="estado" id="">
								<option value='0'></option>
								<?php  geraOpcaoEstado($ped['estado'],1); ?>
							</select>
						</div>
						<div class="col-md-3"><br/>
							<input type="hidden" name="idEstado" value="<?php echo $id_ped; ?>" />
                            <?php foreach ($relacionados as $relacionado) { ?>
                                <input type="hidden" name="relacionados[]" value="<?= $relacionado ?>" />
                            <?php } ?>
                            <input type="submit" class="btn btn-theme  btn-block" value="Atualizar status">
						</div>
					</div>
					</form>
				
					<div class="form-group">                  
						<div class="col-md-offset-2 col-md-8"><hr/></div>
					</div> 
				
			<?php 
				}
				elseif ($coord['contratos'] == 2)
				{ 
			?>	
					<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $id_ped; ?>" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-5"><strong>Status:</strong><br/>
							<select class="form-control" name="estado" id="">
								<option value='0'></option>
								<?php  geraOpcaoEstado($ped['estado'],1); ?>
							</select>
						</div>
						<div class="col-md-3"><br/>
							<input type="hidden" name="idEstado" value="<?php echo $id_ped; ?>" />
                            <?php foreach ($relacionados as $relacionado) { ?>
                                <input type="hidden" name="relacionados[]" value="<?= $relacionado ?>" />
                            <?php } ?>
							<input type="submit" class="btn btn-theme  btn-block" value="Atualizar status">
						</div>
					</div>
					</form>
				
					<div class="form-group">                  
						<div class="col-md-offset-2 col-md-8"><hr/></div>
					</div> 
			<?php
			}
			?>		

			<!-- Fim Coordenador de Contratos -->
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Código do Pedido de Contratação:</strong><br/> <?php echo "$ano-$id_ped"; ?><br/>	  
					</div>
					<div class="col-md-6"><strong>Data do Cadastro:</strong><br/>
						<?php echo exibirDataBr($evento['dataEnvio']);?><br/>
					</div>
				</div>
                  
                <div class="form-group">                  
					<div class="col-md-offset-2 col-md-8"><br/></div>
                </div>  
                  
				<div class="form-group">                    
                    <div class=" col-md-offset-2 col-md-6"><strong>Setor:</strong><br/>
						<?php echo $linha_tabelas['Setor'];?> <br /><br />
                    </div>
                    <div class="col-md-6"><strong>Categoria da Contratação:</strong><br/>
						<?php echo retornaTipo($evento['ig_tipo_evento_idTipoEvento']);?> <br /><br />
					</div>
				</div>
				  
                <div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Proponente:</strong><br/>
						<input type='text' readonly class='form-control' name='nome' id='nome' value='<?php echo $fisico['Nome'];?>'>
					</div>
                </div>

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <form class="form-horizontal" role="form" action="?perfil=contratos&p=busca_pf" method="post">
                            <input type="hidden" name="idPedido" value="<?php echo $id_ped; ?>" />
                            <input type="submit" class="btn btn-warning btn-med btn-block" value="Alterar proponente">
                        </form>
                    </div>
                </div>
                
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
					<form class="form-horizontal" role="form" action="<?php echo "?perfil=contratos&p=frm_edita_pf&id_pf=".$linha_tabelas['IdProponente']."&id_ped=".$id_ped; ?>" method="post">
						<input type="hidden" name="idPedido" value="<?php echo $id_ped; ?>" />
						<input type="submit" class="btn btn-theme btn-med btn-block" value="Abrir proponente">
                    </form>
					</div>
				</div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
													  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
				  
				<!-- Atualiza Grupo -->			  
				<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $id_ped; ?>&idEvento=<?php echo $pedido['idEvento']; ?>" method="post">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-5">
						<label>Nome do Grupo</label>
						<input type="text" name="nomeGrupo" id="nomeGrupo" class="form-control" value="<?php echo $evento['nomeGrupo'] ?>" >
					</div>
					<div class="col-md-3"><br/>
						<input type="hidden" name="atualizaGrupo" value="<?php echo $pedido['idEvento']; ?>" />
						<input type="submit" class="btn btn-theme  btn-block" value="Atualizar Grupo">
					</div>				
				</div>
				</form>

				<form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $id_ped; ?>" method="post">
				
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br /></div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Integrantes do grupo:</strong><br/>
						<textarea name="integrantes" class='form-control' cols="40" rows="5"><?php echo $ped['integrantes'] ?></textarea>
					</div>
				</div>

                <div class="form-group">                  
					<div class="col-md-offset-2 col-md-8" align="left"><strong>Objeto:</strong> 
						<?php echo $linha_tabelas['Objeto'];?><br/><br/>
					</div>
				</div>                
                  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8" align="left"><strong>Local:</strong> 		
						<?php echo $linha_tabelas['Local'];?><br/><br/>			 
					</div>
				</div>
   
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Período:</strong><br/>
						<input type='text' readonly name="Periodo" class='form-control' <?php echo "value='$linha_tabelas[Periodo]'";?>><br/>	
					</div>
				</div>
				  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Duração:</strong> 
						<?php echo $linha_tabelas['Duracao'];?><br/>
					</div>
					<div class="col-md-6"><strong>Carga Horária:</strong> 
						<?php echo $linha_tabelas['CargaHoraria'];?><br/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Relação Jurídica:</strong> 
						<?php echo retornaRelacaoJuridica($evento['ig_modalidade_IdModalidade']);?></div>
				</div>	
                  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br /></div>
				</div>

                <?php 
					if($pedido['parcelas'] > 1)
					{ 
				?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Valor:</strong><br/>
								<input type='text' disabled name="valor_parcela" id='valor' class='form-control' value="<?php echo dinheiroParaBr($pedido['valor']) ?>" >
							</div>
						</div>
				<?php 
					}
					else
					{ 
				?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Valor:</strong><br/>
								<input type='text' name="Valor" id='valor' class='form-control' value="<?php echo dinheiroParaBr($pedido['valor']) ?>" >
							</div>
						</div>							
				<?php 
					}
					if($pedido['parcelas'] > 0)
					{
                        if ($evento['ig_tipo_evento_idTipoEvento'] == 4)
                        {
                ?>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-8"><strong>Forma de Pagamento / Valor da Prestação de Serviço:</strong><br/>
                                    <textarea name="FormaPagamento" class="form-control" cols="40" rows="5"><?php echo txtParcelasOficinas($_SESSION['idPedido'],$pedido['parcelas'],$pedido['tipoParcela']); ?></textarea>
                                    <p>&nbsp;</p>
                                </div>
                            </div>
                <?php
                        }
                        else
                        {
                ?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Forma de Pagamento:</strong><br/>
								<textarea name="FormaPagamento" class="form-control" cols="40" rows="5"><?php echo txtParcelas($_SESSION['idPedido'],$pedido['parcelas']); ?>
								</textarea>
							<p>&nbsp;</p>
							</div>
						</div>
				<?php
                        }
					}
					else
					{ 
				?>				
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Forma de Pagamento / Valor da Prestação do serviço:</strong><br/>
								<textarea name="FormaPagamento" class="form-control" cols="40" rows="5"><?php echo $pedido['formaPagamento'] ?></textarea>
							</div>
						</div>
				<?php 
					}
				?>   	

					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Parcelas (antes de editar as parcelas, é preciso salvar o pedido)</strong><br/>
                            <select class="form-control" id="parcelas" name="parcelas">
                                <?php if ($evento['ig_tipo_evento_idTipoEvento'] == 4) { /*Caso seja evento tipo OFICINA*/?>
                                    <option value="0" <?php if($pedido['parcelas'] == '0'){ echo "selected"; } ?> >Outros</option>
                                    <option value="1" <?php if($pedido['parcelas'] == '1'){ echo "selected"; } ?> >Parcela única Oficinas de Curta Duração (1 mês)</option>
                                    <option value="21" <?php if(($pedido['parcelas'] == '2') && ($pedido['tipoParcela'] == 1)){ echo "selected"; } ?> >2 parcelas Oficinas de Média Duração I (3 meses)</option>
                                    <option value="22" <?php if(($pedido['parcelas'] == '2') && ($pedido['tipoParcela'] == 2)){ echo "selected"; } ?> >2 parcelas Oficinas de Média Duração II (4 meses) </option>
                                    <option value="3" <?php if($pedido['parcelas'] == '3'){ echo "selected"; } ?> >3 parcelas Oficina Estendida I (6 meses)</option>
                                    <option value="5" <?php if($pedido['parcelas'] == '5'){ echo "selected"; } ?> >5 parcelas Oficina Estendida II  (10 meses)</option>
                                <?php } else { ?>
                                    <option value="0" <?php if($pedido['parcelas'] == '0'){ echo "selected"; } ?> >Outros</option>
                                    <option value="1" <?php if($pedido['parcelas'] == '1'){ echo "selected"; } ?> >Parcela única</option>
                                    <option value="2" <?php if($pedido['parcelas'] == '2'){ echo "selected"; } ?> >2 parcelas</option>
                                    <option value="3" <?php if($pedido['parcelas'] == '3'){ echo "selected"; } ?> >3 parcelas</option>
                                    <option value="4" <?php if($pedido['parcelas'] == '4'){ echo "selected"; } ?> >4 parcelas</option>
                                    <option value="5" <?php if($pedido['parcelas'] == '5'){ echo "selected"; } ?> >5 parcelas</option>
                                    <option value="6" <?php if($pedido['parcelas'] == '6'){ echo "selected"; } ?> >6 parcelas</option>
                                    <option value="7" <?php if($pedido['parcelas'] == '7'){ echo "selected"; } ?> >7 parcelas</option>
                                    <option value="8" <?php if($pedido['parcelas'] == '8'){ echo "selected"; } ?> >8 parcelas</option>
                                    <option value="9" <?php if($pedido['parcelas'] == '9'){ echo "selected"; } ?> >9 parcelas</option>
                                    <option value="10" <?php if($pedido['parcelas'] == '10'){ echo "selected"; } ?> >10 parcelas</option>
                                    <option value="11" <?php if($pedido['parcelas'] == '11'){ echo "selected"; } ?> >11 parcelas</option>
                                    <option value="12" <?php if($pedido['parcelas'] == '12'){ echo "selected"; } ?> >12 parcelas</option>
                                <?php } ?>
                            </select>
						</div>	
                    </div>
                                     
				<?php
					if($pedido['parcelas'] > 1)
					{ //libera a edição de parcelas
				?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<a href="?perfil=contratos&p=frm_parcelas" class="btn btn-theme btn-block">Editar parcelas</a>
							</div>  
						</div>
				<?php 
					} 
				?>
				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><strong>Verba: <span style="color:red"><strike><?= $verba['Verba'] ?></strike></span></strong><br/>
						<select class="form-control" name="Verba" id="Verba">
                            <option value="">Selecione...</option>
							<?php geraOpcaoPublicado("sis_verba",$linha_tabelas['Verba'],"") ?>
						</select>
					</div>
				</div>
				<?php 
					if($pedido['idVerba'] == 33 OR $pedido['idVerba'] == 69)
					{ 
				?>
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8"><strong>Verbas Multiplas</strong> <br/><br />
							<?php
								 $verbas = retornaVerbaMultiplas($id_ped); 
								 for($i = 0; $i < $verbas['numero']; $i++)
								 {
							?>
									<p class="left"><b><?php echo $verbas[$i]['verba']; ?> </b>: <?php echo $verbas[$i]['valor']; ?></p>
							<?php 
								} 
							?>
							</div>
						</div>	
				<?php 
					} 
				?>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><strong>Número processo mãe:</strong><br/>
                        <input type="text" class="form-control" id="NumProcesso" name="processoMae" value="<?= $pedido['processoMae'] ?>">
                    </div>
                </div>

                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Justificativa:</strong><br/>
						<textarea readonly name="Justificativa" cols="40" rows="5"><?php echo $pedido['justificativa']; ?></textarea>
					</div>
				</div>
                
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Fiscal:</strong>
						<select class="form-control" name="Fiscal" id="Fiscal">
							<?php opcaoUsuario($_SESSION['idInstituicao'],$evento['idResponsavel']); ?>
						</select>
					</div>					
					<div class="col-md-6"><strong>Suplente:</strong>
						<select class="form-control" name="Suplente" id="Fiscal">
							<?php opcaoUsuario($_SESSION['idInstituicao'],$evento['suplente']); ?>
						</select>
					</div>
				</div>
                
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Parecer Técnico:</strong><br/>
						<textarea readonly name="ParecerTecnico" cols="40" rows="5"><?php echo $pedido['parecerArtistico']; ?></textarea><br/> <br/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Número do Processo:</strong>
						<input type="text" class="form-control" id="NumeroProcesso" name="NumeroProcesso" placeholder="Número do Processo"  value="<?php echo $pedido['NumeroProcesso']; ?>" />
					</div>
				</div>
				  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Observação do Programador:</strong><br/>
					    <textarea name="Observacao" cols="40" rows="2"><?php echo $linha_tabelas['Observacao'];?></textarea>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Pendência(s) no Setor de Contratos Artísticos:</strong><br/>
					    <textarea name="pendenciaDocumento" cols="40" rows="3"><?php echo $linha_tabelas['pendenciaDocumento'];?></textarea>
					</div>
				</div>
                  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="atualizar" value="<?php echo $pedido['parcelas']; ?>" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Gravar">
					</div>
				</div>
				</form>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<a href="?perfil=detalhe_pedido&id_ped=<?php echo $pedido['idPedidoContratacao']; ?>" class="btn btn-theme btn-block" target="_blank" >Abrir detalhes do evento</a>
					</div>			
					<div class="col-md-6">
						<a href="?perfil=contratos&p=frm_arquivos_pedidos&id_ped=<?php echo  $_GET['id_ped']; ?>" class="btn btn-theme btn-block" target="_blank" >Abrir Anexos do Pedido</a>
					</div>	
				</div>
				
				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><br /></div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<a href="../perfil/m_contratos/frm_arquivos_todos_pedidos.php?idPedido=<?php echo $_GET['id_ped'];  ?>&all=true" class="btn btn-theme btn-block" >Baixar todos os arquivos do pedido e do proponente</a>
					</div>	
				</div>
					
				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
				
				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
				
	            <div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<a href="?perfil=contratos&p=frm_chamados&pag=evento&idEvento=<?php echo $pedido['idEvento'];  ?>" class="btn btn-theme btn-block" target="_blank" >Chamados</a>
					</div>	
				</div>
				
				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
				
				<!-- reabrir -->
                <?php
                if($coord['contratos'] ==  3) {
                ?>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <form class="form-horizontal" role="form" action="?perfil=contratos&p=frm_reabre"
                                  method="post">
                                <input type="hidden" name="final" value="?perfil=contratos&p=frm_busca">
                                <input type="hidden" name="voltar" value="?<?php echo $_SERVER["QUERY_STRING"] ?>">
                                <input type="hidden" name="idEvento" value="<?php echo $pedido['idEvento'] ?>">
                                <input type="submit" class="btn btn-theme btn-lg btn-block" value="Reabertura">
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>
				<!-- // reabrir -->
				
				<div class="form-group">
                    <div class="col-md-offset-2 col-md-8"><br /></div>
				</div>
		
	  		</div>
	  	</div>
	</div>	
</section>  
