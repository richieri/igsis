<?php 
	include 'includes/menu.php';
	$con = bancoMysqli();
	$ano = date('Y');
	$pasta = "?perfil=formacao&p=frm_lista_dadoscontratacao&pag=";
	if(isset($_GET['pag']))
	{
		$p = $_GET['pag'];
	}
	else
	{
		$p = $ano;	
	}
?>
<section id="list_items">
	<div class="container">
		<div class="sub-title"><br/><br/>
			<h4>Escolha o ano<br/> 
				| <a href="<?php echo $pasta?><?php echo $ano;?>"><?php echo $ano; ?></a> 
				| <a href="<?php echo $pasta?><?php echo $ano - 1; ?>"><?php echo $ano - 1; ?></a> |
			</h4>
		</div>
	</div>
</section>
<section id="list_items">
	<div class="container">
		<div class="sub-title"><br/><h5>Dados de contratação</h5>
		</div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Id</td>
						<td>Proponente</td>
						<td>Telefone</td>
						<td>Programa</td>
						<td>Ano</td>
						<td>Cargo</td>
						<td>Status</td>
					</tr>
				</thead>
				<tbody>
<?php
	switch($p)
	{
		case $ano:
			$sql_lista_formacao = "SELECT * FROM sis_formacao WHERE publicado = '1' AND Ano = $ano";
		break;
		case $ano - 1:
			$sql_lista_formacao = "SELECT * FROM sis_formacao WHERE publicado = '1' AND Ano = $ano - 1";
		break; 
	} 
	$query_lista_formacao = mysqli_query($con,$sql_lista_formacao);
	while($formacao = mysqli_fetch_array($query_lista_formacao))
	{
		$pessoa = recuperaDados("sis_pessoa_fisica",$formacao['IdPessoaFisica'],"Id_PessoaFisica");	
?>
					<tr>
						<td class="list_description"><?php echo $formacao['Id_Formacao'];  ?></td>
						<td class="list_description"><a href="?perfil=formacao&p=frm_cadastra_dadoscontratacao&id=<?php echo $formacao['Id_Formacao']; ?>"><?php echo $pessoa['Nome'];  ?></a></td>
						<td class="list_description"><?php echo $pessoa['Telefone1'];  ?></td>
						<td class="list_description"><?php echo retornaPrograma($formacao['IdPrograma']);  ?></td>
						<td class="list_description"><?php echo $formacao['Ano'];  ?></td>
						<td class="list_description"><?php echo retornaCargo($formacao['IdCargo']);  ?></td>
						<td class="list_description"><?php echo retornaStatus($formacao['Status']);  ?></td>    
					</tr>
<?php 
	}
?>
				</tbody>
			</table>
		</div>
	</div>
</section>