<?php

require_once "../funcoes/funcoesConecta.php";
$path = "../../igsiscapac/uploadsdocs/";
$path2 = "../../igsiscapac/uploads/";

function comparaArquivoOficineiro($idPessoa, $tipoPessoa)
{
    $con = bancoMysqliProponente();

    $sql = "SELECT * FROM upload_lista_documento as list
                INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
                WHERE arq.idPessoa = '$idPessoa'
                AND arq.idTipoPessoa = '".$tipoPessoa[0]."'
                AND arq.publicado = '1'";
    $sqlOficineiro = "SELECT * FROM upload_lista_documento as list
                        INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
                        WHERE arq.idPessoa = '$idPessoa'
                        AND arq.idTipoPessoa = '".$tipoPessoa[1]."'
                        AND arq.publicado = '1'";

    $query = $con->query($sql);
    $queryOficineiro = $con->query($sqlOficineiro);

    $registrosPf = [];
    $registrosOficineiro = [];
    $documentos = [];
    while ($registroPf = mysqli_fetch_assoc($query))
    {
        array_push($registrosPf, $registroPf);
    }
    while ($registroOficineiro = mysqli_fetch_assoc($queryOficineiro))
    {
        array_push($registrosOficineiro, $registroOficineiro);
    }

    foreach ($registrosPf as $documentoPf => $arquivoPf)
    {
        foreach ($registrosOficineiro as $documentoPfOficineiro => $arquivoPfOficineiro)
        {
            if ($arquivoPf['documento'] == $arquivoPfOficineiro['documento'])
            {
                if ($arquivoPf['dataEnvio'] > $arquivoPfOficineiro['dataEnvio'])
                {
                    array_push($documentos, $registrosPf[$documentoPf]);
                    unset($registrosOficineiro[$documentoPfOficineiro]);
                    unset($registrosPf[$documentoPf]);
                }
                else
                {
                    array_push($documentos, $registrosOficineiro[$documentoPfOficineiro]);
                    unset($registrosOficineiro[$documentoPfOficineiro]);
                    unset($registrosPf[$documentoPf]);
                }
            }
        }
    }
    $registros = array_merge($registrosPf, $registrosOficineiro);
    foreach ($registros as $registro)
    {
        array_push($documentos, $registro);
    }
    return $documentos;
}


$con = bancoMysqliProponente();
$idEvento = $_GET['idEvento'];
$idPj = $_GET['idPj'];
$idPf = $_GET['idPf'];

$data = date('YmdHis');
$nome_arquivo = $data.".zip";

$zip = new ZipArchive();

if( $zip->open( $nome_arquivo , ZipArchive::CREATE )  === true)
{
   	// arquivos do evento
	$sql = "SELECT * FROM upload_arquivo WHERE publicado = '1' AND idPessoa = '$idEvento' AND idTipoPessoa = '3'";
	$query = mysqli_query($con,$sql);

	$sql_evento = "SELECT * FROM evento WHERE id = '$idEvento'";
	$query_evento = mysqli_query($con,$sql_evento);
	$evento = mysqli_fetch_array($query_evento);

	// arquivos da empresa
    if ($evento['idPj'] != NULL)
    {
        if($evento['idPj'] != 0)
        {
//		$sql_pj = "SELECT * FROM upload_arquivo WHERE publicado = '1' AND idPessoa = '$idPj' AND idTipoPessoa = '2'";
            $query_pj = comparaArquivoOficineiro($idPj,[2,5]);
        }
    }

	// arquivos do líder / artista
    if ($evento['idPf'] != NULL)
    {
        if($evento['idPf'] != 0)
        {
            //		$sql_pf = "SELECT * FROM upload_arquivo WHERE publicado = '1' AND idPessoa = '$idPf' AND idTipoPessoa = '1'";
            $query_pf = comparaArquivoOficineiro($idPf,[1,4]);
        }
    }

	// arquivos comunicação / produção
	$sql_com_prod = "SELECT * FROM upload_arquivo_com_prod WHERE publicado = '1' AND idEvento = '$idEvento'";
	$query_com_prod = mysqli_query($con,$sql_com_prod);


	while($arquivo = mysqli_fetch_array($query))
	{
		$file = $path.$arquivo['arquivo'];
		$file2 = $arquivo['arquivo'];
		$zip->addFile($file, "evento/".$file2);
	}

	if ($evento['idPj'] != NULL)
    {
        if($evento['idPj'] != 0)
        {
            foreach ($query_pj as $arquivo)
            {
                $file = $path.$arquivo['arquivo'];
                $file2 = $arquivo['arquivo'];
                $zip->addFile($file, "pj/".$file2);
            }
        }
    }

	if ($evento['idPf'] != NULL)
	{
        if($evento['idPf'] != 0)
        {
            foreach ($query_pf as $arquivo)
            {
                $file = $path.$arquivo['arquivo'];
                $file2 = $arquivo['arquivo'];
                $zip->addFile($file, "pf/".$file2);
            }
        }
    }

	while($arquivo = mysqli_fetch_array($query_com_prod))
	{
		$file = $path2.$arquivo['arquivo'];
		$file2 = $arquivo['arquivo'];
		$zip->addFile($file, "com_prod/".$file2);
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

unlink($nome_arquivo); // aqui apaga
?>