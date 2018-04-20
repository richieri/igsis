<?php

require_once "../funcoes/funcoesConecta.php";
$path = "../../igsiscapac/uploadsdocs/";
$path2 = "../../igsiscapac/uploads/";

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
	if($evento['idPj'] != "" OR $evento['idPj'] != NULL)
	{
		$sql_pj = "SELECT * FROM upload_arquivo WHERE publicado = '1' AND idPessoa = '$idPj' AND idTipoPessoa = '2'";
		$query_pj = mysqli_query($con,$sql_pj);
	}

	// arquivos do líder / artista
	if($evento['idPf'] != "" OR $evento['idPf'] != NULL)
	{
		$sql_pf = "SELECT * FROM upload_arquivo WHERE publicado = '1' AND idPessoa = '$idPf' AND idTipoPessoa = '1'";
		$query_pf = mysqli_query($con,$sql_pf);
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

	if($evento['idPj'] != "" OR $evento['idPj'] != NULL)
	{
		while($arquivo = mysqli_fetch_array($query_pj))
		{
			$file = $path.$arquivo['arquivo'];
			$file2 = $arquivo['arquivo'];
			$zip->addFile($file, "pj/".$file2);
		}
	}

	if($evento['idPf'] != "" OR $evento['idPf'] != NULL)
	{
		while($arquivo = mysqli_fetch_array($query_pf))
		{
			$file = $path.$arquivo['arquivo'];
			$file2 = $arquivo['arquivo'];
			$zip->addFile($file, "pf/".$file2);
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

?>