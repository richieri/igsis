<?php

//require '../include/';
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");
   require_once("../funcoes/funcoesSiscontrat.php");

//CONEXÃO COM BANCO DE DADOS 
   $conexao = bancoMysqli();

//CONSULTA 
$id_ped=$_GET['id'];
dataReserva($id_ped);
$linha_tabelas = siscontrat($id_ped);

$codPed = $id_ped;
$objeto = $linha_tabelas["Objeto"];
$ValorGlobal = $linha_tabelas["ValorGlobal"];
$ValorPorExtenso = valorPorExtenso($linha_tabelas["ValorGlobal"]); 
$periodo = $linha_tabelas["Periodo"];
$duracao = $linha_tabelas["Duracao"];
$dataAtual = date("d/m/Y");
$NumeroProcesso = $linha_tabelas["NumeroProcesso"];
$assinatura = $linha_tabelas["Assinatura"];
$cargo = $linha_tabelas["Cargo"];
$qtdApresentacoes = $linha_tabelas["qtdApresentacoes"];
$qtdApresentacoesPorExtenso = qtdApresentacoesPorExtenso ($linha_tabelas["qtdApresentacoes"]);
$Periodo = $linha_tabelas["Periodo"];

$linha_tabelas_pessoa = siscontratDocs($linha_tabelas['IdProponente'],1);
$nome = $linha_tabelas_pessoa["Nome"];
$cpf = $linha_tabelas_pessoa["CPF"];

$setor = $linha_tabelas["Setor"];

$ano=date('Y');
  
 ?>
 
 
<html>
<head> 
<meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">

<style>

.texto{
 	width: 900px;
 	border: solid;
 	padding: 20px;
 	font-size: 12px;
 	font-family: Arial, Helvetica, sans-serif;
	text-align:justify;
}
</style>
<script src="include/dist/ZeroClipboard.min.js"></script>
</head>

 <body>

  
<?php

$sei = 
  "<p><strong>Do processo nº:</strong> ".$NumeroProcesso."</p>".
  "<p>&nbsp;</p>".
  "<p><strong>INTERESSADO:</strong> ".$nome."  </span></p>".
  "<p><strong>ASSUNTO:</strong> ".$objeto."  </p>".
  "<p>&nbsp;</p>".
  "<p><strong>SMC/CAF/SCO</strong></p>".
  "<p><strong>Sr(a). Supervisor</strong></p>".
  "<p>&nbsp;</p>".
  "<p>O presente processo trata da contratação de ".$objeto.", no valor de R$ ".$ValorGlobal." ( ".$ValorPorExtenso." ), conforme solicitação LINK, tendo sido anexados os documentos necessários e incluido o parecer técnico LINK, ratificando o caráter artístico e o valor proposto para o cachê referente a ".$qtdApresentacoes." ( ".$qtdApresentacoesPorExtenso." ) apresentação, no período de ".$Periodo.".</p>".
   "<p>Autorizo a reserva que deverá onerar recursos do FEPAC, dotação 95.10.13.392.3001.6354.3390.3600.08 (Pessoa Física), mediante RESERVA COM TRANSFERÊNCIA PARA U.O. 25.10.</p>". 
  "<p>&nbsp;</p>".
  "<p>&nbsp;</p>".
  "<p>Após, enviar para SMC/Assessoria Jurídica para prosseguimento. </p>".
  "<p>&nbsp;</p>"

?>

<div align="center">
 <div id="texto" class="texto"><?php echo $sei; ?></div>
</div> 

 <p>&nbsp;</p>
 
 <div align="center"><button id="botao-copiar" data-clipboard-target="texto"><img src="img/copy-icon.jpg"> CLIQUE AQUI PARA COPIAR O TEXTO</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
 <button>CLIQUE AQUI PARA ACESSAR O <img src="img/sei.jpg"></button></a>
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