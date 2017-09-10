<?php  
 require_once("../funcoes/funcoesVerifica.php");
 require_once("../funcoes/funcoesSiscontrat.php");
 include "includes/menu.php"; 
?>
 
<?php
if(isset($_GET['b']))
{
	$b = $_GET['b'];	
}
else
{
	$b = 'inicial';
}

switch($b)
{
case 'inicial':
	if(isset($_POST['pesquisar']))
	{
		$operador = $_POST['operador'];

		if($operador == 0)
		{ ?>
			<section id="services" class="home-section bg-white">
				<div class="container">
					<div class="row">
						<div class="col-md-offset-2 col-md-8">
							<div class="section-heading">
								<h3>Busca por Operador de Contratos</h3>
								<p>É preciso ao menos um critério de busca ou você pesquisou por um evento inexistente. Tente novamente.</p>
							</div>
						</div>
					</div>
					<div class="row">
					<form method="POST" action="?perfil=contratos&p=frm_por_operador" class="form-horizontal" role="form">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
								<label>Operador do Contrato</label>
								<select class="form-control" name="operador" id="inputSubject" >
									<option value='0'></option>
									<?php  geraOpcaoContrato(""); ?>
								</select>
							</div>
						</div>
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
			
		<?php
		}
		else
		{
			$con = bancoMysqli();
			
			$sql_evento = "SELECT * FROM ig_evento, igsis_pedido_contratacao WHERE ig_evento.publicado = '1' AND igsis_pedido_contratacao.publicado = '1' AND igsis_pedido_contratacao.idEvento = ig_evento.idEvento AND idContratos = '$operador' AND dataEnvio IS NOT NULL ORDER BY ig_evento.idEvento DESC";
			$query_evento = mysqli_query($con,$sql_evento);
			$i = 0;
	
			while($ped = mysqli_fetch_array($query_evento))
			{
				$pedido = recuperaDados("igsis_pedido_contratacao",$ped['idPedidoContratacao'],"idPedidoContratacao");
				$evento = recuperaDados("ig_evento",$pedido['idEvento'],"idEvento"); 
				$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
				$instituicao = recuperaDados("ig_instituicao",$evento['idInstituicao'],"idInstituicao");
				$local = listaLocais($pedido['idEvento']);
				$periodo = retornaPeriodo($pedido['idEvento']);
				$pessoa = recuperaPessoa($pedido['idPessoa'],$pedido['tipoPessoa']);
				$operador = recuperaUsuario($pedido['idContratos']);
				
				$x[$i]['id']= $pedido['idPedidoContratacao'];
				$x[$i]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
				switch($pedido['tipoPessoa'])
				{
					case 1:
						$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
						$x[$i]['proponente'] = $pessoa['Nome'];
						$x[$i]['tipo'] = "Física";
						$x[$i]['pessoa'] = 1;
					break;
					case 2:
						$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
						$x[$i]['proponente'] = $pessoa['RazaoSocial'];
						$x[$i]['tipo'] = "Jurídica";
						$x[$i]['pessoa'] = 2;
					break;
					case 4:
						$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
						$x[$i]['proponente'] = $pessoa['Nome'];
						$x[$i]['tipo'] = "Formação";
						$x[$i]['pessoa'] = 4;
					break;
				}
				$x[$i]['local'] = substr($local,1);
				$x[$i]['instituicao'] = $instituicao['sigla'];
				$x[$i]['periodo'] = $periodo;
				$x[$i]['status'] = $pedido['estado'];	
				$x[$i]['operador'] = $operador['nomeCompleto'];		
				$i++;			
}
			$x['num'] = $i;				
		} 
		$mensagem = "Foram encontrados ".$x['num']." pedido(s) de contratação.";
?>
	<section id="list_items">
		<div class="container">
			<h3>&nbsp; </h3>
            <h5>Foram encontrados <?php echo $x['num']; ?> pedidos de contratação.</h5>
            <h5><a href="?perfil=contratos&p=frm_por_operador">Fazer outra busca</a></h5>
			<div class="table-responsive list_info">
						
			<?php 
				if($x['num'] == 0)
				{
				}
				else
				{ 
				?>
					<table class="table table-condensed">
						<thead>
							<tr class="list_menu">
								<td>Codigo do Pedido</td>
								<td>Proponente</td>
								<td>Tipo</td>
								<td>Objeto</td>
								<td width="20%">Local</td>
								<td>Instituição</td>
								<td>Periodo</td>
								<td>Status</td>
								<td>Operador</td>
							</tr>
						</thead>
						<tbody>		
						<?php
							$link="index.php?perfil=gestao_prazos&p=detalhe_evento&id_eve=";
							$data=date('Y');
							
							for($h = 0; $h < $x['num']; $h++)
							{
								$status = recuperaDados("sis_estado",$x[$h]['status'],"idEstado");
								switch($x[$h]['pessoa'])
								{
									case 1:
										echo "<tr><td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapf&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
									break;
									case 2:
										echo "<tr><td class='lista'> <a href='?perfil=contratos&p=frm_edita_propostapj&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
									break;
									case 4:
										echo "<tr><td class='lista'> <a href='?perfil=contratos&p=frm_edita_proposta_formacao&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
									break;	
								}
								echo '<td class="list_description">'.$x[$h]['proponente'].					'</td> ';
								echo '<td class="list_description">'.$x[$h]['tipo'].					'</td> ';
								echo '<td class="list_description">'.$x[$h]['objeto'].						'</td> ';
								echo '<td class="list_description">'.$x[$h]['local'].				'</td> ';
								echo '<td class="list_description">'.$x[$h]['instituicao'].				'</td> ';
								echo '<td class="list_description">'.$x[$h]['periodo'].						'</td> ';
								echo '<td class="list_description">'.$status['estado'].						'</td> ';
								echo '<td class="list_description">'.$x[$h]['operador'].						'</td> </tr>';

							}
						?>
						</tbody>
					</table>
				<?php 
				} 
				?>		
			</div>			
		</div>
	</section>

	<?php
	}
	else
	{
	?>
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
			    <div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h3>Busca por Operador de Contratos</h3>
					</div>
				</div>
			</div>
	        <div class="row">
			<form method="POST" action="?perfil=contratos&p=frm_por_operador" class="form-horizontal" role="form">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
						<label>Operador do Contrato</label>
						<select class="form-control" name="operador" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcaoContrato(""); ?>
						</select>
					</div>
				</div>
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

	<?php 
	} 
	break;	
} // fim da switch
?>