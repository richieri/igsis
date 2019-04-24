<?php
include "../funcoes/funcoesConecta.php";

$con = bancoMysqli();
//mysqli_set_charset($con,"utf8");

$usuarios = [];

$sql = "SELECT nomeCompleto
		FROM ig_usuario
		WHERE ig_papelusuario_idPapelUsuario IN ('1', '6', '33', '46', '58', '69', '72', '74', '75', '84', '87', '91', '92', '94', '97', '98', '99' )
		ORDER BY nomeCompleto";
$res = mysqli_query($con,$sql);

while ( $row = mysqli_fetch_array( $res ) ) {
    $usuarios[] = [
        'nomeCompleto'	=> $row['nomeCompleto'],
    ];
}

echo( json_encode( $usuarios ) );