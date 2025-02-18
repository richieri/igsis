﻿<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="#enviar">Início</a></li>
			<li><a href="#lista">Arquivos anexados</a></li>
			<li><a href="#services">Services</a></li>
			<li><a href="#works">Works</a></li>
			<li><a href="#contact">Contact</a></li>
			<li>
				<a href="#">Ocorrência</a>
				<ul class="dl-submenu">
					<li><a href="#">Inserir ocorrência</a></li>
					<li><a href="#">Listar ocorrência</a></li>
				</ul>
			</li>
		</ul>
	</div><!-- /dl-menuwrapper -->
</div>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="text-hide">
					<h2>Inserir ocorrência</h2>
					<p> </p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><label>Data início *</label><input type="text" name="dataInicio" class="form-control" id="datepicker10" placeholder="Name">
						</div>
						<div class=" col-md-6"><label>Data encerramento</label>
							<input type="text" name="dataFinal" class="form-control" id="datepicker11" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Dias da semana</label>
							<input type="checkbox" id="diaespecial" />
							<div class='other' name='other' title='other' style='display:none;'>
								<input type="checkbox">
								<input type="checkbox">
							</div>
						</div>
					</div>  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-2">
							<label>Horário de início</label>
							<input type="text" name="hora" class="form-control"id="hora" />
						</div> 
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><label>Valor do ingresso *</label><input type="text" name="valorIngresso" class="form-control" id="valor" placeholder="Name">
						</div>
						<div class=" col-md-6"><label>Duração do evento em minutos *</label>
							<input type="email" id="duracao" name="duracao" class="form-control" id="" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Sistema de retirada de ingressos</label>
							<select class="form-control" name="retiradaIngresso" id="inputSubject" ><option>Selecione</option></select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Local / instituição *</label>
							<select class="form-control" name="instituicao" id="inputSubject" ><option>Selecione</option></select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Sala / espaço</label>
							<select class="form-control" name="local" id="inputSubject" ><?php echo geraOpcao("ig_local","","") ?></select>
						</div>
					</div>	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><label>Ingressos disponíveis</label><input type="text" class="form-control" id="" placeholder="">
						</div>
						<div class=" col-md-6"><label>Ingressos reservados</label>
							<input type="email" class="form-control" id="" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<button type="button" class="btn btn-theme btn-lg btn-block">Inserir ocorrência</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>