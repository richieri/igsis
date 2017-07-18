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
$pessoa = siscontratDocs($pedido['IdProponente'],1);

$setor = $pedido["Setor"];

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
$rfFiscal = $pedido["RfFiscal"];
$Suplente = $pedido["Suplente"];
$rfSuplente = $pedido["RfSuplente"];
$NumeroProcesso = $pedido["NumeroProcesso"];
$notaempenho = $pedido["NotaEmpenho"];
$data_entrega_empenho = exibirDataBr($pedido['EntregaNE']);
$data_emissao_empenho = exibirDataBr($pedido['EmissaoNE']);
$ingresso = dinheiroParaBr($pedido['ingresso']);
$ingressoExtenso = valorPorExtenso($pedido['ingresso']);

$grupo = grupos($id_ped);
$integrantes = $grupo["texto"];

//PessoFisica

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
		"<p align='center'><strong>ORDEM DE EXECUÇÃO DE SERVIÇOS</strong></p>".
		"<p>&nbsp;</p>".
		"<p><strong>Emanada de:</strong> Divisão Administrativa</p>".
		"<p><strong>Suporte Legal:</strong> Artigo 25, inciso III, da Lei Federal nº. 8.666/93 e alterações posteriores e artigo 1º da Lei Municipal nº. 13.278/02, nos termos dos artigos 16 e 17 do Decreto nº. 44.279/03.</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Prestador e/ou executor do serviço</strong></p>".
		"<p><strong>Nome:</strong> ".$Nome."</p>".
		"<p><strong>CPF:</strong> ".$CPF."</p>".
		"<p><strong>RG:</strong> ".$RG."</p>".
		"<p><strong>Endereço:</strong> ".$Endereco."</p>".
		"<p><strong>Estado Civil:</strong> ".$EstadoCivil."</p>".
		"<p><strong>Nacionalidade:</strong> ".$Nacionalidade."</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Serviço</strong></p>".
		"<p>Especificações: Contratação dos serviços profissionais de natureza artística de ".$Objeto.", através dos integrantes mencionados na Declaração de Exclusividade, por intermédio de ".$Nome.", CPF: ".$CPF.", para realização de evento no ".$Local.", no período ".$Periodo.", conforme proposta e cronograma.</p>".
		"<p>Fica designado como fiscal do contrato ".$Fiscal.", RF ".$rfFiscal." e como suplente ".$Suplente.", RF ".$rfSuplente.".</p>".
		"<p>&nbsp;</p>".
		"<p>Valor do Ingresso: ".$ingresso." ( ".$ingressoExtenso." ).</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Pagamento</strong></p>".
		"<p>O pagamento do cachê corresponderá à reversão integral da renda obtida na bilheteria a/o contratada/o, deduzidos os impostos e taxas pertinentes.</p>".
		"<p><strong>Penalidades</strong></p>".
		"<p>- Multa de 10% (dez por cento) sobre o valor do contrato ou sobre o valor integral da venda de todos os ingressos disponíveis por atraso de até 30 (trinta) minutos no evento. Ultrapassado esse tempo, e independentemente da aplicação de penalidade, fica a critério do equipamento da Secretaria Municipal de Cultura autorizar a realização do evento, visando evitar prejuízos à grade de programação. Não sendo autorizada a realização do evento, será considerada inexecução total do contrato, com aplicação de multa prevista por inexecução total.</p>".
		"<p>- Multa de 10% (dez por cento) para casos de infração de cláusula contratual e/ou inexecução parcial do ajuste e de 30% (trinta por cento) para casos de inexecução total do ajuste. O valor da multa será calculado sobre o valor do contrato ou sobre o valor integral da venda de todos os ingressos disponíveis.</p>".
		"<p>- Multa de 10% (dez por cento) sobre o valor do contrato ou sobre o valor integral da venda de todos os ingressos disponíveis, em função da falta de regularidade fiscal do contratado, bem como, pela verificação de que possui pendências junto ao Cadastro Informativo Municipal (CADIN).</p>".
		"<p>- As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria.</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Cancelamento</strong></p>".
		"<p>Esta O.E.S. poderá ser cancelada no interesse da administração, devidamente justificada ou em virtude da inexecução total ou parcial do serviço sem prejuízo de multa.</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Foro</strong></p>".
		"<p>Fica eleito o foro da Fazenda Pública para todo e qualquer procedimento judicial oriundo desta ordem de execução de serviços.</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Observações</strong></p>".
		"<p>- Compete à contratada a realização do espetáculo, e a fazer constar o crédito - PMSP/SECRETARIA MUNICIPAL DE CULTURA, em toda divulgação escrita ou falada, realizada sobe o espetáculo programado.</p>".
		"<p>- A empresa contratada fica sujeita ao atendimento no disposto nas Leis Municipais nÂº 10.973/9, regulamentada pelo DM 30.730/91; 11.113/91; 11.357/93; 12.975/2000 e portaria 66/SMC/2007; Leis Estaduais nº 7.844/92; Medida Provisória Federal 12.933/2013 e Lei Federal 10.741/2013.</p>".
		"<p>- A contratada é responsável por qualquer prejuízo ou dano causado ao patrimônio municipal ou a bens de terceiros que estejam sob a guarda do equipamento local de realização do evento.</p>".
		"<p>- Quaisquer outras despesas não ressalvadas aqui serão de responsabilidade da contratada, que se compromete a adotar as providências necessárias junto à OMB.</p>".
		"<p>- As providências administrativas para liberação da autorização do ECAD serão de responsabilidade da contratada, sendo que eventuais pagamento serão efetuados pela SMC.</p>".
		"<p>- A Municipalidade não é responsável por qualquer material ou equipamento que não lhe pertence utilizado no espetáculo, devendo esse material ser retirado no seu término.</p>".
		"<p>- A renda integral apurada na bilheteria, com ingressos vendidos ao preço único de ".$ingresso." ( ".$ingressoExtenso." ), será revertida a/o contratada/o, por intermédio da Empresa Brasileira de Comercialização de Ingressos Ltda, que também confeccionará os ingressos, já deduzidos os impostos e taxas pertinentes, podendo ter preços reduzidos em face de promoções realizadas pela produção do evento.</p>".
		"<p>- Compete, ainda, à Municipalidade, o fornecimento da sonorização necessária à realização de espetáculos e dos equipamentos de iluminação disponíveis no local do evento, assim como providências quanto à divulgação de praxe (confecção de cartaz a ser afixado no equipamento cultural e encaminhamento de release à mídia impressa e televisiva).</p>".
		"<p>- Serão reservados ingressos aos funcionários da PMSP, até 10% (dez por cento) da lotação da sala.</p>".
		"<p>- A/o contratada/o se compromete a realizar o espetáculo para um número mínimo de 10 (dez) pagantes.</p>".
		"<p>&nbsp;</p>".
		"<p>Aceito as condições dessa O.E.S para todos os efeitos de direito.</p>".
		"<p>&nbsp;</p>".
		"<p>".$Nome."</p>".
		"<p>".$CPF."</p>".
		"<p>&nbsp;</p>".
		"<p>Com competência delegada pela Portaria nº 19/2016 - SMC/G, determino a execução do serviço na forma desta O.E.S.</p>".
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