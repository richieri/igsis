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
					<form method="POST" action="?perfil=comunicacao&p=filtro_resultado" class="form-horizontal" role="form">
					<div class="form-group">					
						<div class="col-md-offset-2 col-md-8"> 
							| <input type="checkbox" name="editado" /> <label>Editado</label>  |
							<input type="checkbox" name="revisado" /> <label>Revisado</label> |
							<input type="checkbox" name="site" /> <label>Site</label> | 
							<input type="checkbox" name="publicacao" /> <label>Impresso</label> |
							<input type="checkbox" name="foto" /> <label>Foto</label> |
						</div>
					</div>
					<br />             
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="filtrar" value="1" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Filtrar">                    
						</div>
					</div>
					</form>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><br/></div>
					</div>
					<form method="POST" action="?perfil=comunicacao&p=todos_eventos" class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-4 col-md-6">
							<input type="hidden" name="todos" value="1" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="Todos os eventos">                    
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>             
