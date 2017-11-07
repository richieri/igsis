<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=contratos_lite&p=";
$usuario = recuperaDados("ig_usuario",$_SESSION['idUsuario'],"idUsuario");
 ?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="<?php echo $pasta ?>frm_busca">Contratos</a></li>
			<li><a href="<?php echo $pasta ?>frm_busca_periodo">Contratos por período</a></li>
			<li><a href="#">Virada</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=0">Não Atribuídos</a>
					<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=1">Atribuídos</a>
					<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=3">Geral</a>
					<li><a href="<?php echo $pasta ?>frm_imprime_proposta_facc">Gerar Proposta/FACC</a>
				</ul>
			</li>
			<li style="color:white;">-------------------------</li>
			<li><a href="index.php?secao=perfil">Carregar Módulos</a></li>
			<li><a href="http://smcsistemas.prefeitura.sp.gov.br/igsis/manual/index.php/contratos-lite/">Ajuda</a></li>
			<li><a href="../index.php">Sair</a></li>
		</ul>
	</div>
</div>	