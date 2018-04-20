<?php

@ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once "../funcoes/funcoesConecta.php";
$path = "../../igsiscapac/uploadsdocs/";

$con = bancoMysqliProponente();
$idEvento = $_GET['idEvento'];
$idPj = $_GET['idPj'];
$idPf = $_GET['idPf'];

$sql_evento = "SELECT * FROM upload_arquivo WHERE publicado = '1' AND
	(idPessoa = '$idEvento' AND idTipoPessoa = '3') OR
	(idPessoa = '$idPj' AND idTipoPessoa = '2') OR
	(idPessoa = '$idPf' AND idTipoPessoa = '1')";
$query_evento = mysqli_query($con,$sql_evento);
$data = date('YmdHis');
$nome_arquivo_evento = $data.".zip";


// Criando o objeto
$z_evento = new ZipArchive();

// Criando o pacote chamado "teste.zip"
$criou_evento = $z_evento->open($nome_arquivo_evento, ZipArchive::CREATE);
if ($criou_evento === true)
{
    // Criando um diretorio chamado "teste" dentro do pacote
    //$z->addEmptyDir('teste');

    // Criando um TXT dentro do diretorio "teste" a partir do valor de uma string
    //$z->addFromString('teste/texto.txt', 'Conteúdo do arquivo de Texto');

    // Criando outro TXT dentro do diretorio "teste"
    //$z->addFromString('teste/outro.txt', 'Outro arquivo');

    // Copiando um arquivo do HD para o diretorio "teste" do pacote
	while($arquivo = mysqli_fetch_array($query_evento))
	{
		$file = $path.$arquivo['arquivo'];
		$file2 = $arquivo['arquivo'];
    	$z_evento->addFile($file, $file2);
	}
    // Apagando o segundo TXT
    //$z->deleteName('teste/outro.txt');

    // Salvando o arquivo
    $z_evento->close();

	//SETANDO OS HEADERS NECESSARIOS
	// Enviando para o cliente fazer download
	// Configuramos os headers que serão enviados para o browser
	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename="'.$nome_arquivo_evento.'"');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($nome_arquivo_evento));
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Expires: 0');
	// Envia o arquivo para o cliente
	//echo $nome_arquivo;
	ob_end_clean(); //essas duas linhas antes do readfile
	flush();

	readfile($nome_arquivo_evento);

	//ABRINDO O ARQUIVO
}
else
{
    echo 'Erro: '.$criou_evento;
}

/**********************************************************************/

//PJ
if(isset($_GET['idPj']))
{
	$idPj = $_GET['idPj'];

	$sql_pj = "SELECT * FROM upload_arquivo WHERE idPessoa = '$idPj' AND idTipoPessoa = '2' AND publicado = '1'";
	$query_pj = mysqli_query($con,$sql_pj);
	$data = date('YmdHis');
	$nome_arquivo_pj = $data.".zip";

	// Criando o objeto
	$z_pj = new ZipArchive();

	// Criando o pacote chamado "teste.zip"
	$criou_pj = $z_pj->open($nome_arquivo_pj, ZipArchive::CREATE);
	if ($criou_pj === true)
	{
	    // Criando um diretorio chamado "teste" dentro do pacote
	    //$z->addEmptyDir('teste');

	    // Criando um TXT dentro do diretorio "teste" a partir do valor de uma string
	    //$z->addFromString('teste/texto.txt', 'Conteúdo do arquivo de Texto');

	    // Criando outro TXT dentro do diretorio "teste"
	    //$z->addFromString('teste/outro.txt', 'Outro arquivo');

	    // Copiando um arquivo do HD para o diretorio "teste" do pacote
		while($arquivo = mysqli_fetch_array($query_pj))
		{
			$file = $path.$arquivo['arquivo'];
			$file2 = $arquivo['arquivo'];
	    	$z_pj->addFile($file, $file2);
		}
	    // Apagando o segundo TXT
	    //$z->deleteName('teste/outro.txt');

	    // Salvando o arquivo
	    $z_pj->close();

		//SETANDO OS HEADERS NECESSARIOS
		// Enviando para o cliente fazer download
		// Configuramos os headers que serão enviados para o browser
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="'.$nome_arquivo_pj.'"');
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($nome_arquivo_pj));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');
		// Envia o arquivo para o cliente
		//echo $nome_arquivo;
		ob_end_clean(); //essas duas linhas antes do readfile
		flush();

		readfile($nome_arquivo_pj);

		//ABRINDO O ARQUIVO
	}
	else
	{
	    echo 'Erro: '.$criou;
	}
}

/**********************************************************************/

//PF
$idPf = $_GET['idPf'];

$sql_idPf = "SELECT * FROM upload_arquivo WHERE idPessoa = '$idPf' AND idTipoPessoa = '1' AND publicado = '1'";
$query_idPf = mysqli_query($con,$sql_idPf);
$data = date('YmdHis');
$nome_arquivo_idPf = $data.".zip";

$z_idPf = new ZipArchive();

$criou_idPf = $z->open($nome_arquivo_idPf, ZipArchive::CREATE);
if ($criou_idPf === true)
{
	while($arquivo = mysqli_fetch_array($query_idPf))
	{
		$file = $path.$arquivo['arquivo'];
		$file2 = $arquivo['arquivo'];
    	$z_idPf->addFile($file, $file2);
	}
    $z_idPf->close();

	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename="'.$nome_arquivo_idPf.'"');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($nome_arquivo_idPf));
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Expires: 0');
	ob_end_clean(); //essas duas linhas antes do readfile
	flush();

	readfile($nome_arquivo_idPf);
}
else
{
    echo 'Erro: '.$criou_idPf;
}

?>