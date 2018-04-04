<?php
$con = bancoMysqliProponente();

include '../include/menuEventoInicial.php';
unset($_SESSION['idCapac']);
unset($_SESSION['nomeEvento']);
unset($_SESSION['nomeGrupo']);
unset($_SESSION['tipoEvento']);
?>

<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>BUSCAR EVENTOS NO CAPAC</h3>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-6">
				<form method="POST" action="?perfil=capac_lista_pf" class="form-horizontal" role="form">
					<input type="submit" class="btn btn-theme btn-md btn-block" Value="Todos PF">
				</form>
			</div>
			<div class="col-md-6">
				<form method="POST" action="?perfil=capac_lista_pj" class="form-horizontal" role="form">
					<input type="submit" class="btn btn-theme btn-md btn-block" Value="Todos PJ">
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<br/>
				<h5><?php if(isset($mensagem)){echo $mensagem;};?></h5>
				<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>

				<form method="POST" action="?perfil=capac_busca_resultado" class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Código do cadastro no CAPAC</strong><br/>
							<input type="text" name="idCapac" class="form-control" placeholder="Insira o Código do Evento" >
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome do Evento</strong><br/>
							<input type="text" name="nomeEvento" class="form-control" placeholder="Insira nome do evento" >
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome do Grupo</strong><br/>
							<input type="text" name="nomeGrupo" class="form-control" placeholder="Insira o nome do grupo" >
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Tipo de evento</strong><br/>
							<select class="form-control" name="tipoEvento" id="inputSubject" >
								<option value="0"></option>
								<?php
								$sql = "SELECT * FROM tipo_evento";
								$query = mysqli_query($con,$sql);
								while($option = mysqli_fetch_row($query))
								{
									echo "<option value='".$option[0]."'>".$option[1]."</option>";
								}
								?>
							</select>
						</div>
					</div>
		            <div class="form-group">
			            <div class="col-md-offset-2 col-md-8">
							<input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Pesquisar">
	        	    	</div>
	        	    </div>
				</form>
			</div>
		</div>
	</div>
</section>