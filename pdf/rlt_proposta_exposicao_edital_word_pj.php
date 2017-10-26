<?php

//require '../include/';
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli();


//CONSULTA  
//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];
$idPenalidade = $_GET['penal'];
dataProposta($id_ped);
gravaPenalidade($id_ped,$idPenalidade);
$penal = recuperaDados("sis_penalidades",$idPenalidade,"idPenalidades");
$ano=date('Y');
$dataAtual = date("d/m/Y");


$pedido = siscontrat($id_ped);

$pj = siscontratDocs($pedido['IdProponente'],2);
$ex = siscontratDocs($pedido['IdExecutante'],1);
$rep01 = siscontratDocs($pj['Representante01'],3);
$rep02 = siscontratDocs($pj['Representante02'],3);

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

$codPed = "";

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
  

 

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$id_ped.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";

  
	echo 
		"<p>(A)</p>".
		"<p align='center'><strong>CONTRATADO</strong></p>".
		"<p><i>(Quando se tratar de grupo, o líder do grupo)</i></p>".
		"<p><strong>Nome:</strong> ".$exNome."</p>".
		"<p><strong>Nome Artístico:</strong> ".$exNomeArtistico."</p>".
		"<p><strong>Estado Civil:</strong> ".$exEstadoCivil."</p>".
		"<p><strong>Nacionalidade:</strong> ".$exNacionalidade."</p>".
		"<p><strong>RG:</strong> ".$exRG."</p>".
		"<p><strong>CPF:</strong> ".$exCPF."</p>".
		"<p><strong>CCM:</strong> ".$exCCM."</p>".
		"<p><strong>OMB:</strong> ".$exOMB."</p>".
		"<p><strong>DRT:</strong> ".$exDRT."</p>".		
		"<p><strong>Função:</strong> ".$exFuncao."</p>".
		"<p><strong>Endereço:</strong> ".$exEndereco."</p>".
		"<p><strong>Telefone:</strong> ".$exTelefones."</p>".
		"<p><strong>E-mail:</strong> ".$exEmail."</p>".
		"<p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> ".$exINSS."</p>".
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
		"<p>".$rep01Nome."</p>".
		"<p>".$rep01RG."</p>".
		"<p>&nbsp;</p>";
		if($rep02Nome AND $rep02RG != '')
		{
		"<p>".$rep02Nome."</p>";
		"<p>".$rep02RG."</p>";
		}
		echo
		"<p>&nbsp;</p>".
		"<p>(C)</p>".
		"<p align='center'><strong>PESSOA JURÍDICA</strong></p>".
		"<p><i>(Empresário exclusivo SE FOR O CASO)</i></p>".
		"<p><strong>Nome da Empresa:</strong> ".$pjRazaoSocial."</p>".
		"<p><strong>CCM:</strong> ".$pjCCM."</p>".
		"<p><strong>CNPJ:</strong> ".$pjCNPJ."</p>".
		"<p><strong>Endereço:</strong> ".$pjEndereco."</p>".
		"<p><strong>Telefone:</strong> ".$pjTelefones."</p>".
		"<p><strong>Email:</strong> ".$pjEmail."</p>".
		"<p>&nbsp;</p>".
		"<p><strong>Representante:</strong> ".$rep01Nome."</p>".
		"<p><strong>Estado Civil:</strong> ".$rep01EstadoCivil."</p>".
		"<p><strong>Nacionalidade:</strong> ".$rep01Nacionalidade."</p>".
		"<p><strong>RG:</strong> ".$rep01RG."</p>".
		"<p><strong>CPF:</strong> ".$rep01CPF."</p>".
		"<p>&nbsp;</p>";
		if($rep02Nome AND $rep02EstadoCivil AND $rep02Nacionalidade AND $rep02RG AND $rep02CPF != '')
		{
		"<p><strong>Representante:</strong> ".$rep02Nome."</p>";
		"<p><strong>Estado Civil:</strong> ".$rep02EstadoCivil."</p>";
		"<p><strong>Nacionalidade:</strong> ".$rep02Nacionalidade."</p>";
		"<p><strong>RG:</strong> ".$rep02RG."</p>";
		"<p><strong>CPF:</strong> ".$rep02CPF."</p>";
		}
		echo
		"<p>&nbsp;</p>".
		"<p>(D)</p>".
		"<p align='center'><strong>OBSERVAÇÃO</strong></p>".
		"<p>As idéias e opiniões expressas durante as apresentações artísticas e culturais não representam a posição da Secretaria Municipal de Cultura, sendo os artistas e seus representantes os únicos e exclusivos responsáveis pelo conteúdo de suas manifestações, ficando a Municipalidade de São Paulo com direito de regresso sobre os mesmos, inclusive em caso de indenização por dano material, moral ou à imagem de terceiros.</p>".
		"<p>Os registros das atividades e ações poderão ser utilizados para fins institucionais de divulgação, promoção e difusão do Programa e da Secretaria Municipal de Cultura.</p>".
		"<p>&nbsp;</p>".
		"<p align='center'><strong>DECLARAÇÕES</strong></p>".
		"<p>Declaramos que não temos débitos perante as Fazendas Públicas, Federal, Estadual e, em especial perante a Prefeitura do Município de São Paulo.</p>".
		"<p>Declaramos que estamos cientes e de acordo com todas as regras do [INSIRA O TÍTULO DO EDITAL AQUI. Ex: Edital de Concurso Programa de Exposições 2016].</p>".
		"<p>Declaramos que estamos cientes da aplicação das penalidades previstas [INSIRA A CLÁUSULA DA PENALIDADE AQUI. Ex: na cláusula 10 do Edital de Concurso Programa de Exposições 2016.]</p>".
		"<p>As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria.</p>".
		"<p>Declaramos que estamos cientes que do valor do serviço serão descontados os impostos cabíveis.".
		"<p>Todas as informações precedentes são formadas sob as penas da Lei.</p>".
		"<p>&nbsp;</p>".
		"<p>Data: ____ / ____ / ".$ano."</p>".
		"<p>&nbsp;</p>".
		"<p>___________________________</p>".
		"<p>".$rep01Nome."</p>".
		"<p>RG: ".$rep01RG."</p>".
		"<p>&nbsp;</p>";
		if ($rep02Nome AND $rep02RG != '')
		{
		echo "<p>___________________________</p>";
		echo "<p>".$rep02Nome."</p>";
		echo "<p>RG: ".$rep02RG."</p>";
		}
		echo		
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
		"<p>".$rep01Nome."</p>".
		"<p>RG:".$rep01RG."</p>".
		"<p>&nbsp;</p>";
		if ($rep02Nome AND $rep02RG != '')
		{
		echo "<p>___________________________</p>";
		echo "<p>".$rep02Nome."</p>";
		echo "<p>RG: ".$rep02RG."</p>";
		}	
	echo "</body>";
echo "</html>";	
?>