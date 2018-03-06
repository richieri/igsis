<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php

$con = bancoMysqli();
$sql = "SELECT distinct idPedido FROM `igsis_grupos` AS gru
		INNER JOIN igsis_pedido_contratacao AS ped ON gru.idPedido = ped.idPedidoContratacao
		WHERE gru.publicado = 1 AND ped.publicado = 1 ORDER BY idPedidoContratacao DESC";
$query = mysqli_query($con,$sql);

while($x= mysqli_fetch_array($query))
{
	$id = $x[0];

	$sql_grupos = "SELECT * 
		FROM igsis_grupos 
		WHERE idPedido = '$id' 
		AND publicado = '1'";
	$query_grupos = mysqli_query($con,$sql_grupos);
	$num = mysqli_num_rows($query_grupos);
	if($num > 0)
	{
		$txt = "";
		while($grupo = mysqli_fetch_array($query_grupos))
		{
			$txt .= $grupo['nomeCompleto'].'\r\n';
		}
	}
	else
	{
		$txt = "Não há integrantes de grupo inseridos";
	}
	echo "<p>UPDATE `igsis_pedido_contratacao` SET `integrantes` = '".$txt."' WHERE `igsis_pedido_contratacao`.`idPedidoContratacao` = ".$id.";</p>";
}
