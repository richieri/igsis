<?php
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

$dataAtual = date('Y:m:d H:i:s');

header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=$dataAtual_hiphop_2019.xls" );

$con = bancoMysqli();
?>
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<body>
	<table class="table table-condensed">
		<thead>
			<tr class="list_menu">
				<td>Codigo do Pedido</td>
				<td>Número do Processo</td>
				<td>Tipo Pessoa</td>
				<td>Documento</td>
				<td>Proponente</td>
				<td>Objeto</td>
				<td>Local</td>
				<td>Periodo</td>
				<td>Pendências</td>
				<td>Valor</td>
				<td>Usuário</td>
				<td>Status</td>
			</tr>
		</thead>
		<tbody>
	<?php
		$sql_enviados = "SELECT eve.idEvento, ped.idPedidoContratacao, ped.tipoPessoa, ped.idPessoa, eve.nomeEvento, ped.valor, proj.projetoEspecial, st.estado,eve.dataEnvio, eve.idUsuario, usr.nomeCompleto
			FROM ig_evento AS eve
			INNER JOIN igsis_pedido_contratacao AS ped ON eve.idEvento=ped.idEvento
			INNER JOIN ig_projeto_especial AS proj ON eve.projetoEspecial=proj.idProjetoEspecial
            LEFT JOIN sis_estado As st ON ped.estado = st.idEstado
            INNER JOIN ig_usuario AS usr ON eve.idUsuario = usr.idUsuario
			WHERE eve.publicado=1 AND ped.publicado=1 AND eve.projetoEspecial = 74
			ORDER BY idPedidoContratacao DESC";
		$query_enviados = mysqli_query($con,$sql_enviados);
		while($pedido = mysqli_fetch_array($query_enviados))
		{
			$pj = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
			$ped = siscontrat($pedido['idPedidoContratacao']);
			if($ped['tipoPessoa'] == 1)
			{
				$link="?perfil=contratos&p=frm_edita_propostapf&id_ped=";
			}
			else
			{
				$link="?perfil=contratos&p=frm_edita_propostapj&id_ped=";
			}
			echo "<tr><td class='lista'>".$pedido['idPedidoContratacao']."</td>";
			echo '<td class="list_description">'.$ped['NumeroProcesso'].'</td>';
			if($ped['tipoPessoa'] == 1)
			{
				$pessoa = recuperaDados("sis_pessoa_fisica",$pedido['idPessoa'],"Id_PessoaFisica");
				echo '<td class="list_description">Física</td>';
				echo '<td class="list_description">'.$pessoa['CPF'].'</td>';
				echo '<td class="list_description">'.$pessoa['Nome'].'</td>';
			}
			else
			{
				$pessoa = recuperaDados("sis_pessoa_juridica",$pedido['idPessoa'],"Id_PessoaJuridica");
				echo '<td class="list_description">Jurídica</td>';
				echo '<td class="list_description">'.$pessoa['CNPJ'].'</td>';
				echo '<td class="list_description">'.$pessoa['RazaoSocial'].'</td>';
			}
			echo '
			<td class="list_description">'.$ped['Objeto'].'</td>
			<td class="list_description">'.$ped['Local'].'</td>
			<td class="list_description">'.$ped['Periodo'].'</td>
			<td class="list_description">'.$ped['pendenciaDocumento'].'</td>
			<td class="list_description">'.dinheiroParaBr($ped['ValorGlobal']).'</td>
			<td class="list_description">'.$pedido['nomeCompleto'].'</td>
			<td class="list_description">'.$pedido['estado'].'</td>';
			echo "</tr>";
		}
	?>
		</tbody>
	</table>
</body>
</html>