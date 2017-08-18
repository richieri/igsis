<?php

include 'includes/menu.php';	
	
$server = "http://".$_SERVER['SERVER_NAME']."/igsis/"; //mudar para pasta do igsis
$http = $server."/pdf/";
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

<section id="services" class="home-section bg-white">
	<div class="container">			
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<h3>Relatório por Período</h3><br/>				
			</div>
		</div>
		<div class="row">
		<form method="POST" action="<?php echo $http ?>rlt_periodo_juridico.php" class="form-horizontal" role="form">
			<div class="form-group">
				<div class="col-md-offset-2 col-md-6">
					<label>Data início *</label>
						<input type="text" name="inicio" class="form-control" id="datepicker03" placeholder="">
				</div>
				<div class=" col-md-6">
					<label>Data encerramento *</label>
						<input type="text" name="final" class="form-control" id="datepicker04"  placeholder="">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<label>Instituição *</label><img src="images/loading.gif" class="loading" style="display:none" />
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
			<br />             
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<input type="hidden" name="periodo" value="1" />
					<input type="submit" class="btn btn-theme btn-lg btn-block" value="Gerar">
				</div>
			</div>
		</form>
		</div>
	</div>
</section>