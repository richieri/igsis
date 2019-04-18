<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=agendao&p=";
$perfil = $_SESSION['perfil'];
?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Busca</button>
			<ul class="dl-menu">
				<li><a href="<?= $pasta ?>filtro">Cadastra evento</a></li>
				<li><a href="<?= $pasta ?>programacao_local">Lista evento</a></li>
                <li><a href="<?= $pasta ?>agendao_filtro_excel">Exporta Excel</a></li>
				<li style="color:white;">-------------------------</li>
				<li><a href="?secao=perfil">Carregar módulo</a></li>
				<li selected><a href="http://smcsistemas.prefeitura.sp.gov.br/manual/igsis">Ajuda</a></li>
				<li><a href="../include/logoff.php">Sair</a></li>
			</ul>
	</div>
</div>