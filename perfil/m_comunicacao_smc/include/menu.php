<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=comunicacao_smc&p=";
$perfil = $_SESSION['perfil'];

$server = "http://".$_SERVER['SERVER_NAME']."/igsis/";
$http = $server."/pdf/";
$link = $http."rlt_comunicacao_fotos.php";
?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Busca</button>
			<ul class="dl-menu">
				<li><a href="<?php echo $pasta ?>programacao_local">Programação por período</a></li>
				<li><a href="<?php echo $pasta ?>docs">Em Cartaz</a></li>
				<li><a href="<?php echo $pasta ?>gerar_csv">Gerar Arquivo .csv</a></li>
                <?php if ($_SESSION['perfil'] == 1): ?>
                    <li><a href="<?php echo $pasta ?>gerar_csv_locais">Gerar Arquivo .csv Locais</a></li>
                <?php endif ?>
				<li style="color:white;">-------------------------</li>
				<li><a href="?secao=perfil">Carregar módulo</a></li>
				<li selected><a href="http://smcsistemas.prefeitura.sp.gov.br/igsis/manual/index.php/modulo-comunicacao/">Ajuda</a></li>
				<li><a href="../include/logoff.php">Sair</a></li>
			</ul>
	</div>
</div>