<?php
include 'includes/menu.php';
$conexao = bancoMysqli();
$id_ped = $_GET['id_ped'];
$id = $id_ped;

$pedido = recuperaDados("igsis_pedido_contratacao",$id_ped,"idPedidoContratacao");
$id_pf = $pedido['idPessoa'];

$server = "http://".$_SERVER['SERVER_NAME']."/igsis/"; //mudar para pasta do igsis
$http = $server."/pdf/";

$link02 = $http."rlt_pedido_contratacao_pf_formacao.php";
$link04 = $http."rlt_proposta_formacao.php"."?id=".$id_ped."&penal";
//$link19 = $http."rlt_declaracao_naoservidor_pf.php";
//$link20 = $http."rlt_declaracao_exclusividade_pf.php";
//$link21 = $http."rlt_declaracao_convenio500_pf.php";
//$link22 = $http."rlt_declaracao_abertura_bb_pf.php";
//$link23 = $http."rlt_direitos_conexos.php";
//$link24 = $http."rlt_pedido_reserva_fepac_pf.php";
$link25 = $http."rlt_pedido_reserva_vocacional_pf.php";
$link34 = $http."rlt_fac_pf.php"."?id_pf=".$id_pf;
//$link35 = $http."rlt_ordemservico_pf_word.php";
//$link36 = $http."rlt_ordemservico_gabinete_pf_assinatura_word.php";
//$link37 = $http."rlt_parecer_pf.php";
//$link38 = $http."rlt_termo_doacaoobradearte_pf.php";
//$link39 = $http."rlt_termo_parceria_pf.php";
//$link40 = $http."rlt_termo_doacao_pf_assinatura_word.php";
//$link41 = $http."rlt_pedido_reserva_ccsp_pf.php";
//$link42 = $http."rlt_pedido_reserva_oficina.php";
//$link43 = $http."rlt_pedido_reserva_bma_pf.php";
//$link44 = $http."rlt_reserva_pf.php";
$link45 = $http."rlt_pedido_reserva_pia_pf.php";
$link46 = $http."rlt_pedido_reserva_vocacional_sme_pf.php";
$link49 = "?perfil=formacao&p=frm_cadastra_notaempenho_pf&id_ped=$id_ped";
$link50 = $http."rlt_despacho_formacao.php?id=".$id_ped;

$last_id = mysqli_insert_id($conexao);

echo "
<section id='list_items' class='home-section bg-white'><h6>Qual modelo de documento deseja imprimir?</h6>
	<div class='container'>
		<div class='col-md-offset-2 col-md-8'>
			<div class='table-responsive list_info'>
				<table class='table table-condensed'>
					<tbody>
						<tr class='list_menu'>
							<td colspan='2'><strong>PEDIDO</strong></td>
						</tr>

						<tr>
							<td colspan='2'><a href='$link02?id=$id_ped' target='_blank'><strong>Pedido de Contratação - Formação</strong></a></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>PROPOSTA</strong></td></tr>

				
						<tr>
							<td><a href='$link04=20' target='_blank'><strong>Vocacional</strong></a></td>
							<td><a href='$link04=21' target='_blank'><strong>PIÁ</strong></a></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>OUTROS</strong></td></tr>

						<tr>
							<td><a href='$link34' target='_blank'><strong>FACC</strong></a></td>
							<td><a href='$link49' target='_blank'><strong>Recibo da Nota de Empenho</strong></a></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>PEDIDO DE RESERVA</strong></td></tr>

						<tr>
							<td><a href='$link25?id=$id_ped' target='_blank'><strong>FORMAÇÃO - Vocacional</strong></a></td>
							<td><a href='$link45?id=$id_ped' target='_blank'><strong>FORMAÇÃO - PIÁ</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link46?id=$id_ped' target='_blank'><strong>VOCACIONAL/PIÁ - SME</strong></a></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>DESPACHO</strong></td></tr>
						
						<tr>
							<td colspan='2'><a href='$link50' target='_blank'><strong>Vocacional / Piá</strong></a></td>
						</tr>
						
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>";