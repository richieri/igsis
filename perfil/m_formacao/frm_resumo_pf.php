<?php
include 'includes/menu.php';
$con = bancoMysqli();
$idPf = $_GET['idPf'];

$pf = recuperaDados('sis_pessoa_fisica', $idPf, 'Id_PessoaFisica');

$estadoCivil = recuperaDados('sis_estado_civil', $pf['IdEstadoCivil'], 'Id_EstadoCivil')['EstadoCivil'];

$sqlFoto = "SELECT arquivo FROM igsis_arquivos_pessoa WHERE idTipoPessoa = '1' AND idPessoa = '".$pf['Id_PessoaFisica']."' AND tipo = '29' AND publicado = '1'";
$foto = $con->query($sqlFoto)->fetch_assoc()['arquivo'];

$formacao = recuperaDados("sis_pessoa_fisica_formacao",$pf['Id_PessoaFisica'],"IdPessoaFisica");

$dadosPf = [
    'Nome' => $pf['Nome'],
    'Nome Artístico' => $pf['NomeArtistico'],
    'RG' => $pf['RG'],
    'CPF' => $pf['CPF'],
    'CCM' => $pf['CCM'],
    'Data de Nascimento' => exibirDataBr($pf['DataNascimento']),
    'Endereço' => "Rua Exemplo, ".$pf['Numero']."",
    'Bairro' => '',
    'CEP' => $pf['CEP'],
    'Cidade / Estado' => '',
    'Email' => $pf['Email'],
    'Telefone #1' => $pf['Telefone1'],
    'Telefone #2' => $pf['Telefone2'],
    'Estado Civil' => $estadoCivil,
    'Nacionalidade' => $pf['Nacionalidade'],
    'PIS/PASEP/NIT' => $pf['Pis']
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
                        if (isset($formacao['IdPessoaFisica'])) {
                            echo "<strong>Possui Dados Cadastrados</strong>";
                        } else {
                            echo "<strong>Sem Dados Cadastrados</strong>";
                        }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-6">
                    <div class="form-group">
                        <a href="../pdf/rlt_formacao_pf_pdf.php?idPf=<?=$idPf?>" class="btn btn-theme btn-block">Imprimir Resumo</a>
                    </div>
                </div>
                <div class="col-md-offset-4 col-md-6">
                    <a href="?perfil=formacao&p=frm_lista_pf" class="btn btn-theme btn-block">Voltar para busca</a>
                </div>
            </div>
        </div>

    </div>
</section>