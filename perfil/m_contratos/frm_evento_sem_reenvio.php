<?php 
include 'includes/menu.php';
	
$con = bancoMysqli();

$sql = "SELECT ped.idPedidoContratacao, eve.idEvento, ig_tipo_evento_idTipoEvento, nomeEvento, ped.tipoPessoa, ped.idPessoa, ped.parcelas, ped.valor, reab.data, iu.nomeCompleto as reabertoPor, ii.sigla, iuo.nomeCompleto as operador
FROM  ig_evento AS eve
INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento = ped.idEvento
INNER JOIN  ig_log_reabertura AS reab ON ped.idPedidoContratacao = reab.idPedido
LEFT JOIN ig_usuario iu on reab.idUsuario = iu.idUsuario
LEFT JOIN ig_usuario iuo on ped.idContratos = iuo.idUsuario
LEFT JOIN ig_instituicao ii on eve.idInstituicao = ii.idInstituicao
WHERE eve.dataEnvio IS NULL AND eve.publicado = 1 AND ped.publicado = 1 AND reab.data > '2021-01-01' GROUP BY idPedidoContratacao ORDER BY idPedidoContratacao DESC";
$listas = $con->query($sql);
?>

<section id="list_items">
	<h1>&nbsp;</h1>
	<div class="container">
		<div class="sub-title"><h2>EVENTOS REABERTOS E SEM REENVIO</h2></div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Codigo do Pedido</td>
						<td>Proponente</td>
						<td>Objeto</td>
						<td>Valor</td>
						<td width="20%">Local</td>
						<td>Periodo</td>
						<td>Data Reabertura</td>
						<td>Reaberto Por</td>
						<td>Prazo (Dias)</td>
						<td>Operador</td>
						<td></td>
					</tr>
				</thead>
				<tbody>		
				<?php
                $i=0;
                foreach ($listas as $lista){
                    if ($lista['tipoPessoa'] == 1){
                        $proponente_sql = $con->query("SELECT Nome FROM sis_pessoa_fisica WHERE Id_PessoaFisica = '{$lista['idPessoa']}'")->fetch_assoc();
                        $proponente = $proponente_sql['Nome'] ?? null;
                    } else{
                        $proponente_sql = $con->query("SELECT RazaoSocial FROM sis_pessoa_juridica WHERE Id_PessoaJuridica = '{$lista['idPessoa']}'")->fetch_assoc();
                        $proponente = $proponente_sql['RazaoSocial'] ?? null;
                    }

                    $dataPrazo = date('d/m/Y', strtotime('-5 days', strtotime(retornaPrazo($lista['idEvento']))));

                    $dataInicial = retornaPrazo($lista['idEvento']);
                    $dataFinal = exibirDataMysql($dataPrazo);

                    $diferenca = strtotime($dataFinal) - strtotime(date('Y-m-d'));

                    $dias = floor($diferenca / (60 * 30 * 24));
                    ?>
                    <tr>
                        <td class="list_description"><a target='_blank' href='?perfil=detalhe_pedido&id_ped=<?= $lista['idPedidoContratacao'] ?>'><?= $lista['idPedidoContratacao'] ?></a></td>
                        <td class="list_description"><?= $proponente ?></td>
                        <td class="list_description"><?=retornaTipo($lista['ig_tipo_evento_idTipoEvento'])." - ".$lista['nomeEvento']?></td>
                        <td class="list_description"><?= $lista['valor']?></td>
                        <td class="list_description"><?= substr(listaLocais($lista['idEvento']), 1) ?></td>
                        <td class="list_description"><?= retornaPeriodo($lista['idEvento']) ?></td>
                        <td class="list_description"><?= exibirDataBr($lista['data'])?></td>
                        <td class="list_description"><?= $lista['reabertoPor']?></td>
                        <td class="list_description"><?= $dias ?></td>
                        <td class="list_description"><?= $lista['operador']?></td>
                    </tr>
                <?php
                    $i++;
                }

				?>
				</tbody>
			</table>
		</div>
        <p>Foram encontrados <?= $i ?> registros</p>
	</div>
</section>