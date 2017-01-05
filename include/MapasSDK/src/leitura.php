<?php

function jsonMapas($get_addr){
	$ch = curl_init($get_addr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($ch);
	return $page;
}

function converterObjParaArray($data) { //função que transforma objeto vindo do json em array
    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }

    else {
        return $data;
    }
}

$hoje = date('Y-m-d');
$cem_dias = date('Y-m-d');

$url_space = "http://spcultura.prefeitura.sp.gov.br/api/space/getChildrenIds/169";
$locais = json_decode(jsonMapas($url_space));


$loc = converterObjParaArray($locais);
var_dump($loc);

$var_loc = "169,";

$url = "http://spcultura.prefeitura.sp.gov.br/api/event/findByLocation";

$data = array(
	"space"=> "IN(169)",
   	"@from" => "2016-08-25",
	"@to" => "2016-08-29",
	"@select" => "id, name, longDescription, terms, project, occurrences, isVerified", 
	"@order" => "id ASC", 
	"@limit" => "5", 
	"isVerified" => "EQ(TRUE)"
	);
	
$get_addr = $url.'?'.http_build_query($data);

$evento = json_decode(jsonMapas($get_addr));

echo "<pre>";
var_dump($evento);
echo "</pre>";

?>
