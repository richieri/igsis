<?php
$con = bancoMysqliProponente();

if (!(isset($_GET['pagina'])))
{
    if (isset($_POST['pesquisaGeral']))
    {
        $_SESSION['proponente'] = $proponente = addslashes($_POST['proponente']);
        $_SESSION['pesquisaGeral'] = true;
    }
    else
    {
        $_SESSION['ano'] = $ano = $_POST['ano'];
        $_SESSION['tipoCadastro'] = $tipoCadastro = $_POST['tipoCadastro'];
        $_SESSION['proponente'] = $proponente = addslashes($_POST['proponente']);
        $_SESSION['programa'] = $programa = $_POST['programa'];
        $_SESSION['funcao'] = $funcao = $_POST['funcao'];
        $_SESSION['linguagem'] = $linguagem = $_POST['linguagem'];
        $_SESSION['regiao'] = $regiao = $_POST['regiao'];
        if (empty($_POST['ano']))
        {
            echo "<script>window.location = '?perfil=formacao&p=frm_adm_capac_importar&erro=1';</script>";
        }
    }
}
else
{
    if (isset($_SESSION['pesquisaGeral']))
    {
        $proponente = $_SESSION['proponente'];
    }
    else
    {
        $ano = $_SESSION['ano'];
        $tipoCadastro = $_SESSION['tipoCadastro'];
        $proponente = $_SESSION['proponente'];
        $programa = $_SESSION['programa'];
        $funcao = $_SESSION['funcao'];
        $linguagem = $_SESSION['linguagem'];
        $regiao = $_SESSION['regiao'];
    }
}

function pesquisaProponente($ano, $tipoCadastro, $proponente, $programa, $funcao, $linguagem, $regiao)
{
    if ($ano > 2019) {
        $innerRegiao['regiao'] = ",r.regiao";
        $innerRegiao['innerJoin'] = "LEFT JOIN regioes as r on pf.formacao_regiao_preferencial = r.id";
    } else {
        $innerRegiao['regiao'] = "";
        $innerRegiao['innerJoin'] = "";
    }
    if ($tipoCadastro == 1)
    {
        $innerCadastro = "INNER JOIN (SELECT DISTINCT idPessoa FROM upload_arquivo
                                   WHERE idTipoPessoa = 6 AND publicado = '1' AND idUploadListaDocumento = '141'
                                   GROUP BY idPessoa) AS ua ON ua.idPessoa = pf.id";
    }
    else
    {
        $innerCadastro = "";
    }
    $query = "SELECT
                  pf.id,
                  pf.nome,
                  pf.nomeArtistico,
                  pf.dataNascimento,
                  pf.cpf,
                  pf.tipo_formacao_id,
                  pf.formacao_funcao_id,
                  pf.formacao_linguagem_id,
                  fl.linguagem,
                  pf.formacao_regiao_preferencial
                  {$innerRegiao['regiao']}
                FROM pessoa_fisica AS pf
                       INNER JOIN tipo_formacao as tf ON pf.tipo_formacao_id = tf.id
                       INNER JOIN formacao_linguagem as fl ON pf.formacao_linguagem_id = fl.id
                       INNER JOIN formacao_funcoes as ff ON pf.formacao_funcao_id = ff.id
                       {$innerRegiao['innerJoin']}
                       $innerCadastro";
    $condicoes = [];

    if(!(empty($ano)))
    {
        $condicoes[] = "`formacao_ano` = '$ano'";
    }

    if(!(empty($proponente)))
    {
        $condicoes[] = "pf.nome LIKE '%$proponente%'";
    }

    if(!(empty($programa)))
    {
        $condicoes[] = "pf.tipo_formacao_id = '$programa'";
    }

    if(!(empty($funcao)))
    {
        $condicoes[] = "pf.formacao_funcao_id = '$funcao'";
    }

    if(!(empty($linguagem)))
    {
        $condicoes[] = "pf.formacao_linguagem_id = '$linguagem'";
    }

    if(!(empty($regiao)))
    {
        $condicoes[] = "pf.formacao_regiao_preferencial = '$regiao'";
    }

    $sql = $query;
    if (count($condicoes) > 0)
    {
        $sql .= " WHERE " . implode(' AND ', $condicoes) . " AND pf.formacao_funcao_id IS NOT NULL AND pf.publicado = '1'";
    }

    return $sql;
}

$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
if (isset($_SESSION['pesquisaGeral']))
{
    $sql_lista = "SELECT * FROM `pessoa_fisica` WHERE `nome` LIKE '%$proponente%' AND `publicado` = 1";
}
else
{
    $sql_lista = pesquisaProponente($ano, $tipoCadastro, $proponente, $programa, $funcao, $linguagem, $regiao)." AND pf.tipo_formacao_id IS NOT NULL ORDER BY `nome`";
}
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
if (isset($_SESSION['pesquisaGeral']))
{
    $sql_lista = "SELECT * FROM `pessoa_fisica` WHERE `nome` LIKE '%$proponente%' AND `publicado` = 1 LIMIT $inicio,$registros";
}
else
{
    $sql_lista = pesquisaProponente($ano, $tipoCadastro, $proponente, $programa, $funcao, $linguagem, $regiao)." AND pf.tipo_formacao_id IS NOT NULL ORDER BY pf.nome LIMIT $inicio,$registros ";

}
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

include 'includes/menu_administrativo.php';
?>
<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <h3>Resultado da busca</h3>
            <h5><?php if(isset($mensagem)){echo $mensagem;};?></h5>
            <h5><a href="?perfil=formacao&p=frm_adm_capac_importar">Fazer outra busca</a></h5>
        </div>
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="table-responsive list_info">
                    <table class="table table-condensed">
                        <thead>
                            <tr class="list_menu">
                                <td>Código CAPAC</td>
                                <td>Nome</td>
                                <td>CPF</td>
                                <td>Data de Nascimento</td>
                                <td>Função</td>
                                <td>Linguagem</td>
                                <td>Região Preferencial</td>
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
                                    <td class="list_description"><?= $linha['cpf'] ?></td>
                                    <td class="list_description"><?= exibirDataBr($linha['dataNascimento']) ?></td>
                                    <td class="list_description"><?= $funcao['funcao'] ?></td>
                                    <td class="list_description"><?= $linguagem['linguagem'] ?></td>
                                    <td class="list_description"><?= !isset($linha['regiao']) || $linha['regiao'] == null ? "Não Cadastrado" : $linha['regiao'] ?></td>
                                    <td><a class='btn btn-theme btn-md btn-block' target='_blank' href='?perfil=formacao&p=frm_capac_resumo&id_capac=<?=$linha['id']?>'>Exibir Resumo</a></td>
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
                                        echo "<a href='?perfil=formacao&p=frm_adm_capac_importar_resultado&pagina=$i'> [".$i."]</a> ";
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