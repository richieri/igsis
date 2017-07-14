<?php 
	session_start();
	   @ini_set('display_errors', '1');
	error_reporting(E_ALL); 	
   
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

   //CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli(); 
   
   
//CONSULTA  (copia inteira em todos os docs)
$id_ped=$_GET['id'];

$ano=date('Y');
$dataAtual = date("d/m/Y");

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
header("Content-Disposition: attachment;Filename=$NumeroProcesso em $dataAtual.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";
  
echo 
	"<p align='center'><strong>PREFEITURA DO  MUNICÍPIO DE SÃO PAULO<br/>
		SECRETARIA MUNICIPAL DE CULTURA<br/>
		PROCESSO SEI Nº ".$NumeroProcesso."</strong></p>".
	"<p align='center'><strong>ORDEM DE EXECUÇÃO DE SERVIÇO Nº ______/2017</strong></p>".
	"<p>&nbsp;</p>".
	"<p><strong>Emanada de:</strong> Divisão Administrativa</p>".
	"<p><strong>Suporte Legal:</strong> Artigo 25, inciso III, da Lei Federal nº 8.666/93 e alterações posteriores e artigo 1º da Lei Municipal nº 13.278/02, nos termos dos artigos 16 e 17 do Decreto nº 44.279/03.</p>".
	"<p>&nbsp;</p>".
	"<p><strong>Nome:</strong> ".$Nome."<br />
		<strong>CPF:</strong> ".$CPF."<br />
		<strong>RG:</strong> ".$RG."<br />
		<strong>Endereço:</strong> ".$Endereco."<br />
		<strong>Representante:</strong> ".$Nome."<br />
		<strong>Estado Civil:</strong> ".$EstadoCivil."<br />
		<strong>Nacionalidade:</strong> ".$Nacionalidade.
	"</p>".
   "<p>&nbsp;</p>".
   "<p><strong>Serviço</strong></p>".
	"<p>Especificações: Contratação dos serviços profissionais de natureza artística de ".$Objeto.", através dos integrantes mencionados na Declaração de Exclusividade, por intermédio de ".$Nome.", CPF: ".$CPF.", para realização de evento no ".$Local.", no período ".$Periodo.", conforme proposta e cronograma.</p>".
	"<p>&nbsp;</p>".
   	"<p>Fica designado como fiscal do contrato ".$Fiscal.", RF ".$rfFiscal." e como suplente ".$Suplente.", RF ".$rfSuplente."</p>
	<p>&nbsp;</p>";
	
	if($pedido['ingresso'] == '0')
    {
		echo "<p>Valor do Ingresso: ".$observacao."</p>";
	}
    else 
    {
		echo "<p>Valor do Ingresso: ".$ingresso." ( ".$ingressoExtenso." ).</p>";
	}
	echo
	"<p>&nbsp;</p>".
	"<p><strong>Pagamento</strong></p>".
	"<p>O  pagamento corresponderá à reversão integral da renda obtida na bilheteria a/o  contratada/o, deduzidos os impostos e taxas pertinentes.</p>".
	"<p>&nbsp;</p>".
	"<p><strong>Penalidades</strong></p>".
	" <p>- Multa de 10% (dez por cento) sobre  o valor do contrato ou sobre o valor integral da venda de todos os ingressos  disponíveis por atraso de até 30 (trinta) minutos no evento. Ultrapassado esse  tempo, e independentemente da aplicação de penalidade, fica a critério do  equipamento da Secretaria Municipal de   Cultura autorizar a realização do evento, visando evitar prejuízos à  grade de programação. Não sendo autorizada a realização do evento, será  considerada inexecução total do contrato, com aplicação de multa prevista por  inexecução total.</p>
  <p>- Multa de 10% (dez por cento) para  casos de infração de cláusula contratual e/ou inexecução parcial do ajuste e de  30% (trinta por cento) para casos de inexecução total do ajuste. O valor da  multa será calculado sobre o valor do contrato ou sobre o valor integral da  venda de todos os ingressos disponíveis.</p>
  <p>- Multa de 10% (dez por cento) sobre  o valor do contrato ou sobre o valor integral da venda de todos os ingressos  disponíveis, em função da falta de regularidade fiscal do contratado, bem como,  pela verificação de  que possui  pendências junto ao Cadastro Informativo Municipal (CADIN).</p>
  <p>- As penalidades serão aplicadas sem prejuízo das  demais sanções previstas na legislação que rege a matéria.</p>
<p>&nbsp;</p>
  <p><strong>Cancelamento</strong></p>
  <p>Esta O.E.S. poderá ser cancelada no interesse da  administração, devidamente justificada ou em virtude da inexecução total ou  parcial do serviço sem prejuízo de multa.</p>
<h2>&nbsp;</h2>
  <p><strong>Foro</strong></p>
  <p>Fica eleito o foro da Fazenda Pública para todo e qualquer  procedimento judicial oriundo desta ordem de execução de serviços.</p>
<h2>&nbsp;</h2>
  <p><strong>Observações</strong></p>
  <p>- Compete à contratada a realização do espetáculo, e a  fazer constar o crédito - PMSP/SECRETARIA MUNICIPAL DE CULTURA, em toda  divulgação escrita ou falada,realizada sobe o espetáculo programado.</p>
  <p>- A contratada fica sujeita ao  atendimento no disposto nas Leis Municipais nº 10.973/9, regulamentada pelo DM  30.730/91; 11.113/91; 11.357/93; 12.975/2000 e portaria 66/SMC/2007; Leis  Estaduais nº 7.844/92; Medida Provisória Federal 12.933/2013 e Lei Federal  10.741/2013.</p>
  <p>- A contratada é responsável por  qualquer prejuízo ou dano causado ao patrimônio municipal ou a bens de  terceiros que estejam sob a guarda do equipamento local de realização do  evento.</p>
  <p>- Quaisquer outras despesas não  ressalvadas aqui serão de responsabilidade da contratada, que se compromete a  adotar as providências necessárias junto à OMB.</p>
<p>- As   providências  administrativas  para   liberação  da  autorização   do  ECAD  serão   de  responsabilidade     da contratada,  assim como eventuais pagamentos.
</p>
<p>- A Municipalidade não é responsável  por qualquer material ou equipamento que não lhe pertença utilizado no  espetáculo, devendo esse material ser retirado no seu término.</p>
<p>- A renda integral apurada na bilheteria, com ingressos vendidos ao preço único de ".$ingresso." ( ".$ingressoExtenso." ), será revertida a/o contratada/o, já deduzidos os impostos pertinentes, podendo ter preços reduzidos em face de promoções realizadas pela produção do evento.</p>
<p>- Compete, ainda, à Municipalidade, o  fornecimento da sonorização necessária à realização de espetáculos e  dos equipamentos de iluminação disponíveis no  local do evento, assim como providências quanto à divulgação  de praxe (confecção de cartaz a ser afixado  no equipamento cultural e encaminhamento de release à mídia impressa e  televisiva).</p>
<p>- Serão reservados ingressos aos funcionários da PMSP,  até 10% (dez por cento) da lotação da sala.</p>
<p>- A/o contratada/o se compromete a  realizar o espetáculo para um número mínimo de 10 (dez) pagantes. Aceito as  condições dessa O.E.S para todos os efeitos de direito.</p>".
    "<p>&nbsp;</p>".
	"<p>Local e data: São Paulo,_______________________________.</p>".
	"<p>&nbsp;</p>".
	"<p>&nbsp;</p>".
	"<p>&nbsp;</p>".
	"<p><b>".$Nome."<br/>".
		"CPF: ".$CPF."</b></p>".
	"<p>&nbsp;</p>".
	"<p>Determino a execução do serviço na forma desta O.E.S.</p>".
	"<p>&nbsp;</p>".
	"<p>&nbsp;</p>".
	"<p>Local e data: São Paulo,_______________________________.</p>".
	"<p>&nbsp;</p>".
	"<p>&nbsp;</p>".
	"<p>&nbsp;</p>".
	"<p><b>Giovanna M. R. Lima<br/>".
		"Chefe de Gabinete<br/>".
		"Secretaria Municipal de Cultura</b></p>";
	echo "</body>";
echo "</html>";	
?>