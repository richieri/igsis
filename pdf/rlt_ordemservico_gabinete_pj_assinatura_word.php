<?php 
	session_start();
	   @ini_set('display_errors', '1');
	error_reporting(E_ALL); 	
   
   // INSTALAÇÃO DA CLASSE NA PASTA FPDF.
	require_once("../include/lib/fpdf/fpdf.php");
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli(); 
   

class PDF extends FPDF
{
// Page header
function Header()
{
	$inst = recuperaDados("ig_instituicao",$_SESSION['idInstituicao'],"idInstituicao");	$logo = "img/".$inst['logo']; // Logo
    $this->Image($logo,20,20,50);
    // Move to the right
    $this->Cell(80);
    $this->Image('../visual/img/logo_smc.jpg',170,10);
    // Line break
    $this->Ln(20);
}

// Simple table
function Cabecalho($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data

}

// Simple table
function Tabela($header, $data)
{
    //Data
    foreach($data as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data

}


// Page footer
/*
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
*/

//INSERIR ARQUIVOS

function ChapterBody($file)
{
    // Read text file
    $txt = file_get_contents($file);
    // Arial 10
    $this->SetFont('Arial','',10);
    // Output justified text
    $this->MultiCell(0,5,$txt);
    // Line break
    $this->Ln();
}

function PrintChapter($file)
{
    $this->ChapterBody($file);
}

}


//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];

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
$rfFiscal = $pedido["RfFiscal"];
$Suplente = $pedido["Suplente"];
$rfSuplente = $pedido["RfSuplente"];
$NumeroProcesso = $pedido["NumeroProcesso"];
$notaempenho = $pedido["NotaEmpenho"];
$data_entrega_empenho = exibirDataBr($pedido['EntregaNE']);
$data_emissao_empenho = exibirDataBr($pedido['EmissaoNE']);
$ingresso = dinheiroParaBr($pedido['ingresso']);
$ingressoExtenso = valorPorExtenso($pedido['ingresso']);
$observacao = $pedido["observacao"];

$grupo = grupos($id_ped);
$integrantes = $grupo["texto"];

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
header("Content-Disposition: attachment;Filename=$NumeroProcesso em $dataAtual.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";
echo 
	"<p align='center'><strong>PROCESSO SEI Nº ".$NumeroProcesso."</strong></p>".
	"<p align='center'><strong>ORDEM DE EXECUÇÃO DE SERVIÇO</strong></p>".
	"<p>&nbsp;</p>".
	"<p><strong>Emanada de:</strong> Secretaria Municipal de Cultura</p>".
	"<p><strong>Suporte Legal:</strong> Artigo 25, inciso III, da Lei Federal nº 8.666/93 e alterações posteriores e artigo 1º da Lei Municipal nº 13.278/02, nos termos dos artigos 16 e 17 do Decreto nº 44.279/03.</p>".
	"<p>&nbsp;</p>".
	"<p><strong>Prestador e/ou executor do serviço</strong></p>".
	"<p><strong>Razão Social:</strong> ".$pjRazaoSocial."</p>".
	"<p><strong>CNPJ:</strong> ".$pjCNPJ."</p>".
	"<p><strong>Endereço:</strong> ".$pjEndereco."</p>".
	"<p><strong>Representante Legal:</strong> ".$rep01Nome."</p>".
	"<p><strong>RG:</strong> ".$rep01RG."</p>".
	"<p><strong>CPF:</strong> ".$rep01CPF."</p>".
	"<p>&nbsp;</p>".
	"<p><strong>Serviço</strong></p>".
	"<p>Especificações: Contratação dos serviços profissionais de natureza artística de ".$Objeto." através de ".$exNome." e demais integrantes mencionados na Declaração de Exclusividade, por intermédio da empresa ".$pjRazaoSocial. ", CNPJ: ".$pjCNPJ. ", representada legalmente por ".$rep01Nome. ", CPF: ".$rep01CPF.", para realização de evento no(s) local(is) ".$Local." no período ".$Periodo." conforme proposta e cronograma.</p>".
	"<p>Fica designado como fiscal do contrato ".$Fiscal.", RF ".$rfFiscal." e como suplente ".$Suplente.", RF ".$rfSuplente.".</p>".
	"<p>&nbsp;</p>".
	"<p>Valor do Ingresso: ".$ingresso." ( ".$ingressoExtenso." ).</p>".
	"<p>&nbsp;</p>".
	"<p><strong>Pagamento</strong></p>".
	"<p>O pagamento do cachê corresponderá à reversão integral da renda obtida na bilheteria a/o contratada/o, deduzidos os impostos e taxas pertinentes.</p>".
	"<p>".$FormaPagamento."</p>".
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
	"<p>- A empresa contratada fica sujeita ao atendimento no disposto nas Leis Municipais nº 10.973/9, regulamentada pelo DM 30.730/91; 11.113/91; 11.357/93; 12.975/2000 e portaria 66/SMC/2007; Leis Estaduais nº 7.844/92; Medida Provisória Federal 12.933/2013 e Lei Federal 10.741/2013.</p>".
	"<p>- A contratada é responsável por qualquer prejuízo ou dano causado ao patrimônio municipal ou a bens de terceiros que estejam sob a guarda do equipamento local de realização do evento.</p>".
	"<p>- Quaisquer outras despesas não ressalvadas aqui serão de responsabilidade da contratada, que se compromete a adotar as providências necessárias junto à OMB.</p>".
	"<p>- As providências administrativas para liberação da autorização do ECAD serão de responsabilidade da contratada, assim como eventuais pagamentos.</p>".
	"<p>- A Municipalidade não é responsável por qualquer material ou equipamento que não lhe pertence utilizado no espetáculo, devendo esse material ser retirado no seu término.</p>".
	"<p>- Compete, ainda, à Municipalidade, o fornecimento da sonorização necessária à realização de espetáculos e dos equipamentos de iluminação disponíveis no local do evento, assim como providências quanto à divulgação de praxe (confecção de cartaz a ser afixado no equipamento cultural e encaminhamento de release à mídia impressa e televisiva).</p>".
	"<p>- Serão reservados ingressos aos funcionários da PMSP, até 10% (dez por cento) da lotação da sala.</p>".
	"<p>- A/o contratada/o se compromete a realizar o espetáculo para um número mínimo de 10 (dez) pagantes.</p>".
	"<p>&nbsp;</p>".
	"<p>Aceito as condições dessa O.E.S para todos os efeitos de direito.</p>".
	"<p>&nbsp;</p>".
	"<p><b>".$rep01Nome."</br>".
	"<p>CPF: ".$rep01CPF."</b></p>".
	"<p>&nbsp;</p>".
	"<p>Com competência delegada pela Portaria nº. 19/2016- SMC/G, determino a execução do serviço na forma desta O.E.S.</p>".
	"<p>&nbsp;</p>".
	"<p>&nbsp;</p>".
	"<p><b>Carla Mingolla<br/>
		Chefe de Gabinete<br/>
		Secretaria Municipal de Cultura</b></p>";
echo "</body>";
echo "</html>";	
?>