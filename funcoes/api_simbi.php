<?php
include ('funcoesConecta.php');

$conn = bancoPDO();

$protocol = 'http'. "://". $_SERVER['HTTP_HOST']. ":" . $_SERVER['SERVER_PORT'];

// echo $protocol;
$autenticar = "http://smcsistemas.prefeitura.sp.gov.br:80";
$autenticar = "http://localhost:80";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

$id = $_GET['id'];

$sql = "SELECT * FROM ig_evento AS evento
	LEFT OUTER JOIN igsis_agenda AS agenda
	ON evento.idEvento = agenda.idEvento
	LEFT OUTER JOIN ig_ocorrencia AS ocorrencia
	ON evento.idEvento = ocorrencia.idEvento
	WHERE evento.idEvento = :id";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();
$eventos = $stmt->fetch();

if ($autenticar === $protocol) {
    echo json_encode($eventos);
}