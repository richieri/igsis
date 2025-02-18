﻿<?php
include 'includes/menu.php';

$con = bancoMysqli();
$idPessoa = $_REQUEST['idPessoa'];
$tipoPessoa = $_REQUEST['tipoPessoa'];
$id = $_SESSION['id'];

if(isset($_POST['fisica']) OR ($_GET['tipoPessoa'] == 5))
{
	$form = "<form method='POST' action='?perfil=emia&p=frm_edita_pf&id_pf=$idPessoa' />
				<input type='hidden' name='fisica' value='1'>";
	$p = "fisica";
}

if(isset($_POST["enviar"]))
{
	$sql_arquivos = "SELECT * FROM sis_emia_upload";
	$query_arquivos = mysqli_query($con,$sql_arquivos);
	while($arq = mysqli_fetch_array($query_arquivos))
	{ 
		$y = $arq['idTipoDoc'];
		$x = $arq['sigla'];
		$nome_arquivo = $_FILES['arquivo']['name'][$x];
		if($nome_arquivo != "")
		{
			$nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
			$new_name = date("YmdHis")."_".semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
			$hoje = date("Y-m-d H:i:s");
			$dir = '../uploadsdocs/'; //Diretório para uploads
			if(move_uploaded_file($nome_temporario, $dir.$new_name))
			{		  
				$sql_insere_arquivo = "INSERT INTO `igsis_arquivos_pessoa` (`idArquivosPessoa`, `idTipoPessoa`, `idPessoa`, `arquivo`, `dataEnvio`, `publicado`, `tipo`) VALUES (NULL, '$tipoPessoa', '$idPessoa', '$new_name', '$hoje', '1', '$y'); ";
				$query = mysqli_query($con,$sql_insere_arquivo);
				if($query)
				{
					$mensagem = "Arquivo recebido com sucesso";
				}
				else
				{
					$mensagem = "Erro ao gravar no banco";
				}
			}
			else
			{
				$mensagem = "Erro no upload";   
			}
		}
	}
}

if(isset($_POST['apagar']))
{
	$idArquivo = $_POST['apagar'];
	$sql_apagar_arquivo = "UPDATE igsis_arquivos_pessoa SET publicado = 0 WHERE idArquivosPessoa = '$idArquivo'";
	if(mysqli_query($con,$sql_apagar_arquivo))
	{
		$arq = recuperaDados("igsis_arquivos_pessoa",$idArquivo,"idArquivosPessoa");
		$mensagem =	"Arquivo<strong> ".$arq['arquivo']."</strong> apagado com sucesso!";
		gravarLog($sql_apagar_arquivo);
	}
	else
	{
		$mensagem = "Erro ao apagar o arquivo. Tente novamente!";
	}
}
$campo = recuperaPessoa($_REQUEST['idPessoa'],$_REQUEST['tipoPessoa']); 
?>

<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h3><?php echo $campo["nome"] ?>  </h3>              
					<h4>Arquivos anexados</h4>
					<p><strong>Se na lista abaixo, o seu arquivo começar com "http://", por favor, clique, grave em seu computador, faça o upload novamente e apague a ocorrência citada.</p></strong>
				</div>
				<div class="table-responsive list_info">
<?php listaArquivosPessoaSiscontratEmia($idPessoa,$tipoPessoa,"",$p,"emia"); ?>
				</div>
			</div>
		</div>  
	</div>

	<div class="form-group">
		<div class="col-md-offset-2 col-md-8"><br /></div>
	<div />
	
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h4>Envio de Arquivos</h4>
					<p><?php if(isset($mensagem)){echo $mensagem;} ?></p>	
					<p>Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 50MB.</p>
					<br />
					<div class = "center">
						<form method="POST" action="?<?php echo $_SERVER['QUERY_STRING'] ?>" enctype="multipart/form-data">
						<table>
							<tr>
								<td width="50%"><td>
							</tr>
						<?php 
							$sql_arquivos = "SELECT * FROM sis_emia_upload";
							$query_arquivos = mysqli_query($con,$sql_arquivos);
							while($arq = mysqli_fetch_array($query_arquivos))
							{ 
						?>
								<tr>
									<td><label><?php echo $arq['documento']?></label></td>
									<td><input type='file' name='arquivo[<?php echo $arq['sigla']; ?>]'></td>
								</tr>							
						<?php 
							} 
						?>
						</table>	
						<br />	
						<input type="hidden" name="idPessoa" value="<?php echo $idPessoa; ?>"  />
						<input type="hidden" name="tipoPessoa" value="<?php echo $tipoPessoa; ?>"  />
						<?php if(isset($_POST['volta']))
						{
							echo 	"<input type='hidden' name='volta' value='".$_POST['volta']."' />";
						} 
						?>
						<input type='hidden' name='<?php echo $p; ?>' value='1' />
						<input type="hidden" name="enviar" value="1"  />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value='Enviar'>
						</form>
					</div>						
					<br />
					<div class="center">
						<?php echo $form ?>	
						<input type="hidden" name="idPessoa" value="<?php echo $idPessoa; ?>"  />
						<input type="hidden" name="tipoPessoa" value="<?php echo $tipoPessoa; ?>"  />
						<input type="submit" class="btn btn-theme btn-block" value='Voltar ao Cadastro de Pessoa'>
						<br/>
					</div>
					</form>

					<br />
				</div>
			</div>
		</div>
	</div>
</section>

