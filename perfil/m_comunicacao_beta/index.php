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
<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
					<h3>Bem-vindo(a) à IGSIS!</h3>
                    <p>&nbsp;</p>
                    <h2>Módulo Comunicação Beta</h2>
                    <p>&nbsp;</p>
					<h6>Esse módulo tem a função de gerenciar a comunicação da instituição e preparar as informações a serem enviadas para o SPCultura.</h6>
                </div>
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