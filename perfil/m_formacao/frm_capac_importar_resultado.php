<?php
$con = bancoMysqliProponente();

if (!(isset($_GET['pagina'])))
{
    $_SESSION['idCapacPf'] = $idCapacPf = trim($_POST['idCapacPf']);
    $_SESSION['proponente'] = $proponente = addslashes($_POST['proponente']);
    $_SESSION['programa'] = $programa = $_POST['programa'];
    $_SESSION['ano'] = $ano = $_POST['ano'];
    if (empty($_POST['ano']))
    {
        echo "<script>window.location = '?perfil=formacao&p=frm_capac_importar&erro=1';</script>";
    }
}
else
{
    $idCapacPf = $_SESSION['idCapacPf'];
    $proponente = $_SESSION['proponente'];
    $programa = $_SESSION['programa'];
    $ano = $_SESSION['ano'];
}

function pesquisaProponente($idCapacPf, $proponente, $programa, $ano)
{
    $query = "SELECT
                  pf.id,
                  pf.nome,
                  pf.nomeArtistico,
                  pf.dataNascimento,
                  pf.tipo_formacao_id,
                  pf.formacao_funcao_id,
                  pf.formacao_linguagem_id
                FROM pessoa_fisica AS pf
                       INNER JOIN tipo_formacao as tf ON pf.tipo_formacao_id = tf.id
                       INNER JOIN formacao_linguagem as fl ON pf.formacao_linguagem_id = fl.id
                       INNER JOIN formacao_funcoes as ff ON pf.formacao_funcao_id = ff.id
                       INNER JOIN (SELECT DISTINCT idPessoa FROM upload_arquivo
                                   WHERE idTipoPessoa = 6 AND publicado = '1' AND idUploadListaDocumento = '141'
                                   GROUP BY idPessoa) AS ua ON ua.idPessoa = pf.id";
    $condicoes = [];

    if(!(empty($idCapacPf)))
    {
        $condicoes[] = "pf.id = '$idCapacPf'";
    }
    if(!(empty($proponente)))
    {
     $condicoes[] = "pf.nome LIKE '%$proponente%'";
    }
    if(!(empty($programa)))
    {
        $condicoes[] = "pf.tipo_formacao_id = '$programa'";
    }
    if(!(empty($ano)))
    {
        $condicoes[] = "pf.formacao_ano = '$ano'";
    }


    $sql = $query;
    if (count($condicoes) > 0)
    {
        $sql .= " WHERE " . implode(' AND ', $condicoes) . " AND pf.formacao_funcao_id IS NOT NULL AND pf.publicado = '1'";
    }

    return $sql;
}

$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
$sql_lista = pesquisaProponente($idCapacPf, $proponente, $programa, $ano)." AND pf.tipo_formacao_id IS NOT NULL ORDER BY `nome`";
$query_lista = mysqli_query($con, $sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);

//seta a quantidade de itens por página
$registros = 30;

//calcula o número de páginas arredondando o resultado para cima
$numPaginas = ceil($total/$registros);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($registros*$pagina)-$registros;

//seleciona os itens por página
$sql_lista = pesquisaProponente($idCapacPf, $proponente, $programa, $ano)." AND pf.tipo_formacao_id IS NOT NULL ORDER BY `nome` LIMIT $inicio,$registros ";
$query_lista = mysqli_query($con,$sql_lista);

//conta o total de itens
$total = mysqli_num_rows($query_lista);

if($total <= '1')
{
    $mensagem = "Nesta página contém ".$total." resultado.<br/>";
}
else
{
    $mensagem = "Nesta página contém ".$total." resultados.";
}

function recuperaDadosCapac($tabela, $campo, $valor)
{
    $con = bancoMysqliProponente();
    $sql = "SELECT * FROM $tabela WHERE ".$campo." = '$valor' LIMIT 0,1";
    $query = mysqli_query($con,$sql);
    $campo = mysqli_fetch_array($query);
    return $campo;
}

include 'includes/menu.php';
?>
<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <h3>Resultado da busca</h3>
            <h5><?php if(isset($mensagem)){echo $mensagem;};?></h5>
            <h5><a href="?perfil=formacao&p=frm_capac_importar">Fazer outra busca</a></h5>
        </div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="table-responsive list_info">
                    <table class="table table-condensed">
                        <thead>
                            <tr class="list_menu">
                                <td>Codigo</td>
                                <td>Nome</td>
                                <td>Nome Artístico</td>
                                <td>Data de Nascimento</td>
                                <td>Programa</td>
                                <td>Função</td>
                                <td>Linguagem</td>
                                <td width="20%"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($linha = mysqli_fetch_assoc($query_lista))
                            {
                                $formacao = recuperaDadosCapac('tipo_formacao', 'id', $linha['tipo_formacao_id']);
                                $funcao = recuperaDadosCapac('formacao_funcoes', 'id', $linha['formacao_funcao_id']);
                                $linguagem = recuperaDadosCapac('formacao_linguagem', 'id', $linha['formacao_linguagem_id']);
                            ?>
                                <tr>
                                    <td class="list_description"><?= $linha['id'] ?></td>
                                    <td class="list_description"><?= $linha['nome'] ?></td>
                                    <td class="list_description"><?= $linha['nomeArtistico'] ?></td>
                                    <td class="list_description"><?= exibirDataBr($linha['dataNascimento']) ?></td>
                                    <td class="list_description"><?= $formacao['descricao'] ?></td>
                                    <td class="list_description"><?= $funcao['funcao'] ?></td>
                                    <td class="list_description"><?= $linguagem['linguagem'] ?></td>
                                    <td><a class='btn btn-theme btn-md btn-block' target='_blank' href='?perfil=formacao&p=frm_capac_detalhes&id_capac=<?=$linha['id']?>'>CARREGAR</a></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="10" bgcolor="#DEDEDE">
                                    <?php
                                    //exibe a paginação
                                    echo "<strong>Páginas</strong>";
                                    for($i = 1; $i < $numPaginas + 1; $i++)
                                    {
                                        echo "<a href='?perfil=formacao&p=frm_capac_importar_resultado&pagina=$i'> [".$i."]</a> ";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>