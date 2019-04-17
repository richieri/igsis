<?php
include ('funcoesConecta.php');

$conn = bancoPDO();

$protocol = 'http'. "://". $_SERVER['HTTP_HOST']. ":" . $_SERVER['SERVER_PORT'];

// echo $protocol;
//$autenticar = "http://smcsistemas.prefeitura.sp.gov.br:80";
$autenticar = "http://localhost:80";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

$sql = "SELECT
          idLocal AS `igsis_id`,
          sala AS `nome`
          FROM ig_local WHERE idInstituicao = 14
           AND publicado = '1'
           AND sala LIKE 'Biblioteca%'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$equipamentos = $stmt->fetchAll();

if ($autenticar === $protocol) {
    echo json_encode($equipamentos);
}
