	<div class="menu-area">
		<div id="dl-menu" class="dl-menuwrapper">
			<button class="dl-trigger">Busca</button>
			<ul class="dl-menu"> 
				<?php if($_SESSION['idInstituicao'] == 5){ ?>
			<li><a href="?perfil=bilhetagem&p=all" > Todos os eventos</a></li>      
			<?php } ?>
			<li style="color:white;">-------------------------</li>
			<li><a href="?secao=perfil">Carregar módulos</a></li>
			<li><a href="http://smcsistemas.prefeitura.sp.gov.br/igsis/manual/index.php/acessando-o-modulo-bilhetagem/">Ajuda</a></li>
			<li><a href="../include/logoff.php">Sair</a></li>
			</ul>
		</div>
	</div>	