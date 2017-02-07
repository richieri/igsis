<?php
	include "include/menu.php";
	//verifica se o usuário tem acesso a página
	$verifica = verificaAcesso($_SESSION['idUsuario'],$_GET['perfil']); 
	if($verifica == 1)
	{
		$idInstituicao = $_SESSION['idInstituicao'];	
			
		if(isset($_GET['order']))
		{
			$order = $_GET['order'];
		}
		else
		{
			$order = "";
		}
		if(isset($_GET['sentido']))
		{
			$sentido = $_GET['sentido'];
			if($sentido == "ASC")
			{
				$invertido = "DESC";
			}
			else
			{
				$invertido = "ASC";
			}
		}

		$mapas = recuperaDados("ig_comunicacao",$_GET['id'],"ig_evento_idEvento");
		$evento = recuperaDados("ig_evento",$_GET['id'],"idEvento");
		$usuario = recuperaDados("ig_usuario",$_SESSION['idUsuario'],"idUsuario");
		$instituicao = recuperaDados("ig_instituicao",$usuario['idInstituicao'],"idInstituicao");
		$idEvento = $_GET['id'];
		$chamado = recuperaAlteracoesEvento($idEvento);	
?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
				    <h2>SPCultura</h2>                 
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">	
				<h6><a href="?perfil=busca&p=detalhe&evento=<?php echo $_GET['id']; ?>" target="_blank">Documento enviado </a> |  <a href="?perfil=comunicacao&p=editar&id=<?php echo $_GET['id']; ?>" >Edição </a> | SPCultura  | 
			<?php 
				if($chamado['numero'] == '0')
				{
					echo "Chamados [0]";
				}
				else
				{
					echo "<a href='?perfil=chamado&p=evento&id=".$idEvento."' target='_blank'>Chamados [".$chamado['numero']."]</a>";	
				}				
			?>
				</h6>	
			</div>                     
		</div>
		<div class="table-responsive list_info" >
			<h4><?php echo $mapas['nomeEvento'] ?></h4>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8"><br/>
					<div class="left">
						<p>* Habilitar logo da SMC</p>        
						<p>Nome de exibição: <strong><?php echo $mapas['nomeEvento']; ?></strong></p>
						<p>Descrição curta:<br /> <?php echo $mapas['sinopse']; ?></p>
						<p>Inscrições: <strong>(tabela oficinas)</strong> </p>
						<p>Site: <strong><?php echo $instituicao['site']; ?></strong> </p>
						<p>Mais informações: <strong><?php echo $instituicao['telefone']; ?></strong> </p>
						<p>Descrição:<br />
							<?php echo nl2br($mapas['observacao']); ?>
						</p>			            
					</div>
                </div>
			</div>	
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8"><strong>Ocorrências</strong><br/>
					<div class="left">
						<?php listaOcorrenciasTexto($_GET['id']); ?>		            
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8"><strong>Teste (não utilizar)</strong><br/>
					<form method="POST" action="http://centrocultural.pagina-oficial.ws/igsis/MapasSDK/src/recebe.php" class="form-horizontal" role="form">
						<input type="hidden" name="name" value="<?php echo $mapas['nomeEvento']; ?>" />
						<input type="hidden" name="shortDescription" value="<?php echo $mapas['sinopse'] ?>" />
						<input type="hidden" name="classificacaoEtaria" value="Livre" />
						<input type="hidden" name="linguagem" value="Música Popular" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Enviar para o SPCultura"  onclick=” this.form.target=’_blank’;return true;>
					</form>
				</div>
			</div>			  
		</div>
	</div>
</section>
<?php
	}
	else
	{ 
?>
<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
		    <h1>Você não tem acesso. Por favor, contacte o administrador do sistema.</h1>
		</div>
	</div>
</section> 
 <?php
	}
 ?>