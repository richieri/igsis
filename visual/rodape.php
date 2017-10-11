

<footer>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<p><?php geraFrase(); ?></p>
				<table width="100%">
					<tr>
						<td width="20%"><img src="images/logo_cultura_q.png" align="left"/></td>
						<td align="center"><font color="#ccc">2017 @ IGSIS - Cadastro de Artistas e Profissionais de Arte e Cultura<br/>Secretaria Municipal de Cultura<br/>Prefeitura de São Paulo</font></td>
						<td width="20%"><img src="images/logo_igsis_azul.png" align="right"/></td>
					</tr>
				</table>
			</div>
			<div class="col-md-12">
				<?php
				if($_SESSION['perfil'] == 1)
				{
					echo "<strong>SESSION</strong><pre>", var_dump($_SESSION), "</pre>";
					echo "<strong>POST</strong><pre>", var_dump($_POST), "</pre>";
					echo "<strong>GET</strong><pre>", var_dump($_GET), "</pre>";
					echo "<strong>SERVER</strong><pre>", var_dump($_SERVER), "</pre>";
					echo "<strong>FILES</strong><pre>", var_dump($_FILES), "</pre>";

					echo ini_get('session.gc_maxlifetime')/60; // em minutos
				}
				?>
			</div>
		</div>		
	</div>	
</footer>
	 
	 <!-- js -->
    <!--<script src="js/jquery.js"></script>-->
    
    <?php 
	if(isset($_GET['perfil'])){
	$modulo = recuperaDados("ig_modulo",$_GET['perfil'],"pag");
	
	?>
    	<script>
	var enter = new Date();
	
	$(document).ready(function() {
		var load = (new Date()).getTime() - enter.getTime();
		$('#doc').text('- Você está no Módulo <?php echo $modulo['nome'] ?>');
	});
	
	<?php } ?>

	</script>
    
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.smooth-scroll.min.js"></script>
	<script src="js/jquery.dlmenu.js"></script>
	<script src="js/wow.min.js"></script>
	<script src="js/custom.js"></script>
  	</body>
