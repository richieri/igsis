<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=contratos&p=";
 ?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="#">Cadastro de Pessoas</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>frm_lista_pf">Pessoa Física</a></li>
					<li><a href="<?php echo $pasta ?>frm_lista_pj">Pessoa Jurídica</a></li>
				</ul>
			</li>
			<li><a href="<?php echo $pasta ?>frm_busca">Contratos</a></li>
			<li><a href="#">Filtro por Operador</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>frm_sem_operador">Sem Operador</a></li>
					<li><a href="<?php echo $pasta ?>frm_por_operador">Por Operador</a></li>
				</ul>
			</li>
			<li><a href="<?php echo $pasta ?>frm_busca_periodo">Contratos por período</a></li>
			<li><a href="<?php echo $pasta ?>frm_busca_dataenvio">Contratos por data de envio</a></li>
			<li><a href="<?php echo $pasta ?>frm_lista_pedidocontratacao_pf&enviados=1">Formação</a></li>
			<li><a href="#">Virada</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=0">Sem nº Processo</a>
					<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=1">Com nº Processo</a>
					<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=3">Geral</a>
				</ul>
			</li>
			<li><a href="<?php echo $pasta ?>frm_chamados">Chamados</a></li>
			<li style="color:white;">-------------------------</li>
			<li><a href="index.php?secao=perfil">Carregar módulos</a></li>
			<li><a href="http://www.centrocultural.cc/igsis/manual/index.php/modulo-contratos/">Ajuda</a></li>
			<li><a href="../index.php">Sair</a></li>
		</ul>
	</div>
</div>