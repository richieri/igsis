<?php
$server = "http://".$_SERVER['SERVER_NAME']."/igsis/";
$http = $server."/pdf/";
$link = $http."rlt_comunicacao_fotos.php";
?>	
	<div class="menu-area">
		<div id="dl-menu" class="dl-menuwrapper">
			<button class="dl-trigger">Busca</button>
				<ul class="dl-menu">
					<?php if($_SESSION['idInstituicao'] == 5){ ?>
					<li><a href="?perfil=comunicacao&p=sem" > Eventos não editados e não revisados</a></li>      
					<?php } ?>
					<li><a href="?perfil=comunicacao&p=editados" > Eventos editados</a></li>                
					<li><a href="?perfil=comunicacao&p=revisados" > Eventos revisados</a></li>
					<li><a href="?perfil=comunicacao&p=all" > Todos os eventos</a></li>
					<li><a href="#">Foto / Imagem de divulgação</a> 
						<ul class="dl-submenu">
							<li selected><a href="?perfil=comunicacao&p=foto&foto=1" >Aprovados</a></li>
							<li> <a href="?perfil=comunicacao&p=foto&foto=0" >Pendentes</a></li>
							<li> <a href="?perfil=comunicacao&p=foto" >Todos os eventos</a>  </li>
							<li><a href='<?php echo $link ?>' target='_blank'>Relatório do mês</a></li>
						</ul>
					</li>   
					<li><a href="?perfil=comunicacao&p=docs" >Gerar Docs</a></li>                
					<li><a href="?perfil=comunicacao&p=agenda" > Agenda</a></li>   
					<li><a href="?perfil=comunicacao&p=chamados" > Lista de chamados</a></li>  				
					<li style="color:white;">-------------------------</li>
					<li><a href="?secao=perfil">Carregar Módulos</a></li>
					<li selected><a href="http://www.centrocultural.cc/igsis/manual/index.php/modulo-comunicacao/">Ajuda</a></li>
					<li><a href="../include/logoff.php">Sair</a></li>
				</ul>
		</div>
	</div>	