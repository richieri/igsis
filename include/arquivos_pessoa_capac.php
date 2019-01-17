<?php

@ini_set('display_errors', '1');
error_reporting(E_ALL); 

require_once "../funcoes/funcoesConecta.php";
$path = "../../igsiscapac/uploadsdocs/";

function comparaArquivoOficineiro($idPessoa)
{
    $con = bancoMysqliProponente();

    $sql = "SELECT * FROM upload_lista_documento as list
                INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
                WHERE arq.idPessoa = '$idPessoa'
                AND arq.idTipoPessoa = '1'
                AND arq.publicado = '1'";
    $sqlOficineiro = "SELECT * FROM upload_lista_documento as list
                        INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
                        WHERE arq.idPessoa = '$idPessoa'
                        AND arq.idTipoPessoa = '4'
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
$idPessoa = $_GET['idPessoa'];

if (count($_GET['tipo']) == 2)
{
    $a = 1;
    $query = comparaArquivoOficineiro($idPessoa);
}
else
{
    $a = 0;
    $tipo = implode('',$_GET['tipo']);
    $sql = "SELECT * FROM upload_arquivo WHERE idPessoa = '$idPessoa' AND idTipoPessoa = '$tipo' AND publicado = '1'";
    $query = mysqli_query($con,$sql);
}

$data = date('YmdHis');
$nome_arquivo = $data.".zip";


// Criando o objeto
$z = new ZipArchive();

// Criando o pacote chamado "teste.zip"
$criou = $z->open($nome_arquivo, ZipArchive::CREATE);
if ($criou === true)
{
    // Criando um diretorio chamado "teste" dentro do pacote
    //$z->addEmptyDir('teste');

    // Criando um TXT dentro do diretorio "teste" a partir do valor de uma string
    //$z->addFromString('teste/texto.txt', 'Conteúdo do arquivo de Texto');

    // Criando outro TXT dentro do diretorio "teste"
    //$z->addFromString('teste/outro.txt', 'Outro arquivo');

    // Copiando um arquivo do HD para o diretorio "teste" do pacote
    foreach ($query as $arquivo)
	{
		$file = $path.$arquivo['arquivo'];
		$file2 = $arquivo['arquivo'];
    	$z->addFile($file, $file2);
	}
    // Apagando o segundo TXT
    //$z->deleteName('teste/outro.txt');

    // Salvando o arquivo
    $z->close();

	//SETANDO OS HEADERS NECESSARIOS
	// Enviando para o cliente fazer download
	// Configuramos os headers que serão enviados para o browser
	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename="'.$nome_arquivo.'"');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($nome_arquivo));
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Expires: 0');
	// Envia o arquivo para o cliente
	//echo $nome_arquivo;
	ob_end_clean(); //essas duas linhas antes do readfile
	flush();

	readfile($nome_arquivo);

	//ABRINDO O ARQUIVO
}
else
{
    echo 'Erro: '.$criou;
}
?>