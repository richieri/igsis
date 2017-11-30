<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h1><font color="red">EM CONSTRUÇÃO</font></h1>
						<h4>Buscar Eventos no CAPAC</h4>
						<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>
					</div>
				</div>
			</div>
	        <div class="row">
				<form method="POST" action="?perfil=contratos&p=frm_busca" class="form-horizontal" role="form">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
						<label>Código do cadastro no CAPAC</label>
							<input type="text" name="idEvento" class="form-control" id="palavras" placeholder="Insira o Código do Evento" ><br />
						<label>Nome do Evento</label>
							<input type="text" name="evento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />
						<label>Tipo de evento</label>
							<select class="form-control" name="tipo" id="inputSubject" >
								<option value="0"></option>
								<?php echo geraOpcao("ig_tipo_evento","","") ?>
							</select>
						<br />
					</div>
				</div>
				<br />
	            <div class="form-group">
		            <div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="pesquisar" value="1" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
        	    	</div>
        	    </div>
				</form>
			</div>
		</div>
	</section>