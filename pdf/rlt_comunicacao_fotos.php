<?php

// Definimos o nome do arquivo que será exportado
$arquivo = 'planilha.xls';

//Consulta
 require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
$con = bancoMysqli();
$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '4' AND foto = 1 ORDER BY idCom DESC";
$query_busca_dic = mysqli_query($con,$sql_busca_dic);
while($evento = mysqli_fetch_array($query_busca_dic))
{ 
	$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
	$nome = recuperaUsuario($event['idUsuario']);
	$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);
						
						


// Criamos uma tabela HTML com o formato da planilha
$html = '';
$html .= '<table>';
$html .= '<tr>';
$html .= '<td colspan="3">Planilha teste</tr>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td><b>Coluna 1</b></td>';
$html .= '<td><b>Coluna 2</b></td>';
$html .= '<td><b>Coluna 3</b></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>'.$evento['ig_evento_idEvento'].'</td>';
$html .= '<td>'.$evento['nomeEvento'].'</td>';
$html .= '<td>'.$nome['nomeCompleto'].'</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>L2C1</td>';
$html .= '<td>L2C2</td>';
$html .= '<td>L2C3</td>';
$html .= '</tr>';
$html .= '</table>';

}

// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );


// Envia o conteúdo do arquivo
echo $html;
exit;

// Definimos o nome do arquivo que será exportado
$arquivo = 'planilha2.xls';

//Consulta
 require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
$con = bancoMysqli();
$sql_busca_dic = "SELECT * FROM ig_comunicacao WHERE idInstituicao = '4' AND foto = 1 ORDER BY idCom DESC";
$query_busca_dic = mysqli_query($con,$sql_busca_dic);
while($evento = mysqli_fetch_array($query_busca_dic))
{ 
	$event = recuperaDados("ig_evento",$evento['ig_evento_idEvento'],"idEvento");
	$nome = recuperaUsuario($event['idUsuario']);
	$chamado = recuperaAlteracoesEvento($evento['ig_evento_idEvento']);
						
						


// Criamos uma tabela HTML com o formato da planilha
$html = '';
$html .= '<table>';
$html .= '<tr>';
$html .= '<td colspan="3">Planilha teste</tr>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td><b>Coluna 1</b></td>';
$html .= '<td><b>Coluna 2</b></td>';
$html .= '<td><b>Coluna 3</b></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>'.$evento['ig_evento_idEvento'].'</td>';
$html .= '<td>'.$evento['nomeEvento'].'</td>';
$html .= '<td>'.$nome['nomeCompleto'].'</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>L2C1</td>';
$html .= '<td>L2C2</td>';
$html .= '<td>L2C3</td>';
$html .= '</tr>';
$html .= '</table>';

}

// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );


// Envia o conteúdo do arquivo
echo $html;
exit;
?>