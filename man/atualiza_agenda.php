<?php 
	$con = bancoMysqli();	
	$sql_data = "SELECT * FROM igsis_agenda";
	$query_data = mysqli_query($con,$sql_data);
	$i = 0;
	$num = mysqli_num_rows($query_data);
	while($agenda = mysqli_fetch_array($query_data)){
		
		$id = $agenda['idAgenda'];
		$inst = recuperaDados("ig_local",$agenda['idLocal'],"idLocal");
		$idInst = $inst['idInstituicao'];
		$sql_atualiza = "UPDATE igsis_agenda SET idInstituicao = '$idInst' WHERE idAgenda = '$id'";
		$query_atualiza = mysqli_query($con,$sql_atualiza);
		if($query_atualiza){
			$i++;	
		}
	}
	$relatorio .= "<h2>Atualizando as instituições no Módulo Agenda</h2>";
	$relatorio .= "<p>Foram atualizados $i de $num registros.</p>";


?>