<?php
include 'includes/menu.php';
$conexao = bancoMysqli();
$_SESSION['idPedido'] = $_GET['id_ped'];
$id_ped = $_GET['id_ped'];
$id = $id_ped;

$server = "http://".$_SERVER['SERVER_NAME']."/igsis"; //mudar para pasta do igsis
$http = $server."/pdf/";

$link0 = $http."rlt_pedido_contratacao_pj.php";
$link1 = $http."rlt_proposta_padrao_pj.php";
$link2 = $http."rlt_proposta_artistico_pj.php";
$link3 = $http."rlt_proposta_comunicado_001-15_pj.php";
$link4 = $http."rlt_proposta_eventoexterno_pj.php";
$link5 = $http."rlt_fac_pj.php";
$link6 = $http."rlt_evento_pj.php";
$link7 = $http."rlt_direitos_conexos.php";
$link8 = $http."rlt_parecer_pj.php";
$link9 = $http."rlt_pedido_reserva_nova_pj.php";
$link11 = $http."rlt_pedido_reserva_fepac_pj.php";
$link12 = $http."rlt_pedido_reserva_atividadecultural_pj.php";
$link13 = $http."rlt_pedido_reserva_atividadecultural_cooperativa_pj.php";
$link14 = $http."rlt_pedido_reserva_vocacional_pj.php";
$link15 = $http."rlt_proposta_reversaolonga_pj.php"."?id=".$id_ped."&penal=18";
$link16 = $http."rlt_proposta_reversaocurta_pj.php"."?id=".$id_ped."&penal=16";
$link17 = $http."rlt_declaracao_iss_1rep_pj.php";
$link18 = $http."rlt_declaracao_iss_2rep_pj.php";
$link19 = $http."rlt_declaracao_exclusividade_1rep_pj.php";
$link20 = $http."rlt_declaracao_exclusividade_2rep_pj.php";
$link21 = $http."rlt_proposta_doacao_pj.php"."?id=".$id_ped."&penal=6";
$link22 = $http."rlt_ordemservico_pj.php";
$link23 = $http."rlt_ordemservico_pj_word.php";
$link24 = $http."rlt_proposta_cinema_pj.php"."?id=".$id_ped."&penal=3";
$link25 = $http."rlt_proposta_exposicao_pj.php"."?id=".$id_ped."&penal=10";
$link26 = $http."rlt_termo_doacao_pj.php";
$link27 = $http."rlt_termo_parceria_pj.php";
$link28 = $http."rlt_pedido_reserva_existente_pj.php";
$link29 = $http."rlt_pedido_reserva_transferencia_virada_pj.php";
$link30 = $http."rlt_pedido_reserva_transferencia_virada_centro_pj.php";
$link32 = $http."rlt_normas_internas_teatros_pj.php"."?id=".$id_ped;
$link33 = $http."rlt_proposta_exposicao_edital_pj.php"."?id=".$id_ped."&penal=22";
$link34 = $http."rlt_proposta_mediacao_edital_pj.php"."?id=".$id_ped."&penal=24";
$link35 = $http."rlt_proposta_contadores_edital_pj.php"."?id=".$id_ped."&penal=26";
$link36 = $http."rlt_proposta_galadeballet_edital_pj.php"."?id=".$id_ped."&penal=28";
$link37 = $http."rlt_ordemservico_pj_assinatura.php";
$link38 = $http."rlt_termo_doacao_pj_assinatura.php";
$link39 = $http."rlt_pedido_reserva_centrosculturais_pj.php";
$link40 = $http."rlt_declaracao_convenio500_1rep_pj.php";
$link41 = $http."rlt_declaracao_convenio500_2rep_pj.php";
$link42 = $http."rlt_declaracao_abertura_bb_pj.php";
$link43 = $http."rlt_pedido_reserva_gabinete_pj.php";
$link44 = $http."rlt_ordemservico_gabinete_pj.php";
$link45 = $http."rlt_ordemservico_gabinete_pj_assinatura.php";
$link46 = $http."rlt_pedido_reserva_dph_pj.php";
$link47 = $http."rlt_pedido_reserva_csmb_pj.php";
$link48 = $http."rlt_pedido_reserva_casasdecultura_pj.php";
$link49 = $http."rlt_pedido_reserva_ccsp_pj.php";
$link50 = $http."rlt_proposta_virada.php"."?id=".$id_ped."&penal=30";



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
							<td width='50%'><a href='$link0?id=$id_ped' target='_blank'><strong>Pedido de Contratação</strong></a></td>
							<td></td>
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>PROPOSTA</strong></td></tr>
											
						<tr>
							<td><a href='$link24' target='_blank'><strong>Cinema</strong></a></td>
							<td><a href='$link21' target='_blank'><strong>Doação de Serviços</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link15' target='_blank'><strong>Reversão Longa Temporada</strong></a></td>
							<td><a href='$link16' target='_blank'><strong>Reversão Curta Temporada</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link25' target='_blank'><strong>Exposição | Outros</strong></a></td>
							<td><a href='$link33?id=$id_ped' target='_blank'><strong>Edital - Exposição</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link34?id=$id_ped' target='_blank'><strong>Edital - Mediação</strong></a></td>
							<td><a href='$link35?id=$id_ped' target='_blank'><strong>Edital - Contação de Histórias </strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link36?id=$id_ped' target='_blank'><strong>Edital - Gala de Balé</strong></a></td>
							<td><a href='$link32' target='_blank'><strong>NORMAS INTERNAS - Teatros Municipais</strong></a></td>
						</tr>
						<tr>
							<td><a href='$link50' target='_blank'><strong>Virada 2017</strong></a></td>
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>DECLARAÇÃO</strong></td></tr>
						
						<tr>
							<td class='list_menu'>01 Representante</td>
							<td class='list_menu'>02 Representantes</td>
						</tr>
						<tr>
							<td><a href='$link17?id=$id_ped' target='_blank'><strong>ISS</strong></a></td>
							<td><a href='$link18?id=$id_ped' target='_blank'><strong>ISS</strong></a></td>
						</tr>
						<tr>
							<td><a href='$link19?id=$id_ped' target='_blank'><strong>Exclusividade</strong></a></td>
							<td><a href='$link20?id=$id_ped' target='_blank'><strong>Exclusividade</strong></a></td>
						</tr>
						<tr>
							<td><a href='$link40?id=$id_ped' target='_blank'><strong>Convênio 500</strong></a></td>
							<td><a href='$link41?id=$id_ped' target='_blank'><strong>Convênio 500</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link42?id=$id_ped' target='_blank'><strong>Abertura de Conta - Banco do Brasil</strong></a></td>
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>OUTROS</strong></td></tr>
						
						<tr>
							<td><a href='$link5?id=$id_ped' target='_blank'><strong>FACC</strong></a></td>
							<td><a href='$link7?id=$id_ped' target='_blank'><strong>Uso de Direitos Conexos</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link37?id=$id_ped' target='_blank'><strong>Ordem de Serviço - Assinatura</strong></a></td>
							<td><a href='$link45?id=$id_ped' target='_blank'><strong>Ordem de serviço - Gabinete Assinatura</strong></a></td>

						</tr>

						<tr>
							<td><a href='$link38?id=$id_ped' target='_blank'><strong>Termo de Doação - Assinatura</strong></a></td>
							<td><a href='$link8?id=$id_ped' target='_blank'><strong>Parecer da Comissão</strong></a></td>
						</tr>	

						<tr>
							<td><a href='$link27?id=$id_ped' target='_blank'><strong>Termo de Parceria</strong></a></td>
						</tr>						
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>PEDIDO DE RESERVA</strong></td></tr>
						
						<tr>
							<td><a href='$link29?id=$id_ped' target='_blank'><strong>Reserva de Verba com Transferência - VIRADA</strong></a></td>		
							<td><a href='$link39?id=$id_ped' target='_blank'><strong>Reserva Centros Culturais</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link43?id=$id_ped' target='_blank'><strong>Reserva Gabinete</strong></a></td>
							<td><a href='$link46?id=$id_ped' target='_blank'><strong>Reserva DPH</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link47?id=$id_ped' target='_blank'><strong>Reserva CSMB</strong></a></td>
							<td><a href='$link48?id=$id_ped' target='_blank'><strong>Reserva Casas de Cultura </strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link11?id=$id_ped' target='_blank'><strong>FEPAC</strong></a></td>
							<td><a href='$link49?id=$id_ped' target='_blank'><strong>Reserva CCSP</strong></a></td>
						</tr>
					</tbody>
				</table>
			</div>	
		</div>
	</div>
</section>";
?>