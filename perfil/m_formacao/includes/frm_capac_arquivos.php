<?php

require_once "../../../funcoes/funcoesConecta.php";
$path = "../../../../igsiscapac/uploadsdocs/";

$con = bancoMysqliProponente();
$idPf = $_GET['idPf'];

$data = date('YmdHis');
$nome_arquivo = $data.".zip";

$zip = new ZipArchive();

if( $zip->open( $nome_arquivo , ZipArchive::CREATE )  === true)
{
    // arquivos do proponente
    $sql = "SELECT * FROM upload_arquivo WHERE publicado = '1' AND idPessoa = '$idPf' AND idTipoPessoa = '6'";
    $query = mysqli_query($con,$sql);

    while($arquivo = mysqli_fetch_array($query))
    {
        $file = $path.$arquivo['arquivo'];
        $file2 = $arquivo['arquivo'];
        $zip->addFile($file, $file2);
    }

    $zip->close();
}

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.$nome_arquivo.'"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($nome_arquivo));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');


ob_end_clean(); //essas duas linhas antes do readfile
flush();

readfile($nome_arquivo);