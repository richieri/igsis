<?php
$con = bancoMysqli();

$sqLocais = "SELECT * FROM ig_local WHERE publicado = 1";
$queryLocais = mysqli_query($con, $sqLocais);

$i = 0;

while ($locais = mysqli_fetch_array($queryLocais)) {
    $id = $locais['idLocal'];
    $local = $locais['rua'];
    if ($local != '') {
        $arrayRua = explode(',', $local);
        $ruas[$i] = $arrayRua[0];
        $rua = $ruas[$i];
        $localSemRua = substr_replace($local, '', 0, strlen($rua) + 1);

        $arrayNums = explode('-', $localSemRua);
        $nums[$i] = $arrayNums[0];
        $num = $nums[$i];

        $bairros[$i] = isset($arrayNums[1]) ? trim($arrayNums[1]) : null;
        $bairro = $bairros[$i];

        //print_r($arrayRua);

        $sql = "UPDATE ig_local SET logradouro = '$rua', numero = '$num', bairro = '$bairro' WHERE idLocal = '$id'";
        mysqli_query($con, $sql);

        //echo "rua: $rua, $num - $bairro (" .  ($i+1) . ") ~~~ ";

    } else {
        $sql = "UPDATE ig_local SET logradouro = null, numero = null, bairro = null WHERE idLocal = '$id'";
        mysqli_query($con, $sql);
    }
    $i++;
}


/*function insert($tabela, $campo, $valor) {
    $con = bancoMysqli();
    $sql = "INSERT INTO $tabela ($campo) VALUES ('$valor')";
    mysqli_query($con, $sql);
}*/