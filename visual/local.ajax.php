<?php
//Imprime erros com o banco
@ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once "../funcoes/funcoesConecta.php";
$con = bancoMysqli();

$cod = mysqli_real_escape_string( $con,$_GET['instituicao'] );

$cidades = array();

$sql = "SELECT *
		FROM ig_local
		WHERE idInstituicao = '$cod' AND publicado = 1
		ORDER BY sala";
$res = mysqli_query($con,$sql);

while ( $row = mysqli_fetch_array( $res ) ) {
	$cidades[] = array(
		'idEspaco'	=> $row['idLocal'],
		'espaco'			=> $row['sala'],
	);
}

echo( json_encode( $cidades ) );

?>