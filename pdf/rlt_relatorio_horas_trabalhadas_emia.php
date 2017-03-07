<?php 
	session_start();
	   @ini_set('display_errors', '1');
	error_reporting(E_ALL); 	
   
   // INSTALAÇÃO DA CLASSE NA PASTA FPDF.
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");
   require_once("../funcoes/funcoesFormacao.php");

   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli(); 
  
//CONSULTA 

$id_ped=$_GET['id'];
$pedido = siscontrat($id_ped);
$id_parcela = $_GET['parcela'];

$codPed = $id_ped;
$objeto = $pedido["Objeto"];
$local = $pedido["Local"];
$justificativa = $pedido["Justificativa"];

$pedido_pessoa = siscontratDocs($pedido['IdProponente'],1);
$nome = $pedido_pessoa["Nome"];
$cpf = $pedido_pessoa["CPF"];

//$horas = retornaCargaHoraria($id_ped,$id_parcela);

$parcelamento = retornaParcelaPagamento($id_ped);
$periodoParcela = $parcelamento[$id_parcela]['periodo']; 
$horas = $parcelamento[$id_parcela]['horas'];
$dataFinal = $parcelamento[$id_parcela]['vigencia_final'];


$dataAtual = date("d/m/Y");
$ano=date('Y');

$pedido_pessoa = siscontratDocs($pedido['IdProponente'],1);
$nome = $pedido_pessoa["Nome"];
$cpf = $pedido_pessoa["CPF"];

$formacao = pdfFormacao($id_ped);
$cargo = $formacao["Cargo"];
$programa = $formacao["Programa"];
$linguagem = $formacao["linguagem"];

$retornaMes = retornaMesExtenso(exibirDataMysql($dataFinal));

$dataAtual = date("d/m/Y");
$ano=date('Y');

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Relatorio_Horas_trabalhadas_$ano-$id_ped.doc");

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
<style type='text/css'>
.style_01 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
@page WordSection1
	{size:842.0pt 21.0cm;
	mso-page-orientation:landscape;
	margin:15.9pt 26.1pt 15.9pt 36.0pt;
	mso-header-margin:35.45pt;
	mso-footer-margin:35.45pt;
	mso-paper-source:0;}
div.WordSection1
	{page:WordSection1;}
table {
    border-collapse: collapse;
}
</style>

<div class=WordSection1>

<p align="center"><strong>PREFEITURA  MUNICIPAL DE SÃO PAULO</strong><br />
<strong>SECRETARIA  MUNICIPAL DE CULTURA – ESCOLA MUNICIPAL DE INICIAÇÃO ARTÍSTICA</strong><br /></p>
<strong><?php echo $nome; ?></strong><br />
<strong>Oficinas de <?php echo $cargo; ?> </strong>- de segunda-feira a sexta-feira, em dias e horários determinados pela direção da Escola.<br />
<strong>Período: </strong> <?php echo $periodoParcela; ?>
<table width="100%" border="1"><br />
  <tr>
    <td align="center" width="10%"><strong>DIA</strong></td>
    <td align="center" width="27%"><strong>CARGA HORÁRIA</strong></td>
    <td align="center" width="50%"><strong>TURMA / ATIVIDADE</strong></td>
    <td align="center" width="13%"><strong>ASSINATURA</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><strong> < total carga horária</strong></td>
    <td>&nbsp;</td>
  </tr>
</table>

<table width="100%" border="0">
  <tr>
    <td align="center" width="34%" height="70" valign="bottom">_______________________________________</td>
	</td>  
  </tr>
  <tr>
    <td align="center" width="33%" valign="top">Luciana Schwinden</td>
	<tr><center>Diretora</center></tr>
	<tr><center>RF 791.181.5-4</center></tr>
  </tr>
</table></div>
</body>
</html>
