<?php
@ini_set('display_errors', '1');
error_reporting(E_ALL); 

include "MapasSDK/MapasSDK.php";
include "MapasSDK/Exceptions/Exception.php";
include "MapasSDK/Exceptions/BadRequest.php"
include "MapasSDK/Exceptions/Forbidden.php";
include "MapasSDK/Exceptions/NotFound.php";
include "MapasSDK/Exceptions/Unauthorized.php";
include "MapasSDK/Exceptions/UnexpectedError.php";
include "MapasSDK/Exceptions/ValidationError.php";


$mapas = new MapasSDK\MapasSDK(
    'http://spcultura.prefeitura.sp.gov.br/', 
    '2Wqunh0A2SskhPJbBt8KexQOeoylgYm3', 
    'X1sYAbSFcaD6SDCRTmwvDaTDXpNkdVFZ0QtGAJUOLJpuHk1MltRBpkgaTsEXKegJ'
);

//
$newAgent = $mapas->createEntity('agent', [
    'type' => '2',
    'name' => 'Teste API Marcio ' . date('Y/m/d H:i:s'),
    'shortDescription' => 'Oi',
    'terms' => [
        'area' => [
            'Arqueologia'
        ]
    ],
    'location' => [
        '-46.685684400000014',
        '-23.5404024'
    ],
    'endereco' => 'Rua Capital Federal'
]);

print_r($newAgent);
//var_dump($newAgent);
//var_dump($newAgent->response);


//$agents = $mapas->apiGet('api/agent/find', [
//    '@select' => 'id,name,documento,emailPrivado',
//    'user' => 'EQ(@User:1)'
//]);
//
//var_dump($agents);

//
//print_r($mapas->getEntityDescription('agent'));
//print_r($mapas->getEntityTypes('space'));

//print_r($mapas->findEntity('agent', 83, 'id,name,location'));

//print_r($mapas->createEntity('agent', []));
