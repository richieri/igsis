<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
require_once("../funcoes/funcoesSiscontrat.php");

$con = bancoMysqli();


//CONSULTA  (copia inteira em todos os docs)
$idPf = $_POST['idPf'];

$ano = date('Y');

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
    'Endereço' => "Rua, ".$pf['Numero']."",
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

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=relatório_".$pf['Nome'].".doc");
?>
<html lang="pt-BR">
    <meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
    <body>
        <table>
            <tr>
                <td width="50%"><img src="../visual/images/logo_cultura.png" alt=""></td>
                <td width="50%"><img src="../visual/images/logo_cultura.png" alt=""></td>
            </tr>
        </table>

        <h3>REGISTRO DE PESSOA FÍSICA</h3>

        <?php
        foreach ($dadosPf as $campo => $dado) {
            echo "<p><b>$campo:</b> $dado</p>";
        }
        ?>
    </body>
</html>