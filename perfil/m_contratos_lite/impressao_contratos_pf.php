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

$link03 = $http."rlt_proposta_padrao_pf.php"."?id=".$id_ped."&penal";
$link04 = $http."rlt_proposta_formacao.php"."?id=".$id_ped."&penal";
$link05 = $http."rlt_proposta_exposicao_edital_word_pf.php"."?id=".$id_ped."&penal";
$link06 = $http."rlt_proposta_emia.php"."?id=".$id_ped."&penal";
$link07 = $http."rlt_proposta_pesquisador_formacao.php"."?id=".$id_ped;
$link47 = $http."rlt_proposta_reversao_pf.php"."?id=".$id_ped."&penal";

$link18 = $http."rlt_declaracao_iss_pf.php";
$link19 = $http."rlt_declaracao_naoservidor_pf.php";
$link20 = $http."rlt_declaracao_exclusividade_pf.php";
$link21 = $http."rlt_declaracao_convenio500_pf.php";
$link22 = $http."rlt_declaracao_abertura_bb_pf.php";
$link23 = $http."rlt_direitos_conexos.php";

$link24 = $http."rlt_pedido_reserva_fepac_pf.php";
$link25 = $http."rlt_pedido_reserva_vocacional_pf.php";
$link29 = $http."rlt_pedido_reserva_centrosculturais_pf.php";
$link30 = $http."rlt_pedido_reserva_gabinete_pf.php";
$link31 = $http."rlt_pedido_reserva_dph_pf.php";
$link32 = $http."rlt_pedido_reserva_csmb_pf.php";
$link33 = $http."rlt_pedido_reserva_casasdecultura_pf.php";
$link41 = $http."rlt_pedido_reserva_ccsp_pf.php";
$link42 = $http."rlt_pedido_reserva_oficina.php";
$link43 = $http."rlt_pedido_reserva_bma_pf.php";
$link44 = $http."rlt_reserva_pf.php";
$link45 = $http."rlt_pedido_reserva_pia_pf.php";
$link46 = $http."rlt_pedido_reserva_vocacional_sme_pf.php";
$link48 = $http."rlt_pedido_reserva_oficina_csmb_bma.php";
$link49 = $http."rlt_pedido_reserva_hiphop_sme_pf.php";
$link51 = $http."rlt_pedido_reserva_global_pf.php";
$link52 = $http."rlt_pedido_reserva_jornada_pf.php";
$link61 = $http."rlt_pedido_reserva_vai.php";

$link17 = $http."rlt_normas_internas_teatros_pf.php"."?id=".$id_ped;
$link34 = $http."rlt_fac_pf.php"."?id_pf=".$id_pf;
$link35 = $http."rlt_ordemservico_pf_word.php";
$link36 = $http."rlt_ordemservico_gabinete_pf_assinatura_word.php";
$link37 = $http."rlt_parecer_pf.php";
$link38 = $http."rlt_termo_doacaoobradearte_pf.php";
$link39 = $http."rlt_termo_parceria_pf.php";
$link40 = $http."rlt_termo_doacao_pf_assinatura_word.php";
$link60 = $http."rlt_condicionamento.php?id=".$id_ped ."&tipoPessoa=1";

$link50 = $http."rlt_proposta_oficina_convenio_word.php"."?id=".$id_ped."&penal";





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
							<td width='50%'><a href='$link01' target='_blank'><strong>Pedido de Contratação</strong></a></td>
							<td><a href='$link02?id=$id_ped' target='_blank'><strong>Pedido de Contratação - Formação</strong></a></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>PROPOSTA</strong></td></tr>

				
						<tr>
							<td><a href='$link04=20' target='_blank'><strong>Vocacional</strong></a></td>
							<td><a href='$link04=21' target='_blank'><strong>PIÁ</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link05=23' target='_blank'><strong>Editais</strong></a></td>
							<td><a href='$link06=31' target='_blank'><strong>EMIA</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link03=13' target='_blank'><strong>Contratações gerais - Com cachê</strong></a></td>
							<td><a href='$link47=13' target='_blank'><strong>Contratações gerais - Reversão de Bilheteria</strong></a></td>
						</tr>
						
						<tr>
							<td><a href='$link50=13' target='_blank'><strong>Proposta Oficinas / Convênio MINC</strong></a></td>
							<td></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>DECLARAÇÃO</strong></td></tr>

						<tr>
							<td><a href='$link23?id=$id_ped' target='_blank'><strong>Direitos Conexos</strong></a></td>
							<td><a href='$link20?id=$id_ped' target='_blank'><strong>Exclusividade</strong></a></td>
						</tr>
						<tr>
							<td><a href='$link21?id=$id_ped' target='_blank'><strong>Convênio 500</strong></a></td>
							<td><a href='$link60' target='_blank'><strong>Condicionamento</strong></a></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>OUTROS</strong></td></tr>

						<tr>
							<td><a href='$link34' target='_blank'><strong>FACC</strong></a></td>
							<td><a href='$link37?id=$id_ped' target='_blank'><strong>Parecer da Comissão</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link17' target='_blank'><strong>NORMAS INTERNAS - Teatros Municipais</strong></a></td>
						</tr>

						<tr><td class='list_description'><br/></td></tr>

						<tr class='list_menu'><td colspan='2'><strong>PEDIDO DE RESERVA</strong></td></tr>

						<tr>
							<td><a href='$link25?id=$id_ped' target='_blank'><strong>FORMAÇÃO - Vocacional</strong></a></td>
							<td><a href='$link45?id=$id_ped' target='_blank'><strong>FORMAÇÃO - PIÁ</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link46?id=$id_ped' target='_blank'><strong>VOCACIONAL/PIÁ - SME</strong></a></td>
							<td><a href='$link29?id=$id_ped' target='_blank'><strong>Reserva Centros Culturais</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link32?id=$id_ped' target='_blank'><strong>Reserva CSMB</strong></a></td>
							<td><a href='$link33?id=$id_ped' target='_blank'><strong>Reserva Casas de Cultura</strong></a></td>
						</tr>

						<tr>
							<!-- <td><a href='$link24?id=$id_ped' target='_blank'><strong>FEPAC</strong></a></td> -->
							<td><a href='$link41?id=$id_ped' target='_blank'><strong>Reserva CCSP</strong></a></td>
							<td><a href='$link43?id=$id_ped' target='_blank'><strong>Reserva BMA</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link42?id=$id_ped' target='_blank'><strong>Oficina</strong></a></td>
							<td><a href='$link48?id=$id_ped' target='_blank'><strong>Reserva Oficinas CSMB e BMA</strong></a></td>
						</tr>

						<tr>
							<!-- <td><a href='$link44?id=$id_ped' target='_blank'><strong>Reserva Pessoa Física</strong></a></td> -->
							<td><a href='$link30?id=$id_ped' target='_blank'><strong>Reserva Gabinete</strong></a></td>
							<td><a href='$link51?id=$id_ped' target='_blank'><strong>Reserva Global</strong></a></td>
						</tr>

						<tr>
							<td><a href='$link31?id=$id_ped' target='_blank'><strong>Reserva DPH</strong></a></td>
							<td><a href='$link61?id=$id_ped' target='_blank'><strong>Programa VAI</strong></a></td>
                        </tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>";
?>