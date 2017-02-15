<?php 
	// não precisa chamar a funcao porque o index contrato já chama.
	$con = bancoMysqli();
	include 'includes/menu.php';
?>	  	  
<!-- inicio_list -->
<section id="list_items">
	<div class="container">
		<div class="sub-title">DADOS DE CONTRATAÇÃO</div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
					<td>Id Dados Contratação</td>
					<td>Proponente</td>
					<td>Programa</td>
					<td>Cargo</td>
					</tr>
				</thead>
				<tbody>
					<tr>
<?php
	$sql_lista_emia = "SELECT * FROM sis_emia WHERE publicado = '1' AND IdCargo <> '0'";
	$query_lista_emia = mysqli_query($con,$sql_lista_emia);
	while($emia = mysqli_fetch_array($query_lista_emia))
	{
		$pf = recuperaDados("sis_pessoa_fisica",$emia['IdPessoaFisica'],"Id_PessoaFisica");
		$programa = recuperaDados("sis_emia_programa",$emia['IdPrograma'],"Id_Programa");
		$cargo = recuperaDados("sis_emia_cargo",$emia['IdCargo'],"Id_Cargo"); 
?>
						<td class="list_description"><?php echo $emia['idEmia'];  ?></td>
						<td class="list_description"><?php echo $pf['Nome'];  ?></td>
						<td class="list_description"><?php echo $programa['Programa'];  ?></td>
						<td class="list_description"><?php echo $cargo['Cargo'];  ?></td>
					</tr>
<?php
	}
?>				
				</tbody>
			</table>
		</div>
	</div>
</section>
<!--fim_list-->