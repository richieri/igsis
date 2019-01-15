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
 
		$idCom = $_GET['idCom'];
		
		if(isset($_POST['apagar']))
		{
			$con = bancoMysqli();
			$idCom = $_POST['apagar'];
			$sql_apagar = "UPDATE ig_comunicacao SET publicado = 0 WHERE ig_comunicacao.idCom = '$idCom'";
			$query_apagar = mysqli_query($con,$sql_apagar);
			if($query_apagar)
			{
			    gravarLog($sql_apagar);
				$mensagem = "Registro apagado com sucesso.";	
			}
			else
			{
				$mensagem = "Erro ao apagar.";
			}
		}	

	
		if(isset($_POST['atualizar']))
		{
			if(isset($_POST['editado']))
			{
				$editado = 1;
			}
			else
			{
				$editado = 0;
			}
			if(isset($_POST['revisado']))
			{
				$revisado = 1;
			}
			else
			{
				$revisado = 0;
			}
			if(isset($_POST['site']))
			{
				$site = 1;
			}
			else
			{
				$site = 0;
			}
			if(isset($_POST['publicacao']))
			{
				$publicacao = 1;
			}
			else
			{
				$publicacao = 0;
			}
			if(isset($_POST['foto']))
			{
				$foto = 1;
			}
			else
			{
				$foto = 0;
			}
			$projetoEspecial = $_POST['projetoEspecial']; 
			$nomeEvento = $_POST['nomeEvento'];
			$projeto = $_POST['projeto'];
			$ig_tipo_evento_idTipoEvento = $_POST['ig_tipo_evento_idTipoEvento'];
			$projetoEspecial = $_POST['projetoEspecial'];
			$autor = addslashes($_POST['autor']);
			$fichaTecnica = addslashes($_POST['fichaTecnica']); 
			$sinopse = addslashes($_POST['sinopse']);
			$releaseCom = addslashes($_POST['releaseCom']); 
			$observacao =  addslashes($_POST['observacao']);	
			$idCom = $_POST['atualizar'];
			$sql_atualiza_com = "UPDATE `igsis`.`ig_comunicacao` SET 
				`sinopse` = '$sinopse', 
				`fichaTecnica` = '$fichaTecnica',
				`autor` = '$autor',
				`releaseCom` = '$releaseCom',
				`revisado` = '$revisado',
				`editado` = '$editado',
				`projetoEspecial` = '$projetoEspecial',	
				`site` = '$site',
				`foto` = '$foto',
				`publicacao` = '$publicacao',
				`ig_tipo_evento_idTipoEvento` = '$ig_tipo_evento_idTipoEvento',
				`projeto` = '$projeto',
				`nomeEvento` = '$nomeEvento',
				`observacao` = '$observacao' WHERE `ig_comunicacao`.`idCom` = '$idCom'";
			$con = bancoMysqli();
			$query_atualiza_com = mysqli_query($con,$sql_atualiza_com);
			if($query_atualiza_com)
			{
				$mensagem = "Atualizado com sucesso";	
			}
			else
			{
				$mensagem = "Erro ao atualizar.";
			}
		}
		
		$campo = recuperaDados("ig_comunicacao",$idCom,"idCom");
		$idEvento = $campo['ig_evento_idEvento'];
		$chamado = recuperaAlteracoesEvento($idEvento);
		$evento = recuperaDados("ig_evento",$idEvento,"idEvento");

?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Comunicação - Edição</h3>
                    <h3><?php echo $campo["nomeEvento"] ?></h3>
                    <h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form method="POST" action="?perfil=comunicacao&p=editar&idCom=<?php echo $_GET['idCom'] ?>" class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">	
							<h6><a href="?perfil=busca&p=detalhe&evento=<?php echo $campo['ig_evento_idEvento'] ?>" target="_blank">Documento enviado </a> | Edição | <a href="?perfil=comunicacao&p=spcultura&id=<?php echo $idEvento; ?>" >SPCultura</a> 
			<?php 
				if($campo['ig_tipo_evento_idTipoEvento'] == 1)
				{
					//edição cinema
					$_SESSION['cinema'] = 1;
					$_SESSION['idEvento'] = $campo['ig_evento_idEvento'];
					$_SESSION['com'] = 1;
			?>
								| <a href="?perfil=cinema" >Filmes </a>
			<?php
				}
			?>
								| 
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
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="checkbox" name="editado"  <?php checar($campo['editado']) ?> /><label style="padding:0 10px 0 5px;"> Editado</label>
							<input type="checkbox" name="revisado"  <?php checar($campo['revisado']) ?>/><label  style="padding:0 10px 0 5px;"> Revisado</label>
							<input type="checkbox" name="site"  <?php checar($campo['site']) ?> /><label style="padding:0 10px 0 5px;"> Site</label>
							<input type="checkbox" name="publicacao"  <?php checar($campo['publicacao']) ?>/><label style="padding:0 10px 0 5px;"> Impresso</label>
							<input type="checkbox" name="foto"  <?php checar($campo['foto']) ?>/><label style="padding:0 10px 0 5px;"> Foto para divulgação</label>
						</div>                     
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Nome do Evento </label>
							<input type="text" name="nomeEvento" class="form-control" id="inputSubject" value="<?php echo $campo['nomeEvento'] ?>"/>
						</div> 
					</div>    
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Nome do Projeto especial</label>
							<select class="form-control" name="projetoEspecial" id="inputSubject" >
								<option value="1"></option>
								<?php echo geraOpcao("ig_projeto_especial",$campo['projetoEspecial'],$_SESSION['idInstituicao']) ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Nome do Projeto </label>
							<input type="text" name="projeto" class="form-control" id=""  value="<?php echo $campo['projeto'] ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Tipo de Evento </label>
							<select class="form-control" name="ig_tipo_evento_idTipoEvento" id="inputSubject" >
								<option value="1"></option>
								<?php echo geraOpcao("ig_tipo_evento",$campo['ig_tipo_evento_idTipoEvento'],"") ?>
							</select>					
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Autor:</strong><br/>
							<textarea name="autor" class="form-control" rows="5"><?php echo $campo['autor'] ?></textarea>
						</div>
					</div>	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Ficha Técnica:</strong><br/>
							<textarea name="fichaTecnica" class="form-control" rows="10"><?php echo $campo['fichaTecnica'] ?></textarea>
						</div>
					</div>	 
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Sinopse:</strong><br/>
							<textarea name="sinopse" class="form-control" rows="10" ><?php echo $campo['sinopse'] ?></textarea>
						</div>
					</div>	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Release:</strong><br/>
							<textarea name="releaseCom" class="form-control" rows="20" ><?php echo $campo['releaseCom'] ?></textarea>
						</div>
					</div>
			<?php
				if($_SESSION['idInstituicao'] == 5)
				{
					// Mostra Gerador de formatação
			?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Gerador de Diagramação</strong><br/>
							<div class="left">
								<h5><?php echo $campo['nomeEvento']; ?></h5>
								<p><?php echo resumoOcorrencias($idEvento); ?></p>
								<p><?php echo nl2br($campo['sinopse']); ?></p>
								<p><?php echo nl2br($campo['autor']); ?></p>
								<p><?php echo nl2br($campo['fichaTecnica']); ?></p>
								<p>Duração: <?php echo retornaDuracao($idEvento); ?> min.</p>
								<p>- a venda estará disponível na bilheteria em seu horário de funcionamento (terça a sábado, das 13h às 21h30; e domingos, das 13h às 20h30), e no site www.ingressorapido.com.br a partir de 30 dias antes do evento (mesmo no caso de temporadas longas)</p>
								<br/>
						<?php
							if($evento['subEvento'] == '1')
							{ 
							
								
						?>
								<strong>Sub-eventos</strong>
								<?php echo listaSubEventosCom($idEvento); ?>
						<?php } ?>
								<strong>Ocorrências</strong>	
								<?php listaOcorrenciasTexto($idEvento); ?>
								<p></p>
							</div>
						</div>
					</div>	
					<?php
						} 
					?>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
							<textarea name="observacao" class="form-control" rows="20" ><?php echo $campo['observacao'] ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="atualizar" value="<?php echo $campo['idCom'] ?>" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Gravar">
						</div>
					</div>
				</form>
				<form method="POST" action="?perfil=comunicacao&p=editar&idCom=<?php echo $_GET['idCom'] ?>" class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-4 col-md-4">
							<input type="hidden" name="apagar" value="<?php echo $campo['idCom'] ?>" />
							<input type="hidden" name="idCom" value="<?php echo $idCom; ?>" >
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Apagar">
						</div>
					</div>
				</form>
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