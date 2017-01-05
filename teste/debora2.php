<?php
require "../funcoes/funcoesConecta.php";
require "../funcoes/funcoesGerais.php";
require "../funcoes/funcoesSiscontrat.php";





?>
	
	  	  
	 <!-- inicio_list -->
	<section id="list_items">
		<div class="container">
			 <div class="sub-title">PEDIDO DE CONTRATAÇÃO DE PESSOA FÍSICA</div>
			<div class="table-responsive list_info">
				<table class="table table-condensed"><script type=text/javascript language=JavaScript src=../js/find2.js> </script>
					<thead>
						<tr class="list_menu">
							<td>Codigo do Pedido</td>
							<td>Proponente</td>
							<td>Tipo</td>
							<td>Objeto</td>
							<td>Local</td>
							<td>Instituição</td>
							<td>Periodo</td>
							<td>Valor</td>
							<td>Parcelas</td>
							<td>Verba</td>
                            <td>Processo</td>
						</tr>
					</thead>
					<tbody>
					<tbody>
				<table>
				
<?php

$data=date('Y');
$con = bancoMysqli();
$sql_pf = "SELECT * FROM igsis_pedido_contratacao WHERE estado IS NOT NULL AND (tipoPessoa = 4 OR tipoPessoa = 1)";
$query_pf = mysqli_query($con,$sql_pf);


while($pedido = mysqli_fetch_array($query_pf))
 {
	 
	$pf = recuperaDados("sis_pessoa_fisica",$pf['idPessoa'],"Id_PessoaFisica");
	$ped = siscontrat($pedido['idPedidoContratacao']);
	
	echo '<tr><td class="list_description">'.$pedido['idPedidoContratacao'].'</td> ';
	echo '<td class="list_description">'.$pf['Nome'].'</td>';
	echo '<td class="list_description">Física</td>';
	echo '<td class="list_description">'.$ped['Objeto'].'</td>';
	echo '<td class="list_description">'.$ped['Local'].'</td> ';
	echo '<td class="list_description">'.$ped['Instituicao'].'</td> ';
	echo '<td class="list_description">'.$ped['Periodo'].'</td> ';
	echo '<td class="list_description">'.$ped['ValorGlobal'].'</td> ';
	echo '<td class="list_description">'.$ped['parcelas'].'</td> ';
	echo '<td class="list_description">'.$ped['Verba'].'</td> ';
	echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td> </tr>';
	}

?>
	
					
					</tbody>
				</table>
			</div>
		</div>
	</section>

<!--fim_list-->