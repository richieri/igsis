<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=comunicacao_beta&p=";
$perfil = $_SESSION['perfil'];

$server = "http://".$_SERVER['SERVER_NAME']."/igsis/";
$http = $server."/pdf/";
$link = $http."rlt_comunicacao_fotos.php";
?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Busca</button>
			<ul class="dl-menu">				
				<li><a href="<?php echo $pasta ?>filtro">Filtrar</a></li>
				<li><a href="<?php echo $pasta ?>programacao_local">Programação Local</a></li>
				<li><a href='<?php echo $link ?>' target='_blank'>Relatório de Foto do Mês</a></li>
				<li><a href="<?php echo $pasta ?>docs">Em Cartaz</a></li>   
				<!--<li><a href="<?php echo $pasta ?>programacao_local">Agenda</a></li>-->				
				<li><a href="<?php echo $pasta ?>chamados">Lista de chamados</a></li> 			
				<li style="color:white;">-------------------------</li>
				<li><a href="?secao=perfil">Carregar módulo</a></li>
				<li selected><a href="http://smcsistemas.prefeitura.sp.gov.br/igsis/manual/index.php/modulo-comunicacao/">Ajuda</a></li>
				<li><a href="../include/logoff.php">Sair</a></li>
			</ul>
	</div>
</div>	