<?php
	$lista = lista_prazo(200,1,"DESC"); //esse gera uma array com os pedidos
$con = bancoMysqli();

//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
$sql_lista = "SELECT ped.idEvento FROM igsis_pedido_contratacao AS ped INNER JOIN ig_evento AS eve ON ped.idEvento = eve.idEvento WHERE eve.dataEnvio IS NULL AND eve.publicado = 1 AND ped.publicado = 1 AND eve.statusEvento = 'Aguardando' GROUP BY eve.idEvento ORDER BY eve.idEvento DESC";
$query_lista = mysqli_query($con,$sql_lista);

//conta o total de itens
$total_geral = mysqli_num_rows($query_lista);

//seta a quantidade de itens por página
$registros = 25;

//calcula o número de páginas arredondando o resultado para cima
$numPaginas = ceil($total_geral/$registros);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($registros*$pagina)-$registros;

//seleciona os itens por página
$sql_lista = "
	SELECT ped.idEvento, ped.idPedidoContratacao, ped.tipoPessoa, ped.idPessoa, ped.instituicao, uope.nomeCompleto AS operador, uresp.nomeCompleto AS fiscal, tipo.tipoEvento, eve.nomeGrupo, eve.nomeEvento, eve.dataEnvio 
	FROM igsis_pedido_contratacao AS ped 
	INNER JOIN ig_evento AS eve ON ped.idEvento = eve.idEvento
    INNER JOIN ig_tipo_evento AS tipo ON tipo.idTipoEvento = eve.ig_tipo_evento_idTipoEvento
    INNER JOIN ig_usuario AS uresp ON uresp.idUsuario = eve.idResponsavel
	LEFT JOIN  ig_usuario AS uope ON uope.idUsuario = ped.idContratos
	WHERE eve.dataEnvio IS NULL AND eve.publicado = 1 AND ped.publicado = 1 AND eve.statusEvento = 'Aguardando' 
	GROUP BY eve.idEvento ORDER BY eve.idEvento DESC
	LIMIT $inicio,$registros";
$query_lista = mysqli_query($con,$sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);


$link="index.php?perfil=gestao_prazos&p=detalhe_evento&id_eve=";


/****************************************
 * ENVIO DE DADOS
 ****************************************/

	if(isset($_POST['finalizar']))
	{
		$con = bancoMysqli();
		$datetime = date("Y-m-d H:i:s");
		$instituicao = $_SESSION['idInstituicao'];
		$idEvento = $_SESSION['idEvento'];
		$sql_atualiza_evento = "UPDATE ig_evento SET dataEnvio = '$datetime', statusEvento = 'Enviado' WHERE idEvento = '$idEvento'";
		$query_atualiza_evento = mysqli_query($con,$sql_atualiza_evento);
		if($query_atualiza_evento)
		{
			gravarLog($sql_atualiza_evento);
			atualizarAgenda($idEvento);	
			$sql_data_envio = "INSERT INTO `ig_data_envio`(`idEvento`, `dataEnvio`) VALUES ('$idEvento', '$datetime')";
			$query_data_envio = mysqli_query($con,$sql_data_envio);
		
			if($query_data_envio)
			{
				$sql_atualiza_pedido = "UPDATE `igsis`.`igsis_pedido_contratacao` SET 
				`estado` = '2'
				WHERE `igsis_pedido_contratacao`.`idEvento` = '$idEvento' AND `igsis_pedido_contratacao`.`publicado` = '1' ";
				$query_atualiza_pedido = mysqli_query($con,$sql_atualiza_pedido);
			
				if($query_atualiza_evento)
				{
					gravarLog($sql_atualiza_pedido);						
					$mensagem = "O formulário de evento foi enviado com sucesso!<br/><h5>O número IG é ".$idEvento."</h5>
						Refira-se a este número ao entrar em contato com as áreas de Comunicação e Produção.<br /><br /><br />";
					$sql_recupera_pedidos = "SELECT * FROM igsis_pedido_contratacao WHERE idEvento = '$idEvento' AND publicado = '1'";
					$query_recupera_pedidos = mysqli_query($con,$sql_recupera_pedidos);
					$num_pedidos = mysqli_num_rows($query_recupera_pedidos);
					if($num_pedidos > 0)
					{						
						while($pedido = mysqli_fetch_array($query_recupera_pedidos))
						{
							$idPedido = $pedido['idPedidoContratacao'];
							$i = 0;
							if($sql_atualiza_pedido)
							{								
								$pedidos[$i] = $idPedido;
								$mensagem = $mensagem."Foi gerado um <strong>pedido de contratação</strong> com número <h5>".$pedidos[$i]."</h5>
								Este número é a referência para as áreas de Contratos, Jurídico, Finanças, Contabilidade entre outros.<br />
								<strong><a target='_blank' href='?perfil=detalhe_pedido&id_ped=".$pedidos[$i]."'>Clique aqui caso queira visualizar os detalhes desta contratação.</a></strong>
								<br /><br />
								<a href='http://smcsistemas.prefeitura.sp.gpv.br/igsis/manual/index.php/introducao-ao-sistema-igsis/numero-igpedido-de-contratacao/' target='_blank'>Saiba mais sobre os números gerados no nosso <i>Manual do Sistema</i></a>.<br /><br /><br />
								";
								$i++;
							}
						}
					}
				}
				else
				{
					$mensagem = "Erro ao enviar o pedido de contratação. Contacte o administrador do sistema.";
				}
			}
			else
			{
				$mensagem = "Erro ao registrar data de envio. Contacte o administrador do sistema.";
			}
		}
		else
		{
			$mensagem = "Erro ao enviar formulário";	
		}
		// Gera um registro em ig_comunicacao
		$sql_pesquisar = "SELECT * FROM ig_evento WHERE idEvento = '$idEvento'";
		$query = mysqli_query($con,$sql_pesquisar);
		while($importa = mysqli_fetch_array($query))
		{
			$sql_importar = "INSERT INTO `igsis`.`ig_comunicacao` (`sinopse`, `fichaTecnica`, `autor`, `projeto`, `releaseCom`, `ig_evento_idEvento`, `nomeEvento`, `ig_tipo_evento_idTipoEvento`, `ig_programa_idPrograma`, `idInstituicao`) 
				SELECT `sinopse`, `fichaTecnica`, `autor`, `projeto`,`releaseCom`, `idEvento`, `nomeEvento`, `ig_tipo_evento_idTipoEvento`, `ig_programa_idPrograma`, `idInstituicao` FROM `ig_evento` WHERE `idEvento` = '$idEvento'";
			$query_importar = mysqli_query($con,$sql_importar);
			if($query_importar)
			{
				$mensagem_com = "Registro na Divisão de Comunicação e Informação efetuado com sucesso.";
			}
			else
			{
				$mensagem_com = "Erro ao registrar evento na Divisão de Comunicação e Informação.";
			}
		}	
		
		$_SESSION['idEvento'] = NULL;
	}
?>

<?php include 'includes/menu.php';?>

<section id="list_items">
	<div class="container">
		<div class="sub-title"><br/><br/><h4>PEDIDOS DE CONTRATAÇÃO</h4></div>
        <p><strong>Total de registros:</strong> <?php echo $total_geral;?> | <strong>Registros nesta página:</strong> <?php echo $total;?></p>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
					<td>Id Evento</td>
					<td>Pedido(s)</td>
					<td>Proponente</td>
					<td>Objeto</td>
					<td>Local</td>
					<td>Período</td>
                    <td>Fiscal</td>
                    <td>Operador</td>
					<td></td>
					<td></td>
					</tr>
				</thead>

				<?php
					echo "<tbody>";
                while($lista = mysqli_fetch_array($query_lista))
                {
                    $pedidos = listaTodosPedidos($lista['idEvento']);
                    $chamado = recuperaAlteracoesEvento($lista['idEvento']);
                    $local = listaLocais($lista['idEvento']);
                    $periodo = retornaPeriodo($lista['idEvento']);
                    //$fiscal = recuperaUsuario($lista['idResponsavel']);
                    //$operador = recuperaUsuario($lista['idContratos']);
                    $pessoa = recuperaPessoa($lista['idPessoa'],$lista['tipoPessoa']);
                    echo "<tr>";
                    echo "<td class='lista'> <a target='_blank' href='".$link.$lista['idEvento']."'>".$lista['idEvento']."</a></td>";
                    echo '<td class="list_description">'.$pedidos.'</td> ';
                    echo '<td class="list_description">'.$pessoa['nome'].'</td> ';
                    echo '<td class="list_description">'.$lista['tipoEvento']." - ".$lista['nomeGrupo']." - ".$lista['nomeEvento'].' [';
                        if($chamado['numero'] == '0') {
                            echo "0";
                        }
                        else{
                            echo "<a href='?perfil=chamado&p=evento&id=".$lista['idEvento']."' target='_blank'>".$chamado['numero']."</a>";
                        }
                    echo '] </td> ';
                    echo '<td class="list_description">'.substr($local,1).'</td> ';
                    echo '<td class="list_description">'.$periodo.'</td> ';
                    echo '<td class="list_description">'.strstr($lista['fiscal'], ' ', true).'</td>';
                    echo '<td class="list_description">'.strstr($lista['operador'], ' ', true).'</td>';
                    echo "<td class='list_description'>
						<form method='POST' target='_blank' action='?perfil=gestao_prazos&p=detalhe_evento&pag=finalizar&id_eve=".$lista['idEvento']."'>
						<input type='hidden' name='finalizar' value='".$lista['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='enviar'></td></form>"	;
                    echo "<td class='list_description'>
						<form method='POST' target='_blank'  action='?perfil=gestao_prazos&p=detalhe_evento&pag=desaprovar&id_eve=".$lista['idEvento']."'>
						<input type='hidden' name='carregar' value='".$lista['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='não aprovar'></td></form>";
                    echo "</tr>";
                }?>
					<tr>
						<td colspan="10" bgcolor="#DEDEDE">
						<?php
							//exibe a paginação
							echo "<strong>Páginas</strong>";
							for($i = 1; $i < $numPaginas + 1; $i++)
							{
								echo "<a href='?perfil=gestao_prazos&p=frm_lista&pagina=$i'> [".$i."]</a> ";
							}
						?>
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
	</div>
</section>