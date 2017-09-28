<?php
include 'includes/menu.php';
$conexao = bancoMysqli();
$_SESSION['idPedido'] = $_GET['id_ped'];
$id_ped = $_GET['id_ped'];
$id = $id_ped;

$pedido = recuperaDados("igsis_pedido_contratacao",$id_ped,"idPedidoContratacao");
$id_pf = $pedido['idPessoa'];

$server = "http://".$_SERVER['SERVER_NAME']."/igsis/"; //mudar para pasta do igsis
$http = $server."/pdf/";

$link01 = $http."rlt_pedido_contratacao_pf.php"."?id=".$id_ped;
$link02 = $http."rlt_pedido_contratacao_pf_formacao.php";

$link03 = $http."rlt_proposta_reversaolonga_pf.php"."?id=".$id_ped."&penal=18"; 
$link04 = $http."rlt_proposta_reversaocurta_pf.php"."?id=".$id_ped."&penal=16";
$link05 = $http."rlt_proposta_doacao_pf.php"."?id=".$id_ped."&penal=6";
$link06 = $http."rlt_proposta_cinema_pf.php"."?id=".$id_ped."&penal=3";
$link07 = $http."rlt_proposta_comissaojulgadora_pf.php"."?id=".$id_ped."&penal=5";
$link08 = $http."rlt_proposta_exposicao_pf.php"."?id=".$id_ped."&penal=10";
$link09 = $http."rlt_proposta_oficinassemedital_pf.php"."?id=".$id_ped."&penal=12";
$link10 = $http."rlt_proposta_palestra_pf.php"."?id=".$id_ped."&penal=15";
$link11 = $http."rlt_proposta_formacao.php"."?id=".$id_ped."&penal=20";
$link12 = $http."rlt_proposta_formacao.php"."?id=".$id_ped."&penal=21";
$link13 = $http."rlt_proposta_exposicao_edital_pf.php"."?id=".$id_ped."&penal=23";
$link14 = $http."rlt_proposta_mediacao_edital_pf.php"."?id=".$id_ped."&penal=25";
$link15 = $http."rlt_proposta_contadores_edital_pf.php"."?id=".$id_ped."&penal=26";
$link16 = $http."rlt_proposta_galadeballet_edital_pf.php"."?id=".$id_ped."&penal=29";
$link17 = $http."rlt_normas_internas_teatros_pf.php"."?id=".$id_ped;
$link44 = $http."rlt_proposta_emia.php"."?id=".$id_ped."&penal=31";

$link18 = $http."rlt_declaracao_iss_pf.php";
$link19 = $http."rlt_declaracao_naoservidor_pf.php";
$link20 = $http."rlt_declaracao_exclusividade_pf.php";
$link21 = $http."rlt_declaracao_convenio500_pf.php";
$link22 = $http."rlt_declaracao_abertura_bb_pf.php";
$link23 = $http."rlt_direitos_conexos.php";

$link24 = $http."rlt_pedido_reserva_fepac_pf.php";
$link25 = $http."rlt_pedido_reserva_vocacional_pf.php";
$link27 = $http."rlt_pedido_reserva_transferencia_pf.php";
$link28 = $http."rlt_pedido_reserva_transferencia_virada_pf.php";
$link29 = $http."rlt_pedido_reserva_centrosculturais_pf.php";
$link30 = $http."rlt_pedido_reserva_gabinete_pf.php";
$link31 = $http."rlt_pedido_reserva_dph_pf.php";
$link32 = $http."rlt_pedido_reserva_csmb_pf.php";
$link33 = $http."rlt_pedido_reserva_casasdecultura_pf.php";
$link41 = $http."rlt_pedido_reserva_ccsp_pf.php";
$link42 = $http."rlt_pedido_reserva_oficina.php";
$link43 = $http."rlt_pedido_reserva_virada_pf.php";


$link34 = $http."rlt_fac_pf.php"."?id_pf=".$id_pf;
$link35 = $http."rlt_ordemservico_pf_word.php";
$link36 = $http."rlt_ordemservico_gabinete_pf_assinatura_word.php";
$link37 = $http."rlt_parecer_pf.php";
$link38 = $http."rlt_termo_doacaoobradearte_pf.php";
$link39 = $http."rlt_termo_parceria_pf.php";
$link40 = $http."rlt_termo_doacao_pf_assinatura_word.php";




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
							<td width='50%'><a href='$link01?id=$id_ped' target='_blank'><strong>Pedido de Contratação</strong></a></td>
							<td><a href='$link02?id=$id_ped' target='_blank'><strong>Pedido de Contratação - Formação</strong></a></td>
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>PROPOSTA</strong></td></tr>
											
						<tr>
							<td><a href='$link06' target='_blank'><strong>Cinema</strong></a></td>
							<td><a href='$link07' target='_blank'><strong>Comissão Julgadora</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link05' target='_blank'><strong>Doação de Serviços</strong></a></td>
							<td><a href='$link08' target='_blank'><strong>Exposição | Outros</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link09' target='_blank'><strong>Oficinas sem Edital</strong></a></td>
							<td><a href='$link10' target='_blank'><strong>Palestra | Debate | Workshop</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link04' target='_blank'><strong>Reversão Curta Temporada</strong></a></td>
							<td><a href='$link03' target='_blank'><strong>Reversão Longa Temporada</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link11' target='_blank'><strong>Vocacional</strong></a></td>
							<td><a href='$link12' target='_blank'><strong>PIÁ</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link13' target='_blank'><strong>Edital - Exposição</strong></a></td>
							<td><a href='$link14' target='_blank'><strong>Edital - Mediação</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link15' target='_blank'><strong>Edital - Contação de Histórias</strong></a></td>
							<td><a href='$link16' target='_blank'><strong>Edital - Gala de Balé</strong></a></td>
						</tr>	
						
						<tr>
							<td><a href='$link17' target='_blank'><strong>NORMAS INTERNAS - Teatros Municipais</strong></a></td>
							<td><a href='$link44' target='_blank'><strong>EMIA</strong></a></td>
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>DECLARAÇÃO</strong></td></tr>
						
						<tr>
							<td><a href='$link19?id=$id_ped' target='_blank'><strong>Não Servidor</strong></a></td>
							<td><a href='$link18?id=$id_ped' target='_blank'><strong>ISS</strong></a></td>
						</tr>
						<tr>
							<td><a href='$link23?id=$id_ped' target='_blank'><strong>Direitos Conexos</strong></a></td>
							<td><a href='$link20?id=$id_ped' target='_blank'><strong>Exclusividade</strong></a></td>
						</tr>
						<tr>
							<td><a href='$link21?id=$id_ped' target='_blank'><strong>Convênio 500</strong></a></td>
							<td><a href='$link22?id=$id_ped' target='_blank'><strong>Abertura de Conta - Banco do Brasil </strong></a></td>
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>OUTROS</strong></td></tr>
						
						<tr>
							<td><a href='$link34' target='_blank'><strong>FACC</strong></a></td>
							<td><a href='$link37?id=$id_ped' target='_blank'><strong>Parecer da Comissão</strong></a></td>
						</tr>
						<tr>
							<td><a href='$link35?id=$id_ped' target='_blank'><strong>Ordem de serviço - CCSP Assinatura</strong></a></td>
							<td><a href='$link36?id=$id_ped' target='_blank'><strong>Ordem de serviço - Gabinete Assinatura</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link40?id=$id_ped' target='_blank'><strong>Termo Doação de Serviço - Assinatura</strong></a></td>
							<td><a href='$link39?id=$id_ped' target='_blank'><strong>Termo de Parceria</strong></a></td>

						</tr>
						
						<tr>
							<td><a href='$link38?id=$id_ped' target='_blank'><strong>Termo de Doação de Obra de Arte</strong></a></td>
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>
						
						<tr class='list_menu'><td colspan='2'><strong>PEDIDO DE RESERVA</strong></td></tr>
												
						<tr>
							<td><a href='$link27?id=$id_ped' target='_blank'><strong>Verba com Transferência</strong></a></td>
							<td><a href='$link28?id=$id_ped' target='_blank'><strong>Verba com Transferência - VIRADA</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link25?id=$id_ped' target='_blank'><strong>Formação</strong></a></td>
							<td><a href='$link30?id=$id_ped' target='_blank'><strong>Reserva Gabinete</strong></a></td>
						</tr>
							<td><a href='$link29?id=$id_ped' target='_blank'><strong>Reserva Centros Culturais</strong></a></td>
							<td><a href='$link31?id=$id_ped' target='_blank'><strong>Reserva DPH</strong></a></td>	
						</tr>
						
						</tr>
							<td><a href='$link32?id=$id_ped' target='_blank'><strong>Reserva CSMB</strong></a></td>
							<td><a href='$link33?id=$id_ped' target='_blank'><strong>Reserva Casas de Cultura</strong></a></td>	
						</tr>
						
						<tr>
							<td><a href='$link24?id=$id_ped' target='_blank'><strong>FEPAC</strong></a></td>
							<td><a href='$link41?id=$id_ped' target='_blank'><strong>Reserva CCSP</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link42?id=$id_ped' target='_blank'><strong>Oficina</strong></a></td>
							<td><a href='$link43?id=$id_ped' target='_blank'><strong>Virada</strong></a></td>
						</tr>
						
					</tbody>
				</table>
			</div>	
		</div>
	</div>
</section>";
?>