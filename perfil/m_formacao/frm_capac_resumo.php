<?php

unset($_SESSION['idCapacPf']);
unset($_SESSION['nomeEvento']);
unset($_SESSION['nomeGrupo']);
unset($_SESSION['tipoEvento']);

$idCapac = $_GET['id_capac'];
$con = bancoMysqliProponente();


include 'includes/menu.php';

$sql_funcao = "SELECT ff.id, ff.funcao FROM pessoa_fisica AS pf
                INNER JOIN formacao_funcoes AS ff ON pf.formacao_funcao_id = ff.id
                WHERE pf.id = '$idCapac'";
$funcao = mysqli_fetch_assoc(mysqli_query($con, $sql_funcao));

function funcoesAdicionais($id) {
    $con = bancoMysqliProponente();

    $sql = "SELECT ff1.funcao AS 'funcao2', ff2.funcao AS 'funcao3' FROM formacao_dados_complementares AS fdc
            INNER JOIN formacao_funcoes AS ff1 ON fdc.area_atuacao_2 = ff1.id
            INNER JOIN formacao_funcoes AS ff2 ON fdc.area_atuacao_3 = ff2.id
            WHERE fdc.pessoa_fisica_id = '$id'";

    $outrasFuncoes = $con->query($sql)->fetch_assoc();

    return $outrasFuncoes;
}

function recuperaDadosCapac($tabela, $campo, $valor)
{
    $con = bancoMysqliProponente();
    $sql = "SELECT * FROM $tabela WHERE ".$campo." = '$valor' LIMIT 0,1";
    $query = mysqli_query($con,$sql);
    $campo = mysqli_fetch_array($query);
    return $campo;
}

function recuperaBanco($campoY)
{
    $banco = recuperaDadosCapac("banco",'id', $campoY);
    $nomeBanco = $banco['banco'];
    return $nomeBanco;
}

function listaArquivoCamposMultiplos1($idPessoa, $tipoPessoa = 6)
{
    $con = bancoMysqliProponente();
    $sql = "SELECT *
				FROM upload_lista_documento as list
				INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
				WHERE arq.idPessoa = '$idPessoa'
				AND arq.idTipoPessoa = '$tipoPessoa'
				AND arq.publicado = '1'
				ORDER BY documento";

    $query = mysqli_query($con,$sql);
    $linhas = mysqli_num_rows($query);

    if ($linhas > 0)
    {
        echo "
		<table class='table table-condensed'>
			<thead>
				<tr class='list_menu'>
					<td>Nome do arquivo</td>
					<td width='10%'></td>
				</tr>
			</thead>
			<tbody>";
        while($arquivo = mysqli_fetch_array($query))
        {
            echo "<tr>";
            echo "<td class='list_description' width='5%'>".$arquivo['documento']."</td>";
            echo "<td class='list_description'><a href='../../igsiscapac/uploadsdocs/".$arquivo['arquivo']."' target='_blank'>".$arquivo['arquivo']."</td>";
            echo "</tr>";
        }
        echo "
		</tbody>
		</table>";
    }
    else
    {
        echo "<p>Não há arquivo(s) inserido(s).<p/><br/>";
    }
}

$pf = recuperaDadosCapac("pessoa_fisica",'id', $idCapac);
$estadoCivil = recuperaDadosCapac('estado_civil', 'id', $pf['idEstadoCivil']);
$etnia = recuperaDadosCapac('etnias', 'id', $pf['etnia_id']);
$grauInstrucao = recuperaDadosCapac('grau_instrucoes', 'id', $pf['grau_instrucao_id']);
$programa = recuperaDadosCapac('tipo_formacao', 'id', $pf['tipo_formacao_id']);
$documento = recuperaDadosCapac('tipo_documento', 'id', $pf['idTipoDocumento']);
$linguagem = recuperaDadosCapac('formacao_linguagem', 'id', $pf['formacao_linguagem_id']);
$regiaoPreferencial = recuperaDadosCapac('regioes', 'id', $pf['formacao_regiao_preferencial']);

?>
<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <h4><?= $pf['nome'] ?></h4>

            <div class="container">
                <div class="page-header"> <h5>Informações Pessoais</h5><br></div>
                <div class="well">
                    <p align="justify"><strong>Nome:</strong> <?= $pf['nome']; ?></p>
                    <p align="justify"><strong>Nome artístico:</strong> <?= $pf['nomeArtistico']; ?></p>
                    <p align="justify"><strong>Data de Nascimento:</strong> <?= date_format(date_create($pf['dataNascimento']), 'd/m/Y'); ?></p>
                    <p align="justify"><strong><?= $documento['tipoDocumento'] ?>:</strong> <?= $pf['rg']; ?><p>
                    <p align="justify"><strong>CPF:</strong> <?= $pf['cpf']; ?><p>
                    <p align="justify"><strong>CCM:</strong> <?= $pf['ccm']; ?><p>
                    <p align="justify"><strong>Email:</strong> <?= $pf['email']; ?><p>
                    <p align="justify"><strong>Telefone:</strong> <?= $pf['telefone1']; ?><p>
                    <p align="justify"><strong>Estado Civil:</strong> <?= $estadoCivil['estadoCivil']; ?><p>
                    <p align="justify"><strong>Nacionalidade:</strong> <?= $pf['nacionalidade']; ?><p>
                    <p align="justify"><strong>PIS/PASEP/NIT:</strong> <?= $pf['pis']; ?><p>
                    <p align="justify"><strong>Programa Selecionado:</strong> <?= $programa['descricao']; ?><p>
                </div>


                <div class = "page-header"><h5>Endereço: </h5><br></div>
                <div class="well">
                    <p align="justify"><strong>CEP:</strong> <?php echo $pf['cep']; ?></p>
                    <p align="justify"><strong>Logradouro:</strong> <?php echo $pf['logradouro']; ?></p>
                    <p align="justify"><strong>Número:</strong> <?php echo $pf['numero']; ?></p>
                    <p align="justify"><strong>Complemento:</strong> <?php echo $pf['complemento']; ?></p>
                    <p align="justify"><strong>Bairro:</strong> <?php echo $pf['bairro']; ?></p>
                    <p align="justify"><strong>Cidade:</strong> <?php echo $pf['cidade']; ?></p>
                    <p align="justify"><strong>Estado:</strong> <?php echo $pf['estado']; ?></p>
                </div>


                <div class = "page-header"><h5>Informações Complementares: </h5><br></div>
                <div class="well">
                    <p align="justify"><strong>DRT:</strong> <?php echo $pf['drt']; ?></p>
                    <p align="justify"><strong>Etnia:</strong> <?= $etnia['etnia']; ?><p>
                    <p align="justify"><strong>Grau de Instrução:</strong> <?= $grauInstrucao['grau_instrucao']; ?><p>
                    <p align="justify"><strong>Linguagem:</strong> <?= $linguagem['linguagem']; ?><p>
                    <p align="justify"><strong>Função:</strong> <?= $funcao['funcao']; ?><p>
                    <p align="justify"><strong>Região Preferencial:</strong> <?= $regiaoPreferencial['região']; ?><p>
                    <?php
                    $funcoes = [4,8];
                    if (!in_array($funcao['id'], $funcoes)) {
                        $funcoesAdicionais = funcoesAdicionais($idCapac);
                    ?>
                        <p align="justify"><strong>Função (2º Opção):</strong> <?= $funcoesAdicionais['funcao2']; ?><p>
                        <p align="justify"><strong>Função (3º Opção):</strong> <?= $funcoesAdicionais['funcao3']; ?><p>
                    <?php
                    }
                    ?>
                    <p align="justify"><strong>Banco:</strong> <?php echo recuperaBanco($pf['codigoBanco']); ?></p>
                    <p align="justify"><strong>Agência:</strong> <?php echo $pf['agencia']; ?></p>
                    <p align="justify"><strong>Conta:</strong> <?php echo $pf['conta']; ?></p>
                </div>

                <div class="table-responsive list_info"><h6>Arquivo(s) de Pessoa Física</h6>
                    <?php listaArquivoCamposMultiplos1($pf['id']); ?>
                </div>
            </div>
            <div class="col-md-offset-2 col-md-8">
                <a href="../perfil/m_formacao/includes/frm_capac_arquivos.php?idPf=<?= $idCapac ?>" class="btn btn-theme btn-md btn-block" target="_blank">Baixar todos os arquivos</a><br/>
            </div>
</section>