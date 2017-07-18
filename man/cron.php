<?php
@ini_set('display_errors', '1');
error_reporting(E_ALL); 
ini_set('max_execution_time', 300);

// cron tab 00 3 * * root /var/www/igsisbeta/man/cron.php
require "../funcoes/funcoesConecta.php";
require "../funcoes/funcoesGerais.php";
require "../funcoes/funcoesSiscontrat.php";

$con = bancoMysqli();
$data = date('d/m/Y H:i:s');
$relatorio = "
<p></p>Bom dia!</p>
<p>
Abaixo vai o relatório diário do sistema IGSIS. </p>
<p>Revise sempre as queries, caso encontre algo estranho, avise o administrador.</p>
<p>O objetivo é que todos os relatórios de anomalias possuam 0 (zero) ocorrências. Caso apareçam inconsistências, tente resolver caso a caso.</p>
";
$relatorio .= "<h1>Relatório do Sistema</h1>
<p>Gerado em $data</p>
<br />";

include "anomalias.php";
include "atualiza_agenda.php";
include "espaco.php";


$toEmail = "sistema.igsis@gmail.com";
$toEmail2 = "loreleigab@gmail.com";
$toUsuario = "Sistema IGSIS";
$toUsuario2 = "Lorelei Lourenço";

$fromEmail = "sistema.igsis@gmail.com"; 
$fromUsuario = "Sistema IGSIS";
$subject = "Relatório diário de Sistema";
$conteudo_email = $relatorio;


// Envia o relatório por email

	require_once('../include/phpmailer/class.phpmailer.php');
	//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

	$mail->IsSMTP(); // telling the class to use SMTP

	try {
	  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	  $mail->CharSet = 'UTF-8';
	  $mail->SMTPAuth   = true;                  // enable SMTP authentication
	  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	  $mail->Username   = "sistema.igsis";  // GMAIL username
	  $mail->Password   = "dec1935!";            // GMAIL password
      $mail->AddAddress($toEmail,$toUsuario);
      $mail->AddBCC($toEmail2,$toUsuario2);		
	  
	  //$mail->AddAddress(emailUserLogin($logado), nomeUserLogin($logado));
	
	
	  $mail->SetFrom($fromEmail, $fromUsuario);
	  $mail->AddReplyTo($fromEmail, $fromUsuario);
	
	  //assunto da IGCCSP 	
	  $mail->Subject = $subject;
	
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		//	Criar uma variável com as informações
	  $mail->MsgHTML($conteudo_email);
	  $mail->Send();
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}


//echo $relatorio;

?>

