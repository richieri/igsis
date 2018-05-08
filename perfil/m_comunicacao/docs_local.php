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
?>

<script type="application/javascript">
	$(function()
	{
		$('#instituicao').change(function()
		{
			if( $(this).val() )
			{
				$('#local').hide();
				$('.carregando').show();
				$.getJSON('local.ajax.php?instituicao=',{instituicao: $(this).val(), ajax: 'true'}, function(j)
				{
					var options = '<option value="0"></option>';	
					for (var i = 0; i < j.length; i++)
					{
						options += '<option value="' + j[i].idEspaco + '">' + j[i].espaco + '</option>';
					}	
					$('#local').html(options).show();
					$('.carregando').hide();
				});
			}
			else
			{
				$('#local').html('<option value="">-- Escolha uma instituição --</option>');
			}
		});
	});
</script>

<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Em Cartaz (Por Local)</h2>
					<h4></h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div> 
		<form  action="../pdf/rlt_gera_revista_local.php" method="GET" target="_blank" class="form-horizontal" role="form">
			<div class="form-group">
				<div class="col-md-offset-2 col-md-6">
					<label>Data início *</label>
					<input type="text" name="inicio" class="form-control" id="datepicker01" placeholder="">
				</div>	
				<div class=" col-md-6">
					<label>Data encerramento *</label>
					<input type="text" name="final" class="form-control" id="datepicker02"  placeholder="">
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<label>Local / Instituição </label><img src="images/loading.gif" class="loading" style="display:none" />
					<select class="form-control" name="instituicao" id="instituicao" >
						<option value="">Selecione</option>
						<?php geraOpcao("ig_instituicao","","") ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<label>Sala / espaço (antes selecione a instituição)</label>
					<select class="form-control" name="local" id="local" ></select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<br />
					<input type="submit" class="btn btn-theme btn-lg btn-block" value="GERAR">
					<br />
				</div>
			</div>
		</form>
	</div>
	<div class="table-responsive list_info">			   
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