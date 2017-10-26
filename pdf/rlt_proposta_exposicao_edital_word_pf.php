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
		"<p><strong>RG:</strong> ".$RG."</p>".
		"<p><strong>CPF:</strong> ".$CPF."</p>".
		"<p><strong>CCM:</strong> ".$CCM."</p>".
		"<p><strong>OMB:</strong> ".$OMB."</p>".
		"<p><strong>DRT:</strong> ".$DRT."</p>".		
		"<p><strong>Função:</strong> ".$Funcao."</p>".
		"<p><strong>Endereço:</strong> ".$Endereco."</p>".
		"<p><strong>Telefone:</strong> ".$Telefones."</p>".
		"<p><strong>E-mail:</strong> ".$Email."</p>".
		"<p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> ".$INSS."</p>".
		"<p>&nbsp;</p>".
		"<p>(B)</p>".
		"<p align='center'><strong>PROPOSTA</strong></p>".
		"<p align='right'>".$ano."-".$id_ped."</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Objeto:</strong> ".$Objeto."</p>".
		"<p><strong>Data / Período:</strong> ".$Periodo." - conforme cronograma</p>".
		"<p><strong>Tempo Aproximado de Duração do Espetáculo:</strong> ".$Duracao."utos</p>".
		"<p><strong>Carga Horária:</strong> ".$CargaHoraria."utos</p>".
		"<p><strong>Local:</strong> ".$Local."</p>".
		"<p><strong>Valor:</strong> ".$ValorGlobal." (".$ValorPorExtenso.")</p>".
		"<p><strong>Forma de Pagamento:</strong> ".$FormaPagamento."</p>".
		"<p><strong>Justificativa:</strong> ".$Justificativa."</p>".
		"<p>&nbsp;</p>".
		"<p>&nbsp;</p>".
		"<p>(C)</p>".
		"<p align='center'><strong>OBSERVAÇÃO</strong></p>".
		"<p>As idéias e opiniões expressas durante as apresentações artísticas e culturais não representam a posição da Secretaria Municipal de Cultura, sendo os artistas e seus representantes os únicos e exclusivos responsáveis pelo conteúdo de suas manifestações, ficando a Municipalidade de São Paulo com direito de regresso sobre os mesmos, inclusive em caso de indenização por dano material, moral ou à imagem de terceiros.</p>".
		"<p>Os registros das atividades e ações poderão ser utilizados para fins institucionais de divulgação, promoção e difusão do Programa e da Secretaria Municipal de Cultura.</p>".
		"<p>&nbsp;</p>".
		"<p align='center'><strong>DECLARAÇÕES</strong></p>".
		"<p>Declaro que não tenho débitos perante as fazendas públicas, federal, estadual e, em especial perante a Prefeitura do Município de São Paulo.</p>".
		"<p>Declaro que estou ciente e de acordo com todas as regras do [INSIRA O TÍTULO DO EDITAL AQUI. Ex: Edital de Concurso Programa de Exposições 2016].</p>".
		"<p>Declaro que estou ciente da aplicação das penalidades previstas na cláusula [INSIRA A CLÁUSULA DA PENALIDADE AQUI. Ex: na cláusula 10 do Edital de Concurso Programa de Exposições 2016.].As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria.</p>".
		"<p>Declaro, ainda, estar ciente que do valor do serviço serão descontados os impostos cabíveis.</p>".
		"<p>Declaro, sob as penas da Lei, que não sou servidor público municipal e que não há, de minha parte, impedimento para contratar com a [INSIRA A UNIDADE AQUI. Ex: Prefeitura do Município de São Paulo/Secretaria Municipal de Cultura/Centro Cultural São Paulo], mediante o pagamento de cachê.</p>".
		"<p>Todas as informações precedentes são formadas sob as penas da Lei.</p>".
		"<p>&nbsp;</p>".
		"<p>Data: ____ / ____ / ".$ano."</p>".
		"<p>&nbsp;</p>".
		"<p>___________________________</p>".
		"<p>".$Nome."</p>".
		"<p>RG: ".$RG."</p>".
		"<p>&nbsp;</p>".
		"<p align='center'><strong>CRONOGRAMA</strong></p>".
		"<p>".$Objeto."</p>".
		"<p>&nbsp;</p>";
		
		$ocor = listaOcorrenciasContrato($id);

			for($i = 0; $i < $ocor['numero']; $i++)
			{			
			$tipo = $ocor[$i]['tipo'];
			$dia = $ocor[$i]['data'];
			$hour = $ocor[$i]['hora'];
			$lugar = $ocor[$i]['espaco'];
			
		echo "<p><strong>Tipo:</strong> ".$tipo."</p>";
		echo "<p><strong>Data/Período:</strong> ".$dia."</p>";
		echo "<p><strong>Horário:</strong> ".$hour."</p>";
		echo "<p><strong>Local:</strong> ".$lugar."</p>";
		echo "<p>&nbsp;</p>";

			}
		echo	
		"<p>&nbsp;</p>".
		"<p>___________________________</p>".
		"<p>".$Nome."</p>".
		"<p>RG:".$RG."</p>".
		"<p>&nbsp;</p>";
		
	echo "</body>";
echo "</html>";	
?>