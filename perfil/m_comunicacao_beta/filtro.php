<?php include 'include/menu.php';

$idInstituicao = $_SESSION['idInstituicao'];
unset($_SESSION['editado']);
unset($_SESSION['revisado']);
unset($_SESSION['site']);
unset($_SESSION['publicacao']);
unset($_SESSION['foto']);
?>
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h3>Filtrar por:</h3>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<form method="POST" action="?perfil=comunicacao_beta&p=filtro_resultado" class="form-horizontal" role="form">
					<div class="form-group">					
						<div class="col-md-offset-1 col-md-2" align="left"> 
							<label>Editado</label> <br/>
							<input type="checkbox" name="editado_c" /> Confirmado<br/>
							<input type="checkbox" name="editado_p" /> Pendente
						</div>
						<div class="col-md-2" align="left">
							<label>Revisado</label> <br/>
							<input type="checkbox" name="revisado_c" /> Confirmado<br/>
							<input type="checkbox" name="revisado_p" /> Pendente
						</div>
						<div class="col-md-2" align="left">
							<label>Site</label> <br/>
							<input type="checkbox" name="site_c" /> Confirmado<br/>
							<input type="checkbox" name="site_p" /> Pendente
						</div>
						<div class="col-md-2" align="left">
							<label>Impresso</label> <br/>
							<input type="checkbox" name="publicacao_c" /> Confirmado<br/>
							<input type="checkbox" name="publicacao_p" /> Pendente
						</div>
						<div class="col-md-2" align="left">
							<label>Foto</label><br/>
							<input type="checkbox" name="foto_c" /> Confirmado<br/>
							<input type="checkbox" name="foto_p" /> Pendente
						</div>
					</div>
					<br />             
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="filtrar" value="1" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Filtrar">                    
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><br/></div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-4 col-md-6">
							<input type="hidden" name="filtrar" value="1" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Todos os eventos">                    
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>             
