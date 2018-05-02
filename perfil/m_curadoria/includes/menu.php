<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=curadoria&p=";
$usuario = recuperaDados("ig_usuario",$_SESSION['idUsuario'],"idUsuario");
 ?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="<?php echo $pasta ?>frm_busca">Buscar</a></li>
			<li><a href="<?php echo $pasta ?>frm_lista">Listar todos</a></li>
			<li><a href="<?php echo $pasta ?>frm_lista_ingresso">Listar com Ingresso</a></li>
			<li style="color:white;">-------------------------</li>
			<li><a href="index.php?secao=perfil">Carregar Módulos</a></li>
			<li><a href="http://smcsistemas.prefeitura.sp.gov.br/igsis/manual/" target="_blank">Ajuda</a></li>
			<li><a href="../index.php">Sair</a></li>
		</ul>
	</div>
</div>
