<?php
		$con = bancoMysqli();
	if(isset($_GET['pag']))
{
		$p = $_GET['pag'];
}
	else
{
		$p = 'inicial';	
}

		$idEvento = $_GET['id_eve'];
		$evento = recuperaEvento($_GET['id_eve']);
		/*$argumento = recuperaArgumento($_GET['id_eve']);*/
		$_SESSION['idEvento'] = $idEvento;
?>

<?php include "includes/menu.php"; ?>

<?php switch($p){/* =========== INICIAL ===========*/
case 'inicial':
		$idEvento = $_GET['id_eve'];
?>

	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<h5>Dados do evento | 
				<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=servicos&id_eve=<?php echo $idEvento;?>">Solicitação de serviços</a> | 
				<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=pedidos&id_eve=<?php echo $idEvento;?>">Pedidos de contratação</a> |  
				<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=pendencias&id_eve=<?php echo $idEvento;?>">Pendências</a>
			</h5>
 			<p align="center">
			<?php recuperaArgumento($idEvento); ?></b><br />
			 </p>		<div class="table-responsive list_info" >
			<h4><?php echo $evento['nomeEvento'] ?></h4>
				<p align="left"><?php descricaoEvento($idEvento); ?></p>      
				<h5>Ocorrências</h5>
				
<?php echo resumoOcorrencias($idEvento); ?><br /><br />
<?php listaOcorrenciasTexto($idEvento); ?>
				<h5>Especificidades</h5>
		<div class="left"><?php descricaoEspecificidades($idEvento,$evento['ig_tipo_evento_idTipoEvento']); ?></div>
		</div>
	</section>
	
<?php /* =========== INICIAL ===========*/ break; ?>

<?php /* =========== INÍCIO SERVIÇOS ===========*/
case "servicos":
?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<h5><a href="?perfil=gestao_prazos&p=detalhe_evento&pag=inicial&id_eve=<?php echo $idEvento;?>">Dados do evento</a> | 
				Solicitação de serviços | 
				<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=pedidos&id_eve=<?php echo $idEvento;?>">Pedidos de contratação</a> |  
				<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=pendencias&id_eve=<?php echo $idEvento;?>">Pendências</a>
			</h5>
		<div class="table-responsive list_info" >    
			<h4><?php echo $evento['nomeEvento'] ?></h4>
				<div class="left">
					<h5>Previsão de serviços externos</h5>
 
<?php listaServicosExternos($idEvento); ?><br /><br />
			<h5>Serviços Internos</h5>
			
<?php listaServicosInternos($idEvento) ?>
				</div>
		</div>
	</section>
	
<?php /* =========== FIM SEVIÇOS ===========*/ break; ?>

<?php /* =========== INÍCIO PEDIDOS ===========*/
case "pedidos":
		$pedido = listaPedidoContratacao($_SESSION['idEvento']);
?>

	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<h5><a href="?perfil=gestao_prazos&p=detalhe_evento&pag=inicial&id_eve=<?php echo $idEvento;?>">Dados do evento</a> | 
			<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=servicos&id_eve=<?php echo $idEvento;?>">Solicitação de serviços</a> |  
			Pedidos de contratação |  
			<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=pendencias&id_eve=<?php echo $idEvento;?>">Pendências</a>
			</h5>
		<div class="table-responsive list_info" >

<?php if($pedido != NULL){ ?>
            <h4><?php echo $evento['nomeEvento'] ?></h4>

<?php for($i = 0; $i < count($pedido); $i++){
			$dados = siscontrat($pedido[$i]);
			$pessoa = siscontratDocs($dados['IdProponente'],$dados['TipoPessoa']);
?>
            <p align="left">
           	Código do Pedido: <b><?php echo $pedido[$i] ?></b><br />
			Nome ou Razão Social: <b><?php echo $pessoa['Nome'] ?></b><br />
			Tipo de pessoa: <b><?php echo retornaTipoPessoa($dados['TipoPessoa']);?></b><br />
			Dotação: <b><?php echo retornaVerba($dados['Verba']);?></b><br />
			Valor: <b>R$ <?php echo dinheiroParaBr($dados['ValorGlobal']);?></b><br />		
			 </p>
      
<?php } // fechamento do for
?>
<div class="form-group">
            <div class="col-md-offset-2 col-md-8">
				<h5>Pedidos Relacionados</h5>
				<?php 
					$outros = listaPedidoContratacao($idEvento); 
					for($i = 0; $i < count($outros); $i++)
					{
						$dados = siscontrat($outros[$i]);
						if($dados['TipoPessoa'] == 1)
						{
				?>
							<p align="left">Número do Pedido de Contratação:<b> <a href="?perfil=contratos&p=frm_edita_propostapf&id_ped=<?php echo $outros[$i]; ?>"></b><?php echo $outros[$i]; ?></a><br /></p>
				<?php 
						}
						if($dados['TipoPessoa'] == 2)
						{
				?>
							<p align="left">Número do Pedido de Contratação:<b> <a href="?perfil=contratos&p=frm_edita_propostapj&id_ped=<?php echo $outros[$i]; ?>"></b><?php echo $outros[$i]; ?></a><br /></p>
				<?php 
						}
					}		
				?>
            	<br />
			</div>
		</div> 
<?php		
}
	else
{ 
?>
			<h5> Não há pedidos de contratação. </h5>
<?php }	?>

		</div>
	</section>
<?php /* =========== FIM SEVIÇOS ===========*/ break; ?>

<?php /* =========== INÍCIO PENDENCIAS ===========*/
case "pendencias":
	require_once("../funcoes/funcoesVerifica.php");
	require_once("../funcoes/funcoesSiscontrat.php");
		$evento = recuperaDados("ig_evento",$_SESSION['idEvento'],"idEvento");
		$campos = verificaCampos($idEvento);
		$ocorrencia = verificaOcorrencias($idEvento);
?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<h5><a href="?perfil=gestao_prazos&p=detalhe_evento&pag=inicial&id_eve=<?php echo $idEvento;?>">Dados do evento</a> | 
			<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=servicos&id_eve=<?php echo $idEvento;?>">Solicitação de serviços</a> |  
			<a href="?perfil=gestao_prazos&p=detalhe_evento&pag=pedidos&id_eve=<?php echo $idEvento;?>">Pedidos de contratação</a> | 
			Pendências
			</h5>
		<div class="table-responsive list_info" >
			<h4><?php echo $evento['nomeEvento'] ?></h4>
				<div class="left">
            
<?php if($campos['total'] > 0){
			echo "<h4>Há campos obrigatórios não preenchidos.</h4>";	
			echo "<strong>".substr($campos['campos'],1)."</strong>";
}
	else
{
			echo "<h4>Todos os campos obrigatórios foram preenchidos.</h4>";
}
?>
	<br /><br />
		<p><?php if($ocorrencia > 0){
			echo "<h4>Há ocorrências cadastradas.</h4>";	
			echo "<br /><br />";
			prazoContratos($idEvento);
}
	else
{
			echo "Não há ocorrências cadastradas.";
}
?></p>
				</div>
            <br />
		</div>	
		<div class="form-group">
			<div class="col-md-offset-2 col-md-6">
				<form method='POST' action='?perfil=gestao_prazos&p=detalhe_evento&pag=desaprovar&id_eve=<?php echo $idEvento;?>'>
				<input type='hidden' name='carregar' value='".$idEvento."' />
				<input type ='submit' class='btn btn-theme btn-lg btn-block' value='Não Aprovar'></form>
			</div>
			<div class="col-md-6">
				<form method='POST' action='?perfil=gestao_prazos&p=detalhe_evento&pag=finalizar&id_eve=<?php echo $idEvento;?>'>
				<input type='hidden' name='carregar' value='".$idEvento."' />
				<input type ='submit' class='btn btn-theme btn-lg btn-block' value='Enviar'></form>
			</div>
		</div>
		</div>
	</section>
<?php /* =========== FIM PENDENCIAS ===========*/ break; ?>

<?php /* =========== INÍCIO FINALIZAR ===========*/

case "finalizar":
	include "../include/menuEvento.php";
	require_once("../funcoes/funcoesVerifica.php");
	require_once("../funcoes/funcoesSiscontrat.php");
		$verifca = verificaPendencias($idEvento);
if($verifica == 0)
{
?>

	<section id="contact" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="text-hide">
						<h2>O pedido será enviado!</h2>
					</div>
				</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<p>Uma vez enviado o formulário, não poderá mais editá-lo.</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
<?php $con = bancoMysqli();	?>
				<form method='POST' action='?perfil=finalizar'>
				<input type='hidden' name='finalizar' value='<?php echo $idEvento ?>' />
				<input type ='submit' class='btn btn-theme btn-lg btn-block' value='Enviar' onclick="this.disabled = true; this.value = 'Enviando…'; this.form.submit();"></form>
				</div>
			</div>
			</div>
		</div>
	</section>      

<?php }else{?>
	<section id="contact" class="home-section bg-white">
		<div class="container">
			<div class="row">
                <div class="col-md-offset-2 col-md-8">
					<div class="text-hide">
						<h2>Não é possível enviar o formulário!</h2>
					</div>
				</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<p>Há pendências que necessitam ser resolvidas. </p>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
	       	        <form method='POST' action='?perfil=finalizar'>
					<input type='hidden' name='finalizar' value='".$idEvento."' />
					<input type ='submit' class='btn btn-theme btn-lg btn-block' value='Enviar'></form>
				</div>
			</div>
			</div>
		</div>
	</section>    
<?php } ?> 

<?php /* =========== FIM FINALIZAR ===========*/ break; ?>

<?php /* =========== INÍCIO NÃO APROVAR ===========*/
case "desaprovar":
?>		
	<section id="contact" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="text-hide">
						<h5>O evento e o pedido de contratação receberão o status de NÃO Aprovado!</h5>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<p>Insira abaixo o argumento para a não aprovação: <i>(campo obrigatório)</i></p>
					</div>
				</div>
				<form method='POST' role='form' action='?perfil=gestao_prazos&p=detalhe_evento&pag=desaprovado&id_eve=<?php echo $idEvento;?>'>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<textarea name="argumento" id='argumento' maxlength="250" class="form-control" rows="5"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type='hidden' name='desaprovar' value='<?php echo $idEvento ?>' />
							<input type ='submit' class='btn btn-theme btn-lg btn-block' value='Confirmar' onclick="this.disabled = true; this.value = 'Enviando…'; this.form.submit();">						
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
<?php /* =========== FIM NÃO APROVAR ===========*/ break; ?>

<?php /* =========== INÍCIO DESAPROVADO ===========*/
case "desaprovado":
	require_once("../funcoes/funcoesSiscontrat.php");
?>

<?php
	
	if(trim($_POST['argumento']) == '') 
	{ 
		echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=?perfil=gestao_prazos&p=detalhe_evento&pag=desaprovar&id_eve=".$idEvento."'>";
	}
	else
	{
		$argumento = $_POST['argumento'];	
		
		if(isset($_POST['desaprovar']))
		{
			$con = bancoMysqli();
			$_SESSION['idEvento'] = $_POST['desaprovar'];
			$idContratos = $_SESSION['idUsuario'];
			$datetime = date("Y-m-d H:i:s");
			
			$sql_insere_argumento = "INSERT INTO `igsis_argumento`(`idEvento`, `argumento`, `idContratos`, `data`) VALUES ('$idEvento', '$argumento', '$idContratos', '$datetime')";
			$query_insere_argumento = mysqli_query($con,$sql_insere_argumento);
			if($query_insere_argumento)
			{
				gravarLog($sql_insere_argumento);
				$mensagem = "<h1>Argumento gravado com sucesso!</h1>";
				$sql_atualiza_pedido = "UPDATE igsis_pedido_contratacao AS ped SET estado = 15 WHERE ped.idEvento = '$idEvento' AND ped.publicado = 1 ";
				$query_atualiza_pedido = mysqli_query($con,$sql_atualiza_pedido);
				if($query_atualiza_pedido)
				{
					gravarLog($sql_atualiza_pedido);
					$mensagem = "<h1>Status alterado com sucesso!</h1>";
					$sql_atualiza_evento = "UPDATE `ig_evento` SET `statusEvento` = 'Não Aprovado' WHERE `ig_evento`.`idEvento` = '$idEvento'";
					$query_atualiza_evento = mysqli_query($con,$sql_atualiza_evento);
					if($query_atualiza_evento)
					{
						gravarLog($sql_atualiza_evento);
						$mensagem = "<h3>Status do evento e do pedido alterados com sucesso!</h3>";
					}
					else
					{
						$mensagem = "<h1>Erro ao atualizar o status do evento. Tente novamente!</h1>";
					}
				}
				else
				{
					$mensagem = "<h1>Erro ao gravar dados. Favor entrar em contato com o administrador do sistema</h1>";	
				}
			}
			else
			{
				$mensagem = "<h1>Erro ao gravar o argumento. Tente novamente!</h1>";
			}		
			$_SESSION['idEvento'] = NULL;
		}
	}
?>
	<section id="contact" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="text-hide">
						<?php echo $mensagem; ?>
						<br/>
						<a href="?perfil=gestao_prazos">Voltar à Gestão de Prazos</a><br/>
						<a href="?secao=perfil">Carregar módulos</a><br/>
						<a href="?p=inicio">Voltar ao início</a><br/>
					</div>
				</div>
			</div>
		</div>
	</section>
   
<?php /* =========== FIM DESAPROVADO ===========*/ break; ?>

<?php } //fim da switch ?>