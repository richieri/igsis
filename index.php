<?php 

include "funcoes/funcoesGerais.php";
require "funcoes/funcoesConecta.php";

//autentica usuario e cria inicia uma session


if(isset($_POST['usuario']))
{
	$usuario = $_POST['usuario'];
	$senha = $_POST['senha'];
	$sql = "SELECT * FROM ig_usuario, ig_instituicao, ig_papelusuario WHERE ig_usuario.nomeUsuario = '$usuario' AND ig_instituicao.idInstituicao = ig_usuario.idInstituicao AND ig_papelusuario.idPapelUsuario = ig_usuario.ig_papelusuario_idPapelUsuario AND ig_usuario.publicado = '1' LIMIT 0,1";
	$con = bancoMysqli();
	$query = mysqli_query($con,$sql);
	//query que seleciona os campos que voltarão para na matriz
	if($query)
	{
		//verifica erro no banco de dados
		if(mysqli_num_rows($query) > 0)
		{
			// verifica se retorna usuário válido
			$user = mysqli_fetch_array($query);
			if($user['senha'] == md5($_POST['senha']))
			{
				// compara as senhas
				session_start();
				$_SESSION['usuario'] = $user['nomeUsuario'];
				$_SESSION['perfil'] = $user['idPapelUsuario'];
				$_SESSION['instituicao'] = $user['instituicao'];
				$_SESSION['nomeCompleto'] = $user['nomeCompleto'];
				$_SESSION['idUsuario'] = $user['idUsuario'];
				$_SESSION['idInstituicao'] = $user['idInstituicao'];
				$log = "Fez login.";
				gravarLog($log);
				header("Location: visual/index.php");
			}
			else
			{
				$mensagem = "A senha está incorreta.";
			}
		}
		else
		{
			$mensagem = "O usuário não existe.";
		}
	}
	else
	{
		$mensagem = "Erro no banco de dados";
	}
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>IGSMC - v0.2 - 2017</title>
		<link href="visual/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="visual/css/style.css" rel="stylesheet" media="screen">
		<link href="visual/color/default.css" rel="stylesheet" media="screen">
		<script src="visual/js/modernizr.custom.js"></script>
	</head>
	<body>
		<section id="spacer1" class="home-section spacer">
           <div class="container">
				<div class="color-light">
					<h1 class="wow bounceInDown" data-wow-delay="1s">IGSIS - SMC</h1>
				</div>
            </div>
		</section>
		<section id="contact" class="home-section bg-white">
			<div class="container">
				<div class="col-md-offset-1 col-md-10">
					<h5><?php if(isset($mensagem)){ echo $mensagem; } ?></h5>
					<form method="POST" action="index.php" class="form-horizontal" role="form">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6">
								<input type="text" name="usuario" class="form-control" id="inputName" placeholder="Usuário">
							</div>
							<div class=" col-md-6">
								<input type="password" name="senha" class="form-control" id="inputEmail" placeholder="Senha">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-theme btn-lg btn-block">Entrar</button>
							</div>
						</div>
					</form>

					<br /><br />

                    <div class="well">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Para solicitar cadastro de novo usuário</h5>
                                <a href="https://goo.gl/lHLdnI" target="_blank" class="btn btn-primary">Clique aqui</a>
                            </div>
                        </div>
                    </div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<p>Chamados somente através do e-mail <strong>sistema.igsis@gmail.com</strong></p>
						</div>
					</div>

				</div>
			</div>
		</section>
		<footer>
			<div class="container">
				<div class="row">
					<table width="100%">
						<tr>
							<td width="20%"><img src="visual/images/logo_cultura_q.png" align="left"/></td>
							<td align="center"><font color="#ccc">2015 @ IGSIS - Sistema de Contratação Artística<br/>Secretaria Municipal de Cultura<br/>Prefeitura de São Paulo</font></td>
							<td width="20%"><img src="visual/images/logo_igsis_azul.png" align="right"/></td>
						</tr>
					</table>
				</div>
			</div>
		</footer>
    </body>
</html>