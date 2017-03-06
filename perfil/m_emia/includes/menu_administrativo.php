<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=emia&p=administrativo&pag=";
 ?>

 <div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="#">Função</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>add_cargo">Cadastrar</a></li>
					<li><a href="<?php echo $pasta ?>list_cargo">Listar</a></li>
				</ul>
			</li>			
			<li><a href="#">Vigência</a>
				<ul class="dl-submenu">
					<li><a href="?perfil=emia&p=frm_cadastra_vigencia&novo">Cadastrar</a></li>
					<li><a href="?perfil=emia&p=frm_lista_vigencia">Listar</a></li>
				</ul>
			</li>		
			<li><a href="?perfil=emia">Voltar ao módulo EMIA</a></li>
		</ul>
	</div>
</div>	