<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=comunicacao&p=";
$perfil = $_SESSION['perfil'];
?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Busca</button>
			<ul class="dl-menu">				
				<li><a href="<?php echo $pasta ?>filtro">Filtrar</a></li>
				<li><a href="<?php echo $pasta ?>programacao_local">Programação Local</a></li>				
				<li><a href="<?php echo $pasta ?>docs">Gerar Docs</a></li>                
				<li><a href="?perfil=agenda" target="_blank">Agenda</a></li>   
				<li><a href="<?php echo $pasta ?>chamados">Lista de chamados</a></li> 			
				<li style="color:white;">-------------------------</li>
				<li><a href="?secao=perfil">Carregar módulo</a></li>
				<li selected><a href="http://www.centrocultural.cc/igsis/manual/index.php/modulo-comunicacao/">Ajuda</a></li>
				<li><a href="../include/logoff.php">Sair</a></li>
			</ul>
	</div>
</div>	