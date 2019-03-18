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
			<li><a href="<?php echo $pasta ?>frm_busca">Filtrar Contratos</a></li>
			<li><a href="<?php echo $pasta ?>frm_sem_operador">Filtro Sem Operador</a></li>
			<li><a href="<?php echo $pasta ?>frm_evento_sem_reenvio">Eventos Sem Reenvios</a></li>
			<li><a href="<?php echo $pasta ?>frm_busca_periodo_operador">Filtro por Período/Operador</a></li>
			<li><a href="<?php echo $pasta ?>frm_busca_operador">Filtro por Operador</a></li>
			<li><a href="<?php echo $pasta ?>frm_busca_dataenvio">Filtro por Data de Envio</a></li>
			<li><a href="#">Especiais</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>frm_lista_pedidocontratacao_pf&enviados=1">Formação</a></li>
					<li><a href="<?php echo $pasta ?>frm_lista_pedidocontratacao_emia_pf&enviados=1">Emia</a></li>
					<li><a href="#">Virada</a>
						<ul class="dl-submenu">
							<li><a href="<?php echo $pasta ?>frm_busca_especial_operador">Filtro por Operador</a></li>
							<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=0">Sem nº Processo</a>
							<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=1">Com nº Processo</a>
							<li><a href="<?php echo $pasta ?>frm_lista_projeto&atribuido=3">Geral</a>
						</ul>
					</li>
				</ul>
			</li>
			<li><a href="<?php echo $pasta ?>frm_chamados">Chamados</a></li>
			<li><a href="<?php echo $pasta ?>lista_mesasei">Mesas SEI</a></li>
			<li style="color:white;">-------------------------</li>
			<li><a href="index.php?secao=perfil">Carregar módulos</a></li>
			<li><a href="http://smcsistemas.prefeitura.sp.gov.br/igsis/manual/index.php/modulo-contratos/">Ajuda</a></li>
			<li><a href="../index.php">Sair</a></li>
		</ul>
	</div>
</div>