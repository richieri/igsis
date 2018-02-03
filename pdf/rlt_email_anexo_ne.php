<?php 
	
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS 
$conexao = bancoMysqli();

//CONSULTA 
$id_ped=$_GET['id'];

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],1);

dataPagamento($id_ped);

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$NumeroProcesso = $pedido["NumeroProcesso"];

$ano=date('Y');

$codPed = "";


// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - EmailAnexoNE.doc"); 

?>

<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>

<p align="justify">Boa tarde,</p>
<p align="justify">Tendo em vista a apresentação <?php echo $Objeto?>, encaminhamos em anexo a Nota de Empenho e o Anexo da Nota de Empenho da referida contratação (documento esse que é o contrato devidamente formalizado) para conhecimento e fins de arquivamento da contratada.</p>
<p align="justify">Informamos que em breve será enviado o Kit de Pagamento.</p>
<p align="justify">Atenciosamente,</p>
<p>Contratos Artísticos / Pagamento</p>
<p>Secretaria Municipal da Cultura</p>
<p>Tel: (11) 3397-0191</p>
<p>&nbsp;</p>
</body>
</html>