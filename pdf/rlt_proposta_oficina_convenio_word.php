<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/pdf_html.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS
$conexao = bancoMysqli();

//CONSULTA
$id_ped=$_GET['id'];
$idPenalidade = $_GET['penal'];
dataProposta($id_ped);
gravaPenalidade($id_ped,$idPenalidade);
$penal = recuperaDados("sis_penalidades",$idPenalidade,"idPenalidades");
$txtPenalidade = $penal['txt'];
$ano=date('Y');

$pedido = siscontrat($id_ped);
$pessoa = siscontratDocs($pedido['IdProponente'],1);

$id = $pedido['idEvento'];
$Objeto = $pedido["Objeto"];
$Periodo = $pedido["Periodo"];
$Duracao = $pedido["Duracao"];
$CargaHoraria = $pedido["CargaHoraria"];
$Local = $pedido["Local"];
$ValorGlobal = dinheiroParaBr($pedido["ValorGlobal"]);
$ValorPorExtenso = valorPorExtenso($pedido["ValorGlobal"]);
$FormaPagamento = $pedido["FormaPagamento"];
$Justificativa = $pedido["Justificativa"];
$Fiscal = $pedido["Fiscal"];
$Suplente = $pedido["Suplente"];

$Nome = $pessoa["Nome"];
$NomeArtistico = $pessoa["NomeArtistico"];
$EstadoCivil = $pessoa["EstadoCivil"];
$Nacionalidade = $pessoa["Nacionalidade"];
$DataNascimento = exibirDataBr($pessoa["DataNascimento"]);
$RG = $pessoa["RG"];
$CPF = $pessoa["CPF"];
$CCM = $pessoa["CCM"];
$OMB = $pessoa["OMB"];
$DRT = $pessoa["DRT"];
$cbo = $pessoa["cbo"];
$Funcao = $pessoa["Funcao"];
$Endereco = $pessoa["Endereco"];
$Telefones = $pessoa["Telefones"];
$Email = $pessoa["Email"];
$INSS = $pessoa["INSS"];

$sqlParcelas = "SELECT * FROM `igsis_parcelas` WHERE idPedido = '$id_ped'";
$queryParcelas = $conexao->query($sqlParcelas);
$tempoGlobal = 0;

while ($linha = $queryParcelas->fetch_array()) {
   $tempoGlobal += $linha['horas'];
}

$convenio = utf8_decode("CONVÊNIO FEDERAL N° 849979/2017 cujo o objeto é a Contratação artística de oficinas de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da Secretaria Municipal de Cultura.");

$declaracao = [
    'bullet' => chr(149),
    'margin' => ' ',
    'indent' => 0,
    'spacer' => 0,
    'text' => [
        utf8_decode('Conheço e aceito incondicionalmente as regras do Edital n. 02/2018 - SMC/GAB de Credenciamento;'),
        utf8_decode('Em caso de seleção, responsabilizo-me pelo cumprimento da agenda acordada entre o equipamento municipal e o Oficineiro, no tocante ao local, data e horário, para a realização da Oficina. Em acordo com o previsto no convênio federal n° 849979/2017'),
        utf8_decode('Não sou servidor público municipal.'),
        utf8_decode('Estou ciente de que a contratação não gera vínculo trabalhista entre a Municipalidade e o Contratado.'),
        utf8_decode('Estou ciente da aplicação de penalidades conforme item 11 do Edital de Credenciamento nº 02/2018 SMC/GAB')
    ]
];

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$id_ped.doc");
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
<body>

    <p>(A)</p>
    <p align='center'><strong>CONTRATADO</strong></p>
    <p><i>(Quando se tratar de grupo, o líder do grupo)</i></p>
    <p><strong>Nome:</strong> <?=$Nome?></p>
    <p><strong>Nome Artístico:</strong> <?=$NomeArtistico?></p>
    <p><strong>Estado Civil:</strong> <?=$EstadoCivil?></p>
    <p><strong>Nacionalidade:</strong> <?=$Nacionalidade?></p>
    <p><strong>RG:</strong> <?=$RG?></p>
    <p><strong>CPF:</strong> <?=$CPF?></p>
    <p><strong>CCM:</strong> <?=$CCM?></p>
    <p><strong>OMB:</strong> <?=$OMB?></p>
    <p><strong>DRT:</strong> <?=$DRT?></p>
    <p><strong>Função:</strong> <?=$Funcao?></p>
    <p><strong>Endereço:</strong> <?=$Endereco?></p>
    <p><strong>Telefone:</strong> <?=$Telefones?></p>
    <p><strong>E-mail:</strong> <?=$Email?></p>
    <p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> <?=$INSS?></p>

    <br style='page-break-before: always'>
    <p>(B)</p>
    <p align='center'><strong>PROPOSTA</strong></p>
    <p align='right'><?=$ano?> - <?=$id_ped?></p>
    <p>&nbsp;</p>
    <p><strong>Objeto:</strong> <?=$Objeto?> - <strong>CONVÊNIO FEDERAL N° 849979/2017</strong> cujo o objeto é a Contratação artística de oficinas de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da Secretaria Municipal de Cultura.</p>
    <p><strong>Data / Período:</strong> <?=$Periodo?> - conforme cronograma</p>
    <p><strong>Tempo Global de Oficina:</strong> <?=$tempoGlobal?> horas</p>
    <p><strong>Carga Horária:</strong> <?=$CargaHoraria?></p>
    <p><strong>Local:</strong> <?=$Local?></p>
    <p><strong>Valor:</strong> <?=$ValorGlobal?> (<?=$ValorPorExtenso?>)</p>
    <p><strong>Forma de Pagamento:</strong> <?=$FormaPagamento?></p>
    <p><strong>Justificativa:</strong> <?=$Justificativa?></p>
    <p>&nbsp;</p>
    <p>___________________________</p>
    <p><?=$Nome?></p>
    <p>RG: <?=$RG?></p>
    <p>CPF: <?=$CPF?></p>

    <br style='page-break-before: always'>
    <p>(C)</p>
    <p align='center'><strong>EDITAL DE CREDENCIAMENTO Nº 02/2018 – SMC/GAB</strong></p>
    <p align="center"><strong>CONVÊNIO FEDERAL N° 849979/2017</strong>, cujo o objeto é a Contratação artística de oficinas de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da Secretaria Municipal de Cultura.</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p><strong>Declaro que:</strong></p>
    <ul>
        <li>Conheço e aceito incondicionalmente as regras do Edital n. 02/2018 – SMC/GAB de Credenciamento;</li>
        <li>Em caso de seleção, responsabilizo-me pelo cumprimento da agenda acordada entre o equipamento municipal e o Oficineiro, no tocante ao local, data e horário, para a realização da Oficina. Em acordo com o previsto no convênio federal n° 849979/2017</li>
        <li>Não sou servidor público municipal.</li>
        <li>Estou ciente de que a contratação não gera vínculo trabalhista entre a Municipalidade e o Contratado.</li>
        <li>Estou ciente da aplicação de penalidades conforme item 11 do Edital de Credenciamento nº 02/2018 SMC/GAB</li>
    </ul>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Data: ____ / ____ / <?=$ano?></p>
    <p>&nbsp;</p>
    <p>___________________________</p>
    <p><?=$Nome?></p>
    <p>RG: <?=$RG?></p>
    <p>CPF: <?=$CPF?></p>

    <br style='page-break-before: always'>
    <p align='center'><strong>CRONOGRAMA</strong></p>
    <p><?=$Objeto?> - CONVÊNIO FEDERAL N° 849979/2017 - Contratação artística de oficinas de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da Secretaria Municipal de Cultura</p>
    <p>&nbsp;</p>

<?php
$ocor = listaOcorrenciasContrato($id);

for($i = 0; $i < $ocor['numero']; $i++)
{
   $tipo = $ocor[$i]['tipo'];
   $dia = $ocor[$i]['data'];
   $hour = $ocor[$i]['hora'];
   $lugar = $ocor[$i]['espaco'];
   $observacao = $ocor[$i]['observacao'];

   echo "<p><strong>Tipo:</strong> ".$tipo."</p>";
   echo "<p><strong>Data/Período:</strong> ".$dia."</p>";
   echo "<p><strong>Horário:</strong> ".$hour."</p>";
   echo "<p><strong>Local:</strong> ".$lugar."</p>";
   echo "<p><strong>Observação:</strong> ".$observacao."</p>";
   echo "<p>&nbsp;</p>";

}
?>

    <p>&nbsp;</p>
    <p>___________________________</p>
    <p><?=$Nome?></p>
    <p>RG:<?=$RG?></p>
    <p>CPF:<?=$CPF?></p>
    <p>&nbsp;</p>

    </body>
</html>