<?php

//require '../include/';
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

  
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$id_ped.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";

  
	echo 
		"<p>(A)</p>".
		"<p align='center'><strong>CONTRATADO</strong></p>".
		"<p><i>(Quando se tratar de grupo, o líder do grupo)</i></p>".
		"<p><strong>Nome:</strong> ".$Nome."</p>".
		"<p><strong>Nome Artístico:</strong> ".$NomeArtistico."</p>".
		"<p><strong>Estado Civil:</strong> ".$EstadoCivil."</p>".
		"<p><strong>Nacionalidade:</strong> ".$Nacionalidade."</p>".
		"<p><strong>CCM:</strong> ".$CCM."</p>".
		"<p><strong>RG:</strong> ".$RG."</p>".
		"<p><strong>CPF:</strong> ".$CPF."</p>".
		"<p><strong>OMB:</strong> ".$OMB."</p>".
		"<p><strong>DRT:</strong> ".$DRT."</p>".		
		"<p><strong>Função:</strong> ".$Funcao."</p>".
		"<p><strong>Endereço:</strong> ".$Endereco."</p>".
		"<p><strong>Telefone:</strong> ".$Telefones."</p>".
		"<p><strong>E-mail:</strong> ".$Email."</p>".
		"<p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> ".$INSS."</p>".
		"<p><strong>Data de Nasc.:</strong> ".$DataNascimento."</p>".
		"<p>&nbsp;</p>".
		"<p>(B)</p>".
		"<p align='center'><strong>PROPOSTA</strong></p>".
		"<p align='right'>".$ano."-".$id_ped."</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Objeto:</strong> ".$Objeto."</p>".
		"<p><strong>Data / Período:</strong> ".$Periodo." - conforme cronograma</p>".
		"<p><strong>Local:</strong> SMC e Equipamentos Sob Sua Supervisão (SMC)</p>".
		"<p><strong>Valor:</strong> ".$ValorGlobal." (".$ValorPorExtenso.")</p>".
		"<p><strong>Forma de Pagamento:</strong> ".$FormaPagamento."</p>".
		"<p><strong>Justificativa:</strong>Realizar o trabalho de pesquisa, mapeamento, diagnóstico, sensibilização e divulgação do Programa Vocacional, o que culminara no levantamento de dados para a implementação do Programa de forma mais efetiva em 2018. A contratada irá atuar na cidade de São
		Paulo, em especial nos locais onde já existe o programa, sistematizará as informações em relatórios que serão entregues para a Supervisão de Formação na conclusão do trabalho.</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p>___________________________</p>".
		"<p>".$Nome."</p>".
		"<p>RG: ".$RG."</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".

		"<p>(C)</p>".
		"<p align='center'><strong>OBSERVAÇÃO</strong></p>".
		"<p>Declaro que não tenho débitos perante as fazendas públicas, federal, estadual e, em especial perante a Prefeitura do Município de São Paulo.</p>".
		"<p>Declaro que estou ciente da penalidade de multa de 10% (dez por cento) para casos de inexecução parcial do ajuste, e de 30% (trinta por cento) para casos de inexecução total do ajuste.</p>".
		"<p>Além das multas acima especificadas, multa de 1% (um por cento) por dia pelo atraso na entrega do Relatório.</p>".		
		"<p>As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria.</p>".
		"<p>Declaro estar ciente que a quitação do contrato está condicionada a apresentação do Relatório Final / Objeto do Contrato.</p>".
		"<p>Em razão de atraso na entrega do material, estarei sujeito a:</p>".
		"<p>Até 20 dias: multa de 0,5% (meio por cento) por dia sobre o valor correspondente ao material entregue com atraso;</p>".
		"<p>Superior a 20 dias: a unidade requisitante será consultada para manifestação sobre o interesse em receber o material com atraso. Em caso positivo, o atraso máximo poderá ser de ate mais 20 (vinte) dias e continuara incidindo a multa de 0,5% (meio por cento) por dia de atraso. Em caso negativo, será aplicada a multa correspondente para inexecução total ou parcial, conforme o caso;</p>".
		"<p>Declaro, ainda, estar ciente que do valor do serviço serão descontados os impostos cabíveis.</p>".
		"<p>Declaro, sob as penas da Lei, que não sou servidor público municipal e que não há, de minha parte, impedimento para contratar com a Prefeitura do Município de São Paulo/Secretaria Municipal de Cultura, mediante o pagamento de cachê.</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p align='center'><strong>CRONOGRAMA</strong></p>".
		"<p>Outros - Pesquisa, mapeamento, diagnóstico, sensibilização e divulgação do Programa Vocacional</p>".
		"<p><strong>Local:</strong>SMC e Equipamentos sob sua supervisão</p>".
		"<p><strong>Data / Período:</strong> ".$Periodo."</p>".
		"<p>&nbsp;</p>".
		"<p>Data: ____ / ____ / ".$ano."</p>".
		"<p>&nbsp;</p>".		
		"<p>&nbsp;</p>".
		"<p>___________________________</p>".
		"<p>".$Nome."</p>".
		"<p>RG:".$RG."</p>".
		"<p>&nbsp;</p>";
		
	echo "</body>";
echo "</html>";	
?>