<?php
	$con = bancoMysqli();
	include 'includes/menu_administrativo.php';
	$ano = date('Y');
	$pasta = "?perfil=formacao&p=frm_lista_vigencia&pag=";
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
		<div class="col-md-offset-2 col-md-8">
			<br />
			<h2>Lista de Vigências</h2>
			<p><?php if(isset($mensagem)){ echo $mensagem; } ?></p>
			<br />
		</div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Id</td>
						<td colspan="2">Vigência</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
		<?php
			switch($p)
			{
				case $ano:
					$sql = "SELECT * FROM sis_formacao_vigencia WHERE publicado = '1' AND ano = $ano AND descricao <> ''";
				break;
				case $ano - 1:
					$sql = "SELECT * FROM sis_formacao_vigencia WHERE publicado = '1' AND ano = $ano - 1 AND descricao <> ''";
				break; 
			} 				
			$query = mysqli_query($con,$sql);
			while($vigencia = mysqli_fetch_array($query))
			{
		?>
					<tr>
						<form action="?perfil=formacao&p=frm_cadastra_vigencia" method="post">
							<td><?php echo $vigencia['Id_Vigencia']; ?></td>
							<td><?php echo $vigencia['descricao']; ?></td>
							<td>
								<input type="hidden" name="editar" value="<?php echo  $vigencia['Id_Vigencia']; ?>" />
								<input type ='submit' class='btn btn-theme  btn-block' value='editar'>
							</td>
						</form>
					</tr>
		<?php
			}
		?>
				</tbody>
			</table>
		</div>
	</div>            
</section>