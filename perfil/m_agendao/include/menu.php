﻿<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=agendao&p=";
?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Busca</button>
			<ul class="dl-menu">
				<li><a href="<?= $pasta ?>evento_cadastra">Cadastra evento</a></li>
				<li><a href="<?= $pasta ?>lista_eventos">Lista evento</a></li>
                <li><a href="<?= $pasta ?>exportar_filtra">Exporta Excel</a></li>
				<li style="color:white;">-------------------------</li>
				<li><a href="?secao=perfil">Carregar módulo</a></li>
				<li><a href="http://smcsistemas.prefeitura.sp.gov.br/manual/igsis/modulo-agendao/" target="_blank">Ajuda</a></li>
				<li><a href="../include/logoff.php">Sair</a></li>
			</ul>
	</div>
</div>