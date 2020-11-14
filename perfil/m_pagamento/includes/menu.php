<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=pagamento&p=";

$sql_operador = "SELECT * FROM ig_usuario WHERE idUsuario IN (270, 274, 275, 393, 424, 445, 655, 993, 1121, 1135, 1170, 1256, 1293, 872, 1296, 1126, 1081, 986, 895, 1429, 1440, 1455, 1460, 1461, 1462, 1463, 1464, 1465, 1466, 1467) ORDER BY nomeCompleto";
 ?>

<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="<?php echo $pasta ?>frm_busca_nepagamento">Buscar</a></li>
			<li><a href="<?php echo $pasta ?>frm_busca_periodo">Buscar por período</a></li>
			<!-- <li><a href="<?php echo $pasta ?>frm_busca_parcela">Buscar por data da parcela</a></li> -->
			<li><a href="<?php echo $pasta ?>frm_busca_periodo_operador">Buscar por data kit / operador</a></li>
			<li><a href="<?php echo $pasta ?>frm_listapedidocontratacaopf_cadastrane_vocacional">N.E. Formação</a></li>
			<li><a href="<?php echo $pasta ?>lista_mesasei">Mesas SEI</a></li>
			<li style="color:white;">-------------------------</li>		
			<li><a href="index.php?secao=perfil">Carregar Módulos</a></li>
			<li><a href="http://smcsistemas.prefeitura.sp.gov.br/igsis/manual/index.php/modulo-pagamento/" target="_blank">Ajuda</a></li>
			<li><a href="../index.php">Sair</a></li>
		</ul>
	</div>
</div>	
