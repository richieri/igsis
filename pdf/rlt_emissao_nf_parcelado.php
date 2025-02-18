<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS
$conexao = bancoMysqli();

//CONSULTA
$id_ped=$_GET['id'];
$ano=date('Y');
$dataAtual = date("d/m/Y");

dataPagamento($id_ped);

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],2);
$parcelamento = retornaParcelaPagamento($id_ped);

$id_parcela = $_GET['parcela'];
$valorParcela = $parcelamento[$id_parcela]['valor'];
$ValorPorExtenso = valorPorExtenso(dinheiroDeBr($parcelamento[$id_parcela]['valor']));

$pjRazaoSocial = $pessoa['Nome'];

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];

$FormaPagamento = $pedido["FormaPagamento"];
$notaFiscal = $pedido["notaFiscal"];
$descricaoNF = $pedido["descricaoNF"];
$notaempenho = $pedido["NotaEmpenho"];
$data_entrega_empenho = exibirDataBr($pedido['EntregaNE']);
$verba = $pedido['Verba'];
$NumeroProcesso = $pedido['NumeroProcesso'];


// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI $NumeroProcesso - Parcela $id_parcela.doc");


switch($verba)
{
	case 9:
	   $cnpj = "49.269.244/0003-25";
	   $endereco = "Rua Catão, 611 – Vila Romana - CEP: 05049-000";
	break;
	case 6:
	case 10:
	case 11:
	case 12:
	case 13:
	case 14:
	case 15:
	case 16:
	case 17:
	case 18:
	case 19:
	case 33:
	case 37:
	case 63:
	case 64:
	case 65:
	case 66:
	case 67:
	case 68:
	case 69:
		$cnpj = "49.269.244/0006-78";
		$endereco = "Rua Vergueiro, 1000 - Liberdade - CEP: 01504-000";
	break;
	default:
		$cnpj = "49.269.244/0001-63";
		$endereco = "Av. São João, 473 – 11º andar - CEP: 01035-000";
	break;
}

?>
<html>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
	<body>
		<p><strong><?php echo $pjRazaoSocial ?></strong></p>
		<p>&nbsp;</p>
		<p>Segue abaixo os dados para emissão da Nota Fiscal:</p>
		<p>&nbsp;</p>
		<p><strong>Sacado:</strong> Secretaria Municipal de Cultura</p>
		<p><strong>Unidade:</strong></p>
		<p><strong>CNPJ:</strong> 49.269.244/0001-63</p>
		<p><strong>Endereço:</strong> Av. São João, 473 - 11º andar - Centro - CEP: 01035-000</p>
		<p><strong>Município:</strong> São Paulo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Estado:</strong> São Paulo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>I. Est. Nº</strong>: Isento </p>
		<p>&nbsp;</p>
		<p><strong>Nota Fiscal:</strong> <?php echo $notaFiscal?></p>
		<p align="justify"><strong>Valor:</strong> R$ <?php echo $valorParcela?> (<?php echo $ValorPorExtenso?> )</p>
		<p align="justify"><strong>Descrição:</strong> <?php echo $descricaoNF?></p>
		<p align="justify">Pagamento referente ao <?php echo $Objeto?></p>
		<p>&nbsp;</p>
		<?php
			$ocor = listaOcorrenciasContrato($id);
			for($i = 0; $i < $ocor['numero']; $i++)
			{
				$tipo = $ocor[$i]['tipo'];
				$dia = $ocor[$i]['data'];
				$hour = $ocor[$i]['hora'];
				$lugar = $ocor[$i]['espaco'];

				echo "<p align='justify'><strong>Tipo:</strong> ".$tipo."</p>";
				echo "<p align='justify'><strong>Data/Período:</strong> ".$dia."</p>";
				echo "<p align='justify'><strong>Horário:</strong> ".$hour."</p>";
				echo "<p align='justify'><strong>Local:</strong> ".$lugar."</p>";
				echo "<p>&nbsp;</p>";
			}
		?>
		<p><strong>Nota de Empenho nº:</strong> <?php echo $notaempenho?></p>
		<p><strong>Emitida em:</strong> <?php echo $data_entrega_empenho?></p>
	</body>
</html>