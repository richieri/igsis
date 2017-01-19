<?php

//require '../include/';
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");
   require_once("../funcoes/funcoesFormacao.php");

//CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli();


//CONSULTA  
$id_ped=$_GET['id'];

$ano=date('Y');

$dataAtual = date("d/m/Y");



$pedido = siscontrat($id_ped);
$pj = siscontratDocs($pedido['IdProponente'],2);
$ex = siscontratDocs($pedido['IdExecutante'],1);
$rep01 = siscontratDocs($pj['Representante01'],3);
$rep02 = siscontratDocs($pj['Representante02'],3);

$setor = $pedido["Setor"];

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
$rfFiscal = $pedido["RfFiscal"];
$Suplente = $pedido["Suplente"];
$rfSuplente = $pedido["RfSuplente"];
$NumeroProcesso = $pedido["NumeroProcesso"];

//PessoaJuridica

$pjRazaoSocial = $pj["Nome"];
$pjNomeArtistico = $pj["NomeArtistico"];
$pjEstadoCivil = $pj["EstadoCivil"];
$pjNacionalidade = $pj["Nacionalidade"];
$pjRG = $pj["RG"];
$pjCPF = $pj["CPF"];
$pjCCM = $pj["CCM"];
$pjOMB = $pj["OMB"];
$pjDRT = $pj["DRT"];
$pjFuncao = $pj["Funcao"];
$pjEndereco = $pj["Endereco"];
$pjTelefones = $pj["Telefones"];
$pjEmail = $pj["Email"];
$pjINSS = $pj["INSS"];
$pjCNPJ = $pj['CNPJ'];


// Executante

$exNome = $ex["Nome"];
$exNomeArtistico = $ex["NomeArtistico"];
$exEstadoCivil = $ex["EstadoCivil"];
$exNacionalidade = $ex["Nacionalidade"];
$exRG = $ex["RG"];
$exCPF = $ex["CPF"];
$exCCM = $ex["CCM"];
$exOMB = $ex["OMB"];
$exDRT = $ex["DRT"];
$exFuncao = $ex["Funcao"];
$exEndereco = $ex["Endereco"];
$exTelefones = $ex["Telefones"];
$exEmail = $ex["Email"];
$exINSS = $ex["INSS"];

// Representante01

$rep01Nome = $rep01["Nome"];
$rep01NomeArtistico = $rep01["NomeArtistico"];
$rep01EstadoCivil = $rep01["EstadoCivil"];
$rep01Nacionalidade = $rep01["Nacionalidade"];
$rep01RG = $rep01["RG"];
$rep01CPF = $rep01["CPF"];
$rep01CCM = $rep01["CCM"];
$rep01OMB = $rep01["OMB"];
$rep01DRT = $rep01["DRT"];
$rep01Funcao = $rep01["Funcao"];
$rep01Endereco = $rep01["Endereco"];
$rep01Telefones = $rep01["Telefones"];
$rep01Email = $rep01["Email"];
$rep01INSS = $rep01["INSS"];


// Representante02

$rep02Nome = $rep02["Nome"];
$rep02NomeArtistico = $rep02["NomeArtistico"];
$rep02EstadoCivil = $rep02["EstadoCivil"];
$rep02Nacionalidade = $rep02["Nacionalidade"];
$rep02RG = $rep02["RG"];
$rep02CPF = $rep02["CPF"];
$rep02CCM = $rep02["CCM"];
$rep02OMB = $rep02["OMB"];
$rep02DRT = $rep02["DRT"];
$rep02Funcao = $rep02["Funcao"];
$rep02Endereco = $rep02["Endereco"];
$rep02Telefones = $rep02["Telefones"];
$rep02Email = $rep02["Email"];
$rep02INSS = $rep02["INSS"];
  
 ?>
 
 
<html>
	<head> 
		<meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">
		<style>
			.texto{
				width: 900px;
				border: solid;
				padding: 20px;
				font-size: 13px;
				font-family: Arial, Helvetica, sans-serif;
				text-align:justify;
			}
		</style>
		<script src="include/dist/ZeroClipboard.min.js"></script>
	</head>
	<body>
  
<?php

	$sei = 
		"<p align='center'><strong>TERMO DE DOAÇÃO</strong></p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p>A PREFEITURA DO MUNICÍPIO DE SÃO PAULO, por intermédio da SECRETARIA MUNICIPAL DE CULTURA, neste ato representada  André Sturm, Secretário Municipal de Cultura, doravante denominada donatária e ".$pjRazaoSocial.", CNPJ: ".$pjCNPJ.", localizada na ".$pjEndereco.", representada por ".$rep01Nome.", RG: ".$rep01RG.", CPF: ".$rep01CPF.", denominado/a doador/a, com fundamento no artigo 1º do Decreto Municipal nº 40.384/2001, resolvem, firmar o presente termo de doação, mediante as seguintes cláusulas e condições:</p>".
		"<p>&nbsp;</p>".
		"<p><strong>CLÁUSULA 1 - OBJETO</strong></p>".
		"<p>Doação de serviços artísticos para o evento ".$Objeto.", no ".$Local.", no período ".$Periodo.", conforme proposta e cronograma constantes no processo eletrônico.</p>".
		"<p>&nbsp;</p>".
		"<p><strong>CLÁUSULA 2 - OBRIGAÇÕES DO DOADOR</strong></p>".
		"<p>O/a doador/a compromete-se a:</p>".
		"<p>2.1. Executar os serviços no período e horário constantes na proposta de doação, garantindo sua qualidade e adequação aos propósitos do evento.</p>".
		"<p>2.2 Fazer menção dos créditos da Prefeitura da Cidade de São Paulo, Secretaria Municipal de Cultura, Centro Cultural São Paulo, em toda divulgação, escrita ou falada, realizada sobre o evento programado.</p>".
		"<p>&nbsp;</p>".
		"<p><strong>CLÁUSULA 3 - DOS DIREITOS E ENCARGOS DA DONATÁRIA</strong></p>".
		"<p>A donatária:</p>".
		"<p>3.1. Compete o fornecimento da sonorização necessária à realização de espetáculos e dos equipamentos de iluminação disponíveis no local do evento, assim como providências quanto à divulgação de praxe (confecção de cartaz a ser afixado no equipamento cultural e encaminhamento de release à mídia impressa e televisiva).</p>".
		"<p>3.2. Exercer a coordenação e comunicações necessárias, bem como dirimir dúvidas, para o bom cumprimento das obrigações descritas neste termo.</p>".
		"<p>&nbsp;</p>".
		"<p><strong>CLÁUSULA 4 - DISPOSIÇÕES GERAIS</strong></p>".
		"<p>4.1. O/a doador/a, nos termos do artigo 8° do Decreto Municipal n° 40.384/01, declara, sob as penas da lei, que não está em débito com a Fazenda Municipal.</p>".
		"<p>4.2. A presente doação não acarretará ônus para a Municipalidade.</p>".
		"<p>4.3. A donatária fica autorizada a reproduzir, por processo fotográfico ou digital, e a utilizar, sem qualquer ônus, as imagens do evento realizado em anúncio, catálogo, exposição, folder e outras publicações, sem fins lucrativos, nos eventos promovidos e/ou produzidos pela Prefeitura do Município de São Paulo. Essa autorização terá validade a partir da presente assinatura e vigorará pelo prazo previsto no artigo 41 da Lei Federal nº 9.610/98.</p>".
		"<p>4.4. Nos termos do art. 6 do Decreto nº. 54.873/2014, fica designado como fiscal do contrato ".$Fiscal.", RF ".$rfFiscal." e como suplente ".$Suplente.", RF ".$rfSuplente.".</p>".
		"<p>4.5. Fica eleito o foro da Comarca da Capital, através de uma de suas varas da Fazenda Pública, para qualquer procedimento judicial oriundo do presente Termo, com a renúncia de qualquer outro, por mais especial ou privilegiado que seja.</p>".
		"<p>E por estarem justas e pactuadas firmam as Partes o presente Termo, em 4 (quatro) vias de igual teor, forma e data para um só efeito na presença das testemunhas abaixo.</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p><strong>DOADOR</strong></p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p>".$rep01Nome."</p>".
		"<p>".$rep01RG."</p>".
		"<p>".$rep01CPF."</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p><strong>TESTEMUNHAS</strong></p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>"
?>

		<div align="center">
			<div id="texto" class="texto"><?php echo $sei; ?>
			</div>
		</div>
		<p>&nbsp;</p> 
		<div align="center"><button id="botao-copiar" data-clipboard-target="texto"><img src="img/copy-icon.jpg">CLIQUE AQUI PARA COPIAR O TEXTO</button>
		</div>         
		<script>
		var client = new ZeroClipboard();
		client.clip(document.getElementById("botao-copiar"));
		client.on("aftercopy", function(){
			alert("Copiado com sucesso!");
		});
		</script>
	</body>
</html>