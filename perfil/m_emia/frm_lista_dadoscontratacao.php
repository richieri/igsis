<?php 
	include 'includes/menu.php';
	$con = bancoMysqli();
	$ano = date('Y');
	$pasta = "?perfil=emia&p=frm_lista_dadoscontratacao&pag=";
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
                | <a href="<?php echo $pasta?>2018">2018</a>
                | <a href="<?php echo $pasta?>2019">2019</a>
                | <a href="<?php echo $pasta?>2020">2020</a> |
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
						<td>Cargo</td>
						<td>Ano</td>
						<td>Status</td>
					</tr>
				</thead>
				<tbody>
<?php
    $sql_lista_emia = "SELECT * FROM sis_emia WHERE publicado = '1' AND Ano = $p";
	$query_lista_emia = mysqli_query($con,$sql_lista_emia);
	while($emia = mysqli_fetch_array($query_lista_emia))
	{
		$pessoa = recuperaDados("sis_pessoa_fisica",$emia['IdPessoaFisica'],"Id_PessoaFisica");	
?>
					<tr>
						<td class="list_description"><?php echo $emia['idEmia'];  ?></td>
						<td class="list_description"><a href="?perfil=emia&p=frm_cadastra_dadoscontratacao&id=<?php echo $emia['idEmia']; ?>"><?php echo $pessoa['Nome'];  ?></a></td>
						<td class="list_description"><?php echo $pessoa['Telefone1'];  ?></td>
						<td class="list_description"><?php echo retornaCargo($emia['IdCargo']);  ?></td>
						<td class="list_description"><?php echo $emia['Ano'];  ?></td>
						<td class="list_description"><?php echo retornaStatus($emia['Status']);  ?></td>    
					</tr>
<?php 
	}
?>
				</tbody>
			</table>
		</div>
	</div>
</section>