<?php
include 'includes/menu.php';
$con = bancoMysqli();
$idPf = $_GET['idPf'];

$pf = recuperaDados('sis_pessoa_fisica', $idPf, 'Id_PessoaFisica');

$estadoCivil = recuperaDados('sis_estado_civil', $pf['IdEstadoCivil'], 'Id_EstadoCivil')['EstadoCivil'];
$endereco = retornaEndereco($pf['CEP'],$pf['Numero'],$pf['Complemento']);

$sqlFoto = "SELECT arquivo FROM igsis_arquivos_pessoa WHERE idTipoPessoa = '1' AND idPessoa = '".$pf['Id_PessoaFisica']."' AND tipo = '29' AND publicado = '1'";
$foto = $con->query($sqlFoto)->fetch_assoc()['arquivo'];

$sqlFormacao = "SELECT * FROM sis_formacao WHERE IdPessoaFisica = $idPf AND publicado = '1' ORDER BY Ano DESC";
$formacao = $con->query($sqlFormacao);

/**
 * @param mysqli_result $formacao
 */
function resumoDadosFormacao($formacao) {
    $linhaFormacao = mysqli_fetch_all($formacao, MYSQLI_ASSOC);

//    Aba para cada ano encontrado
    echo "<ul class=\"nav nav-tabs\">";
    $i = 0;
    foreach ($linhaFormacao as $linha) {
        ?>
        <li class="nav <?=$i == 0 ? "active" : ""?>"><a href="#<?=$linha['Ano']?>" data-toggle="tab"><?=$linha['Ano']?></a></li>
        <?php
        $i++;
    }
    echo "</ul>";
//    fim das abas

    echo "<br>";
//    div com os dados de cada ano encontrado
    echo "<div class=\"tab-content\">";
    $i = 0;
    foreach ($linhaFormacao as $linha) {
        $pedido = recuperaDados('igsis_pedido_contratacao', $linha['idPedidoContratacao'], 'idPedidoContratacao');
        $equipamento1 = recuperaDados('ig_local', $linha['IdEquipamento01'], 'idLocal')['sala'];
        $equipamento2 = recuperaDados('ig_local', $linha['IdEquipamento02'], 'idLocal')['sala'];
        $cargo = recuperaDados('sis_formacao_cargo', $linha['IdCargo'], 'Id_Cargo')['Cargo'];
        $vigencia = retornaPeriodoVigencia($linha['idPedidoContratacao']);
        $valor = recuperaDados('igsis_pedido_contratacao', $linha['idPedidoContratacao'], 'idPedidoContratacao')['valor'];
        $miniCurriculo = recuperaDados('sis_pessoa_fisica_formacao', $linha['IdPessoaFisica'], 'IdPessoaFisica')['Curriculo'];
        $numProcesso = recuperaDados('igsis_pedido_contratacao', $linha['idPedidoContratacao'], 'idPedidoContratacao')['NumeroProcesso'];
        $cargaHoraria = retornaCargaHoraria($linha['idPedidoContratacao'], $pedido['parcelas']);
        ?>
        <div class="tab-pane fade in <?=$i == 0 ? "active" : ""?>" id="<?=$linha['Ano']?>">
            <p class='text-justify'><b>Equipamento:</b> <?=$equipamento1?> </p>
            <p class='text-justify'><b>Equipamento 2:</b> <?=$equipamento2?> </p>
            <p class='text-justify'><b>Cargo:</b> <?=$cargo?> </p>
            <p class='text-justify'><b>Vigência:</b> <?=$vigencia?> </p>
            <p class='text-justify'><b>Carga Horária Total:</b> <?=$cargaHoraria?> </p>
            <p class='text-justify'><b>Valor:</b> R$ <?=dinheiroParaBr($valor)?> </p>
            <p class='text-justify'><b>Mini curriculo:</b> <?=$miniCurriculo?> </p>
            <p class='text-justify'><b>Chamados:</b> <?=$linha['Chamados']?> </p>
            <p class='text-justify'><b>Status:</b> <?=($linha['Status'] == 1) ? "Ativo" : "Inativo"?> </p>
            <p class='text-justify'><b>Pontuação:</b> <?=$linha['Pontuacao']?> </p>
            <p class='text-justify'><b>nº Processo:</b> <?=$numProcesso?> </p>
        </div>
        <?php
        $i++;
    }
}

$dadosPf = [
    'Nome' => $pf['Nome'],
    'Nome Artístico' => $pf['NomeArtistico'],
    'RG' => $pf['RG'],
    'CPF' => $pf['CPF'],
    'CCM' => $pf['CCM'],
    'Data de Nascimento' => exibirDataBr($pf['DataNascimento']),
    'Endereço' => $endereco,
    'CEP' => $pf['CEP'],
    'Email' => $pf['Email'],
    'Telefone #1' => $pf['Telefone1'],
    'Telefone #2' => $pf['Telefone2'],
    'DRT' => $pf['DRT'],
    'PIS/PASEP/NIT' => $pf['Pis'],
];

if ($foto == null) {
    $fotoImg = "./images/avatar_default.png";
} else {
    $fotoImg = "../uploadsdocs/$foto";
}

?>

<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <div class="sub-title">
                <h2>Resumo de Cadastro Pessoa Física</h2>
            </div>
        </div>
        <div class="col-md-offset-1 col-md-10">
            <div class="row">
                <div class="well">
                    <a href="<?=$fotoImg?>" target="_blank">
                        <img src="<?=$fotoImg?>" alt="" style="max-width: 20%" align="right">
                    </a>
                    <?php
                    foreach ($dadosPf as $campo => $dado) {
                        echo "<p class='text-justify'><b>$campo:</b> $dado </p>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="sub-title">
                    <h2>Dados de Formação</h2>
                </div>
                <div class="well">
                    <?php
                    if ($formacao->num_rows > 0) {
                        resumoDadosFormacao($formacao);
                    } else { ?>
                        <strong>Sem Dados Cadastrados</strong>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-6">
                    <div class="form-group">
                        <a href="?perfil=formacao&p=frm_edita_pf&id_pf=<?=$idPf?>" class="btn btn-theme btn-block">Editar Cadastro</a>
                    </div>
                </div>
                <div class="col-md-offset-4 col-md-6">
                    <div class="form-group">
                        <a href="../pdf/rlt_formacao_pf_pdf.php?idPf=<?=$idPf?>" target="_blank" class="btn btn-theme btn-block">Imprimir Resumo</a>
                    </div>
                </div>
                <div class="col-md-offset-4 col-md-6">
                    <a href="?perfil=formacao&p=frm_lista_pf" class="btn btn-theme btn-block">Voltar para busca</a>
                </div>
            </div>
        </div>

    </div>
</section>