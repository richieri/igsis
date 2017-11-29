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
						<label>Código do Pedido</label>
							<input type="text" name="id" class="form-control" id="palavras" placeholder="Insira o Código do Pedido" ><br />
						<label>ID do Evento</label>
							<input type="text" name="idEvento" class="form-control" id="palavras" placeholder="Insira o Código do Evento" ><br />
						<label>Nome do Evento</label>
							<input type="text" name="evento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />
						<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
							<select class="form-control" name="fiscal" id="inputSubject" >
								<option value="0"></option>	
								<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
							</select>
						<br />
						<label>Tipo de evento</label>
							<select class="form-control" name="tipo" id="inputSubject" >
								<option value="0"></option>		                
								<?php echo geraOpcao("ig_tipo_evento","","") ?>
							</select>	
						<br />
						<label>Instituição</label>
							<select class="form-control" name="instituicao" id="inputSubject" >
								<option value="0"></option>
								<?php echo geraOpcao("ig_instituicao","","") ?>
							</select>		
						<br />
						<label>Status do pedido</label>
							<select class="form-control" name="estado" id="inputSubject" >
								<option value='0'></option>
								<?php echo geraOpcao("sis_estado","","") ?>
							</select>		
						<label>Tipo de Relação Jurídica</label>
							<select class="form-control" name="juridico" id="inputSubject" >
								<option value='0'></option>
								<?php  geraOpcao("ig_modalidade","",""); ?>
							</select>						
						<label>Tipo de Projeto</label>
							<select class="form-control" name="projeto" id="inputSubject" >
								<option value='0'></option>
								<?php  geraOpcao("ig_projeto_especial","",""); ?>
						</select>
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