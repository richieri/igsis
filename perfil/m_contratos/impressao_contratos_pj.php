<?php
include 'includes/menu.php';
$conexao = bancoMysqli();
$_SESSION['idPedido'] = $_GET['id_ped'];
$id_ped = $_GET['id_ped'];
$id = $id_ped;

$server = "http://".$_SERVER['SERVER_NAME']."/igsis"; //mudar para pasta do igsis
$http = $server."/pdf/";

$link0 = $http."rlt_pedido_contratacao_pj.php";
$link02 = $http."rlt_proposta_padrao_pj.php"."?id=".$id_ped."&penal";
$link03 = $http."rlt_proposta_exposicao_edital_word_pj.php"."?id=".$id_ped."&penal";
$link04 = $http."rlt_proposta_virada.php"."?id=".$id_ped."&penal";
$link56 = $http."rlt_proposta_reversao_pj.php"."?id=".$id_ped."&penal";


$link5 = $http."rlt_fac_pj.php";
$link6 = $http."rlt_evento_pj.php";
$link7 = $http."rlt_direitos_conexos.php";
$link8 = $http."rlt_parecer_pj.php";
$link9 = $http."rlt_pedido_reserva_nova_pj.php";
$link11 = $http."rlt_pedido_reserva_fepac_pj.php";
$link12 = $http."rlt_pedido_reserva_atividadecultural_pj.php";
$link13 = $http."rlt_pedido_reserva_atividadecultural_cooperativa_pj.php";
$link14 = $http."rlt_pedido_reserva_vocacional_pj.php";
$link17 = $http."rlt_declaracao_iss_pj.php?id=".$id_ped;
$link19 = $http."rlt_declaracao_exclusividade_grupo_pj.php?id=".$id_ped;
$link54 = $http."rlt_declaracao_exclusividade_1pessoa_pj.php?id=".$id_ped;
$link22 = $http."rlt_ordemservico_pj.php";
$link23 = $http."rlt_ordemservico_pj_word.php";
$link26 = $http."rlt_termo_doacao_pj.php";
$link27 = $http."rlt_termo_parceria_pj.php";
$link28 = $http."rlt_pedido_reserva_existente_pj.php";
$link32 = $http."rlt_normas_internas_teatros_pj.php"."?id=".$id_ped;
$link37 = $http."rlt_ordemservico_pj_assinatura_word.php";
$link38 = $http."rlt_termo_doacao_pj_assinatura_word.php";
$link39 = $http."rlt_pedido_reserva_centrosculturais_pj.php";
$link40 = $http."rlt_declaracao_convenio500_pj.php?id=".$id_ped;
$link42 = $http."rlt_declaracao_abertura_bb_pj.php";
$link43 = $http."rlt_pedido_reserva_gabinete_pj.php";
$link44 = $http."rlt_ordemservico_gabinete_pj.php";
$link45 = $http."rlt_ordemservico_gabinete_pj_assinatura_word.php";
$link46 = $http."rlt_pedido_reserva_dph_pj.php";
$link47 = $http."rlt_pedido_reserva_csmb_pj.php";
$link48 = $http."rlt_pedido_reserva_casasdecultura_pj.php";
$link49 = $http."rlt_pedido_reserva_ccsp_pj.php";
$link50 = $http."rlt_proposta_virada.php"."?id=".$id_ped."&penal=30";
$link52 = $http."rlt_minuta_acima80k.php";
$link53 = $http."rlt_pedido_reserva_bma_pj.php";
$link55 = $http."rlt_pedido_reserva_pia_pj.php";
$link57 = $http."rlt_pedido_reserva_oficina_pj.php";
$link58 = $http."rlt_pedido_reserva_oficina_csmb_bma_pj.php";
$link59 = $http."rlt_pedido_reserva_hiphop_sme_pj.php";
$link60 = $http."rlt_pedido_reserva_crd.php";
$link61 = $http."rlt_condicionamento.php?id=$id_ped&tipoPessoa=2";
$link62 = $http."rlt_pedido_reserva_memoriacirco_pj.php";
$link63 = $http."rlt_pedido_reserva_global_pj.php";
$link64 = $http."rlt_pedido_reserva_jornada_pj.php";


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
							<td><a href='$link03=22' target='_blank'><strong>Editais</strong></a></td>
							<td><a href='$link02=13' target='_blank'><strong>Contratações Gerais - Com cachê</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link56=13' target='_blank'><strong>Contratações Gerais - Reversão de Bilheteria</strong></a></td>
							
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>DECLARAÇÃO</strong></td></tr>

						<tr>
							<td><a href='$link19' target='_blank'><strong>Exclusividade (Grupo)</strong></a></td>
							<td><a href='$link54?id=$id_ped' target='_blank'><strong>Exclusividade (1 pessoa)</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link40' target='_blank'><strong>Convênio 500</strong></a></td>
							<td><a href='$link61' target='_blank'><strong>Condicionamento</strong></a></td>
							
						</tr>
						
						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>OUTROS</strong></td></tr>

						<tr>
							<td><a href='$link5?id=$id_ped' target='_blank'><strong>FACC</strong></a></td>
							<td><a href='$link8?id=$id_ped' target='_blank'><strong>Parecer da Comissão</strong></a></td>
						</tr>

						<tr>
							<!--<td><a href='$link52?id=$id_ped' target='_blank'><strong>Minuta Acima 80 mil</strong></a></td> 
							Tirar do módulo de contratos e colocar apenas no de pagamentos. Alterar o nome para \" Minuta acima de R$ 176 mil -->
							<td colspan='2'><a href='$link32' target='_blank'><strong>NORMAS INTERNAS - Teatros Municipais</strong></a></td>
						</tr>


						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>PEDIDO DE RESERVA</strong></td></tr>

						<tr>
							<td><a href='$link14?id=$id_ped' target='_blank'><strong>FORMAÇÃO - Vocacional</strong></a></td>
							<td><a href='$link55?id=$id_ped' target='_blank'><strong>FORMAÇÃO - Piá</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link39?id=$id_ped' target='_blank'><strong>Reserva Centros Culturais</strong></a></td>
							<td><a href='$link43?id=$id_ped' target='_blank'><strong>Reserva Gabinete</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link46?id=$id_ped' target='_blank'><strong>Reserva DPH</strong></a></td>
							<td><a href='$link47?id=$id_ped' target='_blank'><strong>Reserva CSMB</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link48?id=$id_ped' target='_blank'><strong>Reserva Casas de Cultura </strong></a></td>
							<td><a href='$link11?id=$id_ped' target='_blank'><strong>FEPAC</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link49?id=$id_ped' target='_blank'><strong>Reserva CCSP</strong></a></td>
							<td><a href='$link53?id=$id_ped' target='_blank'><strong>Reserva BMA</strong></a></td>
						</tr>
						
						<tr>
						    <td><a href='$link57?id=$id_ped' target='_blank'><strong>Reserva Oficina</strong></a></td>
						    <td><a href='$link58?id=$id_ped' target='_blank'><strong>Reserva Oficinas CSMB e BMA</strong></a></td>
                        </tr>
                        <tr>
                           <td><a href='$link62?id=$id_ped' target='_blank'><strong>Centro de Memória do Circo</strong></a></td>
                            <td><a href='$link60?id=$id_ped' target='_blank'><strong>Reserva CRD</strong></a></td>
                        </tr>
                        <tr>                            
                            <td><a href='$link63?id=$id_ped' target='_blank'><strong>Reserva Global</strong></a></td>
                        </tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>";
?>