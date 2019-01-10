<?php
$con = bancoMysqliProponente();

if (!(isset($_GET['pagina'])))
{
    $_SESSION['idCapacPf'] = $idCapacPf = trim($_POST['idCapacPf']);
    $_SESSION['proponente'] = $proponente = addslashes($_POST['proponente']);
    $_SESSION['programa'] = $programa = $_POST['programa'];
    if (empty($_POST['idCapacPf']) && empty($_POST['proponente']) && empty($_POST['programa']))
    {
        echo "<script>window.location = '?perfil=formacao&p=frm_capac_importar&erro=1';</script>";
    }
}
else
{
    $idCapacPf = $_SESSION['idCapacPf'];
    $proponente = $_SESSION['proponente'];
    $programa = $_SESSION['programa'];
}

function pesquisaProponente($idCapacPf, $proponente, $programa)
{
    $query = "SELECT `id`, `nome`, `nomeArtistico`, `tipo_formacao_id`, `formacao_funcao_id` FROM `pessoa_fisica`";
    $condicoes = [];

    if(!(empty($idCapacPf)))
    {
        $condicoes[] = "`id` = '$idCapacPf'";
    }
    if(!(empty($proponente)))
    {
     $condicoes[] = "`nome` LIKE '%$proponente%'";
    }
    if(!(empty($programa)))
    {
        $condicoes[] = "`tipo_formacao_id` = '$programa'";
    }

    $sql = $query;
    if (count($condicoes) > 0)
    {
        $sql .= " WHERE " . implode(' AND ', $condicoes);
    }

    return $sql;
}

$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
$sql_lista = pesquisaProponente($idCapacPf, $proponente, $programa)." AND `tipo_formacao_id` IS NOT NULL ORDER BY `nome`";
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
$sql_lista = pesquisaProponente($idCapacPf, $proponente, $programa)." AND `tipo_formacao_id` IS NOT NULL ORDER BY `nome` LIMIT $inicio,$registros ";
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
                                <td>Programa</td>
                                <td>Função</td>
                                <td width="20%"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($linha = mysqli_fetch_assoc($query_lista))
                            {
                                $formacao = recuperaDadosCapac('tipo_formacao', 'id', $linha['tipo_formacao_id']);
                                $funcao = recuperaDadosCapac('formacao_funcoes', 'id', $linha['formacao_funcao_id']);
                            ?>
                                <tr>
                                    <td class="list_description"><?= $linha['id'] ?></td>
                                    <td class="list_description"><?= $linha['nome'] ?></td>
                                    <td class="list_description"><?= $linha['nomeArtistico'] ?></td>
                                    <td class="list_description"><?= $formacao['descricao'] ?></td>
                                    <td class="list_description"><?= $funcao['funcao'] ?></td>
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