<?php 
include 'includes/menu.php';

$_SESSION['idPedido'] = $_GET['id_ped'];
$id_ped = $_GET['id_ped'];

$idModelo = $_GET['idModelo'];
$modelo = recuperaDados("sis_modelos_juridico", $idModelo, "idModelo");

$pedido = siscontrat($id_ped);
$pf = siscontratDocs($pedido['IdProponente'],1);	
$pj = siscontratDocs($pedido['IdProponente'],2);

$tipoPessoa = $pedido['tipoPessoa'];
$fiscal=$pedido["Fiscal"];
$suplente=$pedido["Suplente"];
$rfSuplente=$pedido["RfSuplente"];
$rfFiscal=$pedido["RfFiscal"];

$amparo=$modelo['amparo'];
$final=$modelo['finalizacao']; 

$final = str_replace("nomeFiscal", $fiscal, $final);
$final = str_replace("rfFiscal", $rfFiscal, $final);
$final = str_replace("nomeSuplente", $suplente, $final);
$final = str_replace("rfSuplente", $rfSuplente, $final);
?>

<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
			<div class="sub-title"><h3>DESPACHO DE PESSOA 
			<?php
				if ($pedido['tipoPessoa'] == 1)
				{
					echo "FÍSICA";
				}
				else
				{
					echo "JURÍDICA";
				}
			?>
			</h3></div>
		</div>
  		<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
                <div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><h5><?php echo $pedido['Objeto'];?></h5>
						<a class="btn btn-theme btn-lg btn-block" href="?perfil=detalhes_contrato&id_ped=<?php echo "$id_ped";?>" target="_blank">Detalhes do Evento</a>
                    </div>
                </div>
				
				<div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><br/></div>
                </div>				  
				  
				<form class="form-horizontal" role="form" action="?perfil=juridico&p=update_juridico&id_ped=<?php echo $_GET['id_ped']; ?>" method="post">	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Código do Pedido de Contratação:</strong><br/><?php echo "$id_ped";?>			</div>                                        
					<div class="col-md-6"><strong>Número do Processo:</strong><br/><?php echo $pedido['NumeroProcesso']; ?>
					</div>
                </div> 			  
				  
				<div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Contratado:</strong><br/>
					<?php 
						if ($pedido['tipoPessoa'] == 1)
						{
							echo $pf['Nome'];
						}
						else
						{
							echo $pj['Nome'];
						}
					?>
                    </div>
                </div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Local:</strong><br/>
						<?php echo $pedido['LocalJuridico'];?>
					</div>
				</div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Valor:</strong><br/>
						<?php echo $pedido['ValorGlobal'];?>					  
					</div>                                        
					<div class="col-md-6"><strong>Período:</strong><br/>
						<?php echo $pedido['Periodo']; ?>
					</div>
                </div>
				  
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Forma de Pagamento:</strong><br/>
						<?php echo $pedido['FormaPagamento'];?>
					</div>
				</div>		
				  
                <div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Amparo:</strong><br/>
						<textarea name="AmparoLegal" cols="40" rows="8"> <?php echo $amparo ?></textarea>
                    </div>
                  </div>  
                  
                <div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Dotação Orçamentária</strong><br />
						<input type="text" name="ComplementoDotacao" class="form-control" id="ComplementoDotacao">
					</div>
				</div>
				  
                <div class="form-group"> 
					<div class="col-md-offset-2 col-md-8"><strong>Finalização:</strong><br/>
						<textarea name="Finalizacao" cols="40" rows="8"> <?php echo $final ?></textarea>
                    </div>
                </div> 
                                    
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="submit" name="enviar" value="GRAVAR" class="btn btn-theme btn-lg btn-block">					
				   </div>
				</div>
				</form>
			</div>
		</div>
	</div>
</section>