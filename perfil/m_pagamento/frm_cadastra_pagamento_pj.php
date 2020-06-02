<?php

$id_ped = $_GET['id_ped'];
$server = "http://".$_SERVER['SERVER_NAME']."/igsis/";
$http = $server."/pdf/";
$link1=$http."rlt_pagamento_integral_1rep_pj.php";
$link2=$http."rlt_pagamento_integral_2rep_pj.php";
$link3=$http."rlt_pagamento_parcelado_1rep_pj.php";
$link4=$http."rlt_pagamento_parcelado_2rep_pj.php";
$link5=$http."rlt_recibo_pagamento_1rep_pj.php";
$link6=$http."rlt_recibo_pagamento_2rep_pj.php";
$link7=$http."rlt_declaracao_simples_pj.php";
$link8=$http."rlt_recibo_pagamento_parcelado_1rep_pj.php";
$link9=$http."rlt_recibo_pagamento_parcelado_2rep_pj.php";
$link10=$http."rlt_recibo_documentacao_pj.php";
$link11=$http."rlt_ateste_confirmacao_pj.php";
$link12=$http."rlt_emissao_nf_integral.php";
$link13=$http."rlt_emissao_nf_parcelado.php";
$link14=$http."rlt_email_empresas.php";
$link15=$http."rlt_minuta_acima80k.php";
$link16=$http."rlt_declaracao_semFinsLucrativos.php";


$data = date('Y-m-d H:i:s');

$con = bancoMysqli();

if(isset($_POST['idPagamentos']))
{
	$con = bancoMysqli();
	$idPagamentos = $_POST['pagamentos'];
	$idPedido = $_POST['idPagamentos'];
	$sql_atualiza_pagamentos = "UPDATE igsis_pedido_contratacao SET idPagamentos = '$idPagamentos' WHERE idPedidoContratacao = '$idPedido'";
	$query_atualiza_pagamentos = mysqli_query($con,$sql_atualiza_pagamentos);
	if($query_atualiza_pagamentos)
	{
		$mensagem = "Responsável por pagamento atualizado.";
	}
	else
	{
		$mensagem = "Erro ao gravar! Tente novamente.";
	}
}

if(isset($_POST['kitPagamento']))
{
	$con = bancoMysqli();
	$dataKitPagamento = exibirDataMysql($_POST['dataKitPagamento']);
	$idPedido = $_POST['kitPagamento'];
	$sql_atualiza_kit = "UPDATE igsis_pedido_contratacao SET dataKitPagamento = '$dataKitPagamento' WHERE idPedidoContratacao = '$idPedido'";
	$query_atualiza_kit = mysqli_query($con,$sql_atualiza_kit);
	if($query_atualiza_kit)
	{
		$mensagem = "Data atualizada.";
	}
	else
	{
		$mensagem = "Erro ao gravar! Tente novamente.";
	}
}

if(isset($_POST['atualizar'])) // atualiza o pedido
{
	$ped = $_GET['id_ped'];

	$sql_atualiza_pedido = "UPDATE igsis_pedido_contratacao SET
		estado = 14,
		DataPagamento = '$data'
		WHERE idPedidoContratacao = '$id_ped'";
	if(mysqli_query($con,$sql_atualiza_pedido))
	{
		$mensagem = "
		<div class='form-group'>
		  <div class='col-md-offset-2 col-md-10'><hr/></div>
		</div><br/>
		<h5>Qual documento deseja gerar?</h5>
		<div class='col-md-offset-1 col-md-10'>
	<!--	<div class='col-md-4'>
		<a href='$link11?id=$id_ped' target='_blank'>Confirmação de Serviço</a>
		
        </div>
        <div class='col-md-4'>
		<a href='$link10?id=$id_ped' target='_blank'>Ateste (documentação)</a>
        </div>
        <div class='col-md-4'>
		<a href='$link15?id=$id_ped' target='_blank'>Minuta acima de R$ 176 mil</a>
        </div>
        <br><br> -->
		<table class='table table-condensed table-striped text-center'>
		<tr class='text-center'>	
            <td colspan='2' class='list_description'><a href='$link11?id=$id_ped' target='_blank'>Confirmação de Serviço</a></td>
            <td class='list_description' align='right'><a href='$link10?id=$id_ped' target='_blank'>Ateste (documentação)</a></td>		
            <td colspan='2' class='list_description' ><a href='$link15?id=$id_ped' target='_blank'>Minuta acima de R$ 176 mil</a></td>            
            
		</tr>
		<tr class='list_menu'><td style='vertical-align: middle' class='list_description text-center' colspan='7' height='60px;'><strong>1 Representante</strong></td></tr>
			<tr class='text-center'>
				<!--<td class='list_description'><strong>01 Representante</strong></td>-->
				<td class='list_description'><a href='$link1?id=$id_ped' target='_blank'>Pedido <br> Integral</a></td>
				<td class='list_description'><a href='$link3?id=$id_ped'  target='_blank'>Pedido <br>  Parcelado</a></td>
				<!--<td class='list_description' style='vertical-align: middle' ><a href='$link5?id=$id_ped' target='_blank'>Recibo</a></td>-->
                <td class='list_description'><a href='$link12?id=$id_ped' target='_blank'>Instruções para <br> emissão de Nota Fiscal</a></td>
				<td class='list_description'><a href='$link7?id=$id_ped'  target='_blank'>Declaração <br> Simples</a></td>
				<td class='list_description'><a href='$link16?id=$id_ped'  target='_blank'>Declaração de Associação sem fins lucrativos</a></td>
								
			</tr>
			<tr class='list_menu'><td style='vertical-align: middle' class='list_description text-center' colspan='7'  height='60px;'><strong>2 Representantes</strong></td></tr>
			<tr>
				<!--<td class='list_description text-center'><strong>02 Representantes</strong></td>-->
				<td class='list_description' width='15%'><a href='$link2?id=$id_ped' target='_blank'>Pedido <br> Integral</a></td>
				<td class='list_description' width='15%'><a href='$link4?id=$id_ped'  target='_blank'>Pedido <br> Parcelado</a></td>
				<!--<td class='list_description' width='15%' style='vertical-align: middle'  ><a href='$link6?id=$id_ped' target='_blank'>Recibo</a></td>-->
                <td class='list_description'><a href='$link12?id=$id_ped' target='_blank'>Instruções para <br> emissão de Nota Fiscal</a></td>
				<td class='list_description' width='15%'><a href='$link7?id=$id_ped'  target='_blank'>Declaração <br> Simples</a></td>
				<td class='list_description' width='20%'><a href='$link16?id=$id_ped'  target='_blank'>Declaração de Associação sem fins lucrativos</a></td>
				<!--<td class='list_description'><a href='$link10?id=$id_ped' target='_blank'>Ateste (documentação)</a></td>
				<td class='list_description'><a href='$link11?id=$id_ped' target='_blank'>Confirmação de Serviço</a></td>-->
			
			</tr>
			<!--<tr><td class='list_description text-center' colspan='7'><strong>02 Representantes</strong></td></tr>
			<tr>
				<td class='list_description'><strong>Emissão de Nota Fiscal</strong></td>
				<td class='list_description'><a href='$link12?id=$id_ped' target='_blank'>Integral</a></td>
				<td class='list_description'><a href='$link13?id=$id_ped'  target='_blank'>Parcelado</a></td>
			</tr>-->
			<tr><td style='vertical-align: middle' class='list_description text-center' colspan='7' height='50px;' ><strong>Email</strong></td></tr>
			<tr>
				<!--<td class='list_description'><strong>Email</strong></td>-->
			
				<td style='vertical-align: middle' class='list_description'><a href='$link14?id=$id_ped&idUsuario=".$_SESSION['idUsuario']."&modelo=empresas' target='_blank'>Empresas</a></td>
				<td style='vertical-align: middle' class='list_description'><a href='$link14?id=$id_ped&idUsuario=".$_SESSION['idUsuario']."&modelo=cooperativas' target='_blank'>Cooperativas</a></td>
				<td style='vertical-align: middle' class='list_description' colspan='2'><a href='$link14?id=$id_ped&idUsuario=".$_SESSION['idUsuario']."&modelo=associacoes' target='_blank'>Associações e institutos</a></td>
				<td class='list_description'><a href='$link14?id=$id_ped&idUsuario=".$_SESSION['idUsuario']."' target='_blank'>Empresas com Minuta de Contrato</a></td>
				
				
				
				
				<!--<td class='list_description'><a href='$ link15?id=$ id_ped' target='_blank'>Anexo e Nota de Empenho</a></td>
				    excluir o modelo \"Anexo e Nota de Empenho\". Não usamos mais.
				    -->
			</tr>	
		</table>
		</div>
		
		
		<div class='form-group'>
		  <div class='col-md-offset-2 col-md-8'><hr/></div>
		</div>";	
	}
	else
	{
		$mensagem = "Erro ao atualizar! Tente novamente.";
	}	
}

$ano=date('Y');
$id_ped = $_GET['id_ped'];	
$linha_tabelas = siscontrat($id_ped);
$pedido = recuperaDados("igsis_pedido_contratacao",$id_ped,"idPedidoContratacao");
$pj = siscontratDocs($linha_tabelas['IdProponente'],2);
$parcelamento = retornaParcelaPagamento($id_ped);

include 'includes/menu.php';
?>

<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="form-group"><h2>CONTRATAÇÃO DE PESSOA JURÍDICA</h2>
			<h4><?php if(isset($mensagem)){ echo $mensagem; } ?></h4>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">

				<!-- Operador de Pagamentos -->
				<form class="form-horizontal" role="form" action="?perfil=pagamento&p=frm_cadastra_pagamento_pj&id_ped=<?php echo $id_ped; ?>" method="post">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-5"><strong>Responsável no Setor de Pagamentos:</strong><br/>
						<select class="form-control" name="pagamentos" id="">
							<option value='655'></option>
							<?php
							$ped = recuperaDados("igsis_pedido_contratacao",$id_ped,"idPedidoContratacao");
                            $sql_operador = "SELECT * FROM ig_usuario WHERE idUsuario IN (270, 274, 275, 393, 424, 445, 655, 993, 1010, 1135, 1170, 1256, 1257, 1295, 872, 1296, 1297, 1126, 147, 1081, 986, 895, 1389, 1125, 1391) ORDER BY nomeCompleto";
							$query_operador = mysqli_query($con,$sql_operador);
							while($user = mysqli_fetch_array($query_operador))
							{
								if($user['idUsuario'] == $ped['idPagamentos'])
								{
									echo "<option value='".$user['idUsuario']."' selected>".$user['nomeCompleto']."</option>";
								}
								else
								{
									echo "<option value='".$user['idUsuario']."'>".$user['nomeCompleto']."</option>";
								}
							}
							?>
						</select>
					</div>
					<div class="col-md-3"><br/>
						<input type="hidden" name="idPagamentos" value="<?php echo $id_ped; ?>" />
						<input type="submit" class="btn btn-theme  btn-block" value="Atualizar responsável">
					</div>
				</div>
				</form>

				<form class="form-horizontal" role="form" action="?perfil=pagamento&p=frm_cadastra_pagamento_pj&id_ped=<?php echo $id_ped; ?>" method="post">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-5"><strong>Data de Entrega do Kit de Pagamentos:</strong><br/>
						<input type='text' name="dataKitPagamento" id="datepicker01" class='form-control' value="<?php echo exibirDataBr($pedido['dataKitPagamento']) ?>">
					</div>
					<div class="col-md-3"><br/>
						<input type="hidden" name="kitPagamento" value="<?php echo $id_ped; ?>" />
						<input type="submit" class="btn btn-theme  btn-block" value="Atualizar">
					</div>
				</div>
				</form>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><hr/></div>
				</div>

				<!-- Fim -->

				<div class="col-md-offset-2 col-md-6"><p><strong>Código do pedido de contratação:</strong><br/><?php echo $ano."-".$id_ped; ?></p></div>
				<div class="col-md-6"><p><strong>Número do Processo:</strong><br/><?php echo $linha_tabelas['NumeroProcesso'];?></p></div>
				
			
				<div class="col-md-offset-2 col-md-8"><p><strong>Setor:</strong> <?php echo $linha_tabelas['Setor'];?></p></div>

				<div class="col-md-offset-2 col-md-8"><p><strong>Proponente:</strong><br/><?php echo $pj['Nome'];?></p></div>
					
				<div class="col-md-offset-2 col-md-8"><p><strong>Objeto:</strong><br/><?php echo $linha_tabelas['Objeto'];?></p></div>

				<div class="col-md-offset-2 col-md-8"><p><strong>Local:</strong><br/><?php echo $linha_tabelas['Local'];?></p></div>
				
				<div class="col-md-offset-2 col-md-8"><p><strong>Data/Período:</strong><br/><?php echo $linha_tabelas['Periodo'];?></p></div>
				
				<div class="col-md-offset-2 col-md-6"><p><strong>Valor:</strong> R$ <?php echo dinheiroParaBr($linha_tabelas["ValorGlobal"]);?><br/></div>
				<div class="col-md-6"><strong>Duração:</strong> <?php echo $linha_tabelas['Duracao'];?>utos</p></div>
				
				<div class="col-md-offset-2 col-md-6"><p><strong>Fiscal:</strong><br/><?php echo $linha_tabelas['Fiscal'];?></p></div>
				<div class="col-md-6"><p><strong>Suplente:</strong><br/><?php echo $linha_tabelas['Suplente'];?></p></div>
				
				<div class="col-md-offset-2 col-md-8"><p><strong>Observação:</strong> <?php echo $linha_tabelas['Observacao'];?></p></div>
			</div>                
        </div>
    </div>

<?php 
	if($linha_tabelas['parcelas'] > 1)
	{ 
?> 		  
		<div class="container">
			<div class="col-md-offset-1 col-md-10">
				<div class="table-responsive list_info">
					<table class="table table-condensed">
						<thead>
							<tr class="list_menu">
								<td>Nº Parcela</td>
								<td>Valor</td>
								<td>Data</td>
								<td>1 Representante</td>
								<td>2 Representantes</td>
							</tr>
						</thead>
						<tbody>
						<?php
							for($i = 1; $i < count($parcelamento); $i++)
							{
								echo '<tr><td class="list_description">'.$i.'</td> ';
								echo '<td class="list_description">R$ '.$parcelamento[$i]['valor'].'</td> ';
								echo '<td class="list_description">'.$parcelamento[$i]['pagamento'].'</td>';
								echo '<td class="list_description">
										<a target="_blank" href='.$link3.'?id='.$id_ped.'&parcela='.$i.'>Pagamento</a><br/>
										<a target="_blank" href='.$link7.'?id='.$id_ped.'&parcela='.$i.'>Declaração</a><br/>
										<a target="_blank" href='.$link10.'?id='.$id_ped.'>Documentação</a><br/>
										<a target="_blank" href='.$link11.'?id='.$id_ped.'>Ateste</a><br/>
										<a target="_blank" href='.$link13.'?id='.$id_ped.'&parcela='.$i.'>Nota Fiscal</a><br />
										<a target="_blank" href='.$link14.'?id='.$id_ped.'&idUsuario='.$_SESSION['idUsuario'].'&parcela='.$i.'&modelo=empresas>Email Kit</a><br />
										<a target="_blank" href='.$link15.'?id='.$id_ped.'&parcela='.$i.'>Email NE</a></td>';

								echo '<td class="list_description">
										<a target="_blank" href='.$link4.'?id='.$id_ped.'&parcela='.$i.'>Pagamento</a><br/>
										<a target="_blank" href='.$link7.'?id='.$id_ped.'&parcela='.$i.'>Declaração</a><br/>
										<a target="_blank" href='.$link10.'?id='.$id_ped.'>Documentação</a><br/>
										<a target="_blank" href='.$link11.'?id='.$id_ped.'>Ateste</a><br/>
										<a target="_blank" href='.$link13.'?id='.$id_ped.'&parcela='.$i.'>Nota Fiscal</a></td>';						}
						?>
						</tbody>
					</table>
					<div class="col-md-offset-1 col-md-10">
						<form class="form-horizontal" role="form" action="?perfil=pagamento&p=frm_edicao_parcelas&id_ped=<?php echo $id_ped; ?>" method="post">
							<div class="col-md-offset-2 col-md-8">
								 <input type="submit" class="btn btn-theme btn-md btn-block" value="Editar parcelas">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
<?php
	}
	else
	{
?>
		<div class="col-md-offset-2 col-md-8">
			<form class="form-horizontal" role="form" action="?perfil=pagamento&p=frm_cadastra_pagamento_pj&id_ped=<?php echo $id_ped; ?>" method="post">
				<div class="col-md-offset-2 col-md-8">
					 <input type="submit" name="atualizar" class="btn btn-theme btn-lg btn-block" value="Confirmar">
				</div>
			</form> 
		</div>
<?php 
	} 
?>   
	<div class="col-md-offset-2 col-md-8"><br/></div>
	
	<div class="container">
		<div class="col-md-offset-1 col-md-10">
			<form class="form-horizontal" role="form" action="?perfil=pagamento&p=frm_concluir_processo_pj&id_ped=<?php echo $id_ped; ?>" method="post">
				<div class="col-md-offset-2 col-md-8">
					 <input type="submit" name="concluir" class="btn btn-theme btn-lg btn-block" value="Concluir Pedido">
				</div>
			</form>
		</div>
	</div>	
</section>     