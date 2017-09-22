<?php
	include 'includes/menu.php';
	$con = bancoMysqli();

require_once("../funcoes/funcoesGerais.php");

?>

<section id="list_items">
	<div class="container">
		<div class="sub-title">
			<br/><br/>
			<h4>MESAS - SEI<br/></h4>
		</div>
	</div>
</section>

<?php
		$sql = "SELECT nomeCompleto, mesa1, mesa2, mesa3
		FROM mesas_sei AS mesa
		INNER JOIN ig_usuario AS user 
		ON mesa.idUsuario = user.idUsuario 
		WHERE user.publicado = 1 ORDER BY nomeCompleto ASC";
		
		$query = mysqli_query($con,$sql); 		
		$row_cnt = mysqli_num_rows($query);	
		$i = 0;
		while($mesas = mysqli_fetch_array($query))	
		{
			$x[$i]['nomeCompleto']= $mesas['nomeCompleto'];
			$x[$i]['mesa1'] = $mesas['mesa1'];
			$x[$i]['mesa2'] = $mesas['mesa2'];
			$x[$i]['mesa3'] = $mesas['mesa3'];
			$i++;
		}
?>

<section id="list_items">
	<div class="container">
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Nome do Servidor</td>
						<td>Mesa 1</td>
						<td>Mesa 2</td>
						<td>Mesa 3</td>
					</tr>
				</thead>
				<tbody>
				<?php 
				for($h = 0; $h < $i; $h++)
				{
					echo '<tr>';
					echo '<td class="list_description">'.$x[$h]['nomeCompleto'].'</td>';
					echo '<td class="list_description">'.$x[$h]['mesa1'].'</td>';
					echo '<td class="list_description">'.$x[$h]['mesa2'].'</td>';
					echo '<td class="list_description">'.$x[$h]['mesa3'].'</td>';
					echo'</tr>';
				}
				?>
				</tbody>	
			</table>
		</div>
	</div>
</section>				
